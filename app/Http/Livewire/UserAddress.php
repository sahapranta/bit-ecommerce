<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserAddress extends Component
{
    public $addresses = [];
    public $user = null;
    public $simple = false;

    public $name;
    public $email;
    public $street_1;
    public $street_2;
    public $phone;
    public $city;
    public $province;
    public $country;
    public $postal_code;

    public $formOpen = false;
    public $editMode = false;
    public $editAddress = null;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'street_1' => 'required|min:3|max:255',
        'street_2' => 'nullable|min:3|max:255',
        'phone' => 'required|min:3|max:15',
        'city' => 'required|min:2|max:255',
        'province' => 'nullable|min:2|max:255',
        'country' => 'nullable|min:2|max:255',
        'postal_code' => 'required|min:3|max:20',
    ];

    protected $validationAttributes = [
        'province' => 'State'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function boot()
    {
        $this->user = Auth::user();
        $this->loadAddress();
    }

    protected function loadAddress()
    {
        $this->addresses = $this->simple ? $this->user->addresses()->paginate(20) : $this->user->addresses()->limit(10)->latest()->get();
    }

    public function openForm()
    {
        $this->populateData();
        $this->editMode = false;
        $this->formOpen = true;
    }

    public function populateData()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function closeForm()
    {
        $this->formOpen = false;
        $this->editAddress = null;
        $this->resetValidation();
        $this->reset(['name', 'email', 'street_1', 'street_2', 'phone', 'city', 'province', 'country', 'postal_code']);
    }

    public function saveAddress()
    {
        $validatedData = $this->validate();
        if ($this->editMode && $this->editAddress) {
            $this->editAddress->update($validatedData);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'title' => 'Address updated successfully!']);
            $this->editMode = false;
        } else {
            $this->user->addresses()->create($validatedData);
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'title' => 'Address saved successfully!']);
        }

        $this->loadAddress();
        $this->closeForm();
    }

    public function openUpdateForm($id)
    {
        $address = $this->user->addresses()->findOrFail($id);
        $this->editMode = true;
        $this->editAddress = $address;
        $this->name = $address->name;
        // $this->email = $address->email;
        $this->street_1 = $address->street_1;
        $this->street_2 = $address->street_2;
        $this->phone = $address->phone;
        $this->city = $address->city;
        $this->province = $address->province;
        $this->country = $address->country;
        $this->postal_code = $address->postal_code;
        $this->formOpen = true;
    }

    public function render()
    {
        return view('livewire.user-address');
    }
}
