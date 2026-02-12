<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'country',
        'latitude',
        'longitude',
    ];

    public function weatherMeasurements(): HasMany
    {
        return $this->hasMany(WeatherMeasurement::class);
    }
}
