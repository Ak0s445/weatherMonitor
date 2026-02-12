<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return view('cities.index', compact('cities'));
    }

    public function create()
    {
        return view('cities.create');
    }

    public function store(Request $request)
    {
        // Validáció
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        // Open-Meteo geocoding API-t igy hivom :
        $response = Http::get('https://geocoding-api.open-meteo.com/v1/search', [
            'name' => $request->name,
            'country' => $request->country,
            'count' => 1,
            'language' => 'en',
            'format' => 'json',
        ]);

        $results = $response->json('results', []);

        if ($response->successful() && count($results) > 0) {
            $result = $results[0];

            City::create([
                'name' => $request->name,
                'country' => $request->country,
                'latitude' => $result['latitude'],
                'longitude' => $result['longitude'],
            ]);

            return redirect()->route('cities.index')->with('success', 'Város sikeresen hozzáadva');
        }

        return redirect()->back()->with('error', 'A megadott város nem található');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('cities.index')->with('success', 'Város sikeresen törölve!');
    }

    public function dashboard()
    {
        $cities = City::with(['weatherMeasurements' => function ($query) {
            $query->orderBy('measured_at', 'desc')->limit(10);
        }])->get();

        $latestTemperatures = [];
        foreach ($cities as $city) {
            $latest = $city->weatherMeasurements()->latest('measured_at')->first();
            if ($latest) {
                $latestTemperatures[$city->id] = [
                    'name' => $city->name,
                    'temperature' => $latest->temperature,
                    'measured_at' => $latest->measured_at,
                ];
            }
        }

        return view('cities.dashboard', compact('cities', 'latestTemperatures'));
    }
}
