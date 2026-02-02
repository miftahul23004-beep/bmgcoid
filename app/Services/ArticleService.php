<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ArticleService
{
    protected int $cacheTime = 1800; // 30 minutes

    /**
     * Allowed sort options (whitelist for security)
     */
    protected array $allowedSorts = ['newest', 'oldest', 'popular'];

    /**
     * Get published articles with pagination
     */
    public function getArticles(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Article::query()
            ->select(['id', 'author_id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'view_count', 'status'])
            ->where('status', 'published')
            ->with(['author:id,name', 'tags:id,name,slug']);

        // Filter by tag (sanitized)
        if (!empty($filters['tag'])) {
            $tagSlug = preg_replace('/[^a-z0-9\-]/', '', strtolower($filters['tag']));
            $query->whereHas('tags', function ($q) use ($tagSlug) {
                $q->where('slug', $tagSlug);
            });
        }

        // Search (sanitized for security)
        if (!empty($filters['search'])) {
            $search = $this->sanitizeSearch($filters['search']);
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%");
                });
            }
        }

        // Sort (whitelist validation)
        $sort = in_array($filters['sort'] ?? 'newest', $this->allowedSorts) 
            ? ($filters['sort'] ?? 'newest') 
            : 'newest';
            
        switch ($sort) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'popular':
                $query->orderByDesc('view_count');
                break;
            case 'newest':
            default:
                $query->latest('published_at');
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Sanitize search input for security
     */
    protected function sanitizeSearch(string $search): string
    {
        // Remove potentially dangerous characters
        $search = strip_tags($search);
        $search = preg_replace('/[<>\"\'%;()&+]/', '', $search);
        $search = trim($search);
        
        // Limit length
        return mb_substr($search, 0, 100);
    }

    /**
     * Get featured articles
     */
    public function getFeaturedArticles(int $limit = 3): Collection
    {
        return Cache::remember('articles.featured', $this->cacheTime, function () use ($limit) {
            return Article::query()
                ->select(['id', 'author_id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'view_count'])
                ->where('status', 'published')
                ->where('is_featured', true)
                ->with(['author:id,name'])
                ->latest('published_at')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get latest articles (featured only for homepage)
     */
    public function getLatestArticles(int $limit = 5, bool $featuredOnly = false): Collection
    {
        $cacheKey = $featuredOnly ? 'articles.latest.featured' : 'articles.latest';
        
        return Cache::remember($cacheKey, $this->cacheTime, function () use ($limit, $featuredOnly) {
            $query = Article::query()
                ->select(['id', 'author_id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'view_count'])
                ->where('status', 'published')
                ->with(['author:id,name'])
                ->latest('published_at');
            
            if ($featuredOnly) {
                $query->where('is_featured', true);
            }
            
            return $query->limit($limit)->get();
        });
    }

    /**
     * Get article by slug
     */
    public function getBySlug(string $slug): ?Article
    {
        // Sanitize slug
        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));

        return Article::query()
            ->select(['id', 'author_id', 'title', 'slug', 'excerpt', 'content', 'featured_image', 'published_at', 'updated_at', 'created_at', 'view_count', 'share_count', 'status'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->with(['author:id,name,email', 'tags:id,name,slug'])
            ->first();
    }

    /**
     * Get related articles
     */
    public function getRelatedArticles(Article $article, int $limit = 3): Collection
    {
        $tagIds = $article->tags->pluck('id')->toArray();

        return Article::query()
            ->select(['id', 'author_id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'view_count'])
            ->where('status', 'published')
            ->where('id', '!=', $article->id)
            ->when(!empty($tagIds), function ($query) use ($tagIds) {
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            })
            ->with(['author:id,name', 'tags:id,name,slug'])
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get articles by tag (accepts slug string)
     */
    public function getByTag(string $slug, int $perPage = 12): LengthAwarePaginator
    {
        // Sanitize slug
        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($slug));

        return Article::query()
            ->select(['articles.id', 'author_id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'view_count', 'status'])
            ->where('status', 'published')
            ->whereHas('tags', function ($q) use ($slug) {
                $q->where('tags.slug', $slug);
            })
            ->with(['author:id,name', 'tags:id,name,slug'])
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * Get popular tags
     */
    public function getPopularTags(int $limit = 10): Collection
    {
        return Cache::remember('tags.popular', $this->cacheTime, function () use ($limit) {
            return Tag::query()
                ->select(['id', 'name', 'slug'])
                ->withCount(['articles' => function ($query) {
                    $query->where('status', 'published');
                }])
                ->having('articles_count', '>', 0)
                ->orderByDesc('articles_count')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear articles cache
     */
    public function clearCache(): void
    {
        Cache::forget('articles.featured');
        Cache::forget('articles.latest');
        Cache::forget('articles.latest.featured');
        Cache::forget('tags.popular');
    }
}
