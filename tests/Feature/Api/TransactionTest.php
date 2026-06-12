<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\events;
use App\Models\transactions;
use App\Models\promos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_checkout()
    {
        $user = User::factory()->create();
        $event = events::factory()->create(['quota' => 100, 'ticket_price' => 150000]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/transactions/checkout', [
                'event_id' => $event->id,
                'quantity' => 2,
                'payment_method' => 'bank_transfer',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id', 'event', 'quantity', 'subtotal', 'discount', 'total',
                'payment_method', 'status', 'created_at'
            ]
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => 2,
        ]);
    }

    public function test_checkout_fails_with_insufficient_quota()
    {
        $user = User::factory()->create();
        $event = events::factory()->create(['quota' => 1, 'ticket_price' => 150000]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/transactions/checkout', [
                'event_id' => $event->id,
                'quantity' => 2,
                'payment_method' => 'bank_transfer',
            ]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_checkout_with_promo_applies_discount()
    {
        $user = User::factory()->create();
        $event = events::factory()->create(['quota' => 100, 'ticket_price' => 100000]);
        $promo = promos::factory()->create(['promo_code' => 'DISKON50', 'discount_percentage' => 50, 'is_active' => true]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/transactions/checkout', [
                'event_id' => $event->id,
                'quantity' => 1,
                'promo_code' => 'DISKON50',
                'payment_method' => 'bank_transfer',
            ]);

        $response->assertStatus(201);
        $this->assertTrue($response->json('data.discount') > 0);
    }

    public function test_user_can_get_transaction_history()
    {
        $user = User::factory()->create();
        $event = events::factory()->create();
        transactions::factory()->count(3)->create(['user_id' => $user->id, 'event_id' => $event->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/transactions');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'event', 'quantity', 'total', 'status', 'created_at']
            ],
            'meta'
        ]);
    }

    public function test_user_can_get_transaction_detail()
    {
        $user = User::factory()->create();
        $event = events::factory()->create();
        $transaction = transactions::factory()->create(['user_id' => $user->id, 'event_id' => $event->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id', 'event', 'quantity', 'subtotal', 'discount', 'total',
                'payment_method', 'status', 'created_at'
            ]
        ]);
    }

    public function test_user_cannot_access_other_user_transaction()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $event = events::factory()->create();
        $transaction = transactions::factory()->create(['user_id' => $user1->id, 'event_id' => $event->id]);

        $response = $this->actingAs($user2, 'sanctum')
            ->getJson("/api/transactions/{$transaction->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_pay_transaction()
    {
        $user = User::factory()->create();
        $event = events::factory()->create(['quota' => 100]);
        $transaction = transactions::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/transactions/{$transaction->id}/pay");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'paid',
        ]);
    }

    public function test_pay_fails_if_already_paid()
    {
        $user = User::factory()->create();
        $event = events::factory()->create();
        $transaction = transactions::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson("/api/transactions/{$transaction->id}/pay");

        $response->assertStatus(400);
    }

    public function test_unauthenticated_user_cannot_checkout()
    {
        $event = events::factory()->create();

        $response = $this->postJson('/api/transactions/checkout', [
            'event_id' => $event->id,
            'quantity' => 1,
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertStatus(401);
    }
}
