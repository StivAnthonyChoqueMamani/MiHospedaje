<?php

namespace Tests\Feature\Logbooks;

use App\Models\Logbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListLogbooksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_logbook()
    {
        $logbook = Logbook::factory()->create();

        $response = $this->getJson(route('api.v1.logbooks.show', $logbook))->assertOk();

        $response->assertJsonApiResource($logbook, [
            'entry_at' => $logbook->entry_at,
            'exit_at' => $logbook->exit_at,
            'reservation' => $logbook->reservation,
            'observation' => $logbook->observation,
        ]);
    }

    /** @test */
    public function can_list_logbooks(): void
    {
        $logbooks = Logbook::factory(4)->create();

        $response = $this->getJson(route('api.v1.logbooks.index'))->assertOk();

        $response->assertJsonApiResourceCollection($logbooks, [
            'entry_at', 'exit_at', 'reservation', 'observation'
        ]);
    }
}
