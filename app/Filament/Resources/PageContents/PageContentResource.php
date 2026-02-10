<?php

namespace App\Filament\Resources\PageContents;

use App\Filament\Resources\PageContents\Pages\CreatePageContent;
use App\Filament\Resources\PageContents\Pages\EditPageContent;
use App\Filament\Resources\PageContents\Pages\ListPageContents;
use App\Filament\Resources\PageContents\Schemas\PageContentForm;
use App\Filament\Resources\PageContents\Tables\PageContentsTable;
use App\Models\PageContent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PageContentResource extends Resource
{
    protected static ?string $model = PageContent::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage settings') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage settings') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('manage settings') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function getNavigationLabel(): string
    {
        return __('Page Contents');
    }

    public static function getModelLabel(): string
    {
        return __('Page Content');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Page Contents');
    }

    public static function form(Schema $schema): Schema
    {
        return PageContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageContentsTable::configure($table);
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
            'index' => ListPageContents::route('/'),
            'create' => CreatePageContent::route('/create'),
            'edit' => EditPageContent::route('/{record}/edit'),
        ];
    }
}
