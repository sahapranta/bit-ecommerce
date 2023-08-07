<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\CartStatusEnum;
use App\Events\OrderPlaced;
use App\Services\AppSettings;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Facades\App\Services\CartService;

class StoreController extends Controller
{
    public function product(Product $product)
    {
        $product->load(['media', 'category']);
        $categories = CategoryService::withSalesCounts();

        return view('frontend.store.product', compact('product', 'categories'));
    }

    public function showCheckout(Request $request)
    {
        $cart = CartService::getCart();

        if ($cart->isEmpty()) {
            return redirect()->route('home')->with([
                'message' => 'Your cart is empty.',
                'alert' => 'error',
            ]);
        }

        return view('frontend.store.checkout');
    }


    public function checkout(Request $request)
    {
        $request->validate([
            'delivery' => 'required',
            'shipping_address_id' => 'required|numeric|exists:addresses,id',
            'billing-address-same' => 'nullable',
            'delivery_note' => 'nullable|string|max:255',
            'billing_address_id' => 'required_unless:billing-address-same,1|numeric|exists:addresses,id',
        ], [
            'shipping_address_id.required' => 'Please select a shipping address.',
            'billing_address_id.required_unless' => 'Please select a billing address.',
        ]);

        $cart = CartService::getCart();

        if ($cart->isEmpty()) {
            return $this->reject('Your cart is empty.');
        }

        DB::beginTransaction();

        try {

            $deliveryMethods = \AppSettings::get('delivery_methods', []);
            $shipping = data_get($deliveryMethods, $request->get('delivery'), 0);

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_id' => Str::uuid(),
                'tracking_id' => \AppHelper::generateTrackingId(),
                'delivery_method' => $request->delivery,
                'shipping_address_id' => $request->shipping_address_id,
                'billing_address_id' => $request->billing_address_id,
                'delivery_note' => $request->delivery_note,
                'status' => 'pending',
                'total' => 0,
                'btc_total' => 0,
                'subtotal' => 0,
                'shipping' => $shipping,
            ]);

            $subtotal = 0;
            $discount = 0;

            foreach ($cart as $key => $value) {
                $product = Product::findOrFail($key);

                $price = (float) $product->price;

                $order->items()->create([
                    'product_id' => $key,
                    'quantity' => $value['quantity'],
                    'price' => $price,
                ]);

                $subtotal += bcmul($product->price, $value['quantity'], 2);
                $discount_value = (float) $product->discount;
                $discount += bcmul($discount_value, $value['quantity'], 2);

                $product->decrement('stock', $value['quantity']);
                $product->increment('sales', $value['quantity']);
            }

            $tax_rate = AppSettings::get('tax_rate', 0);
            $tax = $tax_rate > 0 ? bcmul($subtotal, ($tax_rate / 100), 8) : 0;

            $total = $subtotal - $discount + $tax + $shipping;

            $addressToSave = Address::where('id', $request->shipping_address_id)
                ->select('name', 'phone', 'street_1', 'street_2', 'city', 'province', 'country', 'postal_code')
                ->first();


            $order->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'btc_total' => \AppHelper::convertToBTC($total),
                'address' => $addressToSave,
            ]);

            // Update cart status
            Auth::user()->cart()->update(['status' => CartStatusEnum::PURCHASED->value]);

            \PaymentService::charge($order);

            session()->put('order_id', $order->order_id);

            // Clear cart
            CartService::forget();

            DB::commit();

            event(new OrderPlaced($order));
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return $this->reject($e->getMessage());
        }


        return redirect()->route('checkout.confirm', $order->order_id)
            ->with('success', 'Your order has been placed successfully.');
    }

    public function confirm(Order $order)
    {
        abort_if($order->user_id != auth()->id(), 403, 'You are not authorized to view this page.');

        $order->load(['items', 'shippingAddress', 'billingAddress', 'items.product']);
        return view('frontend.store.confirm', compact('order'));
    }


    public function invoice(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403, 'You are not authorized to view this page.');

        $order->load(['items', 'shippingAddress', 'billingAddress', 'items.product']);

        if ($request->has('no-pdf')) {
            return view('frontend.store.invoice', compact('order'));
        }

        $pdf = Pdf::loadView('frontend.store.invoice', compact('order'));
        return $pdf->download("invoice-{$order->tracking_id}.pdf");
    }

    public function trackOrder(Request $request)
    {
        if (!$request->get('order')) {
            return view('frontend.store.track-form');
        }

        $status = [
            [
                'name' => 'Processing',
                'key' => 'processing,pending,incomplete,returned,refunded,cancelled',
                'icon' => 'fas fa-clipboard-list',
                'color' => '',
                'status' => false,
            ],
            [
                'name' => 'Shipped',
                'key' => 'shipped',
                'icon' => 'fas fa-box-open',
                'color' => 'text-info',
                'status' => false,
            ],
            [
                'name' => 'En Route',
                'key' => 'delivered',
                'icon' => 'fas fa-shipping-fast',
                'color' => 'text-warning',
                'status' => false,
            ],
            [
                'name' => 'Arrived',
                'key' => 'received',
                'icon' => 'fas fa-people-carry',
                'color' => 'text-success',
                'status' => false,
            ],
        ];

        $order = Order::where('order_id', $request->order)
            ->select('id', 'order_id', 'tracking_id', 'status', 'delivery_date')
            ->firstOrFail();

        foreach ($status as $key => $value) {
            $status[$key]['status'] = true;
            if (in_array($order->status->value, explode(',', $value['key']))) {
                break;
            }
        }

        return view('frontend.store.track', compact('order', 'status'));
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $category = $request->category;
        $sort = $request->sort;
        $price_range = $request->price_range;
        $ratings = $request->get('ratings', '');
        $tag = $request->tag;

        $perPage = $request->get('per_page', 9);

        if ($price_range && strpos($price_range, ',') !== false) {
            $price_range = explode(',', $price_range);
        }

        $filter = [
            'search' => $q,
            'category' => $category ?? [],
            'sort' => $sort ?? '0',
            'price' => [
                'min' => $price_range[0] ?? 0,
                'max' => $price_range[1] ?? 0,
            ],
            'ratings' => $ratings,
            'per_page' => $perPage,
        ];

        $products = Product::query()
            ->with('media')
            ->when($q, function ($query, $q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            })
            ->when($price_range, function ($query, $price_range) {
                $query->whereBetween('price', $price_range);
            })
            ->when($category, function ($query, $category) {
                $query->whereIn('category_id', $category);
            })
            ->when($ratings, function ($query, $ratings) {
                $query->where('ratings', '>=', $ratings);
            })
            ->when($tag, function ($query, $tag) {
                $query->whereJsonContains('tags', $tag);
            })
            ->when($sort, function ($query, $sort) {
                // sort includes asc or desc
                if (preg_match('/_(asc|desc)$/', $sort)) {
                    $query->orderBy(...explode('_', $sort));
                } else if ($sort === 'reviews') {
                    $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->simplePaginate($perPage);

        $categories = CategoryService::withProductCounts();
        $price = ProductService::getPriceRange();
        $tags = ProductService::getTags();

        return view('frontend.store.search', compact('products', 'categories', 'filter', 'price', 'tags'));
    }
}
