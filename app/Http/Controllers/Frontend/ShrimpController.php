<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 1/4/2019
 * Time: 9:22 AM
 */

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Helper;

class ShrimpController extends FrontendBaseController
{
    public function viewShrimpMeasure()
    {
        $data = array();
        $this->prepareAquacultureBlockData($data);
        $current_aquaculture = $data['current_aquaculture'];
        if ($current_aquaculture) {
            $start_date = Carbon::createFromFormat('Y-m-d', $current_aquaculture->start_date);
            $estimate_shipping_date = Carbon::createFromFormat('Y-m-d', $current_aquaculture->estimate_shipping_date);

            //get graph data at start screen
            $shrimp_states = DB::table('ebi_shrimp_states')
                ->where('pond_id', $data['pondId'])
                ->where('ponds_aquacultures_id', $current_aquaculture->ponds_aquacultures_id)
                ->orderBy('date_target')
                ->get()
                ->toArray();

            $target_weight = DB::table('threshold_grow')
                ->select('id', 'date', 'weight')
                ->where('ebi_kind_id', $current_aquaculture->ebi_kind_id)
                ->where('aquaculture_method_id', $current_aquaculture->aquaculture_method_id)
                ->orderBy('date')
                ->get()
                ->toArray();

            $training_period = Helper::getEbiAquaculturePeriod($current_aquaculture);

            foreach ($target_weight as $weight) {
                $idealWeight[] = array($weight->date, $weight->weight);
            }

            foreach ($shrimp_states as $state) {
                $date_target = Carbon::createFromFormat('Y-m-d', $state->date_target);
                $num = $date_target->diffInDays($start_date) + 1;
                if ($date_target >= $start_date) {
                    $actualWeight[] = array($num, (float)$state->weight);
                }
            }

            //get fcr data
            $actual_fcr = DB::table('fcr')
                ->where('ponds_aquacultures_id', $current_aquaculture->ponds_aquacultures_id)
                ->orderBy('fcr_date')
                ->get()->toArray();
            $ideal_fcr = DB::table('threshold_fcr')
                ->select('id', 'date', 'fcr')
                ->where('ebi_kind_id', $current_aquaculture->ebi_kind_id)
                ->where('aquaculture_method_id', $current_aquaculture->aquaculture_method_id)
                ->orderBy('date')->get()->toArray();
            if ($actual_fcr) {
                $fcr_shipping_date = end($actual_fcr)->fcr_date;
            }
            if (isset($fcr_shipping_date) && $fcr_shipping_date > $estimate_shipping_date) {
                $fcr_date = $start_date->diffInDays($fcr_shipping_date) + 1;
            } else {
                $fcr_date = $start_date->diffInDays($estimate_shipping_date) + 1;
            }
            foreach ($ideal_fcr as $weight) {
                $threshold_fcr[] = array($weight->date, $weight->fcr);
            }
            foreach ($actual_fcr as $item) {
                $date_target = Carbon::createFromFormat('Y-m-d', $item->fcr_date);
                $num = $date_target->diffInDays($start_date) + 1;
                if ($date_target >= $start_date) {
                    $fcr[] = array($num, $item->fcr);
                }
            }

            //get adg data
            $actual_adg = DB::table('adg')
                ->where('ponds_aquacultures_id', $current_aquaculture->ponds_aquacultures_id)
                ->orderBy('adg_date')
                ->get()->toArray();
            $ideal_adg = DB::table('threshold_adg')
                ->select('id', 'date', 'adg')
                ->where('ebi_kind_id', $current_aquaculture->ebi_kind_id)
                ->where('aquaculture_method_id', $current_aquaculture->aquaculture_method_id)
                ->orderBy('date')->get()->toArray();
            if ($actual_adg) {
                $adg_shipping_date = end($actual_adg)->adg_date;
            }
            if (isset($adg_shipping_date) && $adg_shipping_date > $estimate_shipping_date) {
                $adg_date = $start_date->diffInDays($adg_shipping_date) + 1;
            } else {
                $adg_date = $start_date->diffInDays($estimate_shipping_date) + 1;
            }
            foreach ($ideal_adg as $adg_weight) {
                $threshold_adg[] = array($adg_weight->date, $adg_weight->adg);
            }
            foreach ($actual_adg as $item) {
                $date_target = Carbon::createFromFormat('Y-m-d', $item->adg_date);
                $num = $date_target->diffInDays($start_date) + 1;
                if ($date_target >= $start_date) {
                    $adg[] = array($num, $item->adg);
                }
            }
        } else {
            $shrimp_states = array();
            $idealWeight = array();
            $actualWeight = array();
            $training_period = 0;
            $fcr_date = 0;
            $threshold_fcr = [];
            $fcr = [];
            $adg_date = 0;
            $threshold_adg = [];
            $adg = [];
        }

        $prices = array();
        if ($shrimp_states) {
            foreach ($shrimp_states as $shrimp_state) {
                $price = Helper::priceShrimp($shrimp_state->weight, $current_aquaculture->ebi_kind_id);
                $price = number_format((float)$price, 2, '.', '');
                $prices[] = $price;
            }
        }
        $data['shrimpStates'] = $shrimp_states;
        $data['prices'] = $prices;
        $data['idealWeight'] = $idealWeight;
        $data['actualWeight'] = $actualWeight;
        $data['training_period'] = $training_period;
        $data['fcr_date'] = $fcr_date;
        $data['threshold_fcr'] = $threshold_fcr;
        $data['fcr'] =$fcr;
        $data['adg_date'] = $adg_date;
        $data['threshold_adg'] = $threshold_adg;
        $data['adg'] = $adg;
        return view('frontend/shrimp_measure', $data);
    }
}