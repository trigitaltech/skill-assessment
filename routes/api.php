<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedTokenController;
use App\Http\Controllers\FavoriteQuoteController;
use App\Http\Controllers\QuoteController;

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

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthenticatedTokenController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/quotes', [QuoteController::class, 'index']);

    Route::get('/favorite-quotes', [FavoriteQuoteController::class, 'index']);
    Route::post('/favorite-quotes', [FavoriteQuoteController::class, 'store']);
    Route::delete('/favorite-quotes', [FavoriteQuoteController::class, 'destroy']);

    Route::get('/logout', [AuthenticatedTokenController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
