<?php

namespace Database\Factories;

use App\Models\BedroomLogbook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stay>
 */
class StayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bedroom_logbook_id' => BedroomLogbook::factory()->create()->id,
            'date_stay' => now()->add(fake()->numberBetween(1, 5), 'day'),
            'paid' => false,
        ];
    }
}
