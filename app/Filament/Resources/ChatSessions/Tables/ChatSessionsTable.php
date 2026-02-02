<?php

namespace App\Filament\Resources\ChatSessions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChatSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('session_id')
                    ->searchable(),
                TextColumn::make('operator.name')
                    ->searchable(),
                TextColumn::make('visitor_name')
                    ->searchable(),
                TextColumn::make('visitor_email')
                    ->searchable(),
                TextColumn::make('visitor_phone')
                    ->searchable(),
                TextColumn::make('visitor_ip')
                    ->searchable(),
                TextColumn::make('visitor_user_agent')
                    ->searchable(),
                TextColumn::make('page_url')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('handler')
                    ->badge(),
                TextColumn::make('message_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
