<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductFaqsRelationManager extends RelationManager
{
    protected static string $relationship = 'productFaqs';

    protected static ?string $title = 'FAQ';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('faq_translations')->tabs([
                    Tabs\Tab::make('🇮🇩 Indonesia')->schema([
                        TextInput::make('question.id')
                            ->label(__('Question (ID)'))
                            ->required()
                            ->maxLength(500),
                        Textarea::make('answer.id')
                            ->label(__('Answer (ID)'))
                            ->required()
                            ->rows(4),
                    ]),
                    Tabs\Tab::make('🇬🇧 English')->schema([
                        TextInput::make('question.en')
                            ->label(__('Question (EN)'))
                            ->maxLength(500),
                        Textarea::make('answer.en')
                            ->label(__('Answer (EN)'))
                            ->rows(4),
                    ]),
                ]),

                Grid::make(2)->schema([
                    TextInput::make('order')
                        ->label(__('Order'))
                        ->numeric()
                        ->default(0),
                    Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label(__('Question'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('question', 'id'))
                    ->searchable()
                    ->wrap()
                    ->limit(60),
                TextColumn::make('answer')
                    ->label(__('Answer'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('answer', 'id'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label(__('Active'))
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
