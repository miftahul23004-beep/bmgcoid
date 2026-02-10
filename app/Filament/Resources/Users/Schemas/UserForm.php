<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Services\ImageOptimizationService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->rule(Password::default())
                                    ->revealable()
                                    ->helperText(fn (string $operation): string => $operation === 'edit' ? 'Leave blank to keep current password' : ''),
                                TextInput::make('password_confirmation')
                                    ->label('Confirm Password')
                                    ->password()
                                    ->revealable()
                                    ->same('password')
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(false),
                            ]),
                    ]),

                Section::make('Profile & Roles')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('avatar')
                                    ->label('Avatar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('avatars')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120)
                                    ->saveUploadedFileUsing(function ($file) {
                                        $service = app(ImageOptimizationService::class);
                                        return $service->processUpload($file, 'avatars', 30);
                                    })
                                    ->helperText('Auto-convert to WebP max 30KB')
                                    ->visibility('public')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('200')
                                    ->imageResizeTargetHeight('200')
                                    ->default(null),
                                Select::make('roles')
                                    ->label('Roles')
                                    ->relationship('roles', 'name', function ($query) {
                                        // Non-Super Admin users cannot see/assign Super Admin role
                                        if (!auth()->user()?->hasRole('Super Admin')) {
                                            $query->where('name', '!=', 'Super Admin');
                                        }
                                    })
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                            ]),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive users cannot login to admin panel')
                            ->default(true),
                    ]),
            ]);
    }
}
