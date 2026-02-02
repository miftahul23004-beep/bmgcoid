<?php

namespace App\Filament\Resources\AuditResults\Pages;

use App\Filament\Resources\AuditResults\AuditResultResource;
use App\Models\AuditResult;
use App\Services\Audit\PerformanceAuditService;
use App\Services\Audit\SeoAuditService;
use App\Services\Audit\SecurityAuditService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ViewAuditResult extends ViewRecord
{
    protected static string $resource = AuditResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('rerun')
                ->label(__('Run Again'))
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function () {
                    $record = $this->record;
                    
                    try {
                        $service = match ($record->audit_type) {
                            'performance' => app(PerformanceAuditService::class),
                            'seo' => app(SeoAuditService::class),
                            'security' => app(SecurityAuditService::class),
                            default => null,
                        };

                        if ($service) {
                            $result = $service->audit($record->url, $record->page_type ?? 'page');
                            
                            Notification::make()
                                ->title(__('Audit Completed'))
                                ->body(__('New audit result has been recorded.'))
                                ->success()
                                ->send();

                            return redirect(AuditResultResource::getUrl('view', ['record' => $result->id]));
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('Audit Failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        $record = $this->getRecord();
        
        return $schema
            ->schema([
                Section::make(__('Audit Information'))
                    ->schema([
                        Grid::make(12)->schema([
                            Group::make([
                                Text::make(__('URL'))->weight('bold'),
                                Text::make($record->url),
                            ])->columnSpan(6),
                            Group::make([
                                Text::make(__('Type'))->weight('bold'),
                                Text::make(ucfirst($record->audit_type)),
                            ])->columnSpan(3),
                            Group::make([
                                Text::make(__('Date'))->weight('bold'),
                                Text::make($record->created_at->format('d M Y H:i')),
                            ])->columnSpan(3),
                        ]),
                    ]),

                Section::make(__('Scores'))
                    ->schema([
                        Flex::make([
                            $this->makeScoreGroup(__('Performance'), $record->performance_score),
                            $this->makeScoreGroup(__('SEO'), $record->seo_score),
                            $this->makeScoreGroup(__('Accessibility'), $record->accessibility_score),
                            $this->makeScoreGroup(__('Security'), $record->best_practices_score),
                        ]),
                    ]),

                Section::make(__('Performance Metrics'))
                    ->visible($record->audit_type === 'performance')
                    ->schema([
                        Flex::make([
                            Group::make([
                                Text::make(__('Load Time'))->weight('bold'),
                                Text::make($record->load_time ? number_format($record->load_time) . 'ms' : '-'),
                            ]),
                            Group::make([
                                Text::make(__('Page Size'))->weight('bold'),
                                Text::make($record->page_size ? $this->formatBytes($record->page_size) : '-'),
                            ]),
                            Group::make([
                                Text::make(__('Requests'))->weight('bold'),
                                Text::make((string) ($record->request_count ?? '-')),
                            ]),
                        ]),
                    ]),

                Section::make(__('Issues Found') . ' (' . $record->issues->count() . ')')
                    ->visible($record->issues->isNotEmpty())
                    ->collapsible()
                    ->schema(
                        $record->issues->map(function ($issue) {
                            $severity = match ($issue->severity) {
                                'critical' => '🔴',
                                'warning' => '🟡',
                                'info' => '🔵',
                                default => '⚪',
                            };
                            return Text::make("{$severity} [{$issue->category}] {$issue->title}: {$issue->description}");
                        })->toArray()
                    ),

                Section::make(__('Raw Data'))
                    ->collapsed()
                    ->collapsible()
                    ->visible(!empty($record->raw_data))
                    ->schema([
                        Text::make(json_encode($record->raw_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                            ->fontFamily('mono'),
                    ]),
            ]);
    }

    protected function makeScoreGroup(string $label, ?int $score): Group
    {
        $display = $score !== null ? "{$score}/100" : '-';
        $color = $this->getScoreColor($score);
        return Group::make([
            Text::make($label)->weight('bold'),
            Text::make($display)->color($color)->weight('bold')->size('lg'),
        ]);
    }

    protected function makeScoreText(string $label, ?int $score): Text
    {
        $display = $score !== null ? "{$score}/100" : '-';
        $color = $this->getScoreColor($score);
        return Text::make("{$label}: {$display}")->color($color);
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }
        return round($bytes, 1) . ' ' . $units[$unitIndex];
    }

    protected function getScoreColor(?int $score): string
    {
        if ($score === null) return 'gray';
        if ($score >= 90) return 'success';
        if ($score >= 50) return 'warning';
        return 'danger';
    }
}
