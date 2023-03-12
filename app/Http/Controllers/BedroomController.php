<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveBedroomRequest;
use App\Http\Resources\BedroomResource;
use App\Models\Bedroom;
use Illuminate\Http\Request;

class BedroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bedrooms = Bedroom::paginate();

        return BedroomResource::collection($bedrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveBedroomRequest $request)
    {
        $bedroomData = $request->getAttributes();

        $bedroom = Bedroom::create($bedroomData);

        return BedroomResource::make($bedroom);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bedroom $bedroom)
    {
        return BedroomResource::make($bedroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveBedroomRequest $request, Bedroom $bedroom)
    {
        $bedroomData = $request->getAttributes();

        $bedroom->update($bedroomData);

        return BedroomResource::make($bedroom);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bedroom $bedroom)
    {
        //
    }
}
