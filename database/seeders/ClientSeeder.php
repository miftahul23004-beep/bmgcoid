<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'PT. Wijaya Karya', 'website' => 'https://www.wika.co.id', 'is_active' => true, 'order' => 1],
            ['name' => 'PT. Adhi Karya', 'website' => 'https://www.adhi.co.id', 'is_active' => true, 'order' => 2],
            ['name' => 'PT. PP (Persero)', 'website' => 'https://www.pp.co.id', 'is_active' => true, 'order' => 3],
            ['name' => 'PT. Hutama Karya', 'website' => 'https://www.hutamakarya.com', 'is_active' => true, 'order' => 4],
            ['name' => 'PT. Nusa Konstruksi', 'website' => null, 'is_active' => true, 'order' => 5],
            ['name' => 'PT. Total Bangun Persada', 'website' => 'https://www.totalbp.com', 'is_active' => true, 'order' => 6],
            ['name' => 'CV. Jaya Makmur', 'website' => null, 'is_active' => true, 'order' => 7],
            ['name' => 'PT. Bangun Cipta Kontraktor', 'website' => null, 'is_active' => true, 'order' => 8],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }
    }
}
