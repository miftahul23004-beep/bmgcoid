<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\ChatSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalProducts = Product::where('is_active', true)->count();
        $totalArticles = Article::where('status', 'published')->count();
        $newInquiries = Inquiry::where('status', 'new')->count();
        $activeChats = ChatSession::whereIn('status', ['waiting', 'ai_handling', 'operator_handling'])->count();

        // Get last month data for comparison
        $lastMonthInquiries = Inquiry::where('created_at', '>=', now()->subMonth())->count();
        $previousMonthInquiries = Inquiry::whereBetween('created_at', [now()->subMonths(2), now()->subMonth()])->count();
        
        $inquiryChange = $previousMonthInquiries > 0 
            ? round((($lastMonthInquiries - $previousMonthInquiries) / $previousMonthInquiries) * 100) 
            : 0;

        return [
            Stat::make('Total Products', $totalProducts)
                ->description('Active products in catalog')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Published Articles', $totalArticles)
                ->description('Blog articles published')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('New Inquiries', $newInquiries)
                ->description($inquiryChange >= 0 ? "{$inquiryChange}% increase" : abs($inquiryChange) . "% decrease")
                ->descriptionIcon($inquiryChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($newInquiries > 0 ? 'warning' : 'success'),

            Stat::make('Active Chats', $activeChats)
                ->description('Ongoing chat sessions')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($activeChats > 0 ? 'danger' : 'success'),
        ];
    }
}
