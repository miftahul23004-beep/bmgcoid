<?php

namespace App\Filament\Resources\AuditResults\Pages;

use App\Filament\Resources\AuditResults\AuditResultResource;
use App\Services\Audit\PerformanceAuditService;
use App\Services\Audit\SeoAuditService;
use App\Services\Audit\SecurityAuditService;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListAuditResults extends ListRecords
{
    protected static string $resource = AuditResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('run_audit')
                ->label(__('Run New Audit'))
                ->icon('heroicon-o-play')
                ->color('primary')
                ->form([
                    Forms\Components\TextInput::make('url')
                        ->label(__('URL to Audit'))
                        ->default(config('app.url'))
                        ->required()
                        ->url()
                        ->columnSpanFull(),

                    Forms\Components\CheckboxList::make('types')
                        ->label(__('Audit Types'))
                        ->options([
                            'performance' => __('Performance'),
                            'seo' => __('SEO'),
                            'security' => __('Security'),
                        ])
                        ->default(['performance', 'seo', 'security'])
                        ->required()
                        ->columns(3),

                    Forms\Components\Select::make('page_type')
                        ->label(__('Page Type'))
                        ->options([
                            'home' => __('Homepage'),
                            'product_list' => __('Product List'),
                            'product_detail' => __('Product Detail'),
                            'article_list' => __('Article List'),
                            'article_detail' => __('Article Detail'),
                            'about' => __('About'),
                            'contact' => __('Contact'),
                            'page' => __('Other Page'),
                        ])
                        ->default('page')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = $data['url'];
                    $types = $data['types'];
                    $pageType = $data['page_type'];

                    $results = [];

                    try {
                        if (in_array('performance', $types)) {
                            $result = app(PerformanceAuditService::class)->audit($url, $pageType);
                            $results['performance'] = $result->performance_score;
                        }

                        if (in_array('seo', $types)) {
                            $result = app(SeoAuditService::class)->audit($url, $pageType);
                            $results['seo'] = $result->seo_score;
                        }

                        if (in_array('security', $types)) {
                            $result = app(SecurityAuditService::class)->audit($url);
                            $results['security'] = $result->best_practices_score;
                        }

                        $scoreText = collect($results)
                            ->map(fn ($score, $type) => ucfirst($type) . ": {$score}/100")
                            ->implode(', ');

                        Notification::make()
                            ->title(__('Audit Completed'))
                            ->body($scoreText)
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('Audit Failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('audit_all_pages')
                ->label(__('Audit All Main Pages'))
                ->icon('heroicon-o-globe-alt')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(__('Audit All Main Pages'))
                ->modalDescription(__('This will run performance, SEO, and security audits on all main pages. This may take a few minutes.'))
                ->action(function () {
                    $baseUrl = config('app.url');
                    
                    $pages = [
                        ['url' => $baseUrl, 'type' => 'home'],
                        ['url' => "{$baseUrl}/about", 'type' => 'about'],
                        ['url' => "{$baseUrl}/products", 'type' => 'product_list'],
                        ['url' => "{$baseUrl}/articles", 'type' => 'article_list'],
                        ['url' => "{$baseUrl}/contact", 'type' => 'contact'],
                    ];

                    $totalAudits = 0;
                    
                    foreach ($pages as $page) {
                        try {
                            app(PerformanceAuditService::class)->audit($page['url'], $page['type']);
                            app(SeoAuditService::class)->audit($page['url'], $page['type']);
                            app(SecurityAuditService::class)->audit($page['url']);
                            $totalAudits += 3;
                        } catch (\Exception $e) {
                            // Log and continue
                        }
                    }

                    Notification::make()
                        ->title(__('Bulk Audit Completed'))
                        ->body(__(':count audits completed on :pages pages.', [
                            'count' => $totalAudits,
                            'pages' => count($pages),
                        ]))
                        ->success()
                        ->send();
                }),
        ];
    }
}
