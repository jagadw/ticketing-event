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

        transactions::insert([
            [
                'user_id' => $users['maya@ticket.local']->id,
                'event_id' => $events['Seminar Startup Growth']->id,
                'promo_id' => $promos['FLASH10']->id,

                'subtotal' => 500000,
                'discount' => 50000,
                'quantity' => 2,
                'total' => 450000,

                'status' => 'paid',
                'paid_at' => now(),
                'payment_proof' => null,
                'payment_method' => 'Midtrans',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => $users['bima@ticket.local']->id,
                'event_id' => $events['Workshop UI Minimal']->id,
                'promo_id' => null,

                'subtotal' => 99000,
                'discount' => 0,
                'quantity' => 1,
                'total' => 99000,

                'status' => 'pending',
                'paid_at' => null,
                'payment_proof' => null,
                'payment_method' => 'Transfer Bank',

                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => $users['nabila@ticket.local']->id,
                'event_id' => $events['Music Night Festival']->id,
                'promo_id' => $promos['JUNE15']->id,

                'subtotal' => 700000,
                'discount' => 105000,
                'quantity' => 4,
                'total' => 595000,

                'status' => 'cancelled',
                'paid_at' => null,
                'payment_proof' => null,
                'payment_method' => 'E-Wallet',

                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}