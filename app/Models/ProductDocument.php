<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProductDocument extends Model
{
    use HasTranslations;

    protected $fillable = [
        'product_id',
        'title',
        'type',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'download_count',
        'order',
        'is_public',
    ];

    public array $translatable = [
        'title',
    ];

    protected $casts = [
        'title' => 'array',
        'is_public' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    public function getFileSizeForHumans(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
