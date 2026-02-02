<?php

namespace App\Filament\Widgets;

use App\Models\PerformanceLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerformanceStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        // Today's stats
        $todayStats = PerformanceLog::where('created_at', '>=', $today)
            ->selectRaw('COUNT(*) as total_requests')
            ->selectRaw('AVG(response_time) as avg_response_time')
            ->selectRaw('SUM(CASE WHEN status_code >= 500 THEN 1 ELSE 0 END) as error_count')
            ->first();

        // Yesterday's stats for comparison
        $yesterdayStats = PerformanceLog::whereBetween('created_at', [$yesterday, $today])
            ->selectRaw('AVG(response_time) as avg_response_time')
            ->first();

        // Calculate trends
        $avgResponseToday = $todayStats->avg_response_time ?? 0;
        $avgResponseYesterday = $yesterdayStats->avg_response_time ?? 0;
        $responseTrend = $avgResponseYesterday > 0 
            ? (($avgResponseToday - $avgResponseYesterday) / $avgResponseYesterday) * 100 
            : 0;

        // Uptime (percentage of non-error responses)
        $uptime = $todayStats->total_requests > 0
            ? round((($todayStats->total_requests - $todayStats->error_count) / $todayStats->total_requests) * 100, 2)
            : 100;

        // Slow requests (> 1 second)
        $slowRequests = PerformanceLog::where('created_at', '>=', $today)
            ->where('response_time', '>', 1)
            ->count();

        return [
            Stat::make(
                __('Avg Response Time'),
                round($avgResponseToday * 1000, 0) . 'ms'
            )
                ->description($this->formatTrend($responseTrend, true))
                ->descriptionIcon($responseTrend <= 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up')
                ->color($this->getResponseTimeColor($avgResponseToday * 1000)),

            Stat::make(
                __('Uptime'),
                $uptime . '%'
            )
                ->description(__('Today'))
                ->color($uptime >= 99.9 ? 'success' : ($uptime >= 99 ? 'warning' : 'danger')),

            Stat::make(
                __('Slow Requests'),
                $slowRequests
            )
                ->description(__('> 1 second today'))
                ->color($slowRequests === 0 ? 'success' : ($slowRequests < 10 ? 'warning' : 'danger')),

            Stat::make(
                __('Total Requests'),
                number_format($todayStats->total_requests ?? 0)
            )
                ->description(__('Today'))
                ->color('info'),
        ];
    }

    protected function formatTrend(float $percentage, bool $lowerIsBetter = false): string
    {
        $formatted = ($percentage >= 0 ? '+' : '') . round($percentage, 1) . '%';
        
        if ($lowerIsBetter) {
            return $percentage < 0 ? __('Faster') . " ($formatted)" : __('Slower') . " ($formatted)";
        }
        
        return $percentage > 0 ? __('Increase') . " ($formatted)" : __('Decrease') . " ($formatted)";
    }

    protected function getResponseTimeColor(float $ms): string
    {
        if ($ms < 200) return 'success';
        if ($ms < 500) return 'info';
        if ($ms < 1000) return 'warning';
        return 'danger';
    }
}
