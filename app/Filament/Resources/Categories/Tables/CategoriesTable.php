<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Category::query()
                    ->withCount(['products' => function ($query) {
                        $query->where('is_active', true);
                    }])
                    ->with('parent')
                    ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, `order`')
            )
            ->columns([
                ImageColumn::make('icon')
                    ->label(__('Icon'))
                    ->disk('public')
                    ->size(40)
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        $name = $record->getTranslation('name', 'id');
                        
                        if ($record->parent_id) {
                            // Child category with indent
                            return new HtmlString('<span class="text-gray-400 mr-2">└──</span><span>' . e($name) . '</span>');
                        }
                        
                        // Parent category in bold
                        return new HtmlString('<span class="font-bold text-gray-900 dark:text-white">' . e($name) . '</span>');
                    }),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->searchable()
                    ->color('gray')
                    ->size('sm')
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->label(__('Image'))
                    ->disk('public')
                    ->size(60)
                    ->square(),
                TextColumn::make('products_count')
                    ->label(__('Products'))
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
                TextColumn::make('order')
                    ->label(__('Order'))
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->alignCenter(),
                IconColumn::make('is_featured')
                    ->label(__('Featured'))
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('parent_id')
                    ->label(__('Parent Category'))
                    ->options(
                        Category::whereNull('parent_id')
                            ->pluck('name', 'id')
                            ->map(fn ($name) => is_array($name) ? ($name['id'] ?? $name['en'] ?? '') : $name)
                    )
                    ->placeholder(__('All Categories')),
                SelectFilter::make('is_active')
                    ->label(__('Status'))
                    ->options([
                        '1' => __('Active'),
                        '0' => __('Inactive'),
                    ]),
                SelectFilter::make('is_featured')
                    ->label(__('Featured'))
                    ->options([
                        '1' => __('Featured'),
                        '0' => __('Not Featured'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
