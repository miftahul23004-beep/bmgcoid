<?php

namespace App\Filament\Widgets;

use App\Models\PerformanceLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PerformanceChartWidget extends ChartWidget
{
    protected ?string $heading = 'Response Time (Last 24 Hours)';

    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '120s';

    protected function getData(): array
    {
        $data = PerformanceLog::query()
            ->where('created_at', '>=', now()->subHours(24))
            ->select([
                DB::raw("DATE_FORMAT(created_at, '%H:00') as hour"),
                DB::raw('AVG(response_time) as avg_response_time'),
                DB::raw('MAX(response_time) as max_response_time'),
                DB::raw('COUNT(*) as request_count'),
            ])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $labels = $data->pluck('hour')->toArray();
        $avgResponseTimes = $data->pluck('avg_response_time')->map(fn ($v) => round($v * 1000, 2))->toArray();
        $maxResponseTimes = $data->pluck('max_response_time')->map(fn ($v) => round($v * 1000, 2))->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('Avg Response Time (ms)'),
                    'data' => $avgResponseTimes,
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => __('Max Response Time (ms)'),
                    'data' => $maxResponseTimes,
                    'borderColor' => 'rgba(239, 68, 68, 0.8)',
                    'backgroundColor' => 'transparent',
                    'borderDash' => [5, 5],
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => __('Response Time (ms)'),
                    ],
                ],
            ],
        ];
    }
}
