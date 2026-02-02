<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'PT. Berkah Mandiri Globalindo', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Distributor Besi Baja Terpercaya', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'PT. Berkah Mandiri Globalindo adalah distributor besi baja terkemuka di Indonesia yang menyediakan produk berkualitas dengan harga kompetitif.', 'type' => 'textarea'],
            
            // Contact
            ['group' => 'contact', 'key' => 'address', 'value' => 'Jl. Raya Industri No. 123, Cikarang, Bekasi, Jawa Barat 17530', 'type' => 'textarea'],
            ['group' => 'contact', 'key' => 'phone', 'value' => '+62 21 8900 1234', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'whatsapp', 'value' => '+62 812 3456 7890', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'email', 'value' => 'info@bmg.co.id', 'type' => 'text'],
            
            // Social Media
            ['group' => 'social', 'key' => 'facebook', 'value' => 'https://facebook.com/bmg.id', 'type' => 'text'],
            ['group' => 'social', 'key' => 'instagram', 'value' => 'https://instagram.com/bmg.id', 'type' => 'text'],
            ['group' => 'social', 'key' => 'youtube', 'value' => 'https://youtube.com/@bmg.id', 'type' => 'text'],
            ['group' => 'social', 'key' => 'tiktok', 'value' => 'https://tiktok.com/@bmg.id', 'type' => 'text'],
            
            // SEO
            ['group' => 'seo', 'key' => 'meta_title', 'value' => 'PT. Berkah Mandiri Globalindo - Distributor Besi Baja Terpercaya', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Distributor besi baja berkualitas di Indonesia. Besi beton, hollow, siku, plat, dan berbagai material konstruksi dengan harga kompetitif.', 'type' => 'textarea'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => 'besi baja, distributor besi, besi beton, besi hollow, besi siku, besi plat, material konstruksi', 'type' => 'textarea'],
        ];

        foreach ($settings as $settingData) {
            Setting::create($settingData);
        }
    }
}
