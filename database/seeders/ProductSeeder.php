<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $besiBeton = Category::where('slug', 'besi-beton')->first();
        $besiHollow = Category::where('slug', 'besi-hollow')->first();
        $besiSiku = Category::where('slug', 'besi-siku')->first();
        $besiPlat = Category::where('slug', 'besi-plat')->first();

        $products = [
            // Besi Beton
            [
                'category_id' => $besiBeton?->id,
                'name' => ['id' => 'Besi Beton Polos', 'en' => 'Plain Round Bar'],
                'slug' => 'besi-beton-polos',
                'sku' => 'BBP-001',
                'description' => [
                    'id' => '<p>Besi beton polos berkualitas tinggi untuk kebutuhan konstruksi. Tersedia dalam berbagai ukuran diameter mulai dari 6mm hingga 25mm dengan panjang standar 12 meter.</p><p><strong>Keunggulan:</strong></p><ul><li>Bersertifikasi SNI</li><li>Kualitas terjamin</li><li>Harga kompetitif</li></ul>',
                    'en' => '<p>High quality plain round bar for construction needs. Available in various diameters from 6mm to 25mm with standard length of 12 meters.</p><p><strong>Advantages:</strong></p><ul><li>SNI Certified</li><li>Guaranteed quality</li><li>Competitive price</li></ul>'
                ],
                'short_description' => ['id' => 'Besi beton polos SNI berbagai ukuran', 'en' => 'SNI certified plain round bar in various sizes'],
                'specifications' => ['Standar' => 'SNI 07-2052-2002', 'Panjang' => '12 meter', 'Bahan' => 'Baja Karbon'],
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'category_id' => $besiBeton?->id,
                'name' => ['id' => 'Besi Beton Ulir', 'en' => 'Deformed Bar'],
                'slug' => 'besi-beton-ulir',
                'sku' => 'BBU-001',
                'description' => [
                    'id' => '<p>Besi beton ulir berkualitas tinggi untuk struktur bangunan yang membutuhkan kekuatan tarik tinggi. Ulir pada permukaan memberikan daya cengkram yang lebih baik dengan beton.</p>',
                    'en' => '<p>High quality deformed bar for building structures requiring high tensile strength. The ribs on the surface provide better grip with concrete.</p>'
                ],
                'short_description' => ['id' => 'Besi beton ulir SNI untuk struktur kuat', 'en' => 'SNI certified deformed bar for strong structures'],
                'specifications' => ['Standar' => 'SNI 07-2052-2002', 'Panjang' => '12 meter', 'Grade' => 'BJTP 24, BJTD 40'],
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            // Besi Hollow
            [
                'category_id' => $besiHollow?->id,
                'name' => ['id' => 'Hollow Galvanis', 'en' => 'Galvanized Hollow Section'],
                'slug' => 'hollow-galvanis',
                'sku' => 'HG-001',
                'description' => [
                    'id' => '<p>Besi hollow galvanis anti karat untuk berbagai keperluan konstruksi. Cocok untuk rangka atap, pagar, dan furniture.</p>',
                    'en' => '<p>Galvanized hollow section with anti-rust coating for various construction needs. Suitable for roof frames, fences, and furniture.</p>'
                ],
                'short_description' => ['id' => 'Hollow galvanis anti karat', 'en' => 'Anti-rust galvanized hollow section'],
                'specifications' => ['Lapisan' => 'Hot Dip Galvanized', 'Ketebalan' => '0.8mm - 2.5mm'],
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'category_id' => $besiHollow?->id,
                'name' => ['id' => 'Hollow Hitam', 'en' => 'Black Hollow Section'],
                'slug' => 'hollow-hitam',
                'sku' => 'HH-001',
                'description' => [
                    'id' => '<p>Besi hollow hitam standar untuk berbagai aplikasi konstruksi. Harga ekonomis dengan kualitas terjamin.</p>',
                    'en' => '<p>Standard black hollow section for various construction applications. Economical price with guaranteed quality.</p>'
                ],
                'short_description' => ['id' => 'Hollow hitam ekonomis', 'en' => 'Economical black hollow section'],
                'is_active' => true,
                'order' => 2,
            ],
            // Besi Siku
            [
                'category_id' => $besiSiku?->id,
                'name' => ['id' => 'Siku Sama Sisi', 'en' => 'Equal Angle Bar'],
                'slug' => 'siku-sama-sisi',
                'sku' => 'SS-001',
                'description' => [
                    'id' => '<p>Besi siku sama sisi untuk rangka konstruksi, rak, dan berbagai keperluan industri.</p>',
                    'en' => '<p>Equal angle bar for construction frames, shelving, and various industrial purposes.</p>'
                ],
                'short_description' => ['id' => 'Siku sama sisi berbagai ukuran', 'en' => 'Equal angle bar in various sizes'],
                'specifications' => ['Panjang' => '6 meter', 'Standar' => 'JIS G3101 SS400'],
                'is_active' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            // Besi Plat
            [
                'category_id' => $besiPlat?->id,
                'name' => ['id' => 'Plat Besi Hitam', 'en' => 'Hot Rolled Steel Plate'],
                'slug' => 'plat-besi-hitam',
                'sku' => 'PBH-001',
                'description' => [
                    'id' => '<p>Plat besi hitam berkualitas untuk berbagai aplikasi industri dan konstruksi.</p>',
                    'en' => '<p>Quality hot rolled steel plate for various industrial and construction applications.</p>'
                ],
                'short_description' => ['id' => 'Plat besi hitam berbagai ketebalan', 'en' => 'Hot rolled steel plate in various thicknesses'],
                'specifications' => ['Ukuran' => '4\' x 8\' (1219 x 2438mm)', 'Ketebalan' => '1.2mm - 25mm'],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'category_id' => $besiPlat?->id,
                'name' => ['id' => 'Plat Bordes', 'en' => 'Checkered Plate'],
                'slug' => 'plat-bordes',
                'sku' => 'PB-001',
                'description' => [
                    'id' => '<p>Plat bordes anti slip untuk lantai tangga, platform, dan truk. Pola diamond memberikan grip yang baik.</p>',
                    'en' => '<p>Anti-slip checkered plate for stair floors, platforms, and trucks. Diamond pattern provides good grip.</p>'
                ],
                'short_description' => ['id' => 'Plat bordes anti slip', 'en' => 'Anti-slip checkered plate'],
                'is_active' => true,
                'is_featured' => true,
                'order' => 2,
            ],
        ];

        foreach ($products as $productData) {
            if ($productData['category_id']) {
                $product = Product::create($productData);

                // Add variants for some products
                if (str_contains($product->sku, 'BBP') || str_contains($product->sku, 'BBU')) {
                    $sizes = ['8mm', '10mm', '12mm', '16mm', '19mm', '22mm'];
                    foreach ($sizes as $index => $size) {
                        Variant::create([
                            'product_id' => $product->id,
                            'name' => ['id' => "Diameter {$size}", 'en' => "Diameter {$size}"],
                            'sku' => "{$product->sku}-{$size}",
                            'size' => $size,
                            'length' => '12 meter',
                            'is_active' => true,
                            'order' => $index + 1,
                        ]);
                    }
                }
            }
        }
    }
}
