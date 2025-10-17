<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_rooms(): void
    {
        Room::factory()->count(3)->create();

        $response = $this->getJson('/api/rooms');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->etc()
            );
    }

    public function test_store_persists_room_when_payload_is_valid(): void
    {
        $payload = [
            'room_number' => '101',
            'type' => 'Deluxe',
            'price' => 120.50,
            'availability_status' => true,
        ];

        $response = $this->postJson('/api/rooms', $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.room_number', '101')
            ->assertJsonPath('message', 'Room created successfully.');

        $this->assertDatabaseHas('rooms', [
            'room_number' => '101',
            'type' => 'Deluxe',
        ]);
    }

    public function test_store_returns_validation_errors_when_payload_is_invalid(): void
    {
        $response = $this->postJson('/api/rooms', [
            'room_number' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['room_number', 'type', 'price', 'availability_status'],
            ]);
    }

    public function test_show_returns_room_when_found(): void
    {
        $room = Room::factory()->create();

        $response = $this->getJson("/api/rooms/{$room->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.id', $room->id);
    }

    public function test_show_returns_not_found_when_missing(): void
    {
        $response = $this->getJson('/api/rooms/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', 'Resource not found.');
    }

    public function test_update_modifies_room_when_payload_is_valid(): void
    {
        $room = Room::factory()->create();

        $payload = [
            'room_number' => '102',
            'type' => 'Suite',
            'price' => 250.00,
            'availability_status' => false,
        ];

        $response = $this->putJson("/api/rooms/{$room->id}", $payload);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('message', 'Room updated successfully.');

        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'room_number' => '102',
            'availability_status' => false,
        ]);
    }

    public function test_update_returns_validation_errors_when_payload_is_invalid(): void
    {
        $room = Room::factory()->create();

        $response = $this->putJson("/api/rooms/{$room->id}", [
            'room_number' => $room->room_number,
            'type' => '',
            'price' => -10,
            'availability_status' => 'not_boolean',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['type', 'price', 'availability_status'],
            ]);
    }

    public function test_update_returns_not_found_when_room_missing(): void
    {
        $payload = [
            'room_number' => '103',
            'type' => 'Standard',
            'price' => 100,
            'availability_status' => true,
        ];

        $response = $this->putJson('/api/rooms/999', $payload);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', 'Resource not found.');
    }

    public function test_destroy_deletes_room_and_cascades_bookings(): void
    {
        $room = Room::factory()
            ->has(Booking::factory()->count(2))
            ->create();

        $response = $this->deleteJson("/api/rooms/{$room->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_destroy_returns_not_found_when_room_missing(): void
    {
        $response = $this->deleteJson('/api/rooms/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', 'Resource not found.');
    }
}
