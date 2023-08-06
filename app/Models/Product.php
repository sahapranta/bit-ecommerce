<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Image\Manipulations;
use App\Enums\ProductStatusEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'title',
        'upc',
        'slug',
        'description',
        'short_description',
        'price',
        'discount',
        'status',
        'stock',
        'sales',
        'delivery_fee',
        'tags',
        'options',
        'ratings',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'options' => 'array',
        'tags' => 'array',
        'stock' => 'integer',
        'sales' => 'integer',
        'ratings' => 'decimal:2',
        'status' => ProductStatusEnum::class,
        // 'price' => 'decimal:2',
        // 'discount' => 'decimal',
        // 'delivery_fee' => 'decimal',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', ProductStatusEnum::PUBLISHED->value);
    }

    /** Get the options for generating the slug.*/
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->skipGenerateWhen(fn () => $this->slug !== null)
            ->doNotGenerateSlugsOnUpdate();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Manipulations::FIT_CROP, 400, 270)
            // ->nonQueued();
            ->queued();
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('products', 'thumbnail');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('products', 'thumbnail');
    }

    public function getDiscountedPriceAttribute()
    {
        return (float) $this->price - (float) $this->discount;
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
