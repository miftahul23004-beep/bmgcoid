<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class UpdateHomepageSectionsSeeder extends Seeder
{
    public function run(): void
    {
        // Enterprise-class company profile layout
        // Pattern: alternating white/gray with gradient accents for key sections
        
        $sections = [
            // Opening - Clean white start
            'hero' => [
                'sort_order' => 1,
                'bg_color' => 'white',
                'is_active' => true,
            ],
            
            // Credibility numbers - white for clarity
            'stats' => [
                'sort_order' => 2,
                'bg_color' => 'white',
                'is_active' => true,
            ],
            
            // Products grid - subtle gray background
            'categories' => [
                'sort_order' => 3,
                'bg_color' => 'gray-50',
                'is_active' => true,
            ],
            
            // Featured products - clean white
            'featured' => [
                'sort_order' => 4,
                'bg_color' => 'white',
                'is_active' => true,
            ],
            
            // Why choose us - gradient primary (impactful)
            'why_us' => [
                'sort_order' => 5,
                'bg_color' => 'gradient-primary',
                'is_active' => true,
            ],
            
            // Client logos - white for logo visibility
            'clients' => [
                'sort_order' => 6,
                'bg_color' => 'white',
                'is_active' => true,
            ],
            
            // Social proof - subtle gray
            'testimonials' => [
                'sort_order' => 7,
                'bg_color' => 'gray-50',
                'is_active' => true,
            ],
            
            // Marketplace links - white 
            'marketplace' => [
                'sort_order' => 8,
                'bg_color' => 'white',
                'is_active' => true,
            ],
            
            // Content/Blog - subtle gray
            'articles' => [
                'sort_order' => 9,
                'bg_color' => 'gray-50',
                'is_active' => true,
            ],
            
            // Service coverage - white
            'service_areas' => [
                'sort_order' => 10,
                'bg_color' => 'white',
                'is_active' => true,
            ],
            
            // Final CTA - gradient secondary (action-oriented)
            'cta' => [
                'sort_order' => 11,
                'bg_color' => 'gradient-secondary',
                'is_active' => true,
            ],
            
            // New products - hidden by default
            'new_products' => [
                'sort_order' => 12,
                'bg_color' => 'gradient-dark',
                'is_active' => false,
            ],
        ];

        foreach ($sections as $key => $data) {
            HomepageSection::where('key', $key)->update($data);
        }

        $this->command->info('Homepage sections updated with enterprise layout!');
    }
}
