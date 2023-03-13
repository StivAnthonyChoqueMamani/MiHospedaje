<?php

namespace Tests\Feature\Bedrooms;

use App\Models\Bedroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListBedroomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_bedroom()
    {
        $bedroom = Bedroom::factory()->create();

        $response = $this->getJson(route('api.v1.bedrooms.show',$bedroom));

        $response->assertJsonApiResource($bedroom,[
            'name' => $bedroom->name,
            'description' => $bedroom->description,
            'price' => $bedroom->price,
            'observation' => $bedroom->observation,
            'status' => $bedroom->status,
        ]);
    }

    /** @test */
    public function can_list_bedrooms()
    {
        $bedrooms = Bedroom::factory(4)->create();

        $response = $this->getJson(route('api.v1.bedrooms.index'));

        $response->assertJsonApiResourceCollection($bedrooms, [
            'name', 'description', 'price', 'observation', 'status'
        ]);
    }

    /** @test */
    public function it_returns_a_json_api_error_object_when_an_bedroom_is_not_found()
    {
        $this->getJson(route('api.v1.bedrooms.show', 'not-existing'))
        ->assertJsonApiError(
            title: 'Not Found',
            detail: "No records found with the id 'not-existing' in the 'bedrooms' resource.",
            status: '404'
        );
    }
}
