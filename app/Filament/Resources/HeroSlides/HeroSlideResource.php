<?php

namespace App\Filament\Resources\HeroSlides;

use App\Filament\Resources\HeroSlides\Pages\CreateHeroSlide;
use App\Filament\Resources\HeroSlides\Pages\EditHeroSlide;
use App\Filament\Resources\HeroSlides\Pages\ListHeroSlides;
use App\Filament\Resources\HeroSlides\Schemas\HeroSlideForm;
use App\Filament\Resources\HeroSlides\Tables\HeroSlidesTable;
use App\Models\HeroSlide;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage hero slides') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage hero slides') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('manage hero slides') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('manage hero slides') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function getNavigationLabel(): string
    {
        return __('Hero Slider');
    }

    public static function getModelLabel(): string
    {
        return __('Hero Slide');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Hero Slides');
    }

    public static function form(Schema $schema): Schema
    {
        return HeroSlideForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroSlidesTable::configure($table);
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
            'index' => ListHeroSlides::route('/'),
            'create' => CreateHeroSlide::route('/create'),
            'edit' => EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
