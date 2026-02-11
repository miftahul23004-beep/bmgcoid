<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Services\ImageOptimizationService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('author_id')
                                    ->relationship('author', 'name')
                                    ->label('Author')
                                    ->searchable()
                                    ->preload()
                                    ->default(auth()->id()),
                                Select::make('type')
                                    ->options([
                                        'article' => 'Article',
                                        'news' => 'News',
                                        'tips' => 'Tips',
                                        'tutorial' => 'Tutorial',
                                    ])
                                    ->default('article')
                                    ->required(),
                            ]),
                        TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->helperText('Leave empty to auto-generate from Indonesian title')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),

                Tabs::make('Content')
                    ->tabs([
                        Tab::make('🇮🇩 Indonesia')
                            ->schema([
                                TextInput::make('title.id')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        if (empty($get('slug'))) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                Textarea::make('excerpt.id')
                                    ->label('Ringkasan')
                                    ->rows(3)
                                    ->helperText('Ringkasan singkat artikel (untuk preview)'),
                                RichEditor::make('content.id')
                                    ->label('Konten Artikel')
                                    ->required()
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('🇬🇧 English')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title')
                                    ->maxLength(255),
                                Textarea::make('excerpt.en')
                                    ->label('Excerpt')
                                    ->rows(3)
                                    ->helperText('Short summary of article (for preview)'),
                                RichEditor::make('content.en')
                                    ->label('Article Content')
                                    ->toolbarButtons([
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Featured Image')
                    ->schema([
                        FileUpload::make('featured_image')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('articles')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(10240) // 10MB max upload
                            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                $service = app(ImageOptimizationService::class);
                                return $service->processUpload($file, 'articles', 10);
                            })
                            ->helperText('Upload gambar (JPG, PNG, WebP). Akan otomatis dikonversi ke WebP max 10KB.'),
                    ]),

                Tabs::make('SEO')
                    ->tabs([
                        Tab::make('🇮🇩 SEO Indonesia')
                            ->schema([
                                TextInput::make('meta_title.id')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->helperText('Maksimal 60 karakter. Kosongkan untuk menggunakan judul artikel.'),
                                Textarea::make('meta_description.id')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText('Maksimal 160 karakter. Kosongkan untuk menggunakan ringkasan.'),
                                TextInput::make('meta_keywords.id')
                                    ->label('Meta Keywords')
                                    ->helperText('Pisahkan dengan koma'),
                            ]),
                        Tab::make('🇬🇧 SEO English')
                            ->schema([
                                TextInput::make('meta_title.en')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->helperText('Max 60 characters. Leave empty to use article title.'),
                                Textarea::make('meta_description.en')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText('Max 160 characters. Leave empty to use excerpt.'),
                                TextInput::make('meta_keywords.en')
                                    ->label('Meta Keywords')
                                    ->helperText('Separate with commas'),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Publishing')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'scheduled' => 'Scheduled',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->live(),
                                DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->visible(fn ($get) => in_array($get('status'), ['published', 'scheduled'])),
                                Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->helperText('Show on homepage'),
                                Toggle::make('allow_comments')
                                    ->label('Allow Comments')
                                    ->default(true),
                            ]),
                    ]),

                Section::make('Statistics')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('view_count')
                                    ->label('Views')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(),
                                TextInput::make('share_count')
                                    ->label('Shares')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled(),
                            ]),
                    ])
                    ->collapsed()
                    ->visible(fn ($record) => $record !== null),
            ]);
    }
}
