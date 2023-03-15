<?php

namespace Tests\Feature\Logbooks;

use App\Models\Logbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteLogbookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_softDelete_logbook(): void
    {
        $logbook = Logbook::factory()->create();

        $this->deleteJson(route('api.v1.logbooks.destroy', $logbook))->assertNoContent();

        $this->assertSoftDeleted($logbook);
    }
}
