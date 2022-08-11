<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
        'lat_long_ne',
        'lat_long_nw',
        'lat_long_sw',
        'lat_long_se',
        'delta_measure',
    ];
}
