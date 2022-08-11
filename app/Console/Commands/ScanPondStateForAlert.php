<?php

namespace App\Console\Commands;

use App\Http\Controllers\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScanPondStateForAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan_alert:pond_state {fromId} {sendMail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan pond state for alert';

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
        Log::debug('Start ScanPondStateForAlert');
        $fromId = $this->argument('fromId');
        $isSendingMail = $this->argument('sendMail');
        Log::debug("ScanPondStateForAlert: from id = $fromId");
        if (is_numeric($fromId)){
            try{
                Log::debug("ScanPondStateForAlert: get all pond, date, min value and max value from pond state");
                // get all pond, date, min value and max value from pond state where id > from id

                if (!$fromId){
                    $fromId = 0;
                }
                $mapDatePond = DB::table('ebi_pond_states')
                    ->where('id', '>', $fromId)
                    ->where('pond_id', '<>', 0)
                    ->groupBy('pond_id', 'date_target')
                    ->orderBy('pond_id')
                    ->orderBy('date_target')
                    ->pluck(DB::raw("CONCAT(pond_id, '_', date_target) as mapDatePond"))->toArray();
                 
                if(!count($mapDatePond)){
                    Log::debug("Finish ScanPondStateForAlert: nothing is imported");
                    return;
                }
               
                $pondStates = DB::table('ebi_pond_states')->select(['pond_id', 'date_target'
                    , DB::raw("MAX(ph_values) as max_ph_values"), DB::raw("MIN(ph_values) as min_ph_values")
                    , DB::raw("MAX(mv_values) as max_mv_values"), DB::raw("MIN(mv_values) as min_mv_values")
                    , DB::raw("MAX(orp_values) as max_orp_values"), DB::raw("MIN(orp_values) as min_orp_values")
                    , DB::raw("MAX(ec_values) as max_ec_values"), DB::raw("MIN(ec_values) as min_ec_values")
                    , DB::raw("MAX(ec_abs_values) as max_ec_abs_values"), DB::raw("MIN(ec_abs_values) as min_ec_abs_values")
                    , DB::raw("MAX(res_values) as max_res_values"), DB::raw("MIN(res_values) as min_res_values")
                    , DB::raw("MAX(tds_values) as max_tds_values"), DB::raw("MIN(tds_values) as min_tds_values")
                    , DB::raw("MAX(sal_values) as max_sal_values"), DB::raw("MIN(sal_values) as min_sal_values")
                    , DB::raw("MAX(sigma_t_values) as max_sigma_t_values"), DB::raw("MIN(sigma_t_values) as min_sigma_t_values")
                    , DB::raw("MAX(do_values) as max_do_values"), DB::raw("MIN(do_values) as min_do_values")
                    , DB::raw("MAX(do_ppm_values) as max_do_ppm_values"), DB::raw("MIN(do_ppm_values) as min_do_ppm_values")
                    , DB::raw("MAX(turb_fnu_values) as max_turb_fnu_values"), DB::raw("MIN(turb_fnu_values) as min_turb_fnu_values")
                    , DB::raw("MAX(tmp_values) as max_tmp_values"), DB::raw("MIN(tmp_values) as min_tmp_values")
                    , DB::raw("MAX(press_values) as max_press_values"), DB::raw("MIN(press_values) as min_press_values")
                    , DB::raw("MAX(ammonia_values) as max_ammonia_values"), DB::raw("MIN(ammonia_values) as min_ammonia_values")
                    , DB::raw("MAX(copper_ion_values) as max_ion_values"), DB::raw("MIN(copper_ion_values) as min_ion_values")
                    , DB::raw("MAX(out_temp_values) as max_out_temp_values"), DB::raw("MIN(out_temp_values) as min_out_temp_values")])
                    ->whereIn(DB::raw("CONCAT(pond_id, '_', date_target)"), $mapDatePond)                    
                    ->groupBy('pond_id', 'date_target')
                    ->orderBy('pond_id')
                    ->orderBy('date_target')
                    ->get();
 
                // detect the min imported date for each pond
                Log::debug("ScanPondStateForAlert: detect the min imported date for each pond");
                $minDate = null;
                $mapPondStates = array();
                foreach ($pondStates as $pondState){
                    if ($minDate){
                        if ($minDate > $pondState->date_target){
                            $minDate = $pondState->date_target;
                        }
                    }else{
                        $minDate = $pondState->date_target;
                    }
                    if (key_exists($pondState->pond_id, $mapPondStates)){
                        $mapPondStates[$pondState->pond_id][] = $pondState;
                    }else{
                        $mapPondStates[$pondState->pond_id] = array();
                        $mapPondStates[$pondState->pond_id][] = $pondState;
                    }
                }

                // get min max for the last measure day of each pond
                Log::debug("ScanPondStateForAlert: get min max for the last measure day of each pond");
                $query = DB::table('ebi_minmaxs')
                    ->where(function ($query1) use ($minDate) {
                        $query1->where('start_date', '>', $minDate);
                        $query1->orWhere(function ($query2) use ($minDate)     {
                            $query2->where('start_date', '<=', $minDate)->where(function ($query) use ($minDate) {
                                $query->whereDate('end_date', '>', $minDate)
                                    ->orWhereNull('end_date');
                            });
                        });
                    })
                    ->orderBy('start_date');
                $minmaxs = null;
                if ($query){
                    Log::debug("ScanPondStateForAlert scan query: " .$query->toSql());
                    $minmaxs = $query->get();
                }else{
                    $minmaxs = array();
                }

                // scan the list pond state to detect the new/ updated alert
                Log::debug("ScanPondStateForAlert: scan the list pond state to detect the new/ updated alert");
                $newAlerts = array();
                $mapAlertDatePond = array();
                $abnormalPondErrorQueries = array();
                foreach ($mapPondStates as $pondId => $pondStates){
                    $countMinmax = $minmaxs ? count($minmaxs) : 0;
                    $minmaxIndex = 0;
                    foreach ($pondStates as $pondState) {
                        $stateDate = $pondState->date_target;
                        while ($minmaxIndex < $countMinmax) {
                            if ($minmaxs[$minmaxIndex]->end_date && $stateDate >= $minmaxs[$minmaxIndex]->end_date) {
                                $minmaxIndex++;
                                continue;
                            }
                            if (!$minmaxs[$minmaxIndex]->end_date && $stateDate < $minmaxs[$minmaxIndex]->start_date) {
                                $minmaxIndex++;
                                continue;
                            }
                            break;
                        }

                        $firstErrorValue = null;
                        if ($minmaxIndex < $countMinmax) {
                            $alertCriteria = array();

                            // detect state
                            $ph_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->ph_values_max, $minmaxs[$minmaxIndex]->ph_values_min, $pondState->max_ph_values, $pondState->min_ph_values);
                            if (!$ph_state){
                                $ph_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->ph_values_max, $minmaxs[$minmaxIndex]->ph_values_min, $pondState->max_ph_values, $pondState->min_ph_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_PH, 'value' => number_format($ph_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_PH))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $ph_error_value;
                                }
                            }
                            $mv_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->mv_values_max, $minmaxs[$minmaxIndex]->mv_values_min, $pondState->max_mv_values, $pondState->min_mv_values);
                            if (!$mv_state){
                                $mv_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->mv_values_max, $minmaxs[$minmaxIndex]->mv_values_min, $pondState->max_mv_values, $pondState->min_mv_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_MV, 'value' => number_format($mv_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_MV))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $mv_error_value;
                                }
                            }
                            $orp_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->orp_values_max, $minmaxs[$minmaxIndex]->orp_values_min, $pondState->max_orp_values, $pondState->min_orp_values);
                            if (!$orp_state){
                                $orp_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->orp_values_max, $minmaxs[$minmaxIndex]->orp_values_min, $pondState->max_orp_values, $pondState->min_orp_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_ORP, 'value' => number_format($orp_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_ORP))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $orp_error_value;
                                }
                            }
                            $ec_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->ec_values_max, $minmaxs[$minmaxIndex]->ec_values_min, $pondState->max_ec_values, $pondState->min_ec_values);
                            if (!$ec_state){
                                $ec_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->ec_values_max, $minmaxs[$minmaxIndex]->ec_values_min, $pondState->max_ec_values, $pondState->min_ec_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_EC, 'value' => number_format($ec_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_EC))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $ec_error_value;
                                }
                            }
                            $ec_abs_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->ec_abs_values_max, $minmaxs[$minmaxIndex]->ec_abs_values_min, $pondState->max_ec_abs_values, $pondState->min_ec_abs_values);
                            if (!$ec_abs_state){
                                $ec_abs_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->ec_abs_values_max, $minmaxs[$minmaxIndex]->ec_abs_values_min, $pondState->max_ec_abs_values, $pondState->min_ec_abs_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_EC_ABS, 'value' => number_format($ec_abs_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_EC_ABS))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $ec_abs_error_value;
                                }
                            }
                            $res_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->res_values_max, $minmaxs[$minmaxIndex]->res_values_min, $pondState->max_res_values, $pondState->min_res_values);
                            if (!$res_state){
                                $res_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->res_values_max, $minmaxs[$minmaxIndex]->res_values_min, $pondState->max_res_values, $pondState->min_res_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_RES, 'value' => number_format($res_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_RES))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $res_error_value;
                                }
                            }
                            $tds_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->tds_values_max, $minmaxs[$minmaxIndex]->tds_values_min, $pondState->max_tds_values, $pondState->min_tds_values);
                            if (!$tds_state){
                                $tds_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->tds_values_max, $minmaxs[$minmaxIndex]->tds_values_min, $pondState->max_tds_values, $pondState->min_tds_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_TDS, 'value' => number_format($tds_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_TDS))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $tds_error_value;
                                }
                            }
                            $sal_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->sal_values_max, $minmaxs[$minmaxIndex]->sal_values_min, $pondState->max_sal_values, $pondState->min_sal_values);
                            if (!$sal_state){
                                $sal_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->sal_values_max, $minmaxs[$minmaxIndex]->sal_values_min, $pondState->max_sal_values, $pondState->min_sal_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_SAL, 'value' => number_format($sal_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_SAL))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $sal_error_value;
                                }
                            }
                            $sigma_t_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->sigma_t_values_max, $minmaxs[$minmaxIndex]->sigma_t_values_min, $pondState->max_sigma_t_values, $pondState->min_sigma_t_values);
                            if (!$sigma_t_state){
                                $sigma_t_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->sigma_t_values_max, $minmaxs[$minmaxIndex]->sigma_t_values_min, $pondState->max_sigma_t_values, $pondState->min_sigma_t_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_SIGMA, 'value' => number_format($sigma_t_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_SIGMA))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $sigma_t_error_value;
                                }
                            }
                            $do_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->do_values_max, $minmaxs[$minmaxIndex]->do_values_min, $pondState->max_do_values, $pondState->min_do_values);
                            if (!$do_state){
                                $do_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->do_values_max, $minmaxs[$minmaxIndex]->do_values_min, $pondState->max_do_values, $pondState->min_do_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_DO, 'value' => number_format($do_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_DO))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $do_error_value;
                                }
                            }
                            $do_ppm_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->do_ppm_values_max, $minmaxs[$minmaxIndex]->do_ppm_values_min, $pondState->max_do_ppm_values, $pondState->min_do_ppm_values);
                            if (!$do_ppm_state){
                                $do_ppm_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->do_ppm_values_max, $minmaxs[$minmaxIndex]->do_ppm_values_min, $pondState->max_do_ppm_values, $pondState->min_do_ppm_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_DO_PPM, 'value' => number_format($do_ppm_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_DO_PPM))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $do_ppm_error_value;
                                }
                            }
                            $turb_fnu_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->turb_fnu_values_max, $minmaxs[$minmaxIndex]->turb_fnu_values_min, $pondState->max_turb_fnu_values, $pondState->min_turb_fnu_values);
                            if (!$turb_fnu_state){
                                $turb_fnu_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->turb_fnu_values_max, $minmaxs[$minmaxIndex]->turb_fnu_values_min, $pondState->max_turb_fnu_values, $pondState->min_turb_fnu_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_TURB, 'value' => number_format($turb_fnu_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_TURB))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $turb_fnu_error_value;
                                }
                            }
                            $tmp_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->tmp_values_max, $minmaxs[$minmaxIndex]->tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values);
                            if (!$tmp_state){
                                $tmp_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->tmp_values_max, $minmaxs[$minmaxIndex]->tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_TMP, 'value' => number_format($tmp_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_TMP))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $tmp_error_value;
                                }
                            }
                            $press_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->press_values_max, $minmaxs[$minmaxIndex]->press_values_min, $pondState->max_press_values, $pondState->min_press_values);
                            if (!$press_state){
                                $press_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->press_values_max, $minmaxs[$minmaxIndex]->press_values_min, $pondState->max_press_values, $pondState->min_press_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_PRESS, 'value' => number_format($press_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_PRESS))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $press_error_value;
                                }
                            }
                            $ammonia_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->ammonia_values_max, $minmaxs[$minmaxIndex]->ammonia_values_min, $pondState->max_ammonia_values, $pondState->min_ammonia_values);
                            if (!$ammonia_state){
                                $ammonia_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->ammonia_values_max, $minmaxs[$minmaxIndex]->ammonia_values_min, $pondState->max_ammonia_values, $pondState->min_ammonia_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_AMMONIA, 'value' => number_format($ammonia_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_AMMONIA))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $ammonia_error_value;
                                }
                            }
                            $ion_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->ion_values_max, $minmaxs[$minmaxIndex]->ion_values_min, $pondState->max_ion_values, $pondState->min_ion_values);
                            if (!$ion_state){
                                $ion_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->ion_values_max, $minmaxs[$minmaxIndex]->ion_values_min, $pondState->max_ion_values, $pondState->min_ion_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_ION, 'value' => number_format($ion_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_ION))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $ion_error_value;
                                }
                            }

                            $out_temp_state = Helper::getQualityStateWithRangeValue($minmaxs[$minmaxIndex]->out_temp_values_max, $minmaxs[$minmaxIndex]->out_temp_values_min, $pondState->max_out_temp_values, $pondState->min_out_temp_values);
                            if (!$out_temp_state){
                                $out_temp_error_value = Helper::getQualityValueWithRangeValue($minmaxs[$minmaxIndex]->out_temp_values_max, $minmaxs[$minmaxIndex]->out_temp_values_min, $pondState->max_out_temp_values, $pondState->min_out_temp_values);
                                $alertCriteria[] = ['name' => Helper::WATER_CRITERIA_OUT_TEMP, 'value' => number_format($out_temp_error_value, Helper::getDecimalPlaceForCriteria(Helper::WATER_CRITERIA_OUT_TEMP))];
                                if ($firstErrorValue === null){
                                    $firstErrorValue = $out_temp_error_value;
                                }
                            }

                            if (count($alertCriteria)){
                                $key = implode("_", [$pondId, $pondState->date_target]);
                                $newAlerts[$key] = ['pond_id' => $pondId, 'alert_date' => $pondState->date_target, 'first_criterion' => $alertCriteria[0]['name'],
                                    'criterion_total' => count($alertCriteria), 'alert_detail' => json_encode($alertCriteria)];
                                $mapAlertDatePond[] = $key;

                                $abnormalPondErrorQueries[] = DB::table('ebi_pond_states')->select(['id', 'pond_id', 'date_target', 'time_target'])
                                    ->where('pond_id', $pondId)->where(strtolower($alertCriteria[0]['name']).'_values', $firstErrorValue)
                                    ->whereNotNull('time_target')
                                    ->where('date_target', $pondState->date_target)
                                    ->orderByDesc('time_target')->take(1);
                            }
                        }
                    }
                }

                // insert/update pond_alert for the new/ updated alert
                Log::debug("ScanPondStateForAlert: insert/update pond_alert for the new/ updated alert");
                $lastAlertId = DB::table('ebi_pond_alerts')->max('id');
                if (count($newAlerts)){
                    // get error time
                    $mapErrorTimes = array();
                    $query = null;
                    foreach ($abnormalPondErrorQueries as $errorTimeQuery){
                        if ($query == null){
                            $query = $errorTimeQuery;
                        }else{
                            $query->union($errorTimeQuery);
                        }
                    }
                    $errorTimes = $query->get();
                    foreach ($errorTimes as $errorTime){
                        $mapErrorTimes[implode("_", [$errorTime->pond_id, $errorTime->date_target])] = $errorTime;
                    }

                    $existAlerts = DB::table("ebi_pond_alerts")->select(['id', 'pond_id', 'alert_date'])->whereIn(DB::raw("CONCAT(pond_id, '_', alert_date)"), $mapAlertDatePond)->get();                    
                    $mapExistAlert = array();
                    foreach($existAlerts as $existAlert){
                        $mapExistAlert[implode("_", [$existAlert->pond_id, $existAlert->alert_date])] = $existAlert->id;
                    }
                    
                    $insertedAlerts = array();

                    foreach($newAlerts as $key => $newAlert){
                        if (key_exists($key, $mapExistAlert)){
                            // TODO improve performance with case when
                            DB::table('ebi_pond_alerts')->where('id', $mapExistAlert[$key])->update($newAlert);
                        }else{
                            if (key_exists($key, $mapErrorTimes)){
                                $newAlert['alert_time'] = $mapErrorTimes[$key]->time_target;
                                $newAlert['ebi_pond_states_id'] = $mapErrorTimes[$key]->id;

                                $newAlert['disable_flag'] = 0;
                                $newAlert['created_user'] = 1;
                                $newAlert['created_at'] = now();
                                $insertedAlerts[] = $newAlert;
                            }
                        }
                    }
                    if (count($insertedAlerts)){
                        DB::table('ebi_pond_alerts')->insert($insertedAlerts);
                    }
                }

                // trigger mail
                if ($isSendingMail){
                    Artisan::queue('send_email:pond_state', [ 'fromId' => $lastAlertId ]);
                }
            }catch(\Exception $e){
                Log::error($e->getMessage().$e->getTraceAsString());
            }
        }
        Log::debug('Finish ScanPondStateForAlert');
    }
}
