<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    protected int $cacheTime = 1800; // 30 minutes

    /**
     * Get active products with pagination
     */
    public function getProducts(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['category:id,name,slug', 'marketplaceLinks' => function($q) {
                $q->where('is_active', true)->limit(3);
            }])
            ->select(['id', 'name', 'slug', 'short_description', 'featured_image', 'sku', 'category_id', 'is_featured', 'is_new', 'is_bestseller', 'view_count', 'created_at']);

        // Filter by category
        if (!empty($filters['category'])) {
            $query->where('category_id', (int) $filters['category']);
        }

        // Filter by category slug (sanitized)
        if (!empty($filters['category_slug'])) {
            $categorySlug = preg_replace('/[^a-z0-9\-]/', '', strtolower($filters['category_slug']));
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Search (sanitized input)
        if (!empty($filters['search'])) {
            $search = trim(substr($filters['search'], 0, 100)); // Limit search length
            $search = preg_replace('/[%_]/', '', $search); // Remove SQL wildcards
            if (strlen($search) >= 2) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('short_description', 'like', "%{$search}%");
                });
            }
        }

        // Sort (whitelist approach)
        $allowedSorts = ['newest', 'oldest', 'name_asc', 'name_desc', 'popular'];
        $sort = in_array($filters['sort'] ?? '', $allowedSorts) ? $filters['sort'] : 'newest';
        
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                $query->orderByDesc('view_count');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Limit perPage for security
        $perPage = min(max((int) ($filters['paginate'] ?? $perPage), 6), 48);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get featured products
     */
    public function getFeaturedProducts(int $limit = 8): Collection
    {
        return Cache::remember('products.featured', $this->cacheTime, function () use ($limit) {
            return Product::query()
                ->where('is_active', true)
                ->where('is_featured', true)
                ->with(['category'])
                ->orderBy('order')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get product by slug
     */
    public function getBySlug(string $slug): ?Product
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'category',
                'variants',
                'media',
                'documents',
                'marketplaceLinks',
                'faqs',
            ])
            ->first();

        if ($product) {
            // Increment view count
            $product->increment('view_count');
        }

        return $product;
    }

    /**
     * Get related products
     */
    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->limit($limit)
            ->get();
    }

    /**
     * Get products by category
     */
    public function getByCategory(Category $category, int $perPage = 12): LengthAwarePaginator
    {
        // Get category and all children IDs
        $categoryIds = [$category->id];
        $childIds = $category->children()->pluck('id')->toArray();
        $categoryIds = array_merge($categoryIds, $childIds);

        return Product::query()
            ->where('is_active', true)
            ->whereIn('category_id', $categoryIds)
            ->with(['category'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Search products
     */
    public function search(string $query, int $perPage = 12): LengthAwarePaginator
    {
        return $this->getProducts(['search' => $query], $perPage);
    }

    /**
     * Get popular products
     */
    public function getPopularProducts(int $limit = 8): Collection
    {
        return Cache::remember('products.popular', $this->cacheTime, function () use ($limit) {
            return Product::query()
                ->where('is_active', true)
                ->orderByDesc('view_count')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear products cache
     */
    public function clearCache(): void
    {
        Cache::forget('products.featured');
        Cache::forget('products.popular');
    }
}
