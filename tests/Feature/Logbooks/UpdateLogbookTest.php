<?php

namespace Tests\Feature\Logbooks;

use App\Models\Logbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateLogbookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_logbook()
    {
        $logbook = Logbook::factory()->create(['exit_at' => null]);

        $response = $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'entry_at' => '30-07-2023',
            'reservation' => false,
            'observation' => 'Nueva Observación.',
        ])->assertOk();

        $response->assertJsonApiResource($logbook, [
            'entry_at' => '30-07-2023',
            'reservation' => false,
            'observation' => 'Nueva Observación.'
        ]);
    }

    /** @test */
    public function can_update_the_logbook_of_type_reservation(): void
    {
        $logbook = Logbook::factory()->create();

        $response = $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'entry_at' => '30-07-2023',
            'exit_at' => '3-08-2023',
            'reservation' => true,
            'observation' => 'Nueva Observación.'
        ])->assertOk();

        $response->assertJsonApiResource($logbook, [
            'entry_at' => '30-07-2023',
            'exit_at' => '3-08-2023',
            'reservation' => true,
            'observation' => 'Nueva Observación.'
        ]);
    }

    /** @test */
    public function entry_at_is_required_in_the_logbook_of_type_reservation()
    {
        $logbook = Logbook::factory()->create(['exit_at' => null]);

        $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
        ])->assertJsonApiValidationErrors('entry_at');
    }

    /** @test */
    public function exit_at_is_required_in_the_logbook_of_type_reservation()
    {
        $logbook = Logbook::factory()->create(['exit_at' => null]);

        $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'entry_at' => '2023-05-10',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
        ])->assertJsonApiValidationErrors('exit_at');
    }

    /** @test */
    public function reservation_must_be_of_type_boolean()
    {
        $logbook = Logbook::factory()->create(['exit_at' => null]);

        $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023-05-13',
            'reservation' => 'false',
            'observation' => 'El cliente pago el 50%.',
        ])->assertJsonApiValidationErrors('reservation');
    }

    /** @test */
    public function entry_at_must_be_of_type_date_in_the_logbook_of_type_reservation()
    {
        $logbook = Logbook::factory()->create(['exit_at' => null]);

        $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'entry_at' => '2023 05 10 other format',
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
        ])->assertJsonApiValidationErrors('entry_at');
    }

    /** @test */
    public function exit_at_must_be_of_type_date_in_the_logbook_of_type_reservation()
    {
        $logbook = Logbook::factory()->create(['exit_at' => null]);

        $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023 05 13 other format',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
        ])->assertJsonApiValidationErrors('exit_at');
    }
}
