<?php

namespace Tests\Feature\Bedrooms;

use App\Models\Bedroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateBedroomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_a_bedroom(): void
    {
        $bedroom = Bedroom::factory()->create();

        $response = $this->patchJson(route('api.v1.bedrooms.update', $bedroom), [
            'name' => $bedroom->name,
            'description' => 'Habitacion matrimonial',
            'price' => 30,
            'observation' => 'Espejo roto.',
        ])->assertOk();

        $response->assertJsonApiResource($bedroom, [
            'name' => $bedroom->name,
            'description' => 'Habitacion matrimonial',
            'price' => 30,
            'observation' => 'Espejo roto.',
        ]);
    }

    /** @test */
    public function name_is_required()
    {
        $bedroom = Bedroom::factory()->create();

        $this->patchJson(route('api.v1.bedrooms.update', $bedroom), [
            'description' => 'Habitacion Matrimonial',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('name');
    }

    /** @test */
    public function description_is_required()
    {
        $bedroom = Bedroom::factory()->create();

        $this->patchJson(route('api.v1.bedrooms.update', $bedroom), [
            'name' => 'N201',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('description');
    }

    /** @test */
    public function price_is_required()
    {
        $bedroom = Bedroom::factory()->create();

        $this->patchJson(route('api.v1.bedrooms.update', $bedroom), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('price');
    }

    /** @test */
    public function name_is_unique()
    {
        $bedroom = Bedroom::factory()->create();

        Bedroom::factory()->create(['name' => 'N201']);

        $this->patchJson(route('api.v1.bedrooms.update', $bedroom), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('name');
    }

    /** @test */
    public function price_must_not_be_negative()
    {
        $bedroom = Bedroom::factory()->create();

        $this->patchJson(route('api.v1.bedrooms.update', $bedroom), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => -30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('price');
    }

    /** @test */
    public function price_is_numeric()
    {
        $bedroom = Bedroom::factory()->create();

        $this->patchJson(route('api.v1.bedrooms.update', $bedroom), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => 'H30',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('price');
    }
}
