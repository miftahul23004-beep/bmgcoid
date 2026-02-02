<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    protected int $cacheTime = 3600; // 1 hour

    /**
     * Get all active categories with product count
     */
    public function getActiveCategories(): Collection
    {
        return Cache::remember('categories.active', $this->cacheTime, function () {
            return Category::query()
                ->where('is_active', true)
                ->whereNull('parent_id') // Only root categories
                ->withCount(['products' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->with(['children' => function ($query) {
                    $query->where('is_active', true)
                        ->withCount(['products' => function ($q) {
                            $q->where('is_active', true);
                        }]);
                }])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get category by slug
     */
    public function getBySlug(string $slug): ?Category
    {
        return Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['parent', 'children'])
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->first();
    }

    /**
     * Get root categories for navigation
     */
    public function getNavigationCategories(): Collection
    {
        return Cache::remember('categories.navigation', $this->cacheTime, function () {
            return Category::query()
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->withCount(['products' => function ($query) {
                    $query->where('is_active', true);
                }])
                ->with(['children' => function ($query) {
                    $query->where('is_active', true)
                        ->withCount(['products' => function ($q) {
                            $q->where('is_active', true);
                        }])
                        ->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get all categories as flat array for dropdowns
     */
    public function getCategoriesForSelect(): array
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $result = [];
        foreach ($categories as $category) {
            $prefix = $category->parent_id ? '-- ' : '';
            $result[$category->id] = $prefix . $category->name;
        }

        return $result;
    }

    /**
     * Get category tree
     */
    public function getCategoryTree(): Collection
    {
        return Cache::remember('categories.tree', $this->cacheTime, function () {
            return Category::query()
                ->whereNull('parent_id')
                ->with('allChildren')
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get breadcrumbs for a category
     */
    public function getBreadcrumbs(Category $category): array
    {
        $breadcrumbs = [];
        $current = $category;

        while ($current) {
            array_unshift($breadcrumbs, [
                'name' => $current->name,
                'slug' => $current->slug,
                'url' => route('products.category', $current->slug),
            ]);
            $current = $current->parent;
        }

        return $breadcrumbs;
    }

    /**
     * Clear category cache
     */
    public function clearCache(): void
    {
        Cache::forget('categories.active');
        Cache::forget('categories.navigation');
        Cache::forget('categories.tree');
    }
}
