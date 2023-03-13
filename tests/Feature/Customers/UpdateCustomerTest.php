<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->patchJson(route('api.v1.customers.update', $customer), [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'dni' => $customer->dni,
            'observation' => 'Cliente Ratero',
        ])->assertOk();

        $response->assertJsonApiResource($customer, [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'dni' => $customer->dni,
            'observation' => 'Cliente Ratero',
        ]);
    }

    /** @test */
    public function name_is_required()
    {
        $customer = Customer::factory()->create();

        $this->patchJson(route('api.v1.customers.update', $customer), [
            'lastname' => 'Choque',
            'dni' => $customer->dni,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('name');
    }

    /** @test */
    public function lastname_is_required()
    {
        $customer = Customer::factory()->create();

        $this->patchJson(route('api.v1.customers.update', $customer), [
            'name' => 'Stiv',
            'dni' => $customer->dni,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('lastname');
    }

    /** @test */
    public function dni_is_required()
    {
        $customer = Customer::factory()->create();

        $this->patchJson(route('api.v1.customers.update', $customer), [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('dni');
    }

    /** @test */
    public function dni_is_unique()
    {
        $customer = Customer::factory()->create();

        $otherCustomer = Customer::factory()->create();

        $this->patchJson(route('api.v1.customers.update', $customer), [
            'name' => 'Stiv',
            'lastname' => 'Choque',
            'dni' => $otherCustomer->dni,
            'observation' => 'Sin Ninguna Observación',
        ])->assertJsonApiValidationErrors('dni');
    }
}
