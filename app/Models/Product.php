<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasMedia
{
    use SoftDeletes, HasSlug, HasTranslations, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'short_description',
        'description',
        'slug',
        'base_price',
        'price_unit',
        'price_on_request',
        'featured_image',
        'specifications',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'view_count',
        'inquiry_count',
        'order',
        'is_active',
        'is_featured',
        'is_new',
        'is_bestseller',
    ];

    public array $translatable = [
        'name',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'name' => 'array',
        'short_description' => 'array',
        'description' => 'array',
        'specifications' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
        'base_price' => 'decimal:2',
        'price_on_request' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn($model) => $model->getTranslation('name', 'id'))
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useFallbackUrl('/images/placeholder-product.jpg');

        $this->addMediaCollection('documents');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productMedia(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('order');
    }

    public function marketplaceLinks(): HasMany
    {
        return $this->hasMany(ProductMarketplaceLink::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProductDocument::class)->orderBy('order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }

    public function productFaqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->orderBy('order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->where('is_active', true)->orderBy('order');
    }

    public function allVariants(): HasMany
    {
        return $this->hasMany(Variant::class)->orderBy('order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class)->where('is_active', true)->orderBy('order');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementInquiryCount(): void
    {
        $this->increment('inquiry_count');
    }

    public function getAverageRating(): ?float
    {
        return $this->approvedReviews()->avg('rating');
    }

    public function getReviewCount(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'sku', 'base_price', 'is_active', 'is_featured'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
