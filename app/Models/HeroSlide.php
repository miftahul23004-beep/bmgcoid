<?php

namespace App\Models;

use App\Observers\HeroSlideObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

#[ObservedBy([HeroSlideObserver::class])]
class HeroSlide extends Model
{
    protected $fillable = [
        'title_id',
        'title_en',
        'subtitle_id',
        'subtitle_en',
        'image',
        'mobile_image',
        'gradient_class',
        'text_color',
        'primary_button_text_id',
        'primary_button_text_en',
        'primary_button_url',
        'secondary_button_text_id',
        'secondary_button_text_en',
        'secondary_button_url',
        'badge_text_id',
        'badge_text_en',
        'is_active',
        'order',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get title based on current locale
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->title_en 
            ? $this->title_en 
            : $this->title_id;
    }

    /**
     * Get subtitle based on current locale
     */
    public function getSubtitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->subtitle_en 
            ? $this->subtitle_en 
            : $this->subtitle_id;
    }

    /**
     * Get primary button text based on current locale
     */
    public function getPrimaryButtonTextAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->primary_button_text_en 
            ? $this->primary_button_text_en 
            : $this->primary_button_text_id;
    }

    /**
     * Get secondary button text based on current locale
     */
    public function getSecondaryButtonTextAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->secondary_button_text_en 
            ? $this->secondary_button_text_en 
            : $this->secondary_button_text_id;
    }

    /**
     * Get badge text based on current locale
     */
    public function getBadgeTextAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'en' && $this->badge_text_en 
            ? $this->badge_text_en 
            : $this->badge_text_id;
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return '/images/hero-default.jpg';
        }
        
        // Check if it's already a URL or path starting with /
        if (str_starts_with($this->image, 'http') || str_starts_with($this->image, '/')) {
            return $this->image;
        }
        
        return Storage::url($this->image);
    }

    /**
     * Get mobile image URL
     */
    public function getMobileImageUrlAttribute(): ?string
    {
        if (!$this->mobile_image) {
            return $this->image_url;
        }
        
        if (str_starts_with($this->mobile_image, 'http') || str_starts_with($this->mobile_image, '/')) {
            return $this->mobile_image;
        }
        
        return Storage::url($this->mobile_image);
    }

    /**
     * Scope for active slides
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for scheduled slides (within date range)
     */
    public function scopeScheduled(Builder $query): Builder
    {
        $now = now();
        
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $now);
        });
    }

    /**
     * Scope ordered
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    /**
     * Get all displayable slides
     */
    public static function getDisplayableSlides()
    {
        return static::active()
            ->scheduled()
            ->ordered()
            ->get();
    }
}
