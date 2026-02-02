<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'mif.ulum@gmail.com')->exists()) {
            User::factory()->create([
                'name' => 'Administrator',
                'email' => 'mif.ulum@gmail.com',
            ]);
        }

        // Run seeders in order
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ClientSeeder::class,
            TestimonialSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
