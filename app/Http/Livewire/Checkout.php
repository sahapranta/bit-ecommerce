<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\AppSettings;
use Facades\App\Services\CartService;

class Checkout extends Component
{
    public $cart;
    public $total = 0;
    public $subtotal = 0;
    public $tax = 0;
    public $shipping = 0;
    public $discount = 0;
    public $coupon = null;

    public $shippingCharge = 0;
    public $discountAmount = 0;
    public $shippingMethod = '';

    public $shippingMethods = [];


    public $listeners = [
        'addShipping' => 'addShippingCharge',
        'addDiscount' => 'addDiscountAmount',
        'couponApplied' => 'refreshTotal',
        'couponRemoved' => 'refreshTotal',
        'refreshCart' => 'refreshCart',
    ];

    public function mount()
    {
        $data = \AppSettings::get('delivery_methods', []);
        $methods = [];

        foreach ($data as $key => $value) {
            $methods[] = [
                'key' => $key,
                'name' => $key,
                'fee' => $value,
                'description' => '(4-5 working days)',
            ];
        }

        $this->shippingMethod = $methods[0]['key'];

        $this->shippingMethods = $methods;

        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cart = CartService::getCart();
        $this->subtotal = CartService::getTotal($this->cart);
        $this->calculateTax();
        $this->refreshTotal();
    }

    public function refreshTotal()
    {
        $this->calculateShipping();
        $this->calculateDiscount();

        $this->total = $this->subtotal + $this->tax + $this->shipping - $this->discount;
    }

    public function addDiscountAmount($amount)
    {
        $this->discountAmount = $amount;
        $this->refreshTotal();
    }

    public function addShippingCharge($name)
    {
        $this->shippingMethod = $name;
        $methods = collect($this->shippingMethods);
        $this->shippingCharge = $methods->where('key', $name)->first()['fee'];
        $this->refreshTotal();
    }

    protected function calculateDiscount()
    {
        $this->discount = CartService::getDiscount();
        $this->discount += $this->discountAmount;
    }

    protected function calculateShipping()
    {
        $this->shipping = CartService::getShipping();
        $this->shipping += $this->shippingCharge;
    }

    protected function calculateTax()
    {
        $tax_rate = AppSettings::get('tax_rate', 0);
        $this->tax = $this->subtotal * $tax_rate / 100;
    }


    public function increment($id)
    {
        CartService::increment($id);
        $this->refreshCart();
    }

    public function decrement($id)
    {
        CartService::removeItem($id);
        $this->refreshCart();
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
