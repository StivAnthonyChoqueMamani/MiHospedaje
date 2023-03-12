<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Bedroom $bedroom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bedroom $bedroom)
    {
        //
    }
}
