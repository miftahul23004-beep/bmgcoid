<?php

namespace App\Filament\Widgets;

use App\Models\AuditResult;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AuditScoresWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '300s';

    protected function getStats(): array
    {
        // Get latest audit scores for each type
        $latestPerformance = AuditResult::where('audit_type', 'performance')
            ->latest()
            ->first();

        $latestSeo = AuditResult::where('audit_type', 'seo')
            ->latest()
            ->first();

        $latestSecurity = AuditResult::where('audit_type', 'security')
            ->latest()
            ->first();

        // Calculate trends (compare with previous audit)
        $performanceTrend = $this->calculateTrend('performance', $latestPerformance);
        $seoTrend = $this->calculateTrend('seo', $latestSeo);
        $securityTrend = $this->calculateTrend('security', $latestSecurity);

        return [
            Stat::make(
                __('Performance Score'),
                $this->formatScore($latestPerformance?->performance_score)
            )
                ->description($performanceTrend['description'])
                ->descriptionIcon($performanceTrend['icon'])
                ->color($this->getScoreColor($latestPerformance?->performance_score))
                ->chart($this->getScoreHistory('performance', 'performance_score')),

            Stat::make(
                __('SEO Score'),
                $this->formatScore($latestSeo?->seo_score)
            )
                ->description($seoTrend['description'])
                ->descriptionIcon($seoTrend['icon'])
                ->color($this->getScoreColor($latestSeo?->seo_score))
                ->chart($this->getScoreHistory('seo', 'seo_score')),

            Stat::make(
                __('Security Score'),
                $this->formatScore($latestSecurity?->best_practices_score)
            )
                ->description($securityTrend['description'])
                ->descriptionIcon($securityTrend['icon'])
                ->color($this->getScoreColor($latestSecurity?->best_practices_score))
                ->chart($this->getScoreHistory('security', 'best_practices_score')),
        ];
    }

    protected function formatScore(?int $score): string
    {
        if ($score === null) {
            return __('No data');
        }
        return $score . '/100';
    }

    protected function calculateTrend(string $type, ?AuditResult $latest): array
    {
        if (!$latest) {
            return [
                'description' => __('Run audit to get score'),
                'icon' => 'heroicon-m-play',
            ];
        }

        $scoreField = match ($type) {
            'performance' => 'performance_score',
            'seo' => 'seo_score',
            'security' => 'best_practices_score',
            default => 'performance_score',
        };

        // If current score is null (audit failed)
        if ($latest->{$scoreField} === null) {
            return [
                'description' => __('Audit failed - check URL'),
                'icon' => 'heroicon-m-exclamation-triangle',
            ];
        }

        $previous = AuditResult::where('audit_type', $type)
            ->where('id', '<', $latest->id)
            ->whereNotNull($scoreField)
            ->latest()
            ->first();

        if (!$previous) {
            return [
                'description' => __('First audit'),
                'icon' => 'heroicon-m-arrow-right',
            ];
        }

        $diff = $latest->{$scoreField} - $previous->{$scoreField};

        if ($diff > 0) {
            return [
                'description' => '+' . $diff . ' ' . __('from previous'),
                'icon' => 'heroicon-m-arrow-trending-up',
            ];
        } elseif ($diff < 0) {
            return [
                'description' => $diff . ' ' . __('from previous'),
                'icon' => 'heroicon-m-arrow-trending-down',
            ];
        }

        return [
            'description' => __('No change'),
            'icon' => 'heroicon-m-minus',
        ];
    }

    protected function getScoreColor(?int $score): string
    {
        if ($score === null) return 'gray';
        if ($score >= 90) return 'success';
        if ($score >= 50) return 'warning';
        return 'danger';
    }

    protected function getScoreHistory(string $type, string $field): array
    {
        $results = AuditResult::where('audit_type', $type)
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->pluck($field)
            ->reverse()
            ->values()
            ->toArray();

        return count($results) > 0 ? $results : [0];
    }
}
