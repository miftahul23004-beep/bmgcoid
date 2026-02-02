<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'author_name' => 'Budi Santoso',
                'author_position' => 'Project Manager',
                'author_company' => 'PT. Nusa Konstruksi',
                'content' => 'Kualitas produk besi dari BMG sangat baik dan sesuai dengan standar SNI. Pengiriman selalu tepat waktu, sangat membantu kelancaran proyek kami.',
                'rating' => 5,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'author_name' => 'Ahmad Wijaya',
                'author_position' => 'Purchasing Manager',
                'author_company' => 'CV. Jaya Makmur',
                'content' => 'Sudah 5 tahun bekerja sama dengan BMG. Pelayanan sangat memuaskan dan harga kompetitif. Tim sales sangat responsif dalam menanggapi kebutuhan kami.',
                'rating' => 5,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'author_name' => 'Dewi Lestari',
                'author_position' => 'Owner',
                'author_company' => 'Toko Besi Sejahtera',
                'content' => 'Sebagai reseller, saya sangat puas dengan kerjasama dengan BMG. Stok selalu tersedia dan dokumen sertifikat lengkap untuk setiap pengiriman.',
                'rating' => 5,
                'is_active' => true,
                'order' => 3,
            ],
            [
                'author_name' => 'Hendro Pratama',
                'author_position' => 'Site Engineer',
                'author_company' => 'PT. Bangun Cipta',
                'content' => 'Material besi dari BMG selalu sesuai spesifikasi. Proses pemesanan mudah dan customer service sangat membantu.',
                'rating' => 4,
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonialData) {
            Testimonial::create($testimonialData);
        }
    }
}
