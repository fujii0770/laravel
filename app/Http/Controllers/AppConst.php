<?php

namespace App\Http\Controllers;

class AppConst
{
    //
    const SPECIAL_POND = ['pond-storage-1', 'pond-storage-2'];
    const WEATHER_CONDITION = [
        'Clear' => 0,
        'Clouds' => 1,
        'Rain' => 2,
        'Thunderstorm'=>2,
        'Drizzle' => 2,
        'Snow' => 3,
        'Mist' => 4,
        'Smoke' => 4,
        'Haze' => 4,
        'Dust' => 4,
        'Fog' => 4,
        'Sand' => 4,
        'Ash' => 4,
        'Squall' => 4,
        'Tornado' => 4
    ];

    const STATUS_OPEN = 0;
    const STATUS_COMPLETED = 1;

    const IMPORT_TYPE_WATER = 0;
    const IMPORT_TYPE_BAIT = 1;
    const IMPORT_TYPE_DRUG = 2;

    const MAX_FARM_NUMBER = 6;
}
