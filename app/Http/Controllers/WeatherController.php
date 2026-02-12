<?php

namespace App\Http\Controllers;

use App\Models\City;

class WeatherController extends Controller
{
    /**
     * A kiválasztott város összes hőmérséklet mérésé adatait adja vissza JSON-ban.
     */
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
            'latitude' => $city->latitude,
            'longitude' => $city->longitude,
            'measurements_count' => $measurements->count(),
            'measurements' => $measurements,
        ]);
    }
}
