<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LatestChats;
use App\Filament\Widgets\LatestInquiries;
use App\Filament\Widgets\PopularProducts;
use App\Filament\Widgets\StatsOverview;
use App\Services\SettingService;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $settingService = app(SettingService::class);
        $siteName = $settingService->get('company_name', config('app.name'));
        $logo = $settingService->get('logo');
        $logoUrl = $logo ? Storage::disk('public')->url($logo) : asset('images/logo.png');

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->brandName($siteName)
            ->brandLogo(fn () => view('filament.brand-logo', ['logo' => $logoUrl, 'name' => $siteName]))
            ->darkModeBrandLogo(fn () => view('filament.brand-logo', ['logo' => $logoUrl, 'name' => $siteName, 'dark' => true]))
            ->brandLogoHeight('2.5rem')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                StatsOverview::class,
                LatestInquiries::class,
                PopularProducts::class,
                LatestChats::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \Illuminate\Routing\Middleware\ThrottleRequests::class . ':60,1',
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
