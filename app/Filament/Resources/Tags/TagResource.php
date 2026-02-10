<?php

namespace App\Filament\Resources\Tags;

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Models\Tag;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view products') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create products') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('edit products') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('delete products') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('Products');
    }

    public static function getNavigationLabel(): string
    {
        return __('Tags');
    }

    public static function getModelLabel(): string
    {
        return __('Tag');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tags');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('Tag Information'))
                    ->description(__('Multi-language tag name'))
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Tabs::make('name_tabs')->tabs([
                            Tabs\Tab::make('🇮🇩 Indonesia')->schema([
                                TextInput::make('name.id')
                                    ->label(__('Tag Name (ID)'))
                                    ->required()
                                    ->maxLength(100)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                            ]),
                            Tabs\Tab::make('🇬🇧 English')->schema([
                                TextInput::make('name.en')
                                    ->label(__('Tag Name (EN)'))
                                    ->maxLength(100)
                                    ->helperText(__('Leave empty to use Indonesian name')),
                            ]),
                        ]),
                    ]),

                Section::make(__('Settings'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('slug')
                                ->label(__('Slug'))
                                ->required()
                                ->maxLength(100)
                                ->unique(Tag::class, 'slug', ignoreRecord: true)
                                ->helperText(__('Auto-generated from Indonesian name')),
                            ColorPicker::make('color')
                                ->label(__('Tag Color'))
                                ->helperText(__('Optional color for visual distinction')),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name (ID)'))
                    ->getStateUsing(fn (Tag $record) => $record->getTranslation('name', 'id'))
                    ->searchable(query: function ($query, string $search) {
                        return $query->where('name->id', 'like', "%{$search}%")
                            ->orWhere('name->en', 'like', "%{$search}%");
                    })
                    ->sortable(),
                TextColumn::make('name_en')
                    ->label(__('Name (EN)'))
                    ->getStateUsing(fn (Tag $record) => $record->getTranslation('name', 'en') ?: '-')
                    ->toggleable(),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->copyable()
                    ->toggleable(),
                ColorColumn::make('color')
                    ->label(__('Color'))
                    ->toggleable(),
                TextColumn::make('articles_count')
                    ->label(__('Articles'))
                    ->counts('articles')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name->id');
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
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }
}
