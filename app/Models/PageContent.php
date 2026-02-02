<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PageContent extends Model
{
    use HasTranslations;

    protected $fillable = [
        'page',
        'section',
        'key',
        'content',
        'type',
        'order',
        'is_active',
    ];

    public array $translatable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopePage($query, string $page)
    {
        return $query->where('page', $page);
    }

    public function scopeSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public static function getContent(string $page, string $section, string $key, $default = null)
    {
        $content = static::where('page', $page)
            ->where('section', $section)
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        return $content ? $content->content : $default;
    }

    public static function getSection(string $page, string $section)
    {
        return static::where('page', $page)
            ->where('section', $section)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }
}
