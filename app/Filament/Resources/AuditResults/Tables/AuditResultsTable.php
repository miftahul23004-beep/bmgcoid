<?php

namespace App\Filament\Resources\AuditResults\Tables;

use App\Models\AuditResult;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuditResultsTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label(__('URL'))
                    ->limit(40)
                    ->searchable()
                    ->copyable()
                    ->tooltip(fn ($record) => $record->url),

                Tables\Columns\TextColumn::make('audit_type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'performance' => 'info',
                        'seo' => 'success',
                        'security' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('performance_score')
                    ->label(__('Performance'))
                    ->formatStateUsing(fn ($state) => $state ? "{$state}/100" : '-')
                    ->color(fn ($state) => self::getScoreColor($state))
                    ->weight('bold')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('seo_score')
                    ->label(__('SEO'))
                    ->formatStateUsing(fn ($state) => $state ? "{$state}/100" : '-')
                    ->color(fn ($state) => self::getScoreColor($state))
                    ->weight('bold')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('best_practices_score')
                    ->label(__('Security'))
                    ->formatStateUsing(fn ($state) => $state ? "{$state}/100" : '-')
                    ->color(fn ($state) => self::getScoreColor($state))
                    ->weight('bold')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('issues_count')
                    ->label(__('Issues'))
                    ->counts('issues')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state === 0 => 'success',
                        $state < 5 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('page_type')
                    ->label(__('Page Type'))
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('source')
                    ->label(__('Source'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'internal' => 'gray',
                        'pagespeed' => 'info',
                        'scheduled' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('audit_type')
                    ->label(__('Audit Type'))
                    ->options([
                        'performance' => __('Performance'),
                        'seo' => __('SEO'),
                        'security' => __('Security'),
                    ]),

                Tables\Filters\SelectFilter::make('page_type')
                    ->label(__('Page Type'))
                    ->options([
                        'home' => __('Homepage'),
                        'product_list' => __('Product List'),
                        'product_detail' => __('Product Detail'),
                        'article_list' => __('Article List'),
                        'article_detail' => __('Article Detail'),
                        'about' => __('About'),
                        'contact' => __('Contact'),
                        'page' => __('Other Page'),
                    ]),

                Tables\Filters\Filter::make('score_below_90')
                    ->label(__('Score Below 90'))
                    ->query(fn (Builder $query): Builder => $query->where(function ($q) {
                        $q->where('performance_score', '<', 90)
                            ->orWhere('seo_score', '<', 90)
                            ->orWhere('best_practices_score', '<', 90);
                    }))
                    ->toggle(),

                Tables\Filters\Filter::make('has_critical_issues')
                    ->label(__('Has Critical Issues'))
                    ->query(fn (Builder $query): Builder => $query->whereHas('issues', fn ($q) => $q->where('severity', 'critical')))
                    ->toggle(),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('rerun')
                    ->label(__('Re-run Audit'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (AuditResult $record) {
                        $type = $record->audit_type;
                        $url = $record->url;
                        $pageType = $record->page_type;

                        // Dispatch job or run inline
                        \Artisan::call('audit:run', [
                            'url' => $url,
                            '--type' => $type,
                            '--page-type' => $pageType,
                        ]);
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('120s');
    }

    protected static function getScoreColor(?int $score): string
    {
        if ($score === null) {
            return 'gray';
        }

        return match (true) {
            $score >= 90 => 'success',
            $score >= 50 => 'warning',
            default => 'danger',
        };
    }
}
