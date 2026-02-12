<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;

class WeatherController extends Controller
{
    public function index($city_id)
    {
        $city = City::find($city_id);

        if (!$city) {
            return response()->json([
                'error' => 'Város nem található',
                'city_id' => $city_id,
            ], 404);
        }

        $measurements = $city->weatherMeasurements()
            ->orderBy('measured_at', 'asc')
            ->get();

        return response()->json([
            'city_id' => $city->id,
            'city_name' => $city->name,
            'city_country' => $city->country,
            'latitude' => (float) $city->latitude,
            'longitude' => (float) $city->longitude,
            'measurements_count' => $measurements->count(),
            'measurements' => $measurements->map(function($measurement) {
                return [
                    'temperature' => (float) $measurement->temperature,
                    'measured_at' => $measurement->measured_at,
                ];
            }),
        ]);
    }
}
