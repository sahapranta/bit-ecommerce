<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'group', // 'email', 'sms', 'whatsapp
        'phone',
        'user_id',
        'status',
        'is_verified',
        'verification_code',
        'expires_at',
        'is_unsubscribed',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_unsubscribed' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('is_unsubscribed', true);
    }
}
