<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\CartStatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\SessionManager;
use App\Contracts\CartServiceContracts;


class CartService implements CartServiceContracts
{
    const CART_KEY = 'cart';
    const MERGE_KEY = 'cart-merged';
    const MINIMUM_QUANTITY = 1;

    public function __construct(
        protected SessionManager $session,
        public $userCart = null,
        public $user = null
    ) {
        $this->user = $this->getCurrentUser();
        $this->updateCartSessionFromDb();
    }

    public function updateCartSessionFromDb()
    {
        if (is_null($this->user)) return;

        $saved = $this->user->cart;

        if ($saved && $saved->status->is(CartStatusEnum::OPEN)) {
            $cart = $this->getCart();
            $ids = array_keys($saved->items);

            if ($this->session->get(self::MERGE_KEY, false) === false && count($ids) > 0) {
                $cart->each(function ($item) use ($saved, &$ids) {
                    if (in_array($item->get('product_id'), $ids)) {
                        $quantity = $item->get('quantity') + $saved->items[$item->get('product_id')];
                        $item->put('quantity', $quantity);
                        // remove the id from the array
                        $ids = array_diff($ids, [$item->get('product_id')]);
                    }
                });

                // add the remaining items
                $products = Product::find($ids);

                $products->each(function ($product) use ($saved, &$cart) {
                    $quantity = $saved->items[$product->id];
                    if ($quantity > 0 && $product->stock >= $quantity) {
                        $item = $this->createItem($product, $saved->items[$product->id]);
                        $cart->put($product->id, $item);
                    }
                });

                $this->session->put(self::CART_KEY, $cart);
                $this->session->put(self::MERGE_KEY, true);
            }

            $this->userCart = $saved;
        }

        $this->updateCartToDb();
    }

    protected function updateCartToDb($cartId = null)
    {
        if (is_null($this->user)) return;

        $cart = $this->getCart();

        if ($cart->count() === 0 && !is_null($this->userCart)) {
            $this->userCart->delete();
            $this->userCart = null;
            return;
        }

        $user = $this->user;

        if (!is_null($cartId) && is_null($this->userCart)) {
            $this->userCart = $user->cart()->find($cartId);
        }

        $items = $cart->mapWithKeys(function ($item) {
            return [$item->get('product_id') => $item->get('quantity')];
        });

        if (is_null($this->userCart) && $items->count() > 0) {
            $this->userCart = $user->cart()->create(['items' => $items]);
        } else {
            $this->userCart?->update(['items' => $items]);
        }
    }

    public function getCount($cart = null): int
    {
        if (is_null($cart)) {
            $cart = $this->getCart();
        };
        return $cart->sum('quantity');
    }

    public function getTotals($cart = null): array
    {

        if (is_null($cart)) {
            $cart = $this->getCart();
        };

        $subtotal = 0;
        $discount = 0;
        $shipping = 0;

        foreach ($cart as $item) {
            $subtotal += (float) $item->get('price') * (int) $item->get('quantity');
            $discount += (float) $item->get('discount') * (int) $item->get('quantity');
            $shipping += (float) $item->get('delivery_fee') * (int) $item->get('quantity');
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total'    => $subtotal - $discount + $shipping,
        ];
    }

    protected function calculate($cart = null, $name = 'price')
    {
        if (is_null($cart)) {
            $cart = $this->getCart();
        };
        return $cart->sum(fn ($item) => (float) $item->get($name) * $item->get('quantity'));
    }


    public function getTotal($cart = null): float
    {
        return $this->calculate($cart);
    }

    public function getDiscount($cart = null): float
    {
        return $this->calculate($cart, 'discount');
    }

    public function getShipping($cart = null): float
    {
        return $this->calculate($cart, 'delivery_fee');
    }

    public function getCart(): Collection
    {
        return $this->session->get(self::CART_KEY, collect([]));
    }

    protected function createItem(Product $product, int $quantity = self::MINIMUM_QUANTITY): Collection
    {
        // return $this->getCart()->firstWhere('product_id', $productId);
        return collect([
            'product_id'   => $product->id,
            'quantity'     => $quantity,
            'price'        => $product->price,
            'name'         => $product->name,
            'discount'     => $product->discount,
            'delivery_fee' => $product->delivery_fee,
            'stock'        => $product->stock,
            'image'        => $product->getFirstMedia('products')?->getUrl('thumbnail'),
            'slug'         => $product->slug,
        ]);
    }

    public function addItem(Product $product, int $quantity = self::MINIMUM_QUANTITY): void
    {
        if ($quantity < self::MINIMUM_QUANTITY || $product->stock < $quantity) return;

        $cart = $this->getCart();

        if ($cart->has($product->id)) {
            $item = $cart->get($product->id);
            $quantity = $item->get('quantity') + $quantity;
            $item->put('quantity', $quantity);
        } else {
            $item = $this->createItem($product, $quantity);
        }

        $cart->put($product->id, $item);
        $this->session->put(self::CART_KEY, $cart);
        $this->updateCartToDb();
    }

    public function increment($productId, int $quantity = self::MINIMUM_QUANTITY): void
    {
        $cart = $this->getCart();

        if ($cart->count() === 0 || !$cart->has($productId)) return;

        $item = $cart->get($productId);

        $newQuantity = $item->get('quantity') + $quantity;

        if ($item->get('stock') < $newQuantity) return;

        $item->put('quantity', $newQuantity);

        $this->session->put(self::CART_KEY, $cart);
        $this->updateCartToDb();
    }

    public function removeItem($productId, int $quantity = self::MINIMUM_QUANTITY): void
    {
        $cart = $this->getCart();

        if ($cart->count() === 0 || !$cart->has($productId)) return;

        $qty = $cart->get($productId)->get('quantity');

        if ($qty <= $quantity) {
            $cart->forget($productId);
        } else {
            $cart->get($productId)->put('quantity', $qty - $quantity);
        }

        $this->session->put(self::CART_KEY, $cart);
        $this->updateCartToDb();
    }

    protected function createCartForUser($items = [])
    {
        if (is_null($this->userCart) && is_null($this->user)) return;

        $this->userCart = $this->user->cart()->create([
            'items' => $items,
        ]);
    }

    public function emptyCart()
    {
        $this->session->put(self::CART_KEY, collect([]));
        $this->updateCartToDb();
    }

    public function forget()
    {
        $this->session->forget(self::CART_KEY);
    }

    public function getCurrentUser()
    {
        return Auth::user();
    }
}
