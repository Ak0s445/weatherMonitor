<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create');
Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');


Route::get('/dashboard', [CityController::class, 'dashboard'])->name('dashboard');


