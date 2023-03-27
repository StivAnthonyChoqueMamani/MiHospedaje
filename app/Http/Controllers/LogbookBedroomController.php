<?php

namespace App\Http\Controllers;

use App\Http\Resources\BedroomResource;
use App\Models\Bedroom;
use App\Models\Logbook;
use Illuminate\Http\Request;

class LogbookBedroomController extends Controller
{
    public function index(Logbook $logbook)
    {
        return BedroomResource::identifiers($logbook->bedrooms);
    }

    public function show(Logbook $logbook)
    {
        return BedroomResource::collection($logbook->bedrooms);
    }

    public function update(Logbook $logbook, Request $request)
    {
        $request->validate([
            'data.*.id' => ['exists:bedrooms,name'],
        ]);

        $requestData = $request->input('data');

        $logbook->bedrooms()->sync([]);

        foreach($requestData as $value)
        {
            $bedroom = Bedroom::where('name',$value['id'])->first();
            $logbook->bedrooms()->attach($bedroom,['additional_charge' => $value['pivot']['additional_charge']]);
        }

        return BedroomResource::identifiers($logbook->bedrooms);
    }
}
