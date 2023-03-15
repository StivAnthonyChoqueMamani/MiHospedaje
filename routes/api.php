<?php

use App\Http\Controllers\BedroomController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\LogbookCustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('bedrooms', [BedroomController::class, 'index'])->name('bedrooms.index');
Route::get('bedrooms/{bedroom}', [BedroomController::class, 'show'])->name('bedrooms.show');
Route::post('bedrooms', [BedroomController::class, 'store'])->name('bedrooms.store');
Route::patch('bedrooms/{bedroom}', [BedroomController::class, 'update'])->name('bedrooms.update');
Route::delete('bedrooms/{bedroom}', [BedroomController::class, 'destroy'])->name('bedrooms.destroy');

Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
Route::patch('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');

Route::get('logbooks/{logbook}', [LogbookController::class, 'show'])->name('logbooks.show');
Route::get('logbooks', [LogbookController::class, 'index'])->name('logbooks.index');
Route::post('logbooks', [LogbookController::class, 'store'])->name('logbooks.store');
Route::patch('logbooks/{logbook}', [logbookController::class, 'update'])->name('logbooks.update');

Route::get('logbooks/{logbook}/relationships/customer', [LogbookCustomerController::class, 'index'])->name('logbooks.relationships.customer');
Route::get('logbooks/{logbook}/customer', [LogbookCustomerController::class, 'show'])->name('logbooks.customer');
