<?php

namespace Tests\Feature\Bedrooms;

use App\Models\Bedroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteBedroomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_delete_bedroom(): void
    {
        $bedroom = Bedroom::factory()->create();

        $this->deleteJson(route('api.v1.bedrooms.destroy', $bedroom))->assertNoContent();

        $this->assertSoftDeleted($bedroom);
    }
}
