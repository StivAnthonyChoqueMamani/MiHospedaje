<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveLogbookRequest;
use App\Http\Resources\LogbookResource;
use App\Models\Bedroom;
use App\Models\Customer;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $logbookData = $request->getAttributes();
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
