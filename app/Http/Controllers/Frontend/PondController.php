<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 1/4/2019
 * Time: 9:22 AM
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Request;
use Session;
use App\Http\Controllers\Helper;
use App\Http\Controllers\AppConst;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class PondController extends FrontendBaseController
{
    public function viewFarmMap()
    {
        $myFarms = $this->getMyFarms();

        $data = array();
        if (count($myFarms)) {
            // get farm
            $farms = DB::table('ebi_farms')->whereIn('id', $myFarms)->orderBy('farm_name')->get();
        } else {
            $farms = array();
        }
        $data['myFarms'] = $farms;

        // get abnormal pond
        $myPondId = $this->getMyPonds();
        $alerts = DB::table('ebi_pond_alerts')
            ->whereIn('pond_id', $myPondId)
            ->where('disable_flag', 0)
            ->orderByDesc('alert_date')
            ->orderByDesc('alert_time')
            ->take(100)->get();

        $states = DB::table('ebi_pond_alerts')
            ->leftJoin('solution','ebi_pond_alerts.ebi_pond_states_id','=','solution.ebi_pond_states_id')
            ->select('ebi_pond_alerts.ebi_pond_states_id as id','ebi_pond_alerts.id as pond_alerts_id','solution.id as solution_id')
            ->whereIn('ebi_pond_alerts.pond_id', $myPondId)
            ->where('ebi_pond_alerts.disable_flag', 0)
            ->get()->keyBy('pond_alerts_id');

        $pondsAquacultures = DB::select( DB::raw("SELECT `ebi_shrimp_states`.`weight`, `ponds_aquacultures`.`ebi_remaining`, 
                    `ebi_aquacultures`.`farm_id`, `ponds_aquacultures`.`ebi_kind_id`, 
                    `ebi_aquacultures`.`income_and_expenditure`, `ebi_aquacultures`.`status` AS `aquacultures_status`, 
                    `ebi_farms`.`farm_name`, `ebi_farms`.`farm_name_en`, `ebi_aquacultures`.`start_date` 
                FROM `ponds_aquacultures` 
                INNER JOIN ( SELECT EA.* FROM 
	                (SELECT *, ROW_NUMBER() OVER (PARTITION BY farm_id ORDER BY start_date) AS row_num FROM ebi_aquacultures) EA
                    WHERE EA.row_num = 1) 
                    AS `ebi_aquacultures` ON `ponds_aquacultures`.`ebi_aquacultures_id` = `ebi_aquacultures`.`id` 
                LEFT JOIN `ebi_shrimp_states` ON `ponds_aquacultures`.`id` = `ebi_shrimp_states`.`ponds_aquacultures_id` 
                INNER JOIN `ebi_farms` ON `ebi_aquacultures`.`farm_id` = `ebi_farms`.`id` WHERE `ponds_aquacultures`.`status` = 0"));

//        $pondsAquacultures = DB::table('ponds_aquacultures')
//                            ->join('ebi_aquacultures','ponds_aquacultures.ebi_aquacultures_id','ebi_aquacultures.id')
//                            ->leftJoin('ebi_shrimp_states','ponds_aquacultures.id','ebi_shrimp_states.ponds_aquacultures_id')
//                            ->join('ebi_farms','ebi_aquacultures.farm_id','ebi_farms.id')
//                            ->select(['ebi_shrimp_states.weight','ponds_aquacultures.ebi_remaining','ebi_aquacultures.farm_id','ponds_aquacultures.ebi_kind_id','ebi_aquacultures.income_and_expenditure','ebi_aquacultures.status as aquacultures_status','ebi_farms.farm_name','ebi_farms.farm_name_en','ebi_aquacultures.start_date'])
//                            ->where('ponds_aquacultures.status',0)
//                            ->get()
//                            ->toArray();
        $arrExpectedEarningsByFarm = [];
        if(count($pondsAquacultures)){
            foreach($pondsAquacultures as $pond){
                if($pond->start_date >  date('Y-m-d')){
                    $days = 0;
                }else{
                    $days = ((strtotime(date('Y-m-d')) - strtotime($pond->start_date)) / (60 * 60 * 24)) +1;
                }

                if($pond->aquacultures_status == 0){
                    $actualBalance = $pond->income_and_expenditure;
                }else{
                    $actualBalance = 0;
                }
                $arrExpectedEarningsByFarm[$pond->farm_name][$days] += (($pond->weight*$pond->ebi_remaining)/1000*Helper::priceShrimp($pond->weight,$pond->ebi_kind_id)) + $actualBalance;
            }
        }

        $mapPond = array();
        foreach($alerts as $alert){
            $mapPond[$alert->pond_id] = null;
        }

        if (count($mapPond)) {
            $abnormalPonds = DB::table('ebi_ponds')->join('ebi_farms', 'ebi_ponds.farm_id', '=', 'ebi_farms.id')
                ->select(['ebi_ponds.*', 'ebi_farms.farm_name'])
                ->whereIn('ebi_ponds.id', array_keys($mapPond))->orderBy('ebi_farms.farm_name', 'ebi_ponds.pond_name')
                ->get();

            foreach ($abnormalPonds as $pond){
                $mapPond[$pond->id] = $pond;
            }
        }

        $data['weather_daily'] = DB::table('weather')->whereDate('day','>=', date('Y-m-d'))->get();

        $data['abnormalPonds'] = $mapPond;
        $data['abnormalPondAlerts'] = $alerts;
        $data['arrExpectedEarningsByFarm'] = $arrExpectedEarningsByFarm;
        $data['states'] = $states;
        return view("frontend/farm_map", array('page_title' => '養殖池管理'))->with($data);
    }

    public function listPondByFarm()
    {
        $farmId = (int)Request::get('farm');
        $retPonds = array();
        if ($farmId) {
            $ponds = DB::table('ebi_ponds')
                    ->where('farm_id', $farmId)
                    ->orWhereIn('pond_image_area',AppConst::SPECIAL_POND)
                    ->orderBy('pond_name')
                    ->get();
                    
            foreach ($ponds as $pond) {
                $retPonds[] = array('id' => $pond->id, 'name' => $pond->pond_name, 'pond_image_area' => $pond->pond_image_area);
            }
        }
        echo json_encode($retPonds);
    }

    public function listPondByFarmMap()
    {
        $farmId = (int)Request::get('farm');
        $retPonds = array();
        if ($farmId) {

            $aquacultures = DB::table('ebi_aquacultures')
                        ->select('ebi_aquacultures.id','ebi_aquacultures.start_date')
                        ->where('ebi_aquacultures.status',0)
                        ->where('farm_id',$farmId)
                        ->orderBy('ebi_aquacultures.start_date')
                        ->get();

            $aquaculturesId = $aquacultures->pluck('id')->toArray();
            
            $ponds = DB::table('ebi_ponds')
                    ->where('farm_id', $farmId)
                    ->orWhereIn('pond_image_area',AppConst::SPECIAL_POND)
                    ->leftJoin('ponds_aquacultures', function ($join) use ($aquaculturesId){
                        $join->on('ebi_ponds.id', '=', 'ponds_aquacultures.ebi_ponds_id')
                            ->whereIn('ponds_aquacultures.ebi_aquacultures_id',$aquaculturesId);
                    })
                    ->select('ponds_aquacultures.ebi_aquacultures_id','ponds_aquacultures.status','ebi_ponds.id as pond_id','ebi_ponds.pond_name','ebi_ponds.pond_image_area','ponds_aquacultures.id as pond_aquacultures_id')
                    ->where('ebi_ponds.farm_id',$farmId)
                    ->get()->toArray();
            foreach ($ponds as $pond) {
                $retPonds['list_ponds'][] = array('id' => $pond->pond_id, 'name' => $pond->pond_name, 'status' => $pond->status, 'pond_image_area' => $pond->pond_image_area, 'aquacultures_id' => $pond->ebi_aquacultures_id);
            }

            $retPonds['aquacultures'] = $aquacultures->toArray();
        }
        echo json_encode($retPonds);

    }

    public function listAllPonds()
    {
        $retPonds = array();
        $ponds = DB::table('ebi_ponds')->get();
        foreach ($ponds as $pond) {
            $retPonds[] = array('id' => $pond->id, 'name' => $pond->pond_name, 'pond_image_area' => $pond->pond_image_area, 'farm_id'=>$pond->farm_id);
        }
        echo json_encode($retPonds);
    }

    public function listMyFarm()
    {
        $myFarmIds = $this->getMyFarms();
        $farms = DB::table('ebi_farms')->whereIn('id', $myFarmIds)->orderBy('farm_name')->get();
        $retFarms = array();
        foreach ($farms as $farm) {
            $retFarms[] = array('id' => $farm->id, 'name' => $farm->farm_name);
        }

        echo json_encode($retFarms);
    }

    public function getCurrentEbiAquacultureOfPond()
    {
        $pond_id = Request::get('pondId');
        $aquaculture = null;
        if ($pond_id) {
            $aquaculture = DB::table('ebi_aquacultures')
                ->join('ponds_aquacultures', 'ebi_aquacultures.id', '=', 'ponds_aquacultures.ebi_aquacultures_id')
                ->where('ponds_aquacultures.ebi_ponds_id', '=', $pond_id)
                ->orderBy('ebi_aquacultures.id', 'desc')
                ->where('ponds_aquacultures.status', '=', 0)
                ->orderBy('ponds_aquacultures.created_at', 'desc')
                ->value('ponds_aquacultures.id');
            if (!$aquaculture) {
                $aquaculture = DB::table('ebi_aquacultures')
                    ->join('ponds_aquacultures', 'ebi_aquacultures.id', '=', 'ponds_aquacultures.ebi_aquacultures_id')
                    ->where('ponds_aquacultures.ebi_ponds_id', '=', $pond_id)
                    ->orderBy('ebi_aquacultures.id', 'desc')
                    ->where('ponds_aquacultures.status', '=', 1)
                    ->orderBy('ponds_aquacultures.created_at', 'desc')
                    ->value('ponds_aquacultures.id');
            }
        }
        echo json_encode($aquaculture);
    }

    public function viewPond()
    {
        $data = array();
        $this->prepareAquacultureBlockData($data);
        Session::put('current_pond', $data['pondId']);

        $shrimp = DB::table('ebi_shrimp_states')
            ->where('ebi_shrimp_states.pond_id', '=', $data['pondId'])
            ->orderBy('ebi_shrimp_states.date_target', 'desc')
            ->first();
        $data['shrimp'] = $shrimp;
        //var_dump($fromDate);
        return view('frontend/view_pond', $data);
    }

    public function pondSetting()
    {
        // dd($data);
        return view("frontend/pond_setting", array('page_title' => '養殖池管理'))->with($data);
    }

}