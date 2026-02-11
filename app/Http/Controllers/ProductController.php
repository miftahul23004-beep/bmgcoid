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

    public function index(Request $request): View|RedirectResponse
    {
        // Validate and sanitize inputs
        $validated = $request->validate([
            'category' => 'nullable|string|max:100|regex:/^[a-z0-9\-]+$/',
            'search' => 'nullable|string|max:100',
            'sort' => 'nullable|string|in:newest,oldest,name_asc,name_desc,popular',
            'page' => 'nullable|integer|min:1|max:1000',
        ]);

        $categories = $this->categoryService->getNavigationCategories();

        // Redirect ?category=slug to /products/category/slug for SEO (avoid duplicate content)
        if (!empty($validated['category'])) {
            return redirect(route('products.category', $validated['category']), 301);
        }

        // No active category on index page (categories have their own route)
        $activeCategory = null;

        $products = $this->productService->getProducts([
            'search' => $validated['search'] ?? null,
            'sort' => $validated['sort'] ?? 'newest',
            'category_slug' => null,
            'paginate' => 12
        ]);

        // Get stats with cache
        $stats = [
            'categories' => cache()->remember('stats.categories_count', 3600, fn() => \App\Models\Category::where('is_active', true)->count()),
            'products' => cache()->remember('stats.products_count', 3600, fn() => \App\Models\Product::where('is_active', true)->count()),
        ];

        // Noindex for search/filter/paginated pages
        $metaRobots = (request('search') || request('sort') || $products->currentPage() > 1)
            ? 'noindex, follow'
            : null;

        // Canonical always points to the clean indexable URL
        $canonicalUrl = $metaRobots ? route('products.index') : null;

        return view('pages.products.index', compact('categories', 'products', 'activeCategory', 'stats', 'metaRobots', 'canonicalUrl'));
    }

    public function category(string $slug): View
    {
        $category = $this->categoryService->getBySlug($slug);

        $products = $this->productService->getByCategory($category, 12);

        $breadcrumbs = $this->categoryService->getBreadcrumbs($category);

        // Noindex for paginated category pages
        $metaRobots = $products->currentPage() > 1 ? 'noindex, follow' : null;

        // Canonical always points to page 1
        $canonicalUrl = $metaRobots ? route('products.category', $slug) : null;

        return view('pages.products.category', compact('category', 'products', 'breadcrumbs', 'metaRobots', 'canonicalUrl'));
    }

    public function show(string $slug): View
    {
        $product = $this->productService->getBySlug($slug);

        if (!$product) {
            abort(404);
        }

        // View count already incremented in getBySlug()

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

        return redirect()->away($link->url)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
