<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\ProductMedia;
use App\Services\ImageOptimizationService;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductMediaRelationManager extends RelationManager
{
    protected static string $relationship = 'productMedia';

    protected static ?string $title = 'Gallery';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(2)->schema([
                    Select::make('type')
                        ->label(__('Media Type'))
                        ->options([
                            'image' => __('Image'),
                            'youtube' => __('YouTube'),
                        ])
                        ->default('image')
                        ->required()
                        ->reactive(),
                    TextInput::make('order')
                        ->label(__('Order'))
                        ->numeric()
                        ->default(0),
                ]),

                FileUpload::make('file_path')
                    ->label(__('Image File'))
                    ->disk('public')
                    ->directory('products/media')
                    ->visibility('public')
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->visible(fn ($get) => $get('type') === 'image')
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                        $service = app(ImageOptimizationService::class);
                        return $service->processUpload($file, 'products/media', 10, 744, 496);
                    })
                    ->helperText(__('Auto WebP & resize 744×496px, max 10KB'))
                    ->columnSpanFull(),

                TextInput::make('youtube_url')
                    ->label(__('YouTube URL'))
                    ->url()
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->visible(fn ($get) => $get('type') === 'youtube')
                    ->columnSpanFull()
                    ->helperText(__('Paste YouTube video URL'))
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $youtubeId = ProductMedia::extractYoutubeId($state);
                            $set('youtube_id', $youtubeId);
                        }
                    }),

                TextInput::make('youtube_id')
                    ->label(__('YouTube ID'))
                    ->visible(fn ($get) => $get('type') === 'youtube')
                    ->disabled()
                    ->dehydrated()
                    ->helperText(__('Auto-extracted from URL')),

                Tabs::make('media_translations')->tabs([
                    Tabs\Tab::make('🇮🇩 Indonesia')->schema([
                        TextInput::make('alt_text.id')
                            ->label(__('Alt Text (ID)'))
                            ->maxLength(255),
                        TextInput::make('caption.id')
                            ->label(__('Caption (ID)'))
                            ->maxLength(255),
                    ]),
                    Tabs\Tab::make('🇬🇧 English')->schema([
                        TextInput::make('alt_text.en')
                            ->label(__('Alt Text (EN)'))
                            ->maxLength(255),
                        TextInput::make('caption.en')
                            ->label(__('Caption (EN)'))
                            ->maxLength(255),
                    ]),
                ]),

                Toggle::make('is_primary')
                    ->label(__('Primary Image'))
                    ->helperText(__('Set as primary gallery image')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label(__('Preview'))
                    ->disk('public')
                    ->size(60)
                    ->square()
                    ->defaultImageUrl(fn ($record) => 
                        $record->type === 'youtube' && $record->youtube_id 
                            ? "https://img.youtube.com/vi/{$record->youtube_id}/default.jpg"
                            : null
                    ),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'youtube' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('alt_text')
                    ->label(__('Alt Text'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('alt_text', 'id') ?? '-')
                    ->limit(30),
                IconColumn::make('is_primary')
                    ->label(__('Primary'))
                    ->boolean(),
                TextColumn::make('order')
                    ->label(__('Order'))
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
