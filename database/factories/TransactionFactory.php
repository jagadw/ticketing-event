<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\events;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\transactions>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(100000, 500000);
        $discount = $this->faker->numberBetween(0, intval($subtotal * 0.5));
        $total = $subtotal - $discount;

        return [
            'user_id' => User::factory(),
            'event_id' => events::factory(),
            'promo_id' => null,
            'quantity' => $this->faker->numberBetween(1, 5),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'e_wallet', 'credit_card']),
            'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled']),
            'paid_at' => $this->faker->optional()->dateTime(),
            'payment_proof' => null,
        ];
    }
}
