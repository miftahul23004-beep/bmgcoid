<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'key',
        'name',
        'name_en',
        'description',
        'is_active',
        'sort_order',
        'bg_color',
        'bg_gradient',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'array',
    ];

    /**
     * Get active sections ordered by sort_order (cached for 10 minutes)
     */
    public static function getActiveSections(): \Illuminate\Database\Eloquent\Collection
    {
        return \Illuminate\Support\Facades\Cache::remember('homepage_sections.active', 600, function () {
            return static::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Check if a section is active
     */
    public static function isActive(string $key): bool
    {
        return static::where('key', $key)->where('is_active', true)->exists();
    }

    /**
     * Get section by key
     */
    public static function getByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    /**
     * Get background class for the section
     */
    public function getBgClassAttribute(): string
    {
        if ($this->bg_gradient) {
            return $this->bg_gradient;
        }

        return match ($this->bg_color) {
            'white' => 'bg-white',
            'gray-50' => 'bg-gradient-to-br from-gray-50 to-white',
            'gray-100' => 'bg-gray-100',
            'primary' => 'bg-primary-50',
            'secondary' => 'bg-secondary-50',
            'dark' => 'bg-gray-900 text-white',
            'gradient-primary' => 'bg-gradient-to-br from-primary-600 via-primary-500 to-primary-400 text-white',
            'gradient-secondary' => 'bg-gradient-to-br from-secondary-600 via-secondary-500 to-secondary-400 text-white',
            'gradient-dark' => 'bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white',
            default => 'bg-white',
        };
    }

    /**
     * Get localized name
     */
    public function getLocalizedNameAttribute(): string
    {
        if (app()->getLocale() === 'en' && $this->name_en) {
            return $this->name_en;
        }
        return $this->name;
    }
}
