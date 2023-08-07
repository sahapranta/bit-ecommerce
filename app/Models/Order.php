<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'tracking_id',
        'shipping_address_id',
        'billing_address_id',
        'address',
        'total',
        'btc_total',
        'subtotal',
        'tax',
        'discount',
        'shipping',
        'payment_method',
        'payment_status',
        'delivery_method',
        'delivery_status',
        'delivery_date',
        'delivery_note',
        'is_gift',
        'gift_message',
        'is_paid',
        'status',
        'currency',
    ];


    protected $casts = [
        'address' => 'array',
        'is_gift' => 'boolean',
        'is_paid' => 'boolean',
        'delivery_date' => 'datetime',
        'status' => OrderStatusEnum::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    public function getMailInfoAttribute()
    {
        if ($this->billingAddress && $this->billingAddress->email) {
            return [
                'name' => $this->billingAddress->name || '',
                'email' => $this->billingAddress->email,
            ];
        } else if ($this->shippingAddress && $this->shippingAddress->email) {
            return [
                'name' => $this->shippingAddress->name || '',
                'email' => $this->shippingAddress->email,
            ];
        } else {
            return [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ];
        }
    }

    public static function generateOrderId()
    {
        $maxAttempts = 5;
        $attempts = 0;

        do {
            try {
                $attributes['order_id'] = (string) \Str::uuid();
                $order = Order::create($attributes);
                break;
            } catch (\Illuminate\Database\QueryException $exception) {
                $errorCode = $exception->errorInfo[1];

                if ($errorCode === 1062 && $attempts < $maxAttempts) {
                    $attempts++;
                } else {
                    throw $exception;
                }
            }
        } while ($attempts < $maxAttempts);

        if ($attempts === $maxAttempts) {
            throw new \RuntimeException('Unable to generate a unique order ID.');
        }

        return $order;
    }
}
