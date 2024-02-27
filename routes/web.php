<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\FavoriteQuoteController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create']); 
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/quotes', [QuoteController::class, 'index']);
    Route::get('/dashboard', [QuoteController::class, 'index'])->name('dashboard');
    Route::get('/favorite-quotes', [FavoriteQuoteController::class, 'index'])->name('favorite-quotes');
    Route::post('/favorite-quotes', [FavoriteQuoteController::class, 'store']);
    Route::delete('/favorite-quotes', [FavoriteQuoteController::class, 'destroy']); 

    Route::get('/profile', [UserController::class, 'index'])->name('profile');
    Route::post('/profile/update/info', [UserController::class, 'profile_update'])->name('profile.update.info');
    Route::post('/profile/update/password', [UserController::class, 'password_update'])->name('profile.update.password');
});

// Admin routes
Route::namespace('App\Http\Controllers\Admin')->prefix('admin')->middleware(['auth', 'auth.admin'])->group(function () {
    Route::get('/users', 'UserController@index')->name('admin.users');
    Route::post('/users/ban', 'UserController@ban')->name('admin.users.ban');
    Route::get('/users/{user}/quotes', 'QuoteController@index')->name('admin.users.quotes');
    Route::delete('/quotes', 'QuoteController@destroy')->name('admin.users.quotes.delete'); 
});

require __DIR__.'/auth.php';
