<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService
    ) {}

    public function index(Request $request): View
    {
        // Validate search input for security
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $tags = $this->articleService->getPopularTags();

        $articles = $this->articleService->getArticles([
            'search' => $validated['search'] ?? null,
            'paginate' => 9
        ]);

        return view('pages.articles.index', compact('tags', 'articles'));
    }

    public function tag(string $slug): View
    {
        // Validate slug format for security
        if (!preg_match('/^[a-z0-9\-]+$/', $slug) || strlen($slug) > 100) {
            abort(404);
        }

        $tags = $this->articleService->getPopularTags();
        $articles = $this->articleService->getByTag($slug, 9);
        $tag = $articles->first()?->tags->where('slug', $slug)->first();

        if (!$tag) {
            abort(404);
        }

        return view('pages.articles.tag', compact('tag', 'tags', 'articles'));
    }

    public function show(string $slug): View
    {
        // Validate slug format for security
        if (!preg_match('/^[a-z0-9\-]+$/', $slug) || strlen($slug) > 255) {
            abort(404);
        }

        $article = $this->articleService->getBySlug($slug);

        if (!$article) {
            abort(404);
        }

        // Increment view count (already handled in service, but kept for explicit call)
        $article->incrementViewCount();

        // Related articles with eager loading optimization
        $relatedArticles = $this->articleService->getRelatedArticles($article, 3);

        return view('pages.articles.show', compact('article', 'relatedArticles'));
    }
}
