<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Logbook>
 */
class LogbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
            'entry_at' => now()->format('Y-m-d H:i:s'),
            'exit_at' => now()->add(fake()->numberBetween(1, 5), 'day')->format('Y-m-d H:i:s'),
            'reservation' => false,
            'observation' => 'Sin Observacion',
        ];
    }
}
