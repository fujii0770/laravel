<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AppConst;


class UpdateWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_weather:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        try {
            $app_id = config("app.app_id");
            $lat = config("app.lat_default");
            $lng = config("app.lng_default");
            $url = "https://api.openweathermap.org/data/2.5/onecall?&lat=${lat}&lon=${lng}&appid=${app_id}&lang=vi&units=metric&exclude=hourly,current";
            $client = new Client(['verify' => false ]);
            $res = $client->get($url);
            if ($res->getStatusCode() == 200) {
                $weather = json_decode( $res->getBody());
                $data['weather_daily'] = $weather->daily;
                if($data['weather_daily']){
                    array_pop($data['weather_daily']);
                }

                $weathersInsert = [];
                
                $arrSevenDays = [];
                $lastSevenDays = DB::table('weather')->whereDate('day','>=', date('Y-m-d'))->pluck('day');
                foreach($lastSevenDays as $date){
                    $arrSevenDays[] = date("Y-m-d", strtotime($date));
                }
                foreach($data['weather_daily'] as $weather){
                    $main = $weather->weather[0]->main;
                    if(in_array(date('Y-m-d',$weather->dt), $arrSevenDays)){
                        DB::table('weather')
                            ->whereDate('day','=',date('Y-m-d',$weather->dt))
                            ->update([
                                'day' => date('Y-m-d-H:i:s',$weather->dt),
                                'weather' => array_key_exists($main, AppConst::WEATHER_CONDITION)?AppConst::WEATHER_CONDITION[$main]:0,
                                'temperature_over' => $weather->temp->max,
                                'temperature_under' => $weather->temp->min,
                                'humidity' => $weather->humidity,
                                'precipitation' => $weather->rain?$weather->rain:null,
                                'barometric_pressure' => $weather->pressure?$weather->pressure:null,
                                'wind' => $weather->wind_speed?$weather->wind_speed:null,
                                'updated_at' => Carbon::now()
                            ]);
                    }else{
                        $weathersInsert[] = [
                            'day' => date('Y-m-d-H:i:s',$weather->dt),
                            'weather' => array_key_exists($main, AppConst::WEATHER_CONDITION)?AppConst::WEATHER_CONDITION[$main]:0,
                            'temperature_over' => $weather->temp->max,
                            'temperature_under' => $weather->temp->min,
                            'humidity' => $weather->humidity,
                            'precipitation' => $weather->rain?$weather->rain:null,
                            'barometric_pressure' => $weather->pressure?$weather->pressure:null,
                            'wind' => $weather->wind_speed?$weather->wind_speed:null,
                            'created_at' => Carbon::now()
                        ];
                    }                   
                }
                if(count($weathersInsert)){
                    DB::table('weather')->insert($weathersInsert);
                }
            }

        } catch(\Exception $e){
            Log::error($e->getMessage().$e->getTraceAsString());
        }
    }
}
