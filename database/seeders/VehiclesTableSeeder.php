<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehiclesTableSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            ['id' => 1, 'name' => 'Toyota Corolla 2020', 'price' => 1200000, 'image' => 'toyota_corolla.jpg'],
            ['id' => 2, 'name' => 'Nissan X-Trail 2019', 'price' => 1800000, 'image' => 'nissan_xtrail.jpg'],
            ['id' => 3, 'name' => 'Mazda Demio 2018', 'price' => 850000, 'image' => 'mazda_demio.jpg'],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::updateOrCreate(['id' => $vehicle['id']], $vehicle);
        }
    }
}
