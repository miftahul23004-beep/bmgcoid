<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPageAnalysis extends Model
{
    protected $table = 'seo_page_analyses';

    protected $fillable = [
        'url',
        'page_type',
        'meta_title',
        'meta_title_length',
        'meta_description',
        'meta_description_length',
        'canonical_url',
        'has_robots_meta',
        'h1_tag',
        'h1_count',
        'h2_count',
        'h3_count',
        'word_count',
        'image_count',
        'images_without_alt',
        'internal_links_count',
        'external_links_count',
        'broken_links_count',
        'has_structured_data',
        'has_og_tags',
        'has_twitter_cards',
        'is_mobile_friendly',
        'has_ssl',
        'seo_score',
        'issues',
        'suggestions',
        'last_analyzed_at',
    ];

    protected $casts = [
        'meta_title_length' => 'integer',
        'meta_description_length' => 'integer',
        'has_robots_meta' => 'boolean',
        'h1_count' => 'integer',
        'h2_count' => 'integer',
        'h3_count' => 'integer',
        'word_count' => 'integer',
        'image_count' => 'integer',
        'images_without_alt' => 'integer',
        'internal_links_count' => 'integer',
        'external_links_count' => 'integer',
        'broken_links_count' => 'integer',
        'has_structured_data' => 'boolean',
        'has_og_tags' => 'boolean',
        'has_twitter_cards' => 'boolean',
        'is_mobile_friendly' => 'boolean',
        'has_ssl' => 'boolean',
        'seo_score' => 'integer',
        'issues' => 'array',
        'suggestions' => 'array',
        'last_analyzed_at' => 'datetime',
    ];

    public function scopeForUrl($query, string $url)
    {
        return $query->where('url', $url);
    }

    public function scopePageType($query, string $pageType)
    {
        return $query->where('page_type', $pageType);
    }

    public function scopeWithIssues($query)
    {
        return $query->whereNotNull('issues')
            ->whereRaw('JSON_LENGTH(issues) > 0');
    }

    public function scopeGoodScore($query, int $minScore = 80)
    {
        return $query->where('seo_score', '>=', $minScore);
    }

    public function scopePoorScore($query, int $maxScore = 50)
    {
        return $query->where('seo_score', '<', $maxScore);
    }

    public function scopeNeedsReanalysis($query, int $days = 7)
    {
        return $query->where(function ($q) use ($days) {
            $q->whereNull('last_analyzed_at')
                ->orWhere('last_analyzed_at', '<', now()->subDays($days));
        });
    }

    public function hasOptimalTitleLength(): bool
    {
        return $this->meta_title_length >= 50 && $this->meta_title_length <= 60;
    }

    public function hasOptimalDescriptionLength(): bool
    {
        return $this->meta_description_length >= 150 && $this->meta_description_length <= 160;
    }

    public function hasSingleH1(): bool
    {
        return $this->h1_count === 1;
    }

    public function hasImagesWithoutAlt(): bool
    {
        return $this->images_without_alt > 0;
    }

    public function hasBrokenLinks(): bool
    {
        return $this->broken_links_count > 0;
    }

    public function getScoreGrade(): string
    {
        return match (true) {
            $this->seo_score >= 90 => 'A',
            $this->seo_score >= 80 => 'B',
            $this->seo_score >= 70 => 'C',
            $this->seo_score >= 60 => 'D',
            default => 'F',
        };
    }
}
