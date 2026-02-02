<?php

namespace App\Filament\Resources\Products\RelationManagers;

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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Documents';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('document_translations')->tabs([
                    Tabs\Tab::make('🇮🇩 Indonesia')->schema([
                        TextInput::make('title.id')
                            ->label(__('Document Title (ID)'))
                            ->required()
                            ->maxLength(255),
                    ]),
                    Tabs\Tab::make('🇬🇧 English')->schema([
                        TextInput::make('title.en')
                            ->label(__('Document Title (EN)'))
                            ->maxLength(255),
                    ]),
                ]),

                Grid::make(2)->schema([
                    Select::make('type')
                        ->label(__('Document Type'))
                        ->options([
                            'datasheet' => __('Datasheet'),
                            'brochure' => __('Brochure'),
                            'certificate' => __('Certificate'),
                            'manual' => __('Manual'),
                            'specification' => __('Specification'),
                            'other' => __('Other'),
                        ])
                        ->default('datasheet')
                        ->required(),
                    TextInput::make('order')
                        ->label(__('Order'))
                        ->numeric()
                        ->default(0),
                ]),

                FileUpload::make('file_path')
                    ->label(__('Document File'))
                    ->directory('products/documents')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->maxSize(20480)
                    ->required()
                    ->helperText(__('Accepted: PDF, DOC, DOCX. Max 20MB'))
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $fileName = is_string($state) ? basename($state) : $state->getClientOriginalName();
                            $set('file_name', $fileName);
                            
                            // Extract file extension for file_type
                            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                            $set('file_type', $extension);
                            
                            // Get file size
                            if (!is_string($state)) {
                                $set('file_size', $state->getSize());
                            }
                        }
                    }),

                TextInput::make('file_name')
                    ->label(__('File Name'))
                    ->required()
                    ->maxLength(255)
                    ->helperText(__('Auto-filled from uploaded file')),

                TextInput::make('file_type')
                    ->label(__('File Type'))
                    ->default('pdf')
                    ->maxLength(10)
                    ->helperText(__('Auto-detected: pdf, doc, docx')),

                TextInput::make('file_size')
                    ->label(__('File Size (bytes)'))
                    ->numeric()
                    ->default(0)
                    ->helperText(__('Auto-calculated from file')),

                Toggle::make('is_public')
                    ->label(__('Public Download'))
                    ->helperText(__('Allow public download without login'))
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', 'id'))
                    ->searchable()
                    ->limit(40),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'datasheet' => 'info',
                        'brochure' => 'success',
                        'certificate' => 'warning',
                        'manual' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('file_type')
                    ->label(__('Format'))
                    ->formatStateUsing(fn ($state) => strtoupper($state ?? 'PDF')),
                TextColumn::make('download_count')
                    ->label(__('Downloads'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_public')
                    ->label(__('Public'))
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
