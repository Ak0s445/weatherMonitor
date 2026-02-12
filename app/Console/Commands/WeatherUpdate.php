<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\City;
use App\Models\WeatherMeasurement;
use Illuminate\Support\Facades\Http;

class WeatherUpdate extends Command
{
    /**
     * A parancs neve és aláírása.
     *
     * @var string
     */
    protected $signature = 'app:weather-update';

    /**
     * A parancs leírása.
     *
     * @var string
     */
    protected $description = 'Lekéri az összes város aktuális hőmérsékletét az Open-Meteo API-tól';

    public function handle()
    {
        $cities = City::all();
        if ($cities->isEmpty()) {
            $this->info('Nincs város az adatbázisban');
            return;
        }

        foreach ($cities as $city) {
            try {
                $response = Http::get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $city->latitude,
                    'longitude' => $city->longitude,
                    'current' => 'temperature_2m',
                ]);

                $current = $response->json('current', []);

                if (!empty($current) && isset($current['temperature_2m'])) {
                    $temperature = $current['temperature_2m'];

                    WeatherMeasurement::create([
                        'city_id' => $city->id,
                        'temperature' => $temperature,
                        'measured_at' => now(),
                    ]);

                    $this->info("✓ {$city->name}: {$temperature}°C");
                } else {
                    $this->warn("✗ {$city->name}: Nincs adat az API-tól");
                }
            } catch (\Exception $e) {

                $this->warn("✗ {$city->name}: Hiba - {$e->getMessage()}");
            }
        }

        $this->info('Hőmérséklet frissítése kész');
    }
}
