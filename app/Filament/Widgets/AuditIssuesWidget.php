<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AuditResults\AuditResultResource;
use App\Models\AuditIssue;
use App\Models\AuditResult;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AuditIssuesWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Critical Issues to Fix';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AuditIssue::query()
                    ->where('severity', 'critical')
                    ->whereHas('auditResult', function ($query) {
                        $query->where('created_at', '>=', now()->subDays(7));
                    })
                    ->with('auditResult')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('auditResult.audit_type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'performance' => 'warning',
                        'seo' => 'success',
                        'security' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('Category'))
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('Issue'))
                    ->wrap()
                    ->limit(100),

                Tables\Columns\TextColumn::make('auditResult.url')
                    ->label(__('Page'))
                    ->url(fn ($state) => $state)
                    ->openUrlInNewTab()
                    ->limit(40),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Found'))
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('audit_type')
                    ->label(__('Type'))
                    ->relationship('auditResult', 'audit_type')
                    ->options([
                        'performance' => __('Performance'),
                        'seo' => __('SEO'),
                        'security' => __('Security'),
                    ]),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('View Details'))
                    ->icon('heroicon-o-eye')
                    ->url(fn (AuditIssue $record) => AuditResultResource::getUrl('view', [
                        'record' => $record->audit_result_id,
                    ])),
            ])
            ->emptyStateHeading(__('No Critical Issues'))
            ->emptyStateDescription(__('All audit scores are healthy!'))
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated([5]);
    }
}
