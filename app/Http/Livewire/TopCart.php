<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Facades\App\Services\CartService;

class TopCart extends Component
{
    use Notify;

    public $count = 0;
    protected $cartService;
    public $cart;
    public $total = 0;

    public function mount()
    {
        // $this->cartService = new CartService();
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cart = CartService::getCart();
        $this->count = CartService::getCount($this->cart);
        $this->total = CartService::getTotal($this->cart);
        $this->emit('cartUpdated');
    }


    protected $listeners = ['addToCart' => 'addProduct', 'removeFromCart' => 'removeProduct'];

    public function addProduct($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        if (!$product) {
            $this->notifyError('Product not found');
            return;
        }

        if ($product->stock < 1) {
            $this->notifyError('Product out of stock');
            return;
        }

        CartService::addItem($product);
        $this->refreshCart();
        $this->success('Product added to cart successfully');
    }

    public function removeProduct($productId)
    {
        CartService::removeItem($productId);
        $this->refreshCart();
        $this->success('Product removed from cart successfully');
    }

    public function clearCart()
    {
        if ($this->count < 1) {
            $this->notifyError('Cart is empty');
            return;
        }

        CartService::emptyCart();
        $this->refreshCart();
        $this->success('Cart cleared successfully');
    }

    public function render()
    {
        return view('livewire.top-cart');
    }
}
