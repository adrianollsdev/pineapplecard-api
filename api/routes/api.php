<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\InvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('profile')->group(function(){
        Route::get('/{id}', [ProfileController::class, 'show']);
});

Route::prefix('payment')->group(function(){
        Route::get('/{id}', [PaymentController::class, 'show']);
});

Route::prefix('invoice')->group(function () {
    Route::get('/{profile}/{monthYear}', [InvoiceController::class, 'show']);
});
