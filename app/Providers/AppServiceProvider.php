<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Observers\ArticleObserver;
use App\Observers\CategoryObserver;
use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SettingService;
use App\View\Composers\LayoutComposer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services as singletons
        $this->app->singleton(SettingService::class, function ($app) {
            return new SettingService();
        });

        $this->app->singleton(ProductService::class, function ($app) {
            return new ProductService();
        });

        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService();
        });

        $this->app->singleton(ArticleService::class, function ($app) {
            return new ArticleService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        Article::observe(ArticleObserver::class);
        Category::observe(CategoryObserver::class);

        // Share settings only with layout views (not all views to avoid duplicates)
        View::composer('layouts.app', function ($view) {
            if (!app()->runningInConsole()) {
                try {
                    $siteSettings = Cache::remember('site_settings', 3600, function () {
                        return app(SettingService::class)->getCompanyInfo();
                    });
                    $view->with('siteSettings', $siteSettings);
                } catch (\Exception $e) {
                    // Settings table might not exist yet
                }
            }
        });

        // Register View Composers for layout partials with cached data
        $layoutComposer = app(LayoutComposer::class);
        View::composer('layouts.partials.navbar', function ($view) use ($layoutComposer) {
            $layoutComposer->composeNavbar($view);
        });
        View::composer('layouts.partials.footer', function ($view) use ($layoutComposer) {
            $layoutComposer->composeFooter($view);
        });
        View::composer('layouts.partials.topbar', function ($view) use ($layoutComposer) {
            $layoutComposer->composeTopbar($view);
        });
    }
}
