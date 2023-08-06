<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'received',
        'is_paid',
        'address',
        'txid',
        'confirmations',
        'paid_at',
        'is_refundable',
        'is_refunded',
        'refund_amount',
        'refund_address',
        'refund_txid',
        'refunded_at',
        'active',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_refundable' => 'boolean',
        'is_refunded' => 'boolean',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    public function scopeRefundable($query)
    {
        return $query->where('is_refundable', true);
    }

    public function scopeRefunded($query)
    {
        return $query->where('is_refunded', true);
    }

    public function scopeNotConfirmed($query)
    {
        return $query->where('txid', '!=', '')
            ->where('is_paid', false);
    }
}
