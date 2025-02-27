<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::resource('dashboard/cars', CarsController::class);
Route::resource('dashboard/promos', PromoController::class);

Route::get('/get-car-detail-price/{id}', [PromoController::class, 'getDetailPrice']);


Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');