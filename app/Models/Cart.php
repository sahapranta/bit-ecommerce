<?php

namespace App\Models;

use App\Enums\CartStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'user_id',
        'session_id',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'status' => CartStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest',
        ]);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'user_id');
    }

    public function getTotalPriceAttribute()
    {
        return collect($this->items)->sum(fn ($i) => $i['price'] * $i['quantity']);
    }

    public function getTotalQuantityAttribute()
    {
        return collect($this->items)->sum(fn ($i) => $i['quantity']);
    }

    // set user_id and session_id while created
    public static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            $cart->user_id = auth()->id();
            $cart->session_id = session()->getId();
        });
    }
}
