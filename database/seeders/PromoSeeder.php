<?php

namespace Database\Seeders;

use App\Models\promos;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        promos::query()->insert([
            [
                'promo_code' => 'CUCIGUDANG',
                'discount_percentage' => 10,
                'start_date' => '2026-06-01 00:00:00',
                'end_date' => '2026-06-30 23:59:59',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'promo_code' => 'SEPTEMBERCERIA',
                'discount_percentage' => 15,
                'start_date' => '2026-06-10 00:00:00',
                'end_date' => '2026-07-10 23:59:59',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'promo_code' => 'PENGGUNABARU',
                'discount_percentage' => 5,
                'start_date' => '2026-05-01 00:00:00',
                'end_date' => '2026-05-31 23:59:59',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}