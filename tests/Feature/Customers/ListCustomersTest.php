<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListCustomersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson(route('api.v1.customers.show', $customer));

        $response->assertJsonApiResource($customer, [
            'name' => $customer->name,
            'lastname' => $customer->lastname,
            'dni' => $customer->dni,
            'observation' => $customer->observation,
        ]);
    }

    /** @test */
    public function can_list_customer(): void
    {
        $customers = Customer::factory(5)->create();

        $response = $this->getJson(route('api.v1.customers.index'));

        $response->assertJsonApiResourceCollection($customers, [
            'name', 'lastname', 'dni', 'observation'
        ]);
    }
}
