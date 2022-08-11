<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 8/1/2018
 * Time: 10:06 AM
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Helper
{
    const START_DAY_OF_MONTH = 1;

    const WATER_COLOR_NORMAL = '#92c5eb';
    const WATER_COLOR_ABNORMAL = '#de773f';

    const WATER_POINT_A = 'A';
    const WATER_POINT_B = 'B';
    const WATER_POINT_C = 'C';
    const WATER_POINT_D = 'D';
    const WATER_POINT_A_PLUS = 'A+';
    const WATER_POINT_B_PLUS = 'B+';
    const WATER_POINT_C_PLUS = 'C+';
    const WATER_POINT_D_PLUS = 'D+';

    const WATER_MEASURE_POINTS = array(Helper::WATER_POINT_A, Helper::WATER_POINT_B, Helper::WATER_POINT_C, Helper::WATER_POINT_D);
                       //             Helper::WATER_POINT_A_PLUS, Helper::WATER_POINT_B_PLUS, Helper::WATER_POINT_C_PLUS, Helper::WATER_POINT_D_PLUS);

    const WATER_CRITERIA_PH = 'pH';
    const WATER_CRITERIA_MV = 'mV';
    const WATER_CRITERIA_ORP = 'orp';
    const WATER_CRITERIA_EC = 'ec';
    const WATER_CRITERIA_EC_ABS = 'ec_abs';
    const WATER_CRITERIA_RES = 'res';
    const WATER_CRITERIA_TDS = 'tds';
    const WATER_CRITERIA_SAL = 'sal';
    const WATER_CRITERIA_SIGMA = 'sigma_t';
    const WATER_CRITERIA_DO = 'do';
    const WATER_CRITERIA_DO_PPM = 'do_ppm';
    const WATER_CRITERIA_TURB = 'turb_fnu';
    const WATER_CRITERIA_TMP = 'tmp';
    const WATER_CRITERIA_PRESS = 'press';
    const WATER_CRITERIA_AMMONIA = 'ammonia';
    const WATER_CRITERIA_ION = 'copper_ion';
    const WATER_CRITERIA_OUT_TEMP = 'out_temp';
    const DELTA_MEASURE_UNIT = '0.00001';

    const FEED_IDEAL_AMOUNT = 'ideal_amount';
    const FEED_ACTUAL_AMOUNT = 'actual_amount';
    const FEED_CHART_TITLE = 'chart_title';

    private static function water_criteria_labels(){
        return array(Helper::WATER_CRITERIA_PH => trans("ebi.pH"),
                        Helper::WATER_CRITERIA_MV => trans("ebi.mV"),
                        Helper::WATER_CRITERIA_ORP => trans("ebi.酸化還元電位"),
                        Helper::WATER_CRITERIA_EC => "EC(".trans("ebi.導電率").")",
                        Helper::WATER_CRITERIA_EC_ABS => trans("ebi.絶対EC分解単位"),
                        Helper::WATER_CRITERIA_RES =>  trans("ebi.抵抗率"),
                        Helper::WATER_CRITERIA_TDS => trans("ebi.全溶解度"),
                        Helper::WATER_CRITERIA_SAL => trans("ebi.塩分"),
                        Helper::WATER_CRITERIA_SIGMA => trans("ebi.海水比重"),
                        Helper::WATER_CRITERIA_DO => trans("ebi.溶存酸素").'(%)',
                        Helper::WATER_CRITERIA_DO_PPM =>  trans("ebi.溶存酸素").'(ppm)',
                        Helper::WATER_CRITERIA_TURB => trans("ebi.濁度"),
                        Helper::WATER_CRITERIA_TMP => trans("ebi.水温"),
                        Helper::WATER_CRITERIA_AMMONIA => trans("ebi.アンモニア"),
                        Helper::WATER_CRITERIA_ION => trans("ebi.銅イオン濃度"),
                        Helper::WATER_CRITERIA_OUT_TEMP => trans("ebi.気温(℃)"));
    }

    private static function feed_graph_labels(){
        return array(
            Helper::FEED_IDEAL_AMOUNT => trans("ebi.理想餌量"),
            Helper::FEED_ACTUAL_AMOUNT => trans("ebi.実績餌量"),
            Helper::FEED_CHART_TITLE => trans("ebi.累計投与餌量"));
    }

    public static function getFeedGraphLabel($key){
        return Helper::feed_graph_labels()[$key];
    }

    public static function getWaterCriteriaLabel($criteria){
        return Helper::water_criteria_labels()[$criteria];
    }

    
    public static function getAllWaterCriteriaLabel(){
        return Helper::water_criteria_labels();
    }

    const WATER_CRITERIA_UNIT = array(Helper::WATER_CRITERIA_PH => '',
        Helper::WATER_CRITERIA_MV => '',
        Helper::WATER_CRITERIA_ORP => '',
        Helper::WATER_CRITERIA_EC => '',
        Helper::WATER_CRITERIA_EC_ABS => '',
        Helper::WATER_CRITERIA_RES => '',
        Helper::WATER_CRITERIA_TDS => '',
        Helper::WATER_CRITERIA_SAL => '',
        Helper::WATER_CRITERIA_SIGMA => '',
        Helper::WATER_CRITERIA_DO => '%',
        Helper::WATER_CRITERIA_DO_PPM => 'ppm',
        Helper::WATER_CRITERIA_TURB => '',
        Helper::WATER_CRITERIA_TMP => '°C',
        Helper::WATER_CRITERIA_PRESS => 'psi',
        Helper::WATER_CRITERIA_AMMONIA => '',
        Helper::WATER_CRITERIA_ION => '',
        Helper::WATER_CRITERIA_OUT_TEMP => '');

    public static function getWaterCriteria($criteria){
        switch ($criteria){
            case Helper::WATER_CRITERIA_PH:
            case Helper::WATER_CRITERIA_MV:
            case Helper::WATER_CRITERIA_ORP:
            case Helper::WATER_CRITERIA_EC:
            case Helper::WATER_CRITERIA_EC_ABS:
            case Helper::WATER_CRITERIA_RES:
            case Helper::WATER_CRITERIA_TDS:
            case Helper::WATER_CRITERIA_SAL:
            case Helper::WATER_CRITERIA_SIGMA:
            case Helper::WATER_CRITERIA_DO:
            case Helper::WATER_CRITERIA_DO_PPM:
            case Helper::WATER_CRITERIA_TURB:
            case Helper::WATER_CRITERIA_TMP:
            case Helper::WATER_CRITERIA_PRESS:
            case Helper::WATER_CRITERIA_AMMONIA:
            case Helper::WATER_CRITERIA_ION:
            case Helper::WATER_CRITERIA_OUT_TEMP:
                return $criteria;
            default:
                return Helper::WATER_CRITERIA_PH;
        }
    }

    public static function toMoneyLabel($money, $return_empty = true)
    {
        if ($money) {
            return number_format($money) . '円';
        } else if ($return_empty) {
            return '';
        } else {
            return '0円';
        }
    }

    public static function toPercentageLabel($percentage)
    {
        if ($percentage) {
            return $percentage . '%';
        } else {
            return '';
        }
    }

    public static function toDisableFlagLabel($flag)
    {
        if ($flag) {
            return '無効';
        } else {
            return '有効';
        }
    }

    public static function validateDate($date)
    {
        if ($date) {
            $arr = explode('/', $date);
            if (count($arr) > 3) {
                return false;
            }
            list($yyyy, $mm, $dd) = $arr;
            return checkdate($mm, $dd, $yyyy);
        }
        return true;
    }

    public static function validateTime($time)
    {
        if ($time) {
            return preg_match("/^([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}$/", $time);
        }
        return true;
    }

    public static function days_in_month($month, $year)
    {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public static function getWorkdays($start, $end, $workSat = true){
        if (!defined('SATURDAY')) define('SATURDAY', 6);
        if (!defined('SUNDAY')) define('SUNDAY', 0);
        // Array of all public festivities
        $publicHolidays = array("2018-01-01",
            "2018-01-08",
            "2018-02-11",
            "2018-02-12",
            "2018-03-21",
            "2018-04-29",
            "2018-04-30",
            "2018-05-03",
            "2018-05-04",
            "2018-05-05",
            "2018-07-16",
            "2018-08-11",
            "2018-09-17",
            "2018-09-23",
            "2018-09-24",
            "2018-10-08",
            "2018-11-03",
            "2018-11-23",
            "2018-12-23",
            "2018-12-24",
            "2019-01-01",
            "2019-01-14",
            "2019-02-11",
            "2019-03-21",
            "2019-04-29",
            "2019-04-30",
            "2019-05-01",
            "2019-05-02",
            "2019-05-03",
            "2019-05-04",
            "2019-05-05",
            "2019-05-06",
            "2019-07-15",
            "2019-08-11",
            "2019-08-12",
            "2019-09-16",
            "2019-09-23",
            "2019-10-14",
            "2019-11-03",
            "2019-11-04",
            "2019-11-23",
            "2020-01-01",
            "2020-01-13",
            "2020-02-11",
            "2020-03-20",
            "2020-04-29",
            "2020-05-03",
            "2020-05-04",
            "2020-05-05",
            "2020-05-06",
            "2020-07-20",
            "2020-08-11",
            "2020-09-21",
            "2020-09-22",
            "2020-10-12",
            "2020-11-03",
            "2020-11-23",
            "2020-12-23"
            );

        $firstSaturdays = array();
        $fifthSaturdays = array();
        for($year = 2018; $year < 2021; $year++){
            for ($month =1; $month <= 12; $month++) {
                $monthName = date('F', mktime(0,0,0,$month, 1, date('Y')));
                $firstSaturday = strtotime('first saturday of '.$year.''.$monthName);
                $firstSaturdays[] = date('Y-m-d', $firstSaturday);

                $monthOfFifthSaturday = date('n', strtotime('+4 weeks', $firstSaturday));
                if ($monthOfFifthSaturday == $month){
                    $fifthSaturdays[] = date('Y-m-d', strtotime($monthName.' '.$year.' fifth saturday'));
                }
            }
        }
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $yymmdd = date('Y-m-d', $i);
            if ($day != SUNDAY &&
                !in_array($yymmdd, $publicHolidays) &&
                !($day == SATURDAY && $workSat == FALSE)) {
                if ($day == SATURDAY) {
                    if (!in_array($yymmdd, $firstSaturdays) && !in_array($yymmdd, $fifthSaturdays)){
                        $workdays++;
                    }
                } else {
                    $workdays++;
                }
            }
        }
        return $workdays;
    }

    public static function toPondMethodLabel($method){
        switch ($method){
            case 0:
                return trans('ebi.レースウェイ');
            case 1:
                return trans('ebi.クラシック');
            case 2:
                return trans('ebi.バケツ');
            case 3:
                return trans('ebi.バイオフロック');
            default:
                return $method;
        }
    }

    public static function getMeasurePoints($method){
        switch ($method){
            case 0:
                return [Helper::WATER_POINT_D => array(), Helper::WATER_POINT_C => array() , Helper::WATER_POINT_B => array(), Helper::WATER_POINT_A => array() ];
            case 1:
                return [Helper::WATER_POINT_D => array(), Helper::WATER_POINT_C => array() , Helper::WATER_POINT_B => array(), Helper::WATER_POINT_A => array() ];
            case 2:
                return [Helper::WATER_POINT_D => array(), Helper::WATER_POINT_C => array() , Helper::WATER_POINT_B => array(), Helper::WATER_POINT_A => array() ];
            case 3:
                return [Helper::WATER_POINT_D => array(), Helper::WATER_POINT_C => array() , Helper::WATER_POINT_B => array(), Helper::WATER_POINT_A => array() ];
            default:
                return [Helper::WATER_POINT_D => array(), Helper::WATER_POINT_C => array() , Helper::WATER_POINT_B => array(), Helper::WATER_POINT_A => array() ];
        }
    }

    public static function toAquaculturePeriodLabel($aquaculture){
        if((app()->getLocale() == 'en')){
            return date("m.d.yy", strtotime($aquaculture->start_date))." 〜 ".date("m.d.yy", strtotime($aquaculture->estimate_shipping_date));
        }else{
            return str_replace('-','.', $aquaculture->start_date)." 〜 ".str_replace('-','.', $aquaculture->estimate_shipping_date );
        }
    }

    public static function getCurrentAquaculture($pondId){
        $date_now = new Carbon('now');
        $time_now = $date_now->toDateString();
        return Helper::getSelectedAquaculture($pondId, $time_now);
    }

    public static function getSelectedAquaculture($pondId, $selectedDate){
        $selected_aquaculture = null;
        $time_line = DB::table('ebi_aquacultures')->join('ponds_aquacultures', 'ebi_aquacultures.id', '=', 'ponds_aquacultures.ebi_aquacultures_id')
            ->where([['ponds_aquacultures.ebi_ponds_id', '=', $pondId], ['ebi_aquacultures.start_date', '<=', $selectedDate]])
            ->where(function ($query) use ($selectedDate) {
                $query->where('ebi_aquacultures.completed_date', '>=', $selectedDate)
                    ->orWhereNull('ebi_aquacultures.completed_date');
            })
            //->where('ponds_aquacultures.status',0)  //藤井追加
            ->select(['ebi_aquacultures.*', 'ponds_aquacultures.id as ponds_aquacultures_id', 'ponds_aquacultures.status as ponds_aquacultures_status', 'ponds_aquacultures.sell as ponds_aquacultures_sell', 'ponds_aquacultures.ebi_remaining as ponds_aquacultures_ebi_remaining'])
            ->orderBy('ponds_aquacultures.created_at', 'desc')
            ->first();
        if ($time_line) {
            $selected_aquaculture = $time_line;
        } else {
            $time_line_up = DB::table('ebi_aquacultures')->join('ponds_aquacultures', 'ebi_aquacultures.id', '=', 'ponds_aquacultures.ebi_aquacultures_id')
                ->where([['ponds_aquacultures.ebi_ponds_id', '=', $pondId], ['ebi_aquacultures.start_date', '>=', $selectedDate]])
                ->select(['ebi_aquacultures.*', 'ponds_aquacultures.id as ponds_aquacultures_id', 'ponds_aquacultures.status as ponds_aquacultures_status', 'ponds_aquacultures.sell as ponds_aquacultures_sell', 'ponds_aquacultures.ebi_remaining as ponds_aquacultures_ebi_remaining'])
                ->orderBy('ebi_aquacultures.start_date', 'asc')
                ->orderBy('ponds_aquacultures.created_at', 'desc')
                ->first();
            if ($time_line_up) {
                $selected_aquaculture = $time_line_up;
            } else {
                $time_line_down = DB::table('ebi_aquacultures')->join('ponds_aquacultures', 'ebi_aquacultures.id', '=', 'ponds_aquacultures.ebi_aquacultures_id')
                    ->where([['ponds_aquacultures.ebi_ponds_id', '=', $pondId], ['ebi_aquacultures.completed_date', '<=', $selectedDate]])
                    ->select(['ebi_aquacultures.*', 'ponds_aquacultures.id as ponds_aquacultures_id', 'ponds_aquacultures.status as ponds_aquacultures_status', 'ponds_aquacultures.sell as ponds_aquacultures_sell', 'ponds_aquacultures.ebi_remaining as ponds_aquacultures_ebi_remaining'])
                    ->orderBy('ebi_aquacultures.completed_date', 'desc')
                    ->orderBy('ponds_aquacultures.created_at', 'desc')
                    ->first();
                if ($time_line_down) {
                    $selected_aquaculture = $time_line_down;
                }
            }
        }
        return $selected_aquaculture;
    }

    public static function getLastMinmax($pondId){
        $minmax = DB::table('ebi_minmaxs')
            ->where('pond_id', $pondId)
            ->orderByDesc('id')
            ->first();
        return $minmax;
    }

    public static function getPond($pondId){
        $pond = DB::table('ebi_ponds')
            ->where('id', $pondId)
            ->first();
        return $pond;
    }

    public static function getDecimalPlaceForCriteria($criteria){
        switch ($criteria){
            case Helper::WATER_CRITERIA_PH:
                return 2;
            case Helper::WATER_CRITERIA_MV:
                return 1;
            case Helper::WATER_CRITERIA_ORP:
                return 1;
            case Helper::WATER_CRITERIA_EC:
                return 0;
            case Helper::WATER_CRITERIA_EC_ABS:
                return 0;
            case Helper::WATER_CRITERIA_RES:
                return 0;
            case Helper::WATER_CRITERIA_TDS:
                return 0;
            case Helper::WATER_CRITERIA_SAL:
                return 2;
            case Helper::WATER_CRITERIA_SIGMA:
                return 1;
            case Helper::WATER_CRITERIA_DO:
                return 1;
            case Helper::WATER_CRITERIA_DO_PPM:
                return 2;
            case Helper::WATER_CRITERIA_TURB:
                return 1;
            case Helper::WATER_CRITERIA_TMP:
                return 2;
            case Helper::WATER_CRITERIA_PRESS:
                return 3;
            case Helper::WATER_CRITERIA_AMMONIA:
                return 3;
            case Helper::WATER_CRITERIA_ION:
                return 3;
            case Helper::WATER_CRITERIA_OUT_TEMP:
                return 2;
        }
    }

    public static  function getEbiAquaculturePeriod($aquaculture){
        $start_date = Carbon::createFromFormat('Y-m-d', $aquaculture->start_date);
        if ($aquaculture->completed_date){
            $completed_date = Carbon::createFromFormat('Y-m-d', $aquaculture->completed_date);
            return $start_date->diffInDays($completed_date) + 1;
        }else{
            $estimate_shipping_date = Carbon::createFromFormat('Y-m-d', $aquaculture->estimate_shipping_date);
            $now = Carbon::now();
            if ( $now->gt($estimate_shipping_date)) {
                return $start_date->diffInDays($now) + 1;
            } else {
                return $start_date->diffInDays($estimate_shipping_date) + 1;
            }
        }

    }

    public static function formatPondStateValues($value,$criteria){
        return number_format($value,Helper::getDecimalPlaceForCriteria($criteria));
    }

    public static function getQualityStateWithRangeValue($max, $min, $maxValue, $minValue)
    {
        $result = true;
        if ($maxValue !== null && is_numeric($maxValue)) {
            $result = (is_numeric($max)) ? ($maxValue <= $max) : true;
            if ($result) {
                $result = (is_numeric($min)) ? ($maxValue >= $min) : true;
            }
        }
        if ($result) {
            if ($minValue !== null && is_numeric($minValue)) {
                $result = (is_numeric($max)) ? ($minValue <= $max) : true;
                if ($result) {
                    $result = (is_numeric($min)) ? ($minValue >= $min) : true;
                }
            }
        }
        return $result;
    }

    public static function getQualityValueWithRangeValue($max, $min, $maxValue, $minValue)
    {
        $avgMinMax = null;
        if ($max !== null && is_numeric($max) && $min !== null && is_numeric($min)) {
            $avgMinMax = ($max + $min) / 2;
        } else if ($max !== null && is_numeric($max)) {
            $avgMinMax = $max;
        } elseif ($min !== null && is_numeric($min)) {
            $avgMinMax = $min;
        }
        if (isset($avgMinMax) && $maxValue !== null && is_numeric($maxValue) && $minValue !== null && is_numeric($minValue)) {
            return (abs($maxValue - $avgMinMax) >= abs($minValue - $avgMinMax)) ? $maxValue : $minValue;

        } else if ($minValue !== null && is_numeric($minValue)) {
            return $minValue;
        } else if ($maxValue !== null && is_numeric($maxValue)) {
            return $maxValue;
        } else {
            return null;
        }
    }

    public static function getLastDefaultPonds($farmId){
        $defaultPond = DB::table('default_ponds')
            ->where('farm_id', $farmId)
            ->first();
        return $defaultPond;
    }
    public static function toStockLabel($stock){
        return $stock!==null?floatval($stock) . trans('ebi.袋') :'-';
    }

    public static function toAmountPerBagLabel($amountPerBag){
        $amount = $amountPerBag!==null?floatval($amountPerBag) . 'Kg':'';
        return $amount;
    }

    public static function toStatusBaitInventoriesLabel($selectStatus){
        switch ($selectStatus){
            case 0:
                return trans('ebi.正常');
            case 1:
                return trans('ebi.在庫不足');
            default:
                return $selectStatus;
        }
    }

    public static function toOderStatusBaitInventoriesLabel($selectOderStatus){
        switch ($selectOderStatus){
            case 0:
                return '-';
            case 1:
                return '未発注';
            case 2:
                return '発注済';
            default:
                return $selectOderStatus;
        }
    }

    public static function formatDate($date){
        if ($date){
            return date("Y.m.d",strtotime($date));
        }else{
            return $date;
        }
    }

    public static function toKindBaitInventoriesLabel($selectKind){
        switch ($selectKind){
            case 0:
                return trans('ebi.餌');
            case 1:
                return trans('ebi.薬');
            default:
                return $selectKind;
        }
    }

    public static function priceShrimp($actualWeight, $kindId){
        if($actualWeight < 5){
            $price = 0;
        }else{
            $arrPrice =  DB::table('ebi_price')
                    ->where('ebi_kind_id',$kindId)
                    ->orderBy('updated_at')
                    ->pluck('ebi_price','weight')
                    ->toArray();
            krsort($arrPrice);
            if(array_key_exists($actualWeight, $arrPrice)){
                $price = $arrPrice[$actualWeight];
            }else if($actualWeight < 25){
                foreach($arrPrice as $key => $value){
                    if($key < $actualWeight){
                        $price = $arrPrice[$key];
                        break;
                    }
                }
            }else{
                foreach($arrPrice as $key => $value){
                    if($key < $actualWeight){
                        $price = (($actualWeight - $key)*10)+$arrPrice[$key];
                        break;
                    }
                }
            }

            if(!$price){
                $price = 0;
            }
        }

        return $price;
    }
}
