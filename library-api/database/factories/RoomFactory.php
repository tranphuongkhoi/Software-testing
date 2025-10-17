<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_number' => $this->faker->unique()->numerify('10#'),
            'type' => $this->faker->randomElement(['Standard', 'Deluxe', 'Suite']),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'availability_status' => $this->faker->boolean(),
        ];
    }
}
