<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\events;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_list_events()
    {
        $user = User::factory()->create();
        events::factory()->count(5)->create(['status' => 'Published']);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/events');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'title', 'description', 'image', 'event_date', 'location', 'ticket_price', 'quota', 'status']
            ],
            'meta' => ['current_page', 'total', 'per_page', 'last_page']
        ]);
    }

    public function test_user_can_search_events()
    {
        $user = User::factory()->create();
        events::factory()->create(['title' => 'Konser Musik', 'status' => 'Published']);
        events::factory()->create(['title' => 'Seminar', 'status' => 'Published']);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/events?search=Konser');

        $response->assertStatus(200);
        $this->assertTrue(count($response->json('data')) >= 1);
    }

    public function test_user_can_get_event_detail()
    {
        $user = User::factory()->create();
        $event = events::factory()->create(['status' => 'Published']);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/events/{$event->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['id', 'title', 'description', 'image', 'event_date', 'location', 'ticket_price', 'quota', 'status']
        ]);
        $response->assertJsonPath('data.id', $event->id);
    }

    public function test_unauthenticated_user_cannot_access_events()
    {
        $response = $this->getJson('/api/events');

        $response->assertStatus(401);
    }
}
