<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuditResult extends Model
{
    protected $fillable = [
        'url',
        'page_type',
        'audit_type',
        'performance_score',
        'seo_score',
        'accessibility_score',
        'best_practices_score',
        'fcp',
        'lcp',
        'cls',
        'tti',
        'tbt',
        'speed_index',
        'page_size',
        'request_count',
        'load_time',
        'raw_data',
        'notes',
        'source',
    ];

    protected $casts = [
        'performance_score' => 'integer',
        'seo_score' => 'integer',
        'accessibility_score' => 'integer',
        'best_practices_score' => 'integer',
        'fcp' => 'integer',
        'lcp' => 'integer',
        'cls' => 'decimal:4',
        'tti' => 'integer',
        'tbt' => 'integer',
        'speed_index' => 'integer',
        'page_size' => 'integer',
        'request_count' => 'integer',
        'load_time' => 'decimal:2',
        'raw_data' => 'array',
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(AuditIssue::class);
    }

    public function criticalIssues(): HasMany
    {
        return $this->hasMany(AuditIssue::class)->where('severity', 'critical');
    }

    public function openIssues(): HasMany
    {
        return $this->hasMany(AuditIssue::class)->where('status', 'open');
    }

    public function scopeForUrl($query, string $url)
    {
        return $query->where('url', $url);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('audit_type', $type);
    }

    public function scopeFromSource($query, string $source)
    {
        return $query->where('source', $source);
    }

    public function scopePageType($query, string $pageType)
    {
        return $query->where('page_type', $pageType);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getOverallScore(): ?float
    {
        $scores = array_filter([
            $this->performance_score,
            $this->seo_score,
            $this->accessibility_score,
            $this->best_practices_score,
        ]);

        return count($scores) > 0 ? array_sum($scores) / count($scores) : null;
    }

    public function hasGoodCoreWebVitals(): bool
    {
        return $this->lcp <= 2500 && $this->cls <= 0.1 && $this->fcp <= 1800;
    }
}
