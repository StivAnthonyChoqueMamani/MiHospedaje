<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Logbook;

class LogbookCustomerController extends Controller
{
    public function index(Logbook $logbook)
    {
        return CustomerResource::identifier($logbook->customer);
    }

    public function show(Logbook $logbook)
    {
        return CustomerResource::make($logbook->customer);
    }
}
