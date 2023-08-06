<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShippingMethod extends Component
{
    public $shippingMethods = [
        // [
        //     'key' => 'standard',
        //     'name' => 'Standard Delivery',
        //     'fee' => 0,
        //     'description' => '(4-5 working days)'
        // ],
        // [
        //     'key' => 'express',
        //     'name' => 'Express Delivery 🔥',
        //     'fee' => 10,
        //     'description' => '(1-2 working days)'
        // ],
    ];

    public function mount()
    {
        // $this->shippingMethods = [];
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
        $this->shippingMethods = $methods;
    }

    public function render()
    {
        return view('livewire.shipping-method');
    }
}
