<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'AC', 'WiFi', 'Kolam Renang Pribadi', 'Dapur', 'Parkir Mobil',
            'Parkir Motor', 'TV', 'Air Panas', 'Mesin Cuci', 'Kulkas',
            'Kasur Tambahan', 'Balkon',
        ];

        foreach ($facilities as $name) {
            Facility::firstOrCreate(['name' => $name]);
        }
    }
}
