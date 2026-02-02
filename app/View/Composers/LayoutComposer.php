<?php

namespace App\View\Composers;

use App\Models\Category;
use App\Services\SettingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LayoutComposer
{
    /**
     * In-memory cache to prevent duplicate cache queries within same request
     */
    protected static array $memoryCache = [];

    public function __construct(
        protected SettingService $settingService
    ) {}

    /**
     * Get cached data with in-memory layer to prevent duplicate cache queries
     */
    protected function getCached(string $key, callable $callback): mixed
    {
        if (isset(static::$memoryCache[$key])) {
            return static::$memoryCache[$key];
        }

        static::$memoryCache[$key] = Cache::remember($key, 3600, $callback);
        return static::$memoryCache[$key];
    }

    /**
     * Compose the navbar view with cached data
     */
    public function composeNavbar(View $view): void
    {
        $navbarCategories = $this->getCached('navbar_categories', function () {
            return Category::active()
                ->roots()
                ->ordered()
                ->withCount('products')
                ->with(['children' => function ($query) {
                    $query->active()->ordered()->withCount('products');
                }])
                ->take(6)
                ->get();
        });

        $companyInfo = $this->getCached('company_info', function () {
            return $this->settingService->getCompanyInfo();
        });

        $view->with([
            'navbarCategories' => $navbarCategories,
            'companyInfo' => $companyInfo,
        ]);
    }

    /**
     * Compose the footer view with cached data
     */
    public function composeFooter(View $view): void
    {
        $footerCategories = $this->getCached('footer_categories', function () {
            return Category::active()
                ->roots()
                ->ordered()
                ->take(6)
                ->get(['id', 'name', 'slug']);
        });

        $companyInfo = $this->getCached('company_info', function () {
            return $this->settingService->getCompanyInfo();
        });

        $socialLinks = $this->getCached('social_links', function () {
            return $this->settingService->getSocialLinks();
        });

        $view->with([
            'footerCategories' => $footerCategories,
            'companyInfo' => $companyInfo,
            'socialLinks' => $socialLinks,
        ]);
    }

    /**
     * Compose the topbar view with cached data
     */
    public function composeTopbar(View $view): void
    {
        $companyInfo = $this->getCached('company_info', function () {
            return $this->settingService->getCompanyInfo();
        });

        $socialLinks = $this->getCached('social_links', function () {
            return $this->settingService->getSocialLinks();
        });

        $view->with([
            'companyInfo' => $companyInfo,
            'socialLinks' => $socialLinks,
        ]);
    }
}
