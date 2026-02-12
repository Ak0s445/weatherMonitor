<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherMeasurement extends Model
{
    protected $fillable = [
        'city_id',
        'temperature',
        'measured_at',
    ];

    protected $casts = [
        'measured_at' => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
