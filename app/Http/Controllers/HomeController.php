<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Client;
use App\Models\HeroSlide;
use App\Models\HomepageSection;
use App\Models\Testimonial;
use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    protected int $cacheTime = 300; // 5 minutes

    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected ArticleService $articleService,
        protected SettingService $settingService
    ) {}

    public function index(): View
    {
        // Cache all homepage data together for maximum performance
        $locale = app()->getLocale();
        
        $data = Cache::remember("homepage_data:{$locale}", $this->cacheTime, function () {
            return [
                'homepageSections' => HomepageSection::getActiveSections()->keyBy('key'),
                'heroSlides' => HeroSlide::getDisplayableSlides(),
                'featuredCategories' => $this->categoryService->getActiveCategories()
                    ->where('is_featured', true)
                    ->take(6)
                    ->values(),
                'featuredProducts' => $this->productService->getFeaturedProducts(8),
                'clients' => Client::where('is_active', true)
                    ->where('is_featured', true)
                    ->orderBy('order')
                    ->take(12)
                    ->get(),
                'testimonials' => Testimonial::where('is_active', true)
                    ->where('is_featured', true)
                    ->orderBy('order')
                    ->take(6)
                    ->get(),
                'latestArticles' => $this->articleService->getLatestArticles(3, true),
                'companyInfo' => $this->settingService->getCompanyInfo(),
            ];
        });

        return view('pages.home.index', $data);
    }

    public function about(): View
    {
        $companyInfo = $this->settingService->getCompanyInfo();
        return view('pages.about.index', compact('companyInfo'));
    }

    public function visionMission(): View
    {
        return view('pages.about.vision-mission');
    }

    public function team(): View
    {
        return view('pages.about.team');
    }

    public function certificates(): View
    {
        return view('pages.about.certificates');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function sitemap(): View
    {
        $categories = Category::active()->roots()->ordered()->with('children')->get();
        $articles = Article::where('status', 'published')->latest('published_at')->get();

        return view('pages.sitemap', compact('categories', 'articles'));
    }

    public function testimonials(): View
    {
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('order')
            ->paginate(12);

        return view('pages.testimonials.index', compact('testimonials'));
    }

    public function privacyPdf()
    {
        $companyInfo = $this->settingService->getCompanyInfo();
        
        $data = [
            'companyName' => $companyInfo['company_name'] ?? 'PT. Berkah Mandiri Globalindo',
            'email' => $companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com',
            'phone' => $companyInfo['phone'] ?? '-',
            'address' => $companyInfo['address'] ?? '-',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.privacy-policy', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = app()->getLocale() === 'en' ? 'privacy-policy.pdf' : 'kebijakan-privasi.pdf';
        
        return $pdf->download($filename);
    }

    public function termsPdf()
    {
        $companyInfo = $this->settingService->getCompanyInfo();
        
        $data = [
            'companyName' => $companyInfo['company_name'] ?? 'PT. Berkah Mandiri Globalindo',
            'email' => $companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com',
            'phone' => $companyInfo['phone'] ?? '-',
            'address' => $companyInfo['address'] ?? '-',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.terms-conditions', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = app()->getLocale() === 'en' ? 'terms-conditions.pdf' : 'syarat-ketentuan.pdf';
        
        return $pdf->download($filename);
    }
}
