<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BodaBoda;

class BodaBodaSeeder extends Seeder
{
    public function run(): void
    {
        $bodas = [
            [
                'name' => 'Bajaj Pulsar 150',
                'price' => 150000,
                'description' => 'A reliable and fuel-efficient motorcycle, perfect for daily commuting and small business riders.',
                'image' => 'images/bodas/bajaj_pulsar_150.jpg',
            ],
            [
                'name' => 'TVS Apache RTR 160',
                'price' => 160000,
                'description' => 'Sporty performance and comfortable ride, ideal for city and suburban use.',
                'image' => 'images/bodas/tvs_apache_rtr_160.jpg',
            ],
            [
                'name' => 'Honda CB125F',
                'price' => 140000,
                'description' => 'Durable and easy to maintain motorcycle, perfect for new riders and delivery businesses.',
                'image' => 'images/bodas/honda_cb125f.jpg',
            ],
        ];

        foreach($bodas as $boda) {
            BodaBoda::create($boda);
        }
    }
}
