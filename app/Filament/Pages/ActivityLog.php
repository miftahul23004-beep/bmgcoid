<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityLog extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Activity Log';
    protected static string|UnitEnum|null $navigationGroup = null;
    protected static ?int $navigationSort = 5;
    protected string $view = 'filament.pages.activity-log';

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Action')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ]),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $parts = explode('\\', $state);
                        return end($parts);
                    }),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
                Tables\Filters\SelectFilter::make('subject_type')
                    ->label('Model')
                    ->options([
                        'App\\Models\\Product' => 'Product',
                        'App\\Models\\Category' => 'Category',
                        'App\\Models\\Article' => 'Article',
                        'App\\Models\\User' => 'User',
                        'App\\Models\\Setting' => 'Setting',
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->modalContent(function (Activity $record) {
                        return view('filament.pages.activity-log-detail', ['activity' => $record]);
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
