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
use Session;
use App\Http\Controllers\Helper;


class FeedCumulativeController extends FrontendBaseController
{
    public function feedingGraph()
    {
        $data = array();
        $this->prepareAquacultureBlockData($data);

        $shrimp = DB::table('ebi_shrimp_states')
            ->where('ebi_shrimp_states.pond_id', '=', $data['pondId'])
            ->orderBy('ebi_shrimp_states.date_target', 'desc')
            ->first();
        $data['shrimp'] = $shrimp;

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

        $data['dayOfMonth'] = Helper::days_in_month($activeYear, $activeMonth);
        $data['activeDate'] = $activeDate;
        $data['activeMonth'] = $activeMonth;
        $data['activeYear'] = $activeYear;

        //var_dump($fromDate);
        $idealWeight = [];
        $actualAmount = [];
        $amountByDate = [];
        $idealWeightByDate = [];
        if ($currentAquaculture){
            $start_date = Carbon::createFromFormat('Y-m-d', $currentAquaculture->start_date);
            $training_period = Helper::getEbiAquaculturePeriod($currentAquaculture);
            //餌のレコードのIDを抽出
            
            $feed_ids=array();

            $ebi_bait_inventories_ids=DB::table('ebi_bait_inventories')
            ->where('kind',0)
            ->get();
            
            foreach($ebi_bait_inventories_ids as $id){
                $feed_ids[]=$id->id;
            }
            //元々の条件　feed_idsあたりのコードはなかった(84-92)　->where('ebi_bait_inventories_id',$id)
            $baitAmounts = DB::table('ebi_baits')->where('ponds_aquacultures_id', $currentAquaculture->ponds_aquacultures_id)
                ->select(DB::raw('date(bait_at) as bait_at'), DB::raw('SUM(amount) as total_amount'))
                ->whereIn('ebi_bait_inventories_id',$feed_ids)
                //->where('ebi_bait_inventories_id',$id)
                ->groupBy(DB::raw('date(bait_at)'))->get();
            $targetFeed = DB::table('threshold_feed')
                ->select('id', 'date', 'weight')
                ->where('ebi_kind_id', $currentAquaculture->ebi_kind_id)
                ->where('aquaculture_method_id', $currentAquaculture->aquaculture_method_id)
                ->orderBy('date')
                ->get()
                ->toArray();

            foreach ($targetFeed as $weight) {
                $date_target = clone $start_date;
                $date_target = $date_target->addDays($weight->date - 1);
                $idealWeight[] = array($weight->date, $weight->weight);
                $idealWeightByDate[$date_target->toDateString()] = $weight->weight;
            }

            foreach ($baitAmounts as $bait) {
                $date_target = Carbon::createFromFormat('Y-m-d', $bait->bait_at);
                if ($date_target->gte($start_date)) {
                    $dayNo = $date_target->diffInDays($start_date) + 1;
                    $actualAmount[] = array($dayNo, (float)$bait->total_amount);
                    $amountByDate[$bait->bait_at] = (float)$bait->total_amount;
                }
            }
        }else{
            $training_period = 30;
        }

        $data['idealWeight'] = $idealWeight;
        $data['actualAmount'] = $actualAmount;
        $data['training_period'] = $training_period;
        $data['amountByDate'] = $amountByDate;
        $data['idealWeightByDate'] = $idealWeightByDate;
        return view('frontend/feeding_graph', $data);
    }

    public function getCumulativesByPond(Request $request) {
        $data = array();
        if (!$this->prepareCurrentAquacultureData($data)) {
            return response()->json(null);
        }
        $date = Request::input('date');
        $currentAquaculture = $data['current_aquaculture'];
        if (!$date) {
            if ($currentAquaculture->completed_date){
                $date = $currentAquaculture->completed_date;
            }else{
                $date = Carbon::today()->format('Y-m-d');
            }
        }

        $pondId = $data['pondId'];
        $pond = DB::table('ebi_ponds')->where('id', $pondId)->first();
        $ponds_aquacultures = DB::table('ponds_aquacultures')->where('id', $currentAquaculture->ponds_aquacultures_id)->first();
        $rateShrimpPerM3 = ($pond->pond_width*$pond->water_dept*$pond->pond_vertical_width)?$ponds_aquacultures->shrimp_num/($pond->pond_width*$pond->water_dept*$pond->pond_vertical_width):$ponds_aquacultures->shrimp_num;
        if ($date) {
            $feed_cumulatives = DB::table('feed_cumulative')->join('ebi_bait_inventories', 'feed_cumulative.ebi_bait_inventories_id', '=', 'ebi_bait_inventories.id')
                ->where('ponds_aquacultures_id', $currentAquaculture->ponds_aquacultures_id)
                ->whereDate('feed_cumulative.created_at', $date)
                ->select(['feed_cumulative.*', 'ebi_bait_inventories.bait_name'])
                ->orderBy('ebi_bait_inventories.bait_name')
                ->get();

            $medicine_cumulatives = DB::table('medicine_cumulative')->join('ebi_bait_inventories', 'medicine_cumulative.ebi_bait_inventories_id', '=', 'ebi_bait_inventories.id')
                ->where('ponds_aquacultures_id', $currentAquaculture->ponds_aquacultures_id)
                ->whereDate('medicine_cumulative.created_at', $date)
                ->select(['medicine_cumulative.*', 'ebi_bait_inventories.bait_name'])
                ->orderBy('ebi_bait_inventories.bait_name')
                ->get();

            return response()->json(['date' => $date,'feed_cumulatives' => $feed_cumulatives, 'medicine_cumulatives' => $medicine_cumulatives, 'rateShrimpPerM3' => $rateShrimpPerM3]);
        } else {
            return response()->json(null);
        }
    }
}