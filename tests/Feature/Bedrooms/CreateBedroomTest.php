<?php

namespace Tests\Feature\Bedrooms;

use App\Models\Bedroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateBedroomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_bedroom(): void
    {
        $response = $this->postJson(route('api.v1.bedrooms.store'), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertCreated();

        $bedroom = Bedroom::first();

        $response->assertJsonApiResource($bedroom, [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ]);
    }

    /** @test */
    public function name_is_required()
    {
        $this->postJson(route('api.v1.bedrooms.store'), [
            'description' => 'Habitacion Matrimonial',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('name');
    }

    /** @test */
    public function description_is_required()
    {
        $this->postJson(route('api.v1.bedrooms.store'), [
            'name' => 'N201',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('description');
    }

    /** @test */
    public function price_is_required()
    {
        $this->postJson(route('api.v1.bedrooms.store'), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('price');
    }

    /** @test */
    public function name_is_unique()
    {
        Bedroom::factory()->create(['name' => 'N201']);

        $this->postJson(route('api.v1.bedrooms.store'), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => 30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('name');
    }

    /** @test */
    public function price_must_not_be_negative()
    {
        $this->postJson(route('api.v1.bedrooms.store'), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => -30,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('price');
    }

    /** @test */
    public function price_is_numeric()
    {
        $this->postJson(route('api.v1.bedrooms.store'), [
            'name' => 'N201',
            'description' => 'Habitacion Matrimonial',
            'price' => 'H30',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('price');
    }
}
