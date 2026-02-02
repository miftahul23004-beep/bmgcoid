<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Variant extends Model
{
    use HasTranslations;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'size',
        'thickness',
        'length',
        'width',
        'weight',
        'grade',
        'finish',
        'price',
        'price_unit',
        'min_order',
        'stock_status',
        'order',
        'is_active',
    ];

    public array $translatable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('stock_status', 'available');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function isAvailable(): bool
    {
        return $this->stock_status === 'available';
    }

    public function isLimited(): bool
    {
        return $this->stock_status === 'limited';
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_status === 'out_of_stock';
    }
}
