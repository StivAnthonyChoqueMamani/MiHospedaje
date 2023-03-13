<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_customers(): void
    {
        $response = $this->postJson(route('api.v1.customers.store'), [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'dni' => '76039986',
            'observation' => 'Sin Ninguna Observación',
        ])->assertCreated();

        $customer = Customer::first();

        $response->assertJsonApiResource($customer, [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'dni' => '76039986',
            'observation' => 'Sin Ninguna Observación',
        ]);
    }

    /** @test */
    public function name_is_required()
    {
        $this->postJson(route('api.v1.customers.store'), [
            'lastname' => 'Choque',
            'dni' => '76039986',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('name');
    }

    /** @test */
    public function lastname_is_required()
    {
        $this->postJson(route('api.v1.customers.store'), [
            'name' => 'Stiv',
            'dni' => '76039986',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('lastname');
    }

    /** @test */
    public function dni_is_required()
    {
        $this->postJson(route('api.v1.customers.store'), [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('dni');
    }

    /** @test */
    public function dni_is_unique()
    {
        $customer = Customer::factory()->create();

        $this->postJson(route('api.v1.customers.store'), [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'dni' => $customer->dni,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('dni');
    }
}
