<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\ChatSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $data = Cache::remember('admin.stats.overview', 120, function () {
            $totalProducts = Product::where('is_active', true)->count();
            $totalArticles = Article::where('status', 'published')->count();
            $newInquiries = Inquiry::where('status', 'new')->count();
            $activeChats = ChatSession::whereIn('status', ['waiting', 'ai_handling', 'operator_handling'])->count();

            $lastMonthInquiries = Inquiry::where('created_at', '>=', now()->subMonth())->count();
            $previousMonthInquiries = Inquiry::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
            
            $inquiryChange = $previousMonthInquiries > 0 
                ? round((($lastMonthInquiries - $previousMonthInquiries) / $previousMonthInquiries) * 100) 
                : 0;

            return compact('totalProducts', 'totalArticles', 'newInquiries', 'activeChats', 'inquiryChange');
        });

        return [
            Stat::make('Total Products', $data['totalProducts'])
                ->description('Active products in catalog')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Published Articles', $data['totalArticles'])
                ->description('Blog articles published')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('New Inquiries', $data['newInquiries'])
                ->description($data['inquiryChange'] >= 0 ? "{$data['inquiryChange']}% increase" : abs($data['inquiryChange']) . "% decrease")
                ->descriptionIcon($data['inquiryChange'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($data['newInquiries'] > 0 ? 'warning' : 'success'),

            Stat::make('Active Chats', $data['activeChats'])
                ->description('Ongoing chat sessions')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($data['activeChats'] > 0 ? 'danger' : 'success'),
        ];
    }
}
