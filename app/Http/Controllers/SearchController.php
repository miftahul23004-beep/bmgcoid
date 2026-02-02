<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');

        $products = collect();
        $articles = collect();

        if (strlen($query) >= 2) {
            if ($type === 'all' || $type === 'products') {
                $products = Product::active()
                    ->with('category')
                    ->where(function ($q) use ($query) {
                        $q->where('name->id', 'like', "%{$query}%")
                          ->orWhere('name->en', 'like', "%{$query}%")
                          ->orWhere('description->id', 'like', "%{$query}%")
                          ->orWhere('description->en', 'like', "%{$query}%")
                          ->orWhere('sku', 'like', "%{$query}%");
                    })
                    ->ordered()
                    ->take(20)
                    ->get();
            }

            if ($type === 'all' || $type === 'articles') {
                $articles = Article::published()
                    ->with(['tags', 'author'])
                    ->where(function ($q) use ($query) {
                        $q->where('title->id', 'like', "%{$query}%")
                          ->orWhere('title->en', 'like', "%{$query}%")
                          ->orWhere('content->id', 'like', "%{$query}%")
                          ->orWhere('content->en', 'like', "%{$query}%");
                    })
                    ->latest('published_at')
                    ->take(20)
                    ->get();
            }
        }

        $totalResults = $products->count() + $articles->count();

        return view('pages.search.index', compact('query', 'type', 'products', 'articles', 'totalResults'));
    }
}
