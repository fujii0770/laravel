<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 8/8/2018
 * Time: 9:35 AM
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Helper;
use Carbon\Carbon;
use Session;
use Request;
use DB;
use CRUDBooster;

class FrontendBaseController extends AdminBaseController
{
    protected function prepareCurrentAquacultureData(&$data, $forceRedirect = true){
        $aquaId = Request::get('aquaId');
        if ($aquaId){
            $pond_id = $this->getPondByAqua($aquaId);

            if (!$pond_id){
                if ($forceRedirect){
                    CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
                }else{
                    return false;
                }
            }
        }else{
            $pond_id = Request::get('pondId');

            if (!$this->checkFarmPermissionOnPond($pond_id)){
                if ($forceRedirect){
                    CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
                }else{
                    return false;
                }
            }
        }

        $current_aquaculture = null;
        if ($aquaId) {
            $current_aquaculture = DB::table('ebi_aquacultures')->join('ponds_aquacultures', 'ebi_aquacultures.id', '=', 'ponds_aquacultures.ebi_aquacultures_id')
                ->select(['ebi_aquacultures.*', 'ponds_aquacultures.id as ponds_aquacultures_id', 'ponds_aquacultures.status as ponds_aquacultures_status', 'ponds_aquacultures.sell as ponds_aquacultures_sell', 'ponds_aquacultures.ebi_remaining as ponds_aquacultures_ebi_remaining'])
                ->where('ponds_aquacultures.id', $aquaId)->first();
        }else{
            if (Request::get("date")){
                $selectedDate = Request::get("date");
                $current_aquaculture = Helper::getSelectedAquaculture($pond_id, $selectedDate);
            }
            if (!$current_aquaculture){
                $current_aquaculture = Helper::getCurrentAquaculture($pond_id);
            }
        }

        $data['current_aquaculture'] = $current_aquaculture;
        $data['pondId'] = $pond_id;
        $data['aquaId'] = $current_aquaculture?$current_aquaculture->ponds_aquacultures_id:null;
        return true;
    }

    protected function prepareAquacultureBlockData(&$data)
    {
        $this->prepareCurrentAquacultureData($data);
        $pond_id = $data['pondId'];

        $pond_farm = DB::table('ebi_ponds')->where('ebi_ponds.id', '=', $pond_id)
            ->leftJoin('ebi_farms', 'ebi_farms.id', '=', 'ebi_ponds.farm_id')
            ->select('ebi_ponds.id', 'ebi_ponds.pond_name', 'ebi_farms.id as farm_id', 'ebi_farms.farm_name', 'ebi_ponds.pond_method','ebi_farms.farm_name_en')
            ->first();

        $aquacultures = DB::table('ebi_aquacultures')->join('ponds_aquacultures', 'ebi_aquacultures.id', '=', 'ponds_aquacultures.ebi_aquacultures_id')
            ->where('ponds_aquacultures.ebi_ponds_id', '=', $pond_id)
            ->orderBy('ebi_aquacultures.id', 'desc')
            ->select('ebi_aquacultures.start_date', 'ebi_aquacultures.completed_date', 'ebi_aquacultures.estimate_shipping_date', 'ebi_aquacultures.id', 'ponds_aquacultures.id as ponds_aquacultures_id')
            ->orderBy('ponds_aquacultures.created_at', 'desc')
            ->get();

        $data['pond_farm'] = $pond_farm;
        $data['aquacultures'] = $aquacultures;
        if (isset($data['current_aquaculture']) && $data['current_aquaculture']){
            $shrimp_state = DB::table('ebi_shrimp_states')->where('ponds_aquacultures_id', '=', $data['current_aquaculture']->ponds_aquacultures_id)
                ->orderBy('date_target', 'desc')
                ->first();

            $startDate = Carbon::createFromFormat('Y-m-d', $data['current_aquaculture']->start_date);
            $diff = $startDate->diffInDays(Carbon::now()) + 1;
            $threshold_grow = DB::table('threshold_grow')->where('ebi_kind_id', $data['current_aquaculture']->ebi_kind_id)
                ->where('aquaculture_method_id', $data['current_aquaculture']->aquaculture_method_id)
                ->where('date', '<=', $diff)
                ->orderBy('date', 'desc')
                ->first();

            $data['latest_shrimp_state'] = $shrimp_state;
            $data['threshold_grow'] = $threshold_grow;

            $price = Helper::priceShrimp($shrimp_state->weight, $data['current_aquaculture']->ebi_kind_id);
            $unitPrice = number_format((float)$price, 2, '.', '');
            $data['unitPrice'] = $unitPrice;
        }
    }

    public function getWeatherInfo(){
        $date = Request::input('date');
        if (!$date) {
            $date = Carbon::today()->format('Y-m-d');
        }
        $weather = DB::table('weather')->whereDate('day', $date)->first();

        $pondId = Request::input('pondId');
        $pondState = DB::table('ebi_pond_states')
            ->select([
                DB::raw("MAX(cast(tmp_values AS DECIMAL(20,2))) as max_tmp_values"), DB::raw("MIN(cast(tmp_values AS DECIMAL(20,2))) as min_tmp_values")])
            ->where([['date_target', '=', $date], ['pond_id', '=', $pondId]])->first();
        $water_temp = null;
        if ($pondState) {
            $minmax = DB::table('ebi_minmaxs')->where('start_date', '<=', $date)->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date)
                    ->orWhereNull('end_date');
            })->first();

            $tmp_values_min = $minmax ? $minmax->tmp_values_min : null;
            $tmp_values_max = $minmax ? $minmax->tmp_values_max : null;

            $water_temp = $this->getQualityValueWithRangeValue($tmp_values_max, $tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values);
        }

        return response()->json(['weather' => $weather, 'water_temp' => $water_temp]);
    }

    protected function getWaterStatusByPonds($pondIds)
    {
        // get the last measure day for each pond
        $lastMeasureDays = DB::table('ebi_pond_states')->whereIn('pond_id', $pondIds)->groupBy('pond_id')->get(['pond_id', DB::raw('MAX(date_target) as date')]);

        // get min max for the last measure day of each pond
        $minmaxQueries = array();
        $pondStateKeys = array();
        $mapPondStateDays = array();
        foreach ($lastMeasureDays as $pondStateDay) {
            $date = $pondStateDay->date;
            $minmaxQueries[] = DB::table('ebi_minmaxs')->where('start_date', '<=', $date)->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date)
                    ->orWhereNull('end_date')->take(1);
            });
            $pondStateKeys[] = implode('-', [$pondStateDay->pond_id, $date]);
            $mapPondStateDays[$pondStateDay->pond_id] = $pondStateDay->date;
        }

        $query = null;
        foreach ($minmaxQueries as $q) {
            if ($query == null) {
                $query = $q;
            } else {
                $query->union($q);
            }
        }
        if ($query){
            $minmaxs = $query->get();
        }else{
            $minmaxs = array();
        }

        $mapPondMinmax = array();
        foreach ($minmaxs as $minmax) {
            $mapPondMinmax[] = $minmax;
        }

        // get status of each pond
        $pondStates = DB::table('ebi_pond_states')->select(['pond_id', DB::raw("MAX(ph_values) as max_ph_values"), DB::raw("MIN(ph_values) as min_ph_values")
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
            , DB::raw("MAX(press_values) as max_press_values"), DB::raw("MIN(press_values) as min_press_values")])
            ->whereIn(DB::raw("CONCAT(pond_id, '-', DATE_FORMAT(date_target, '%Y-%m-%d'))"), $pondStateKeys)->groupBy(DB::raw("CONCAT(pond_id, '-', DATE_FORMAT(date_target, '%Y-%m-%d'))"), 'pond_id')
            ->get();

        $mapPondState = array();
        foreach ($pondStates as $pondState) {
            $mapPondState[$pondState->pond_id] = $pondState;
        }

        return [$mapPondMinmax, $mapPondState, $mapPondStateDays];
    }

    protected function getQualityStateWithRangeValue($max, $min, $maxValue, $minValue)
    {
        return Helper::getQualityStateWithRangeValue($max, $min, $maxValue, $minValue);
    }

    protected function getQualityValueWithRangeValue($max, $min, $maxValue, $minValue)
    {
        return Helper::getQualityValueWithRangeValue($max, $min, $maxValue, $minValue);
    }
}
