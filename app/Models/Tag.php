<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasSlug, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public array $translatable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn($model) => $model->getTranslation('name', 'id'))
            ->saveSlugsTo('slug');
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }

    public function publishedArticles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag')
            ->where('status', 'published')
            ->where('published_at', '<=', now());
    }
}
