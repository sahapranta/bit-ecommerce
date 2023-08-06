<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'review',
        'is_approved',
        'is_anonymous'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_anonymous' => 'boolean'
    ];


    protected static function booted()
    {
        static::created(fn ($review) => static::updateProductRating($review));
        static::updated(fn ($review) => static::updateProductRating($review));
        static::deleted(fn ($review) => static::updateProductRating($review));
    }

    public static function updateProductRating($review)
    {
        $review->product->update([
            'ratings' => $review->product->reviews()->avg('rating')
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
