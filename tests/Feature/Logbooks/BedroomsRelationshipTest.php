<?php

namespace Tests\Feature\Logbooks;

use App\Models\Bedroom;
use App\Models\Logbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BedroomsRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_the_associate_bedrooms_identifiers(): void
    {
        $logbook = Logbook::factory()->hasBedrooms(2)->create();

        $url = route('api.v1.logbooks.relationships.bedrooms', $logbook);

        $response = $this->getJson($url);

        $response->assertJsonCount(2, 'data');

        $logbook->bedrooms->map(fn ($bedroom) => $response->assertJsonFragment([
            'id' => (string) $bedroom->getRouteKey(),
            'type' => 'bedrooms',
        ]));
    }

    /** @test */
    public function it_returns_an_empty_array_when_there_are_no_associated_bedrooms()
    {
        $logbook = Logbook::factory()->create();

        $url = route('api.v1.logbooks.relationships.bedrooms', $logbook);

        $response = $this->getJson($url);

        $response->assertJsonCount(0, 'data');

        $response->assertExactJson([
            'data' => [],
        ]);
    }

    /** @test */
    public function can_fetch_the_associate_bedrooms_resource()
    {
        $logbook = Logbook::factory()->hasBedrooms(2)->create();

        $url = route('api.v1.logbooks.bedrooms', $logbook);

        $response = $this->getJson($url);

        $response->assertJson([
            'data' => [
                [
                    'id' => $logbook->bedrooms[0]->getRouteKey(),
                    'type' => 'bedrooms',
                    'attributes' => [
                        'name' => $logbook->bedrooms[0]->name,
                        'description' => $logbook->bedrooms[0]->description,
                        'price' => $logbook->bedrooms[0]->price,
                    ],
                ], [
                    'id' => $logbook->bedrooms[1]->getRouteKey(),
                    'type' => 'bedrooms',
                    'attributes' => [
                        'name' => $logbook->bedrooms[1]->name,
                        'description' => $logbook->bedrooms[1]->description,
                        'price' => $logbook->bedrooms[1]->price,
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function can_update_the_associate_bedrooms()
    {
        $bedrooms = Bedroom::factory(3)->create();

        $logbook = Logbook::factory()->create();

        $url = route('api.v1.logbooks.relationships.bedrooms', $logbook);

        $response = $this->patchJson($url, [
            'data' => [
                [
                    'type' => 'bedrooms',
                    'id' => (string)$bedrooms[0]->getRouteKey(),
                    'pivot' => [
                        'additional_charge' => 5
                    ]
                ],
                [
                    'type' => 'bedrooms',
                    'id' => (string)$bedrooms[1]->getRouteKey(),
                    'pivot' => [
                        'additional_charge' => 0
                    ]
                ],
            ],
        ])->assertOk();

        $logbook->bedrooms->map(fn ($bedroom) => $response->assertJsonFragment([
            'id' => (string) $bedroom->getRouteKey(),
            'type' => 'bedrooms',
        ]));
    }
}
