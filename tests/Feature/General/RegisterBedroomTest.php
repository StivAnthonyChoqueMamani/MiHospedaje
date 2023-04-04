<?php

namespace Tests\Feature\General;

use App\Models\Bedroom;
use App\Models\Customer;
use App\Models\Logbook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterBedroomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bedroom_cannot_be_occupied_twice_in_the_same_day(): void
    {
        $bedrooms = Bedroom::factory(5)->create();
        $customer = Customer::factory()->create();
        $otherCustomer = Customer::factory()->create();

        $this->postJson(route('api.v1.logbooks.store'), [
            'reservation' => false,
            'observation' => null,
            '_relationships' => [
                'customer' => $customer,
                'bedrooms' => [
                    'data' => [
                        [
                            'model' => $bedrooms[0],
                            'pivot' => [
                                'additional_charge' => 0
                            ]
                        ], [
                            'model' => $bedrooms[2],
                            'pivot' => [
                                'additional_charge' => 5
                            ]
                        ],
                    ]
                ],
            ]
        ])->assertCreated();

        $this->postJson(route('api.v1.logbooks.store'), [
            'reservation' => false,
            'observation' => null,
            '_relationships' => [
                'customer' => $otherCustomer,
                'bedrooms' => [
                    'data' => [
                        [
                            'model' => $bedrooms[4],
                            'pivot' => [
                                'additional_charge' => 0
                            ]
                        ],
                        [
                            'model' => $bedrooms[2],
                            'pivot' => [
                                'additional_charge' => 0
                            ]
                        ],
                        [
                            'model' => $bedrooms[0],
                            'pivot' => [
                                'additional_charge' => 0
                            ]
                        ],
                    ]
                ],
            ]
        ])->assertJsonApiValidationErrors('data.relationships.bedrooms.data.1.id', 'data.relationships.bedrooms.data.2.id');
    }

    /** @test */
    public function pass_a_record_of_type_reservation_to_current_record()
    {
        $customer = Customer::factory()->create();

        $bedrooms = Bedroom::factory(5)->create();

        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '10-4-2023',
            'exit_at' => '11-4-2023',
            'reservation' => true,
            '_relationships' => [
                'customer' => $customer,
                'bedrooms' => [
                    'data' => [
                        [
                            'model' => $bedrooms[2],
                            'pivot' => [
                                'additional_charge' => 0,
                            ],
                        ],
                        [
                            'model' => $bedrooms[3],
                            'pivot' => [
                                'additional_charge' => 0,
                            ],
                        ],
                    ]
                ],
            ],
        ])->assertCreated();

        $logbook = Logbook::first();

        $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'reservation' => false,
        ])->assertOk();

        $this->assertDatabaseHas('bedroom_logbook', [
            'bedroom_id' => $bedrooms[2]->id,
            'logbook_id' => $logbook->id,
        ]);

        $this->assertDatabaseHas('bedroom_logbook', [
            'bedroom_id' => $bedrooms[3]->id,
            'logbook_id' => $logbook->id,
        ]);
    }

    /** @test */
    public function it_is_not_possible_to_go_from_a_reservation_record_to_a_current_record_when_a_room_is_in_a_different_status_than_available()
    {
        $customer = Customer::factory()->create();

        $bedrooms = Bedroom::factory(5)->create();

        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => '10-4-2023',
            'exit_at' => '11-4-2023',
            'reservation' => true,
            '_relationships' => [
                'customer' => $customer,
                'bedrooms' => [
                    'data' => [
                        [
                            'model' => $bedrooms[2],
                            'pivot' => [
                                'additional_charge' => 0,
                            ],
                        ],
                        [
                            'model' => $bedrooms[3],
                            'pivot' => [
                                'additional_charge' => 0,
                            ],
                        ],
                    ]
                ],
            ],
        ])->assertCreated();

        $bedrooms[3]->status = 'en mantenimiento';
        $bedrooms[3]->save();

        $logbook = Logbook::first();

        $this->patchJson(route('api.v1.logbooks.update', $logbook), [
            'reservation' => false,
        ])->assertJsonApiValidationErrors('data.relationships.bedrooms.data.1.name');
    }

    /** @test */
    public function cannot_reserve_a_room_on_the_current_day()
    {
        $this->postJson(route('api.v1.logbooks.store'), [
            'entry_at' => now()->format('Y-m-d'),
            'exit_at' => now()->addDay()->format('Y-m-d'),
            'reservation' => true,
            '_relationships' => [
                'customer' => Customer::factory()->create(),
                'bedrooms' => [
                    'data' => [
                        [
                            'model' => Bedroom::factory()->create(),
                            'pivot' => [
                                'additional_charge' => 0,
                            ]
                        ],
                    ],
                ]
            ]
        ])->assertJsonApiValidationErrors('entry_at');
    }
}
