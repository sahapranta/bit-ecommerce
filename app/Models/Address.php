<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'street_1',
        'street_2',
        'city',
        'province',
        'postal_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ordersAsShipping()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    public function ordersAsBilling()
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'user_id');
    }

    public function getFullAddressHtmlAttribute()
    {
        // return "{$this->street_1} {$this->street_2} {$this->city} {$this->province} {$this->postal_code} {$this->country}";
        $addressLines = [];

        if ($this->name) {
            $addressLines[] = "<strong>{$this->name}</strong>";
        }

        if ($this->street_1) {
            $addressLines[] = $this->street_1;
        }

        if ($this->street_2) {
            $addressLines[] = $this->street_2;
        }

        $location = [];
        if ($this->city) {
            $location[] = $this->city;
        }

        if ($this->province) {
            $location[] = $this->province;
        }

        if (!empty($location)) {
            $addressLines[] = implode(', ', $location);
        }

        if ($this->country || $this->postal_code) {
            $postal_code = $this->postal_code ? ", {$this->postal_code}" : '';
            $addressLines[] = $this->country . $postal_code;
        }

        if ($this->phone) {
            $addressLines[] = "<abbr title=\"Phone\">P:</abbr> {$this->phone}";
        }

        $htmlAddress = '<address>' . implode('<br>', $addressLines) . '</address>';

        return $htmlAddress;
    }
}
