<?php

namespace Tests\Feature\Logbooks;

use App\Models\Bedroom;
use App\Models\Customer;
use App\Models\Logbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateLogbookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_logbook(): void
    {
        $response = $this->postJson(route('api.v1.logbooks.store'), [
            'reservation' => false,
            'observation' => null,
            '_relationships' => [
                'customer' => Customer::factory()->create(),
            ],
        ])->assertCreated();

        $logbook = Logbook::first();

        $response->assertJsonApiResource($logbook, [
            'entry_at' => $logbook->entry_at,
            'exit_at' => null,
            'reservation' => false,
            'observation' => null,
        ]);

        $this->assertDatabaseHas('logbooks', [
            'customer_id' => Customer::first()->id,
            'id' => $logbook->id,
        ]);
    }

    /** @test */
    public function can_create_logbook_of_reservation_type()
    {
        $response = $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
            '_relationships' => [
                'customer' => Customer::factory()->create(),
            ],
        ])->assertCreated();

        $logbook = Logbook::first();

        $response->assertJsonApiResource($logbook, [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
        ]);
    }

    /** @test */
    public function entry_at_is_required_in_the_logbook_of_type_reservation()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
            '_relationships' => [
                'customer' => Customer::factory()->create(),
            ],
        ])->assertJsonApiValidationErrors('entry_at');
    }

    /** @test */
    public function exit_at_is_required_in_the_logbook_of_type_reservation()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '2023-05-10',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
            '_relationships' => [
                'customer' => Customer::factory()->create(),
            ],
        ])->assertJsonApiValidationErrors('exit_at');
    }

    /** @test */
    public function customer_is_required_in_the_logbook_of_type_reservation()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
        ])->assertJsonApiValidationErrors('relationships.customer');
    }

    /** @test */
    public function customer_must_exist_in_the_logbook_of_type_reservation()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
            '_relationships' => [
                'customer' => Customer::factory()->make(),
            ],
        ])->assertJsonApiValidationErrors('relationships.customer');
    }

    /** @test */
    public function reservation_must_be_of_type_boolean()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023-05-13',
            'reservation' => 'false',
            'observation' => 'El cliente pago el 50%.',
            '_relationships' => [
                'customer' => Customer::factory()->create(),
            ],
        ])->assertJsonApiValidationErrors('reservation');
    }

    /** @test */
    public function entry_at_must_be_of_type_date_in_the_logbook_of_type_reservation()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '2023 05 10 other format',
            'exit_at' => '2023-05-13',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
            '_relationships' => [
                'customer' => Customer::factory()->create(),
            ],
        ])->assertJsonApiValidationErrors('entry_at');
    }

    /** @test */
    public function exit_at_must_be_of_type_date_in_the_logbook_of_type_reservation()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '2023-05-10',
            'exit_at' => '2023 05 13 other format',
            'reservation' => true,
            'observation' => 'El cliente pago el 50%.',
            '_relationships' => [
                'customer' => Customer::factory()->create(),
            ],
        ])->assertJsonApiValidationErrors('exit_at');
    }
}
