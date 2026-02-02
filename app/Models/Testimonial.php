<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations;

    protected $fillable = [
        'client_id',
        'author_name',
        'author_position',
        'author_company',
        'author_photo',
        'content',
        'rating',
        'project_name',
        'order',
        'is_featured',
        'is_active',
    ];

    public array $translatable = [
        'content',
        'author_position',
        'project_name',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'content' => 'array',
        'author_position' => 'array',
        'project_name' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeWithRating($query, int $minRating = 1)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
