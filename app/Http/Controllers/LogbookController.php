<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLogbookRequest;
use App\Http\Resources\LogbookResource;
use App\Models\Bedroom;
use App\Models\Customer;
use App\Models\Logbook;
use Closure;
use Illuminate\Support\Facades\Validator;

class LogbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logbooks = Logbook::jsonPaginate();

        return LogbookResource::collection($logbooks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveLogbookRequest $request)
    {
        $validatedBedroom = $request->safe()->only(['data.relationships.bedrooms.data']);
        $logbookData = $request->getAttributes();

        if (!$logbookData['reservation']) {
            Validator::validate($validatedBedroom, [
                'data.relationships.bedrooms.data.*.id' => [
                    'required',
                    function (string $attribute, mixed $value, Closure $fail) {
                        $bedroom = Bedroom::where('name', $value)->first();
                        if ($bedroom->status != 'disponible') {
                            $fail("La Habitación {$value} se encuentra {$bedroom->status}.");
                        }
                    },
                ]
            ]);
        }

        $customerDNI = $request->getRelationshipId('customer');
        $bedroomNames = $request->getRelationshipId('bedrooms');

        $logbookData['customer_id'] = Customer::where('dni', $customerDNI)->first()->id;

        if ($logbookData['reservation']) {
            $logbook = Logbook::create($logbookData);
        } else {
            $logbook = new Logbook();
            $logbook->entry_at = now()->format('Y-m-d H:i:s');
            $logbook->reservation = false;
            $logbook->observation = $logbookData['observation'];
            $logbook->customer_id = $logbookData['customer_id'];
            $logbook->save();
        }

        foreach ($bedroomNames as $item) {
            $bedroom = Bedroom::where('name', $item['id'])->first();
            if (!$logbookData['reservation']) {
                $bedroom->status = 'ocupado';
                $bedroom->save();
            }
            $logbook->bedrooms()->attach($bedroom, [
                'additional_charge' => $item['pivot']['additional_charge'],
            ]);
        }

        return LogbookResource::make($logbook);
    }

    /**
     * Display the specified resource.
     */
    public function show(Logbook $logbook)
    {
        return LogbookResource::make($logbook);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveLogbookRequest $request, Logbook $logbook)
    {
        $logbookData = $request->getAttributes();

        if ($logbook->reservation && !$logbookData['reservation']) {
            // Si entra en esta seccion significa que hay un cambio de tipo reservation
            $data['data']['relationships']['bedrooms']['data'] = $logbook->bedrooms->toArray();
            Validator::validate($data, [
                'data.relationships.bedrooms.data.*.name' => [
                    function (string $attribute, mixed $value, Closure $fail) {
                        $bedroom = Bedroom::where('name', $value)->first();
                        if ($bedroom->status != 'disponible') {
                            $fail("No se puede activar la reserva porque la habitación {$value} actualmente se encuentra {$bedroom->status}.");
                        }
                    },
                ]
            ]);

            foreach ($logbook->bedrooms as $item) {
                $item->status = 'ocupado';
                $item->save();
            }
        }


        $logbook->update($logbookData);

        return LogbookResource::make($logbook);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logbook $logbook)
    {
        $logbook->delete();

        return response()->noContent();
    }
}
