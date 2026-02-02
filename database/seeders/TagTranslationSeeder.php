<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagTranslationSeeder extends Seeder
{
    /**
     * Add English translations to existing tags.
     */
    public function run(): void
    {
        $translations = [
            'tips-konstruksi' => [
                'id' => 'Tips Konstruksi',
                'en' => 'Construction Tips',
            ],
            'info-produk' => [
                'id' => 'Info Produk',
                'en' => 'Product Info',
            ],
            'berita-industri' => [
                'id' => 'Berita Industri',
                'en' => 'Industry News',
            ],
            'tutorial' => [
                'id' => 'Tutorial',
                'en' => 'Tutorial',
            ],
            'promo' => [
                'id' => 'Promo',
                'en' => 'Promo',
            ],
        ];

        foreach ($translations as $slug => $names) {
            $tag = Tag::where('slug', $slug)->first();
            
            if ($tag) {
                $tag->setTranslations('name', $names);
                $tag->save();
                
                $this->command->info("Updated tag: {$slug} -> EN: {$names['en']}");
            }
        }

        $this->command->info('Tag translations updated successfully!');
    }
}
