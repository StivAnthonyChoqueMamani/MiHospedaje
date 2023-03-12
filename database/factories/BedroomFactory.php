<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bedroom>
 */
class BedroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'N'.(string)fake()->numberBetween(100,500),
            'description' => fake()->unique()->name(),
            'price' => fake()->numberBetween(10,100),
            'observation' => 'Sin Observaciones',
            'status' => 'disponible',
        ];
    }
}
