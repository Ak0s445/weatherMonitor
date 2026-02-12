<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;








Route::get('/weather/{city_id}', [WeatherController::class, 'index']);
