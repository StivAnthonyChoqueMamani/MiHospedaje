<?php

namespace Tests\Feature\Logbooks;

use App\Models\Logbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_associated_customer_identifier(): void
    {
        $logbook = Logbook::factory()->create();

        $url = route('api.v1.logbooks.relationships.customer', $logbook);

        $response = $this->getJson($url);

        $response->assertExactJson([
            'data' => [
                'id' => $logbook->customer->getRouteKey(),
                'type' => 'customers',
            ]
        ]);
    }

    /** @test */
    public function can_fetch_the_associated_customer_resource()
    {
        $logbook = Logbook::factory()->create();

        $url = route('api.v1.logbooks.customer', $logbook);

        $response = $this->getJson($url);

        $response->assertJson([
            'data' => [
                'id' => $logbook->customer->getRouteKey(),
                'type' => 'customers',
                'attributes' => [
                    'name' => $logbook->customer->name
                ]
            ]
        ]);
    }
}
