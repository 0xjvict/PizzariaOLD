<?php

use App\Http\Controllers\Order\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(OrderController::class)->group(function () {
    Route::get('/place-order', 'placeOrder');
    Route::get('/pay-order/{orderId}', 'payOrder');
    Route::get('/retrieve-order/{orderId}', 'retrieveOrder');
});
