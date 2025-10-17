<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_bookings(): void
    {
        Booking::factory()->count(3)->create();

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->etc()
            );
    }

    public function test_store_creates_booking_when_payload_is_valid(): void
    {
        $room = Room::factory()->create();

        $payload = [
            'room_id' => $room->id,
            'customer_name' => 'Alice Nguyen',
            'check_in_date' => now()->addDay()->toDateString(),
            'check_out_date' => now()->addDays(3)->toDateString(),
            'status' => 'confirmed',
        ];

        $response = $this->postJson('/api/bookings', $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('message', 'Booking created successfully.')
            ->assertJsonPath('data.room_id', $room->id);

        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id,
            'customer_name' => 'Alice Nguyen',
        ]);
    }

    public function test_store_returns_validation_errors_when_payload_is_invalid(): void
    {
        $response = $this->postJson('/api/bookings', [
            'room_id' => null,
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->toDateString(),
            'status' => 'unknown',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['room_id', 'customer_name', 'check_out_date', 'status'],
            ]);
    }

    public function test_show_returns_booking_when_found(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->getJson("/api/bookings/{$booking->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('data.id', $booking->id);
    }

    public function test_show_returns_not_found_when_missing(): void
    {
        $response = $this->getJson('/api/bookings/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', 'Resource not found.');
    }

    public function test_update_modifies_booking_when_payload_is_valid(): void
    {
        $booking = Booking::factory()->create();
        $newRoom = Room::factory()->create();

        $payload = [
            'room_id' => $newRoom->id,
            'customer_name' => 'Bob Smith',
            'check_in_date' => now()->addDays(7)->toDateString(),
            'check_out_date' => now()->addDays(10)->toDateString(),
            'status' => 'pending',
        ];

        $response = $this->putJson("/api/bookings/{$booking->id}", $payload);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('message', 'Booking updated successfully.')
            ->assertJsonPath('data.room_id', $newRoom->id);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'room_id' => $newRoom->id,
            'customer_name' => 'Bob Smith',
            'status' => 'pending',
        ]);
    }

    public function test_update_returns_validation_errors_when_payload_is_invalid(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->putJson("/api/bookings/{$booking->id}", [
            'room_id' => 0,
            'customer_name' => '',
            'check_in_date' => now()->addDays(5)->toDateString(),
            'check_out_date' => now()->addDays(3)->toDateString(),
            'status' => 'invalid',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['room_id', 'customer_name', 'check_out_date', 'status'],
            ]);
    }

    public function test_update_returns_not_found_when_booking_missing(): void
    {
        $room = Room::factory()->create();

        $payload = [
            'room_id' => $room->id,
            'customer_name' => 'Missing Booking',
            'check_in_date' => now()->addDay()->toDateString(),
            'check_out_date' => now()->addDays(2)->toDateString(),
            'status' => 'confirmed',
        ];

        $response = $this->putJson('/api/bookings/999', $payload);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', 'Resource not found.');
    }

    public function test_destroy_deletes_booking(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/bookings/{$booking->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_destroy_returns_not_found_when_booking_missing(): void
    {
        $response = $this->deleteJson('/api/bookings/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', 'Resource not found.');
    }
}
