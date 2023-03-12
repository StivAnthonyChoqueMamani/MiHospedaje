<?php

namespace Database\Factories;

use App\Models\Bedroom;
use App\Models\Logbook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BedroomLogbook>
 */
class BedroomLogbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bedroom_id' => Bedroom::factory()->create()->id,
            'logbook_id' => Logbook::factory()->create()->id,
            'additional_charge' => fake()->numberBetween(0,20),
        ];
    }
}
