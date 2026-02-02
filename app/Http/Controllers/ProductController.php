<?php

namespace App\Http\Controllers;

use App\Models\ProductMarketplaceLink;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService
    ) {}

    public function index(Request $request): View
    {
        // Validate and sanitize inputs
        $validated = $request->validate([
            'category' => 'nullable|string|max:100|regex:/^[a-z0-9\-]+$/',
            'search' => 'nullable|string|max:100',
            'sort' => 'nullable|string|in:newest,oldest,name_asc,name_desc,popular',
            'page' => 'nullable|integer|min:1|max:1000',
        ]);

        $categories = $this->categoryService->getNavigationCategories();

        // Get active category if filtering
        $activeCategory = null;
        if (!empty($validated['category'])) {
            $activeCategory = $this->categoryService->getBySlug($validated['category']);
            
            // 404 if category not found
            if (!$activeCategory) {
                abort(404);
            }
        }

        $products = $this->productService->getProducts([
            'search' => $validated['search'] ?? null,
            'sort' => $validated['sort'] ?? 'newest',
            'category_slug' => $validated['category'] ?? null,
            'paginate' => 12
        ]);

        // Get stats with cache
        $stats = [
            'categories' => cache()->remember('stats.categories_count', 3600, fn() => \App\Models\Category::where('is_active', true)->count()),
            'products' => cache()->remember('stats.products_count', 3600, fn() => \App\Models\Product::where('is_active', true)->count()),
        ];

        return view('pages.products.index', compact('categories', 'products', 'activeCategory', 'stats'));
    }

    public function category(string $slug): View
    {
        $category = $this->categoryService->getBySlug($slug);

        $products = $this->productService->getByCategory($category, 12);

        $breadcrumbs = $this->categoryService->getBreadcrumbs($category);

        return view('pages.products.category', compact('category', 'products', 'breadcrumbs'));
    }

    public function show(string $slug): View
    {
        $product = $this->productService->getBySlug($slug);

        // Increment view count
        $product->incrementViewCount();

        // Related products
        $relatedProducts = $this->productService->getRelatedProducts($product, 4);

        $breadcrumbs = $this->categoryService->getBreadcrumbs($product->category, $product);

        return view('pages.products.show', compact('product', 'relatedProducts', 'breadcrumbs'));
    }

    public function marketplaceRedirect(string $platform, int $productId): RedirectResponse
    {
        $link = ProductMarketplaceLink::where('product_id', $productId)
            ->where('platform', $platform)
            ->active()
            ->firstOrFail();

        // Track click
        $link->incrementClickCount();

        return redirect()->away($link->url);
    }
}
