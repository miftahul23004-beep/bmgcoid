<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProductMedia extends Model
{
    use HasTranslations;

    protected $fillable = [
        'product_id',
        'type',
        'file_path',
        'youtube_url',
        'youtube_id',
        'thumbnail',
        'alt_text',
        'caption',
        'order',
        'is_primary',
    ];

    public array $translatable = [
        'alt_text',
        'caption',
    ];

    protected $casts = [
        'alt_text' => 'array',
        'caption' => 'array',
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->type === 'youtube') {
            return "https://www.youtube.com/embed/{$this->youtube_id}";
        }

        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->type === 'youtube') {
            return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
        }

        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : $this->url;
    }

    public static function extractYoutubeId(?string $url): ?string
    {
        if (!$url) return null;
        
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}
