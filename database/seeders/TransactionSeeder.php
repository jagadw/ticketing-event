<?php

namespace Database\Seeders;

use App\Models\events;
use App\Models\promos;
use App\Models\transactions;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->orderBy('id')->get()->keyBy('email');
        $events = events::query()->orderBy('id')->get()->keyBy('title');
        $promos = promos::query()->orderBy('id')->get()->keyBy('promo_code');

        transactions::query()->insert([
            [
                'user_id' => $users['maya@ticket.local']->id,
                'event_id' => $events['Seminar Startup Growth']->id,
                'promo_id' => $promos['FLASH10']->id,
                'ticket_quantity' => 2,
                'total_price' => 450000,
                'payment_status' => 'paid',
                'payment_method' => 'Midtrans',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users['bima@ticket.local']->id,
                'event_id' => $events['Workshop UI Minimal']->id,
                'promo_id' => null,
                'ticket_quantity' => 1,
                'total_price' => 99000,
                'payment_status' => 'pending',
                'payment_method' => 'Transfer Bank',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $users['nabila@ticket.local']->id,
                'event_id' => $events['Music Night Festival']->id,
                'promo_id' => $promos['JUNE15']->id,
                'ticket_quantity' => 4,
                'total_price' => 595000,
                'payment_status' => 'cancelled',
                'payment_method' => 'E-Wallet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}