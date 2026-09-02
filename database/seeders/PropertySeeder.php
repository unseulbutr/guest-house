<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada 1 akun mitra demo untuk pemilik contoh properti ini.
        $mitra = User::firstOrCreate(
            ['email' => 'mitra@guesthouse.test'],
            ['name' => 'Budi Santoso', 'password' => Hash::make('password'), 'phone' => '081234567890']
        );
        if (! $mitra->hasRole('mitra')) {
            $mitra->assignRole('mitra');
        }

        $facilityIds = Facility::pluck('id')->all();
        if (empty($facilityIds)) {
            $this->command->warn('Belum ada data Facility — jalankan FacilitySeeder dulu supaya checklist fasilitas terisi.');
        }

        $properties = [
            [
                'name' => 'Villa Sejuk Kaliurang',
                'type' => 'guesthouse',
                'city' => 'Yogyakarta',
                'address' => 'Jl. Kaliurang Km 15, Sleman',
                'description' => 'Villa asri dengan pemandangan Gunung Merapi, cocok untuk liburan keluarga maupun rombongan.',
                'bedroom_count' => 3,
                'guest_capacity' => 6,
                'price_per_night' => 750000,
                'management_type' => 'mandiri',
            ],
            [
                'name' => 'Kost Harian Malioboro Residence',
                'type' => 'kost_harian',
                'city' => 'Yogyakarta',
                'address' => 'Jl. Sosrowijayan No. 12, Yogyakarta',
                'description' => 'Kost harian minimalis, 3 menit jalan kaki ke Malioboro. Cocok untuk solo traveler.',
                'bedroom_count' => 1,
                'guest_capacity' => 2,
                'price_per_night' => 175000,
                'management_type' => 'dikelola',
            ],
            [
                'name' => 'Guest House Dago Pakar',
                'type' => 'guesthouse',
                'city' => 'Bandung',
                'address' => 'Jl. Dago Pakar Timur No. 8, Bandung',
                'description' => 'Suasana sejuk khas Bandung utara, dekat Taman Hutan Raya Djuanda.',
                'bedroom_count' => 2,
                'guest_capacity' => 4,
                'price_per_night' => 450000,
                'management_type' => 'mandiri',
            ],
            [
                'name' => 'Homestay Sunset Canggu',
                'type' => 'guesthouse',
                'city' => 'Badung, Bali',
                'address' => 'Jl. Pantai Batu Bolong No. 21, Canggu',
                'description' => 'Homestay tropis 5 menit ke pantai Canggu, kolam renang pribadi, cocok untuk honeymoon.',
                'bedroom_count' => 1,
                'guest_capacity' => 2,
                'price_per_night' => 950000,
                'management_type' => 'dikelola',
            ],
            [
                'name' => 'Kost Harian Kemang Studio',
                'type' => 'kost_harian',
                'city' => 'Jakarta Selatan',
                'address' => 'Jl. Kemang Raya No. 45, Jakarta Selatan',
                'description' => 'Studio modern di kawasan Kemang, dekat perkantoran dan kafe-kafe hits.',
                'bedroom_count' => 1,
                'guest_capacity' => 2,
                'price_per_night' => 280000,
                'management_type' => 'mandiri',
            ],
        ];

        foreach ($properties as $data) {
            $property = Property::firstOrCreate(
                ['name' => $data['name']],
                [
                    'mitra_id' => $mitra->id,
                    'type' => $data['type'],
                    'city' => $data['city'],
                    'address' => $data['address'],
                    'description' => $data['description'],
                    'bedroom_count' => $data['bedroom_count'],
                    'guest_capacity' => $data['guest_capacity'],
                    'price_per_night' => $data['price_per_night'],
                    'management_type' => $data['management_type'],
                    'status' => 'active', // langsung aktif supaya kelihatan di homepage tanpa perlu approve manual
                ]
            );

            // Centang fasilitas secara acak (sekadar contoh) supaya checklist tidak kosong.
            if (! empty($facilityIds) && $property->facilities()->count() === 0) {
                $syncData = [];
                foreach ($facilityIds as $id) {
                    $syncData[$id] = ['is_available' => (bool) rand(0, 1)];
                }
                $property->facilities()->sync($syncData);
            }
        }

        $this->command->info('5 contoh properti berhasil dibuat (mitra: mitra@guesthouse.test / password).');
    }
}