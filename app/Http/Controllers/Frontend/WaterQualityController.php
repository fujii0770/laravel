<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 1/4/2019
 * Time: 9:22 AM
 */

namespace App\Http\Controllers\Frontend;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Request;
use App\Http\Controllers\Helper;
use Carbon\Carbon;
use Session;

class WaterQualityController extends FrontendBaseController
{
    public function getWaterStatusOfPondByFarm()
    {
        $farmId = (int)Request::get('farm');
        $retPonds = array();
        if ($farmId && $this->checkFarmPermission($farmId)) {
            Session::put('current_farm',$farmId);
            $ponds = DB::table('ebi_ponds')
                    ->where('farm_id', $farmId)
                    ->get(['id','pond_image_area','pond_name']);
            $pondIds = array();
            foreach ($ponds as $pond) {
                $pondIds[] = $pond->id;
            }
            $pondStatuses = $this->getWaterStatusByPonds($pondIds);
            $minmaxs = $pondStatuses[0];
            $mapPondState = $pondStatuses[1];
            $mapPondStateDays = $pondStatuses[2];

             $pondInAquacultures = DB::table('ebi_ponds')
                         ->join('ebi_aquacultures', 'ebi_aquacultures.pond_id', '=', 'ebi_ponds.id')
                         ->where('farm_id',$farmId)
                         ->where('start_date', '<=', date("Y-m-d",strtotime(Carbon::now())))
                         ->where('completed_date', '>=', date("Y-m-d",strtotime(Carbon::now())))
                         ->orwhere('completed_date',Null)
                         ->pluck('pond_id')->toArray();
            foreach ($ponds as $pond) {
                if (count($minmaxs) && array_key_exists($pond->id, $mapPondState) && array_key_exists($pond->id, $mapPondStateDays)) {
                    $countMinmax = $minmaxs ? count($minmaxs) : 0;
                    $minmaxIndex = 0;
                    $stateDate = $mapPondStateDays[$pond->id];
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

                    if ($minmaxIndex < $countMinmax) {
                        $minmax = $minmaxs[$minmaxIndex];
                        $pondState = $mapPondState[$pond->id];

                        $status = $this->getQualityStateWithRangeValue($minmax->ph_values_max, $minmax->ph_values_min, $pondState->max_ph_values, $pondState->min_ph_values);
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->mv_values_max, $minmax->mv_values_min, $pondState->max_mv_values, $pondState->min_mv_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->orp_values_max, $minmax->orp_values_min, $pondState->max_orp_values, $pondState->min_orp_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->ec_values_max, $minmax->ec_values_min, $pondState->max_ec_values, $pondState->min_ec_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->ec_abs_values_max, $minmax->ec_abs_values_min, $pondState->max_ec_abs_values, $pondState->min_ec_abs_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->res_values_max, $minmax->res_values_min, $pondState->max_res_values, $pondState->min_res_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->tds_values_max, $minmax->tds_values_min, $pondState->max_tds_values, $pondState->min_tds_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->sal_values_max, $minmax->sal_values_min, $pondState->max_sal_values, $pondState->min_sal_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->sigma_t_values_max, $minmax->sigma_t_values_min, $pondState->max_sigma_t_values, $pondState->min_sigma_t_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->do_values_max, $minmax->do_values_min, $pondState->max_do_values, $pondState->min_do_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->do_ppm_values_max, $minmax->do_ppm_values_min, $pondState->max_do_ppm_values, $pondState->min_do_ppm_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->turb_fnu_values_max, $minmax->turb_fnu_values_min, $pondState->max_turb_fnu_values, $pondState->min_turb_fnu_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->tmp_values_max, $minmax->tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values);
                        }
                        if ($status) {
                            $status = $this->getQualityStateWithRangeValue($minmax->press_values_max, $minmax->press_values_min, $pondState->max_press_values, $pondState->min_press_values);
                        }
                    }else{
                        $status = true;
                    }
                } else {
                    $status = true;
                }
                if (in_array($pond->id, $pondInAquacultures)){
                    $statusPondInAquacultures = true;
                }else{                    
                    $statusPondInAquacultures = false;
                }
                $retPonds[] = array('id' => $pond->id, 'status' => $status, 'pond_class' => $pond->pond_image_area, 'pond_name' => $pond->pond_name, 'pondInAquacultures'=>$statusPondInAquacultures);
               
            }
        }
        echo json_encode($retPonds);
    }

    public function viewWaterQuality()
    {
        $data = array();
        $this->prepareAquacultureBlockData($data);

        $selectedMonth = Request::get('month');

        $currentAquaculture = $data['current_aquaculture'];
        if ($selectedMonth) {
            $date = \DateTime::createFromFormat('Ym', $selectedMonth);
            if ($date) {
                $selectedYear = $date->format('Y');
                $selectedMonth = $date->format('n');
            } else {
                $selectedYear = now()->year;
                $selectedMonth = now()->month;
                if ($currentAquaculture && $currentAquaculture->completed_date){
                    $selectedYear = substr($currentAquaculture->completed_date, 0, 4);
                    $selectedMonth = substr($currentAquaculture->completed_date, 5, 2);
                }
            }
        } else {
            $selectedYear = now()->year;
            $selectedMonth = now()->month;
            if ($currentAquaculture && $currentAquaculture->completed_date){
                $selectedYear = substr($currentAquaculture->completed_date, 0, 4);
                $selectedMonth = substr($currentAquaculture->completed_date, 5, 2);
            }
        }

        if ($selectedMonth == 1) {
            $previousYear = $selectedYear - 1;
            $previousMonth = 12;
        } else {
            $previousYear = $selectedYear;
            $previousMonth = $selectedMonth - 1;
        }
        if ($selectedMonth == 12) {
            $nextYear = $selectedYear + 1;
            $nextMonth = 1;
        } else {
            $nextYear = $selectedYear;
            $nextMonth = $selectedMonth + 1;
        }

        $numberDayOfMonth = Helper::days_in_month($selectedMonth, $selectedYear);
        $data['selectedYear'] = $selectedYear;
        $data['selectedMonth'] = $selectedMonth;
        $data['dayOfMonth'] = $numberDayOfMonth;
        $data['previousYear'] = $previousYear;
        $data['previousMonth'] = $previousMonth;
        $data['nextYear'] = $nextYear;
        $data['nextMonth'] = $nextMonth;

        // get water data
        $pondId = $data['pondId'];
        $retPondStates = array();
        foreach (Helper::getAllWaterCriteriaLabel() as $criteria) {
            $retPondStates[$criteria] = array_fill(0, $numberDayOfMonth - 1, null);
        }
        if ($currentAquaculture) {
            $dataStartDate = $currentAquaculture->start_date;
            $dataEndDate = $currentAquaculture->completed_date ?: date_format(now(), 'Y-m-d');

            $minmaxs = DB::table('ebi_minmaxs')->where(function ($query) use ($dataStartDate, $dataEndDate) {
                $query->where(function ($query1) use ($dataStartDate, $dataEndDate) {
                    $query1->where('start_date', '<=', $dataStartDate);
                    $query1->where(function ($query2) use ($dataEndDate) {
                        $query2->whereDate('end_date', '>=', $dataEndDate);
                        $query2->orWhereNull('end_date');
                    });
                });
                $query->orWhere(function ($query1) use ($dataStartDate, $dataEndDate) {
                    $query1->where('start_date', '>', $dataStartDate);
                    $query1->where('start_date', '<=', $dataEndDate);
                });
            })->orderBy('start_date')->get();

            $pondStates = DB::table('ebi_pond_states')
                ->select([DB::raw("Day(date_target) as date"), DB::raw("date_target as full_date"),
                    DB::raw("MAX(cast(ph_values AS DECIMAL(20,2))) as max_ph_values"), DB::raw("MIN(cast(ph_values AS DECIMAL(20,2))) as min_ph_values"),
                    DB::raw("MAX(cast(mv_values AS DECIMAL(20,1))) as max_mv_values"), DB::raw("MIN(cast(mv_values AS DECIMAL(20,1))) as min_mv_values"),
                    DB::raw("MAX(cast(orp_values AS DECIMAL(20,1))) as max_orp_values"), DB::raw("MIN(cast(orp_values AS DECIMAL(20,1))) as min_orp_values"),
                    DB::raw("MAX(cast(ec_values AS DECIMAL(20,0))) as max_ec_values"), DB::raw("MIN(cast(ec_values AS DECIMAL(20,0))) as min_ec_values"),
                    DB::raw("MAX(cast(ec_abs_values AS DECIMAL(20,0))) as max_ec_abs_values"), DB::raw("MIN(cast(ec_abs_values AS DECIMAL(20,0))) as min_ec_abs_values"),
                    DB::raw("MAX(cast(tds_values AS DECIMAL(20,0))) as max_tds_values"), DB::raw("MIN(cast(tds_values AS DECIMAL(20,0))) as min_tds_values"),
                    DB::raw("MAX(cast(res_values AS DECIMAL(20,0))) as max_res_values"), DB::raw("MIN(cast(res_values AS DECIMAL(20,0))) as min_res_values"),
                    DB::raw("MAX(cast(sal_values AS DECIMAL(20,2))) as max_sal_values"), DB::raw("MIN(cast(sal_values AS DECIMAL(20,2))) as min_sal_values"),
                    DB::raw("MAX(cast(sigma_t_values AS DECIMAL(20,1))) as max_sigma_t_values"), DB::raw("MIN(cast(sigma_t_values AS DECIMAL(20,1))) as min_sigma_t_values"),
                    DB::raw("MAX(cast(do_values AS DECIMAL(20,1))) as max_do_values"), DB::raw("MIN(cast(do_values AS DECIMAL(20,1))) as min_do_values"),
                    DB::raw("MAX(cast(do_ppm_values AS DECIMAL(20,2))) as max_do_ppm_values"), DB::raw("MIN(cast(do_ppm_values AS DECIMAL(20,2))) as min_do_ppm_values"),
                    DB::raw("MAX(cast(turb_fnu_values AS DECIMAL(20,1))) as max_turb_fnu_values"), DB::raw("MIN(cast(turb_fnu_values AS DECIMAL(20,1))) as min_turb_fnu_values"),
                    DB::raw("MAX(cast(tmp_values AS DECIMAL(20,2))) as max_tmp_values"), DB::raw("MIN(cast(tmp_values AS DECIMAL(20,2))) as min_tmp_values"),
                    DB::raw("MAX(cast(press_values AS DECIMAL(20,3))) as max_press_values"), DB::raw("MIN(cast(press_values AS DECIMAL(20,3))) as min_press_values")])
                ->where('pond_id', $pondId)
                ->whereYear('date_target', $selectedYear)
                ->whereMonth('date_target', $selectedMonth)
                ->where('date_target', '>=', $dataStartDate)
                ->where('date_target', '<=', $dataEndDate)
                ->groupBy("date_target")
                ->orderBy("date_target");
            $pondStates = $pondStates->get();

            if ($pondStates) {

                $countMinmax = $minmaxs ? count($minmaxs) : 0;
                foreach ($pondStates as $pondState) {
                    $stateDate = $pondState->full_date;
                    $minmaxIndex = 0;
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

                    $ph_values_min = null;
                    $ph_values_max = null;
                    $mv_values_min = null;
                    $mv_values_max = null;
                    $orp_values_min = null;
                    $orp_values_max = null;
                    $ec_values_min = null;
                    $ec_values_max = null;
                    $ec_abs_values_min = null;
                    $ec_abs_values_max = null;
                    $tds_values_min = null;
                    $tds_values_max = null;
                    $res_values_min = null;
                    $res_values_max = null;
                    $sal_values_min = null;
                    $sal_values_max = null;
                    $sigma_t_values_min = null;
                    $sigma_t_values_max = null;
                    $do_values_min = null;
                    $do_values_max = null;
                    $do_ppm_values_min = null;
                    $do_ppm_values_max = null;
                    $turb_fnu_values_min = null;
                    $turb_fnu_values_max = null;
                    $tmp_values_min = null;
                    $tmp_values_max = null;
                    $press_values_min = null;
                    $press_values_max = null;
                    $ammonia_values_min = null;
                    $ammonia_values_max = null;
                    $ion_values_min = null;
                    $ion_values_max = null;
                    $out_temp_values_min = null;
                    $out_temp_values_max = null;
                    if ($minmaxIndex < $countMinmax) {
                        // found mix max
                        $ph_values_min = $minmaxs[$minmaxIndex]->ph_values_min;
                        $ph_values_max = $minmaxs[$minmaxIndex]->ph_values_max;
                        $mv_values_min = $minmaxs[$minmaxIndex]->mv_values_min;
                        $mv_values_max = $minmaxs[$minmaxIndex]->mv_values_max;
                        $orp_values_min = $minmaxs[$minmaxIndex]->orp_values_min;
                        $orp_values_max = $minmaxs[$minmaxIndex]->orp_values_max;
                        $ec_values_min = $minmaxs[$minmaxIndex]->ec_values_min;
                        $ec_values_max = $minmaxs[$minmaxIndex]->ec_values_max;
                        $ec_abs_values_min = $minmaxs[$minmaxIndex]->ec_abs_values_min;
                        $ec_abs_values_max = $minmaxs[$minmaxIndex]->ec_abs_values_max;
                        $tds_values_min = $minmaxs[$minmaxIndex]->tds_values_min;
                        $tds_values_max = $minmaxs[$minmaxIndex]->tds_values_max;
                        $res_values_min = $minmaxs[$minmaxIndex]->res_values_min;
                        $res_values_max = $minmaxs[$minmaxIndex]->res_values_max;
                        $sal_values_min = $minmaxs[$minmaxIndex]->sal_values_min;
                        $sal_values_max = $minmaxs[$minmaxIndex]->sal_values_max;
                        $sigma_t_values_min = $minmaxs[$minmaxIndex]->sigma_t_values_min;
                        $sigma_t_values_max = $minmaxs[$minmaxIndex]->sigma_t_values_max;
                        $do_values_min = $minmaxs[$minmaxIndex]->do_values_min;
                        $do_values_max = $minmaxs[$minmaxIndex]->do_values_max;
                        $do_ppm_values_min = $minmaxs[$minmaxIndex]->do_ppm_values_min;
                        $do_ppm_values_max = $minmaxs[$minmaxIndex]->do_ppm_values_max;
                        $turb_fnu_values_min = $minmaxs[$minmaxIndex]->turb_fnu_values_min;
                        $turb_fnu_values_max = $minmaxs[$minmaxIndex]->turb_fnu_values_max;
                        $tmp_values_min = $minmaxs[$minmaxIndex]->tmp_values_min;
                        $tmp_values_max = $minmaxs[$minmaxIndex]->tmp_values_max;
                        $press_values_min = $minmaxs[$minmaxIndex]->press_values_min;
                        $press_values_max = $minmaxs[$minmaxIndex]->press_values_max;
                        $ammonia_values_min = $minmaxs[$minmaxIndex]->ammonia_values_min;
                        $ammonia_values_max = $minmaxs[$minmaxIndex]->ammonia_values_max;
                        $ion_values_min = $minmaxs[$minmaxIndex]->ion_values_min;
                        $ion_values_max = $minmaxs[$minmaxIndex]->ion_values_max;
                        $out_temp_values_min = $minmaxs[$minmaxIndex]->out_temp_values_min;
                        $out_temp_values_max = $minmaxs[$minmaxIndex]->out_temp_values_max;
                    }

                    $retPondStates[Helper::WATER_CRITERIA_PH][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ph_values_max, $ph_values_min, $pondState->max_ph_values, $pondState->min_ph_values), Helper::WATER_CRITERIA_PH);
                    $retPondStates[Helper::WATER_CRITERIA_MV][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($mv_values_max, $mv_values_min, $pondState->max_mv_values, $pondState->min_mv_values),Helper::WATER_CRITERIA_MV);
                    $retPondStates[Helper::WATER_CRITERIA_ORP][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($orp_values_max, $orp_values_min, $pondState->max_orp_values, $pondState->min_orp_values),Helper::WATER_CRITERIA_ORP);
                    $retPondStates[Helper::WATER_CRITERIA_EC][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ec_values_max, $ec_values_min, $pondState->max_ec_values, $pondState->min_ec_values),Helper::WATER_CRITERIA_EC);
                    $retPondStates[Helper::WATER_CRITERIA_EC_ABS][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ec_abs_values_max, $ec_abs_values_min, $pondState->max_ec_abs_values, $pondState->min_ec_abs_values),Helper::WATER_CRITERIA_EC_ABS);
                    $retPondStates[Helper::WATER_CRITERIA_TDS][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($tds_values_max, $tds_values_min, $pondState->max_tds_values, $pondState->min_tds_values),Helper::WATER_CRITERIA_TDS);
                    $retPondStates[Helper::WATER_CRITERIA_RES][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($res_values_max, $res_values_min, $pondState->max_res_values, $pondState->min_res_values),Helper::WATER_CRITERIA_RES);
                    $retPondStates[Helper::WATER_CRITERIA_SAL][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($sal_values_max, $sal_values_min, $pondState->max_sal_values, $pondState->min_sal_values),Helper::WATER_CRITERIA_SAL);
                    $retPondStates[Helper::WATER_CRITERIA_SIGMA][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($sigma_t_values_max, $sigma_t_values_min, $pondState->max_sigma_t_values, $pondState->min_sigma_t_values),Helper::WATER_CRITERIA_SIGMA);
                    $retPondStates[Helper::WATER_CRITERIA_DO][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($do_values_max, $do_values_min, $pondState->max_do_values, $pondState->min_do_values),Helper::WATER_CRITERIA_DO);
                    $retPondStates[Helper::WATER_CRITERIA_DO_PPM][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($do_ppm_values_max, $do_ppm_values_min, $pondState->max_do_ppm_values, $pondState->min_do_ppm_values),Helper::WATER_CRITERIA_DO_PPM);
                    $retPondStates[Helper::WATER_CRITERIA_TURB][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($turb_fnu_values_max, $turb_fnu_values_min, $pondState->max_turb_fnu_values, $pondState->min_turb_fnu_values),Helper::WATER_CRITERIA_TURB);
                    $retPondStates[Helper::WATER_CRITERIA_TMP][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($tmp_values_max, $tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values),Helper::WATER_CRITERIA_TMP);
                    $retPondStates[Helper::WATER_CRITERIA_PRESS][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($press_values_max, $press_values_min, $pondState->max_press_values, $pondState->min_press_values),Helper::WATER_CRITERIA_PRESS);
                    $retPondStates[Helper::WATER_CRITERIA_AMMONIA][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ammonia_values_max, $ammonia_values_min, $pondState->max_ammonia_values, $pondState->min_ammonia_values),Helper::WATER_CRITERIA_AMMONIA);
                    $retPondStates[Helper::WATER_CRITERIA_ION][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ion_values_max, $ion_values_min, $pondState->max_ion_values, $pondState->min_ion_values),Helper::WATER_CRITERIA_ION);
                    $retPondStates[Helper::WATER_CRITERIA_OUT_TEMP][$pondState->date] = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($out_temp_values_max, $out_temp_values_min, $pondState->max_out_temp_values, $pondState->min_out_temp_values),Helper::WATER_CRITERIA_OUT_TEMP);
                }
            }
        }


        $data['pondStates'] = $retPondStates;

        return view('frontend/water_quality', $data);
    }

    public function viewWaterQualityDetail()
    {
        $data = array();
        $this->prepareAquacultureBlockData($data);
        $activeCriteria = Request::input('c');
        if (!$activeCriteria) {
            $activeCriteria = Helper::WATER_CRITERIA_PH;
        }

        $currentAquaculture = $data['current_aquaculture'];
        $activeDate = Request::input('date');
        if ($activeDate) {
            $strDate = explode('-', $activeDate);
            $activeYear = $strDate[0];
            $activeMonth = $strDate[1];
            $activeDate = $strDate[2];
        } else {
            $activeYear = now()->year;
            $activeMonth = now()->month;
            $activeDate = now()->day;
            if ($currentAquaculture && $currentAquaculture->completed_date){
                $activeYear = substr($currentAquaculture->completed_date, 0, 4);
                $activeMonth = substr($currentAquaculture->completed_date, 5, 2);
                $activeDate = substr($currentAquaculture->completed_date, 8, 2);
            }
        }

        if ($activeMonth == 1) {
            $previousYear = $activeYear - 1;
            $previousMonth = 12;
        } else {
            $previousYear = $activeYear;
            $previousMonth = $activeMonth - 1;
        }
        if ($activeMonth == 12) {
            $nextYear = $activeYear + 1;
            $nextMonth = 1;
        } else {
            $nextYear = $activeYear;
            $nextMonth = $activeMonth + 1;
        }

        $data['previousYear'] = $previousYear;
        $data['previousMonth'] = $previousMonth;
        $data['nextYear'] = $nextYear;
        $data['nextMonth'] = $nextMonth;

        $data['activeCriteria'] = $activeCriteria;
        $data['dayOfMonth'] = Helper::days_in_month($activeYear, $activeMonth);
        $data['activeDate'] = $activeDate;
        $data['activeMonth'] = $activeMonth;
        $data['activeYear'] = $activeYear;
        //print_r($data);exit;
        return view('frontend/water_quality_detail', $data);
    }

    public function getAllWaterQualityCriteriaValues()
    {
        $data = array();
        if (!$this->prepareCurrentAquacultureData($data)) {
            return response()->json(null);
        }
        $pondId = $data['pondId'];
        $currentAquaculture = $data['current_aquaculture'];

        $returnData = array();
        $returnData['days'] = [];
        if ($currentAquaculture) {
            $dataStartDate = $currentAquaculture->start_date;
            $dataEndDate = $currentAquaculture->completed_date ?: date_format(now(), 'Y-m-d');
            if ($dataEndDate < $currentAquaculture->estimate_shipping_date){
                $dataEndDate = $currentAquaculture->estimate_shipping_date;
            }

            $startPeriod = new \DateTime($dataStartDate);
            $startPeriod->modify('-5 days');
            $endPeriod = new \DateTime($dataEndDate);
            $endPeriod->modify('+5 days');
            $period = new \DatePeriod(
                $startPeriod,
                new \DateInterval('P1D'),
                $endPeriod
            );

            foreach ($period as $key => $value) {
                $mapDayKey[$value->format('Y-m-d')] = $key;
                if ($currentAquaculture->completed_date && $dataEndDate == $value->format('Y-m-d')){
                    $returnData['days'][] = ($value->format('d') == 1)?('#'.$value->format('n')):('#');
                }else {
                    if ($value->format('d') == 1) {
                        $returnData['days'][] = $value->format('n');
                    } else {
                        $returnData['days'][] = '';
                    }
                }
            }

            /*foreach (Helper::WATER_CRITERIA_LABEL as $criteria => $label) {
                foreach ($period as $key => $value) {
                    $returnData[$criteria][] = [$value->format('Y-m-d'), null];
                }
            }*/

            $minmaxs = DB::table('ebi_minmaxs')->where(function ($query) use ($dataStartDate, $dataEndDate) {
                $query->where(function ($query1) use ($dataStartDate, $dataEndDate) {
                    $query1->where('start_date', '<=', $dataStartDate);
                    $query1->where(function ($query2) use ($dataEndDate) {
                        $query2->whereDate('end_date', '>=', $dataEndDate);
                        $query2->orWhereNull('end_date');
                    });
                });
                $query->orWhere(function ($query1) use ($dataStartDate, $dataEndDate) {
                    $query1->where('start_date', '>', $dataStartDate);
                    $query1->where('start_date', '<=', $dataEndDate);
                });
            })->orderBy('start_date')->get();

            $pondStates = DB::table('ebi_pond_states')
                ->select([DB::raw("date_target as full_date"),
                    DB::raw("MAX(cast(ph_values AS DECIMAL(20,2))) as max_ph_values"), DB::raw("MIN(cast(ph_values AS DECIMAL(20,2))) as min_ph_values"),
                    DB::raw("MAX(cast(mv_values AS DECIMAL(20,1))) as max_mv_values"), DB::raw("MIN(cast(mv_values AS DECIMAL(20,1))) as min_mv_values"),
                    DB::raw("MAX(cast(orp_values AS DECIMAL(20,1))) as max_orp_values"), DB::raw("MIN(cast(orp_values AS DECIMAL(20,1))) as min_orp_values"),
                    DB::raw("MAX(cast(ec_values AS DECIMAL(20,0))) as max_ec_values"), DB::raw("MIN(cast(ec_values AS DECIMAL(20,0))) as min_ec_values"),
                    DB::raw("MAX(cast(ec_abs_values AS DECIMAL(20,0))) as max_ec_abs_values"), DB::raw("MIN(cast(ec_abs_values AS DECIMAL(20,0))) as min_ec_abs_values"),
                    DB::raw("MAX(cast(tds_values AS DECIMAL(20,0))) as max_tds_values"), DB::raw("MIN(cast(tds_values AS DECIMAL(20,0))) as min_tds_values"),
                    DB::raw("MAX(cast(res_values AS DECIMAL(20,0))) as max_res_values"), DB::raw("MIN(cast(res_values AS DECIMAL(20,0))) as min_res_values"),
                    DB::raw("MAX(cast(sal_values AS DECIMAL(20,2))) as max_sal_values"), DB::raw("MIN(cast(sal_values AS DECIMAL(20,2))) as min_sal_values"),
                    DB::raw("MAX(cast(sigma_t_values AS DECIMAL(20,1))) as max_sigma_t_values"), DB::raw("MIN(cast(sigma_t_values AS DECIMAL(20,1))) as min_sigma_t_values"),
                    DB::raw("MAX(cast(do_values AS DECIMAL(20,1))) as max_do_values"), DB::raw("MIN(cast(do_values AS DECIMAL(20,1))) as min_do_values"),
                    DB::raw("MAX(cast(do_ppm_values AS DECIMAL(20,2))) as max_do_ppm_values"), DB::raw("MIN(cast(do_ppm_values AS DECIMAL(20,2))) as min_do_ppm_values"),
                    DB::raw("MAX(cast(turb_fnu_values AS DECIMAL(20,1))) as max_turb_fnu_values"), DB::raw("MIN(cast(turb_fnu_values AS DECIMAL(20,1))) as min_turb_fnu_values"),
                    DB::raw("MAX(cast(tmp_values AS DECIMAL(20,2))) as max_tmp_values"), DB::raw("MIN(cast(tmp_values AS DECIMAL(20,2))) as min_tmp_values"),
                    DB::raw("MAX(cast(press_values AS DECIMAL(20,3))) as max_press_values"), DB::raw("MIN(cast(press_values AS DECIMAL(20,3))) as min_press_values")])
                ->where('pond_id', $pondId)
                ->where('date_target', '>=', $dataStartDate)
                ->where('date_target', '<=', $dataEndDate)
                ->groupBy("date_target")
                ->orderBy("date_target");
            $pondStates = $pondStates->get();

            if ($pondStates) {
                $minmaxIndex = 0;
                $countMinmax = $minmaxs ? count($minmaxs) : 0;
                foreach ($pondStates as $pondState) {
                    $stateDate = $pondState->full_date;
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

                    $ph_values_min = null;
                    $ph_values_max = null;
                    $mv_values_min = null;
                    $mv_values_max = null;
                    $orp_values_min = null;
                    $orp_values_max = null;
                    $ec_values_min = null;
                    $ec_values_max = null;
                    $ec_abs_values_min = null;
                    $ec_abs_values_max = null;
                    $tds_values_min = null;
                    $tds_values_max = null;
                    $res_values_min = null;
                    $res_values_max = null;
                    $sal_values_min = null;
                    $sal_values_max = null;
                    $sigma_t_values_min = null;
                    $sigma_t_values_max = null;
                    $do_values_min = null;
                    $do_values_max = null;
                    $do_ppm_values_min = null;
                    $do_ppm_values_max = null;
                    $turb_fnu_values_min = null;
                    $turb_fnu_values_max = null;
                    $tmp_values_min = null;
                    $tmp_values_max = null;
                    $press_values_min = null;
                    $press_values_max = null;
                    $ammonia_values_min = null;
                    $ammonia_values_max = null;
                    $ion_values_min = null;
                    $ion_values_max = null;
                    $out_temp_values_min = null;
                    $out_temp_values_max = null;
                    if ($minmaxIndex < $countMinmax) {
                        // found mix max
                        $ph_values_min = $minmaxs[$minmaxIndex]->ph_values_min;
                        $ph_values_max = $minmaxs[$minmaxIndex]->ph_values_max;
                        $mv_values_min = $minmaxs[$minmaxIndex]->mv_values_min;
                        $mv_values_max = $minmaxs[$minmaxIndex]->mv_values_max;
                        $orp_values_min = $minmaxs[$minmaxIndex]->orp_values_min;
                        $orp_values_max = $minmaxs[$minmaxIndex]->orp_values_max;
                        $ec_values_min = $minmaxs[$minmaxIndex]->ec_values_min;
                        $ec_values_max = $minmaxs[$minmaxIndex]->ec_values_max;
                        $ec_abs_values_min = $minmaxs[$minmaxIndex]->ec_abs_values_min;
                        $ec_abs_values_max = $minmaxs[$minmaxIndex]->ec_abs_values_max;
                        $tds_values_min = $minmaxs[$minmaxIndex]->tds_values_min;
                        $tds_values_max = $minmaxs[$minmaxIndex]->tds_values_max;
                        $res_values_min = $minmaxs[$minmaxIndex]->res_values_min;
                        $res_values_max = $minmaxs[$minmaxIndex]->res_values_max;
                        $sal_values_min = $minmaxs[$minmaxIndex]->sal_values_min;
                        $sal_values_max = $minmaxs[$minmaxIndex]->sal_values_max;
                        $sigma_t_values_min = $minmaxs[$minmaxIndex]->sigma_t_values_min;
                        $sigma_t_values_max = $minmaxs[$minmaxIndex]->sigma_t_values_max;
                        $do_values_min = $minmaxs[$minmaxIndex]->do_values_min;
                        $do_values_max = $minmaxs[$minmaxIndex]->do_values_max;
                        $do_ppm_values_min = $minmaxs[$minmaxIndex]->do_ppm_values_min;
                        $do_ppm_values_max = $minmaxs[$minmaxIndex]->do_ppm_values_max;
                        $turb_fnu_values_min = $minmaxs[$minmaxIndex]->turb_fnu_values_min;
                        $turb_fnu_values_max = $minmaxs[$minmaxIndex]->turb_fnu_values_max;
                        $tmp_values_min = $minmaxs[$minmaxIndex]->tmp_values_min;
                        $tmp_values_max = $minmaxs[$minmaxIndex]->tmp_values_max;
                        $press_values_min = $minmaxs[$minmaxIndex]->press_values_min;
                        $press_values_max = $minmaxs[$minmaxIndex]->press_values_max;
                        $ammonia_values_min = $minmaxs[$minmaxIndex]->ammonia_values_min;
                        $ammonia_values_max = $minmaxs[$minmaxIndex]->ammonia_values_max;
                        $ion_values_min = $minmaxs[$minmaxIndex]->ion_values_min;
                        $ion_values_max = $minmaxs[$minmaxIndex]->ion_values_max;
                        $out_temp_values_min = $minmaxs[$minmaxIndex]->out_temp_values_min;
                        $out_temp_values_max = $minmaxs[$minmaxIndex]->out_temp_values_max;
                    }
                    $key = $mapDayKey[$pondState->full_date];
                    $returnData[Helper::WATER_CRITERIA_PH][] = [$key, (float)$this->getQualityValueWithRangeValue($ph_values_max, $ph_values_min, $pondState->max_ph_values, $pondState->min_ph_values)];
                    $returnData[Helper::WATER_CRITERIA_MV][] = [$key, (float)$this->getQualityValueWithRangeValue($mv_values_max, $mv_values_min, $pondState->max_mv_values, $pondState->min_mv_values)];
                    $returnData[Helper::WATER_CRITERIA_ORP][] = [$key, (float)$this->getQualityValueWithRangeValue($orp_values_max, $orp_values_min, $pondState->max_orp_values, $pondState->min_orp_values)];
                    $returnData[Helper::WATER_CRITERIA_EC][] = [$key, (float)$this->getQualityValueWithRangeValue($ec_values_max, $ec_values_min, $pondState->max_ec_values, $pondState->min_ec_values)];
                    $returnData[Helper::WATER_CRITERIA_EC_ABS][] = [$key, (float)$this->getQualityValueWithRangeValue($ec_abs_values_max, $ec_abs_values_min, $pondState->max_ec_abs_values, $pondState->min_ec_abs_values)];
                    $returnData[Helper::WATER_CRITERIA_TDS][] = [$key, (float)$this->getQualityValueWithRangeValue($tds_values_max, $tds_values_min, $pondState->max_tds_values, $pondState->min_tds_values)];
                    $returnData[Helper::WATER_CRITERIA_RES][] = [$key, (float)$this->getQualityValueWithRangeValue($res_values_max, $res_values_min, $pondState->max_res_values, $pondState->min_res_values)];
                    $returnData[Helper::WATER_CRITERIA_SAL][] = [$key, (float)$this->getQualityValueWithRangeValue($sal_values_max, $sal_values_min, $pondState->max_sal_values, $pondState->min_sal_values)];
                    $returnData[Helper::WATER_CRITERIA_SIGMA][] = [$key, (float)$this->getQualityValueWithRangeValue($sigma_t_values_max, $sigma_t_values_min, $pondState->max_sigma_t_values, $pondState->min_sigma_t_values)];
                    $returnData[Helper::WATER_CRITERIA_DO][] = [$key, (float)$this->getQualityValueWithRangeValue($do_values_max, $do_values_min, $pondState->max_do_values, $pondState->min_do_values)];
                    $returnData[Helper::WATER_CRITERIA_DO_PPM][] = [$key, (float)$this->getQualityValueWithRangeValue($do_ppm_values_max, $do_ppm_values_min, $pondState->max_do_ppm_values, $pondState->min_do_ppm_values)];
                    $returnData[Helper::WATER_CRITERIA_TURB][] = [$key, (float)$this->getQualityValueWithRangeValue($turb_fnu_values_max, $turb_fnu_values_min, $pondState->max_turb_fnu_values, $pondState->min_turb_fnu_values)];
                    $returnData[Helper::WATER_CRITERIA_TMP][] = [$key, (float)$this->getQualityValueWithRangeValue($tmp_values_max, $tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values)];
                    $returnData[Helper::WATER_CRITERIA_PRESS][] = [$key, (float)$this->getQualityValueWithRangeValue($press_values_max, $press_values_min, $pondState->max_press_values, $pondState->min_press_values)];
                    $returnData[Helper::WATER_CRITERIA_AMMONIA][] = [$key, (float)$this->getQualityValueWithRangeValue($ammonia_values_max, $ammonia_values_min, $pondState->max_ammonia_values, $pondState->min_ammonia_values)];
                    $returnData[Helper::WATER_CRITERIA_ION][] = [$key, (float)$this->getQualityValueWithRangeValue($ion_values_max, $ion_values_min, $pondState->max_ion_values, $pondState->min_ion_values)];
                    $returnData[Helper::WATER_CRITERIA_OUT_TEMP][] = [$key, (float)$this->getQualityValueWithRangeValue($out_temp_values_max, $out_temp_values_min, $pondState->max_out_temp_values, $pondState->min_out_temp_values)];
                }
            }
        }

        return response()->json($returnData);
    }

    public function getWaterQualityCriteriaValues()
    {
        $data = array();
        if (!$this->prepareCurrentAquacultureData($data, false)) {
            return response()->json(null);
        }
        $pondId = $data['pondId'];
        $currentAquaculture = $data['current_aquaculture'];

        $month = Request::get('month');
        $year = Request::get('year');
        $criteria = Helper::getWaterCriteria(Request::get('c'));

        if (!$month || !$year) {
            $month = now()->month;
            $year = now()->year;
        }
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);

        $daysOfMonth = Helper::days_in_month($month, $year);
        $startDay = implode('-', [$year, $month, '01']);
        $endDay = implode('-', [$year, $month, $daysOfMonth]);
        // get Min Max
        // query : SELECT * FROM ebi_minmaxs WHERE  ((start_date <= '20190301' AND (end_date > '20190301' OR end_date IS NULL)) OR (start_date <= '20190331'))
        $maxColumn = $criteria . '_values_max';
        $minColumn = $criteria . '_values_min';
        $minmaxs = DB::table('ebi_minmaxs')
            ->select([$maxColumn, $minColumn, DB::raw("DATE_FORMAT(end_date, '%Y-%m-%d') as end_date")])
            ->where(function ($query) use ($startDay, $endDay) {
                $query->where(function ($query1) use ($startDay, $endDay) {
                    $query1->where('start_date', '<=', $startDay);
                    $query1->where(function ($query2) use ($startDay, $endDay) {
                        $query2->whereNull('end_date');
                        $query2->orWhereDate('end_date', '>', $startDay);
                    });
                });
                $query->orWhere(function ($query3) use ($startDay, $endDay) {
                    $query3->where('start_date', '>', $startDay);
                    $query3->where('start_date', '<=', $endDay);
                });
            })->orderBy('start_date')->get();
        $rets = [];
        $rets['minmax'] = [];
        $rets['minmaxFirst'] = [];
        $rets['minmaxLast'] = [];
        $minMaxIndex = 0;
        $countMinMax = count($minmaxs);
        if ($countMinMax) {
            $rets['minmaxFirst']['min'] = (float)$minmaxs[0]->$minColumn;
            $rets['minmaxFirst']['max'] = (float)$minmaxs[0]->$maxColumn;
            for ($i = 0; $i < $daysOfMonth; $i++) {
                $currentDate = implode('-', [$year, $month, str_pad(($i + 1), 2, "0", STR_PAD_LEFT)]);

                while (($minMaxIndex < $countMinMax) && ($minmaxs[$minMaxIndex]->end_date) && ($currentDate >= $minmaxs[$minMaxIndex]->end_date)) {
                    $minMaxIndex++;
                }

                if ($minMaxIndex < $countMinMax) {
                    $rets['minmax'][] = [$i, is_numeric($minmaxs[$minMaxIndex]->$minColumn) ? (float)$minmaxs[$minMaxIndex]->$minColumn : '', is_numeric($minmaxs[$minMaxIndex]->$maxColumn) ? (float)$minmaxs[$minMaxIndex]->$maxColumn : ''];
                } else {
                    $rets['minmax'][] = [$i, is_numeric($minmaxs[$minMaxIndex - 1]->$minColumn) ? (float)$minmaxs[$minMaxIndex - 1]->$minColumn : '', is_numeric($minmaxs[$minMaxIndex - 1]->$maxColumn) ? (float)$minmaxs[$minMaxIndex - 1]->$maxColumn : ''];
                }
            }
            $rets['minmaxLast']['min'] = (float)$minmaxs[$countMinMax - 1]->$minColumn;
            $rets['minmaxLast']['max'] = (float)$minmaxs[$countMinMax - 1]->$maxColumn;
        } else {
            $rets['minmax'][] = [0, 0, 0];
            $rets['minmaxFirst']['min'] = 0;
            $rets['minmaxFirst']['max'] = 0;
            $rets['minmaxLast']['min'] = 0;
            $rets['minmaxLast']['max'] = 0;
        }

        // get pond state for each point
        if ($currentAquaculture){
            $stateColumn = $criteria . '_values';
            $pondStates = DB::table('ebi_pond_states')->select([DB::raw("DAY(date_target) as date"),
                DB::raw("MAX($stateColumn) as max_values"),
                DB::raw("MIN($stateColumn) as min_values"),
                DB::raw('UPPER(remarks) as point')])
                ->where([[DB::raw('MONTH(date_target)'), '=', $month], [DB::raw('YEAR(date_target)'), '=', $year], ['pond_id', '=', $pondId], ['date_target', '>=', $currentAquaculture->start_date]]);
            if ($currentAquaculture->completed_date) {
                $pondStates->where('date_target', '<=', $currentAquaculture->completed_date);
            }
            $pondStates->whereNotNull('remarks')->groupBy(DB::raw('DAY(date_target)'), DB::raw('UPPER(remarks)'))
                ->orderBy(DB::raw('DAY(date_target)'));
            $pondStates = $pondStates->get();
        }else{
            $pondStates = array();
        }

        // TODO method pond
        $rets['points'] = Helper::getMeasurePoints('');

        $minMaxIndex = 0;
        if ($pondStates && count($pondStates)) {
            foreach ($pondStates as $pondState) {
                $pointValue = null;
                $currentDate = implode('-', [$year, $month, str_pad($pondState->date, 2, "0", STR_PAD_LEFT)]);

                while (($minMaxIndex < $countMinMax) && ($minmaxs[$minMaxIndex]->end_date) && ($currentDate >= $minmaxs[$minMaxIndex]->end_date)) {
                    $minMaxIndex++;
                }

                if ($minMaxIndex < $countMinMax) {
                    $pointValue = [$pondState->date - 1, Helper::formatPondStateValues($this->getQualityValueWithRangeValue($minmaxs[$minMaxIndex]->$maxColumn, $minmaxs[$minMaxIndex]->$minColumn, $pondState->max_values, $pondState->min_values), $criteria)];
                } else {
                    $pointValue = [$pondState->date - 1, Helper::formatPondStateValues($this->getQualityValueWithRangeValue(null, null, $pondState->max_values, $pondState->min_values), $criteria)];
                }
                if (key_exists($pondState->point, $rets['points'])){
                    $rets['points'][$pondState->point][] = $pointValue;
                }
            }
        } else {
            for ($i = 0; $i < $daysOfMonth; $i++) {
                foreach($rets['points'] as $point){
                    $point[] = [$i, null, null];
                }
            }
        }

        $rets['days'] = [];
        $rets['dayAlerts'] = [];
        for ($i = 0; $i < $daysOfMonth; $i++) {
            $rets['days'][] = $i + 1;
            $rets['dayAlerts'][] = 0;
        }

        // Get Pond alert In Month
        $arrPondAlert = DB::table('ebi_pond_alerts')
                            ->select(['alert_detail','alert_date'])
                            ->whereMonth('alert_date', $month)
                            ->where('pond_id', $pondId)
                            ->where('disable_flag', 0)
                            ->where('alert_detail', 'LIKE', "%$criteria%")
                            ->get();
        if($arrPondAlert){            
            foreach($arrPondAlert as $itemPondAlert){
                $itemPondAlert->alert_detail = json_decode($itemPondAlert->alert_detail);
                if(count($itemPondAlert->alert_detail))
                    foreach($itemPondAlert->alert_detail as $oneAlert){
                        if($oneAlert->name == $criteria){                            
                            $rets['dayAlerts'][date('d', strtotime($itemPondAlert->alert_date)) - 1] = 1;
                            break;            
                        }
                    }
            }
        }


        return response()->json($rets);
    }

    public function confirmWaterQualityCriteriaAlert(){
        $pondId = Request::get('pondId');
        if (!$this->checkFarmPermissionOnPond($pondId)){
            return 0;
        }

        $date = Request::get('date');
        $month = Request::get('month');
        $year = Request::get('year');
        $criteria = Helper::getWaterCriteria(Request::get('c'));

        if (!$month || !$year || !$date || !$criteria) {
            return 0;
        }        
        $alert_date = "$year-$month-$date";

        $pondAlert = DB::table('ebi_pond_alerts')
                        ->where('pond_id','=',(int) $pondId)
                        ->where('alert_date','=',$alert_date)
                        ->first();
        if(!$pondAlert) return 0;

        try{
            $pondAlert->alert_detail = json_decode($pondAlert->alert_detail);
            $arrNew = array();
            if(count($pondAlert->alert_detail)){
                foreach ($pondAlert->alert_detail as $alert_detail) {
                    if($alert_detail->name != $criteria)
                        $arrNew[] = $alert_detail;
                }
            }

            $columnUpdate = [];

            $columnUpdate['alert_detail'] = json_encode($arrNew);
            $columnUpdate['updated_at'] = now();
            $columnUpdate['updated_user'] = CRUDBooster::myId();
            if(count($arrNew)){
                $columnUpdate['first_criterion'] = $arrNew[0]->name;
                $columnUpdate['criterion_total'] = count($arrNew);
            }else{
                $columnUpdate['first_criterion'] = "";
                $columnUpdate['criterion_total'] = 0;
                $columnUpdate['disable_flag'] = 1;
            }

            DB::table('ebi_pond_alerts')->where('id', $pondAlert->id)->update($columnUpdate);
            return 1;
        }catch(\Exception $e){
            Log::error($e->getMessage(). $e->getTraceAsString());
            return 0;
        }
    }

    public function getWaterCriteriasByPond()
    {
        $data = array();
        if (!$this->prepareCurrentAquacultureData($data)) {
            return response()->json(null);
        }
        $pondId = $data['pondId'];
        $date = Request::input('date');
        $minmax = null;
        if (!$date) {
            $currentAquaculture = $data['current_aquaculture'];
            if ($currentAquaculture->completed_date){
                $date = $currentAquaculture->completed_date;
            }else{
                $date = DB::table('ebi_pond_states')->where('pond_id', $pondId)->max('date_target');

                $now = Carbon::now();
                if ($now->lessThan($date) || $date < $currentAquaculture->start_date){
                    $date = $now->format('Y-m-d');
                }
            }
        }
        if ($date) {
            $minmax = DB::table('ebi_minmaxs')->where('start_date', '<=', $date)->where(function ($query) use ($date) {
                $query->where('end_date', '>=', $date)
                    ->orWhereNull('end_date');
            })->first();

            $ph_values_min = $minmax ? $minmax->ph_values_min : null;
            $ph_values_max = $minmax ? $minmax->ph_values_max : null;
            $mv_values_min = $minmax ? $minmax->mv_values_min : null;
            $mv_values_max = $minmax ? $minmax->mv_values_max : null;
            $orp_values_min = $minmax ? $minmax->orp_values_min : null;
            $orp_values_max = $minmax ? $minmax->orp_values_max : null;
            $ec_values_min = $minmax ? $minmax->ec_values_min : null;
            $ec_values_max = $minmax ? $minmax->ec_values_max : null;
            $ec_abs_values_min = $minmax ? $minmax->ec_abs_values_min : null;
            $ec_abs_values_max = $minmax ? $minmax->ec_abs_values_max : null;
            $tds_values_min = $minmax ? $minmax->tds_values_min : null;
            $tds_values_max = $minmax ? $minmax->tds_values_max : null;
            $res_values_min = $minmax ? $minmax->res_values_min : null;
            $res_values_max = $minmax ? $minmax->res_values_max : null;
            $sal_values_min = $minmax ? $minmax->sal_values_min : null;
            $sal_values_max = $minmax ? $minmax->sal_values_max : null;
            $sigma_t_values_min = $minmax ? $minmax->sigma_t_values_min : null;
            $sigma_t_values_max = $minmax ? $minmax->sigma_t_values_max : null;
            $do_values_min = $minmax ? $minmax->do_values_min : null;
            $do_values_max = $minmax ? $minmax->do_values_max : null;
            $do_ppm_values_min = $minmax ? $minmax->do_ppm_values_min : null;
            $do_ppm_values_max = $minmax ? $minmax->do_ppm_values_max : null;
            $turb_fnu_values_min = $minmax ? $minmax->turb_fnu_values_min : null;
            $turb_fnu_values_max = $minmax ? $minmax->turb_fnu_values_max : null;
            $tmp_values_min = $minmax ? $minmax->tmp_values_min : null;
            $tmp_values_max = $minmax ? $minmax->tmp_values_max : null;
            $press_values_min = $minmax ? $minmax->press_values_min : null;
            $press_values_max = $minmax ? $minmax->press_values_max : null;
            $ammonia_values_min = $minmax ? $minmax->ammonia_values_min : null;
            $ammonia_values_max = $minmax ? $minmax->ammonia_values_max : null;
            $ion_values_min = $minmax ? $minmax->ion_values_min : null;
            $ion_values_max = $minmax ? $minmax->ion_values_max : null;
            $out_temp_values_min = $minmax ? $minmax->out_temp_values_min : null;
            $out_temp_values_max = $minmax ? $minmax->out_temp_values_max : null;

            $pondState = DB::table('ebi_pond_states')
                ->select([
                    DB::raw("MAX(cast(ph_values AS DECIMAL(20,2))) as max_ph_values"), DB::raw("MIN(cast(ph_values AS DECIMAL(20,2))) as min_ph_values"),
                    DB::raw("MAX(cast(mv_values AS DECIMAL(20,1))) as max_mv_values"), DB::raw("MIN(cast(mv_values AS DECIMAL(20,1))) as min_mv_values"),
                    DB::raw("MAX(cast(orp_values AS DECIMAL(20,1))) as max_orp_values"), DB::raw("MIN(cast(orp_values AS DECIMAL(20,1))) as min_orp_values"),
                    DB::raw("MAX(cast(ec_values AS DECIMAL(20,0))) as max_ec_values"), DB::raw("MIN(cast(ec_values AS DECIMAL(20,0))) as min_ec_values"),
                    DB::raw("MAX(cast(ec_abs_values AS DECIMAL(20,0))) as max_ec_abs_values"), DB::raw("MIN(cast(ec_abs_values AS DECIMAL(20,0))) as min_ec_abs_values"),
                    DB::raw("MAX(cast(tds_values AS DECIMAL(20,0))) as max_tds_values"), DB::raw("MIN(cast(tds_values AS DECIMAL(20,0))) as min_tds_values"),
                    DB::raw("MAX(cast(res_values AS DECIMAL(20,0))) as max_res_values"), DB::raw("MIN(cast(res_values AS DECIMAL(20,0))) as min_res_values"),
                    DB::raw("MAX(cast(sal_values AS DECIMAL(20,2))) as max_sal_values"), DB::raw("MIN(cast(sal_values AS DECIMAL(20,2))) as min_sal_values"),
                    DB::raw("MAX(cast(sigma_t_values AS DECIMAL(20,1))) as max_sigma_t_values"), DB::raw("MIN(cast(sigma_t_values AS DECIMAL(20,1))) as min_sigma_t_values"),
                    DB::raw("MAX(cast(do_values AS DECIMAL(20,1))) as max_do_values"), DB::raw("MIN(cast(do_values AS DECIMAL(20,1))) as min_do_values"),
                    DB::raw("MAX(cast(do_ppm_values AS DECIMAL(20,2))) as max_do_ppm_values"), DB::raw("MIN(cast(do_ppm_values AS DECIMAL(20,2))) as min_do_ppm_values"),
                    DB::raw("MAX(cast(turb_fnu_values AS DECIMAL(20,1))) as max_turb_fnu_values"), DB::raw("MIN(cast(turb_fnu_values AS DECIMAL(20,1))) as min_turb_fnu_values"),
                    DB::raw("MAX(cast(tmp_values AS DECIMAL(20,2))) as max_tmp_values"), DB::raw("MIN(cast(tmp_values AS DECIMAL(20,2))) as min_tmp_values"),
                    DB::raw("MAX(cast(press_values AS DECIMAL(20,3))) as max_press_values"), DB::raw("MIN(cast(press_values AS DECIMAL(20,3))) as min_press_values")])
                ->where([['date_target', '=', $date], ['pond_id', '=', $pondId]])->first();

            if ($pondState) {
                $pondStateRet = new \stdClass();
                $pondStateRet->date = $date;
                $pondStateRet->ph_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ph_values_max, $ph_values_min, $pondState->max_ph_values, $pondState->min_ph_values), Helper::WATER_CRITERIA_PH);
                $pondStateRet->ph_values_state = $this->getQualityStateWithRangeValue($ph_values_max, $ph_values_min, $pondState->max_ph_values, $pondState->min_ph_values);
                $pondStateRet->mv_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($mv_values_max, $mv_values_min, $pondState->max_mv_values, $pondState->min_mv_values), Helper::WATER_CRITERIA_MV);
                $pondStateRet->mv_values_state = $this->getQualityStateWithRangeValue($mv_values_max, $mv_values_min, $pondState->max_mv_values, $pondState->min_mv_values);
                $pondStateRet->orp_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($orp_values_max, $orp_values_min, $pondState->max_orp_values, $pondState->min_orp_values), Helper::WATER_CRITERIA_ORP);
                $pondStateRet->orp_values_state = $this->getQualityStateWithRangeValue($orp_values_max, $orp_values_min, $pondState->max_orp_values, $pondState->min_orp_values);
                $pondStateRet->ec_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ec_values_max, $ec_values_min, $pondState->max_ec_values, $pondState->min_ec_values), Helper::WATER_CRITERIA_EC);
                $pondStateRet->ec_values_state = $this->getQualityStateWithRangeValue($ec_values_max, $ec_values_min, $pondState->max_ec_values, $pondState->min_ec_values);
                $pondStateRet->ec_abs_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ec_abs_values_max, $ec_abs_values_min, $pondState->max_ec_abs_values, $pondState->min_ec_abs_values), Helper::WATER_CRITERIA_EC_ABS);
                $pondStateRet->ec_abs_values_state = $this->getQualityStateWithRangeValue($ec_abs_values_max, $ec_abs_values_min, $pondState->max_ec_abs_values, $pondState->min_ec_abs_values);
                $pondStateRet->tds_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($tds_values_max, $tds_values_min, $pondState->max_tds_values, $pondState->min_tds_values), Helper::WATER_CRITERIA_TDS);
                $pondStateRet->tds_values_state = $this->getQualityStateWithRangeValue($tds_values_max, $tds_values_min, $pondState->max_tds_values, $pondState->min_tds_values);
                $pondStateRet->res_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($res_values_max, $res_values_min, $pondState->max_res_values, $pondState->min_res_values), Helper::WATER_CRITERIA_RES);
                $pondStateRet->res_values_state = $this->getQualityStateWithRangeValue($res_values_max, $res_values_min, $pondState->max_res_values, $pondState->min_res_values);
                $pondStateRet->sal_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($sal_values_max, $sal_values_min, $pondState->max_sal_values, $pondState->min_sal_values), Helper::WATER_CRITERIA_SAL);
                $pondStateRet->sal_values_state = $this->getQualityStateWithRangeValue($sal_values_max, $sal_values_min, $pondState->max_sal_values, $pondState->min_sal_values);
                $pondStateRet->sigma_t_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($sigma_t_values_max, $sigma_t_values_min, $pondState->max_sigma_t_values, $pondState->min_sigma_t_values), Helper::WATER_CRITERIA_SIGMA);
                $pondStateRet->sigma_t_values_state = $this->getQualityStateWithRangeValue($sigma_t_values_max, $sigma_t_values_min, $pondState->max_sigma_t_values, $pondState->min_sigma_t_values);
                $pondStateRet->do_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($do_values_max, $do_values_min, $pondState->max_do_values, $pondState->min_do_values), Helper::WATER_CRITERIA_DO);
                $pondStateRet->do_values_state = $this->getQualityStateWithRangeValue($do_values_max, $do_values_min, $pondState->max_do_values, $pondState->min_do_values);
                $pondStateRet->do_ppm_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($do_ppm_values_max, $do_ppm_values_min, $pondState->max_do_ppm_values, $pondState->min_do_ppm_values), Helper::WATER_CRITERIA_DO_PPM);
                $pondStateRet->do_ppm_values_state = $this->getQualityStateWithRangeValue($do_ppm_values_max, $do_ppm_values_min, $pondState->max_do_ppm_values, $pondState->min_do_ppm_values);
                $pondStateRet->turb_fnu_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($turb_fnu_values_max, $turb_fnu_values_min, $pondState->max_turb_fnu_values, $pondState->min_turb_fnu_values), Helper::WATER_CRITERIA_TURB);
                $pondStateRet->turb_fnu_values_state = $this->getQualityStateWithRangeValue($turb_fnu_values_max, $turb_fnu_values_min, $pondState->max_turb_fnu_values, $pondState->min_turb_fnu_values);
                $pondStateRet->tmp_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($tmp_values_max, $tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values), Helper::WATER_CRITERIA_TMP);
                $pondStateRet->tmp_values_state = $this->getQualityStateWithRangeValue($tmp_values_max, $tmp_values_min, $pondState->max_tmp_values, $pondState->min_tmp_values);
                $pondStateRet->press_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($press_values_max, $press_values_min, $pondState->max_press_values, $pondState->min_press_values), Helper::WATER_CRITERIA_PRESS);
                $pondStateRet->press_values_state = $this->getQualityStateWithRangeValue($press_values_max, $press_values_min, $pondState->max_press_values, $pondState->min_press_values);
                $pondStateRet->ammonia_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ammonia_values_max, $ammonia_values_min, $pondState->max_ammonia_values, $pondState->min_ammonia_values), Helper::WATER_CRITERIA_AMMONIA);
                $pondStateRet->ammonia_values_state = $this->getQualityStateWithRangeValue($ammonia_values_max, $ammonia_values_min, $pondState->max_ammonia_values, $pondState->min_ammonia_values);
                $pondStateRet->ion_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($ion_values_max, $ion_values_min, $pondState->max_ion_values, $pondState->min_ion_values), Helper::WATER_CRITERIA_ION);
                $pondStateRet->ion_values_state = $this->getQualityStateWithRangeValue($ion_values_max, $ion_values_min, $pondState->max_ion_values, $pondState->min_ion_values);
                $pondStateRet->out_temp_values = Helper::formatPondStateValues($this->getQualityValueWithRangeValue($out_temp_values_max, $out_temp_values_min, $pondState->max_out_temp_values, $pondState->min_out_temp_values), Helper::WATER_CRITERIA_OUT_TEMP);
                $pondStateRet->out_temp_values_state = $this->getQualityStateWithRangeValue($out_temp_values_max, $out_temp_values_min, $pondState->max_out_temp_values, $pondState->min_out_temp_values);
            } else {
                return response()->json(null);
            }

            return response()->json($pondStateRet);
        } else {
            return response()->json(null);
        }
    }
}