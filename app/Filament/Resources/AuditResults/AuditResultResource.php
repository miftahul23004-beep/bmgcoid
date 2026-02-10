<?php

namespace App\Filament\Resources\AuditResults;

use App\Filament\Resources\AuditResults\Tables\AuditResultsTable;
use App\Filament\Resources\AuditResults\Pages;
use App\Models\AuditResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditResultResource extends Resource
{
    protected static ?string $model = AuditResult::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Admin']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public static function getNavigationLabel(): string
    {
        return __('Audit Results');
    }

    public static function getModelLabel(): string
    {
        return __('Audit Result');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Audit Results');
    }

    public static function table(Table $table): Table
    {
        return AuditResultsTable::make($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditResults::route('/'),
            'view' => Pages\ViewAuditResult::route('/{record}'),
        ];
    }
}
