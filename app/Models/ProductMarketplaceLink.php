<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMarketplaceLink extends Model
{
    protected $fillable = [
        'product_id',
        'platform',
        'url',
        'price',
        'click_count',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    public function getPlatformNameAttribute(): string
    {
        return config("marketplace.platforms.{$this->platform}.name", ucfirst($this->platform));
    }

    public function getPlatformColorAttribute(): string
    {
        return config("marketplace.platforms.{$this->platform}.color", '#000000');
    }

    public function getPlatformIconAttribute(): string
    {
        return config("marketplace.platforms.{$this->platform}.icon", 'shopping-cart');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
