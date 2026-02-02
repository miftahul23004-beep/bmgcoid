<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => ['id' => 'Besi Beton', 'en' => 'Rebar / Deformed Bar'],
                'slug' => 'besi-beton',
                'description' => ['id' => 'Berbagai ukuran besi beton untuk konstruksi', 'en' => 'Various sizes of rebar for construction'],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => ['id' => 'Besi Hollow', 'en' => 'Hollow Section'],
                'slug' => 'besi-hollow',
                'description' => ['id' => 'Besi hollow berbagai ukuran', 'en' => 'Hollow sections in various sizes'],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => ['id' => 'Besi Siku', 'en' => 'Angle Bar'],
                'slug' => 'besi-siku',
                'description' => ['id' => 'Besi siku untuk konstruksi dan rangka', 'en' => 'Angle bars for construction and framing'],
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => ['id' => 'Besi Pipa', 'en' => 'Steel Pipe'],
                'slug' => 'besi-pipa',
                'description' => ['id' => 'Pipa besi berbagai diameter', 'en' => 'Steel pipes in various diameters'],
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name' => ['id' => 'Besi Plat', 'en' => 'Steel Plate'],
                'slug' => 'besi-plat',
                'description' => ['id' => 'Plat besi berbagai ketebalan', 'en' => 'Steel plates in various thicknesses'],
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name' => ['id' => 'Besi WF (Wide Flange)', 'en' => 'Wide Flange Beam'],
                'slug' => 'besi-wf-wide-flange',
                'description' => ['id' => 'Besi WF untuk struktur bangunan', 'en' => 'Wide flange beams for building structures'],
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name' => ['id' => 'Besi UNP (U Channel)', 'en' => 'U Channel'],
                'slug' => 'besi-unp-u-channel',
                'description' => ['id' => 'Besi kanal U untuk konstruksi', 'en' => 'U Channel steel for construction'],
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name' => ['id' => 'Wiremesh', 'en' => 'Wiremesh'],
                'slug' => 'wiremesh',
                'description' => ['id' => 'Wiremesh untuk pengecoran', 'en' => 'Wiremesh for concrete reinforcement'],
                'is_active' => true,
                'order' => 8,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
