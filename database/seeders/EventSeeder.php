<?php

namespace Database\Seeders;

use App\Models\events;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        events::query()->insert([
            [
                'title' => 'Seminar Startup Growth',
                'description' => 'Sesi inspiratif untuk founder, owner, dan tim yang sedang membangun produk digital.',
                'event_date' => '2026-06-18 19:00:00',
                'location' => 'Jakarta Convention Center',
                'ticket_price' => 250000,
                'quota' => 250,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Music Night Festival',
                'description' => 'Konser malam dengan beberapa penampil utama dan pengalaman venue outdoor.',
                'event_date' => '2026-07-02 20:00:00',
                'location' => 'Lapangan Banteng',
                'ticket_price' => 175000,
                'quota' => 800,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Workshop UI Minimal',
                'description' => 'Workshop praktis untuk desain antarmuka bersih, modern, dan fokus pada pengalaman pengguna.',
                'event_date' => '2026-07-10 09:00:00',
                'location' => 'Online Zoom',
                'ticket_price' => 99000,
                'quota' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}