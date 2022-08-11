<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\FrontendBaseController;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use CRUDBooster;
use App\Http\Controllers\AppConst;

class PondAquaculturesController extends FrontendBaseController
{
    //
    public function shrimpMigrationGet(Request $request)
    {
        $currentPondId = $request['pondId'];
        $data = array();
        $this->prepareAquacultureBlockData($data);

        $farmId = DB::table('ebi_ponds')
                    ->where('ebi_ponds.id', '=', $currentPondId)
                    ->join('ebi_farms', 'ebi_farms.id', '=', 'ebi_ponds.farm_id')
                    ->value('ebi_farms.id');

        $aquaculturesId = DB::table('ponds_aquacultures')
                            ->join('ebi_aquacultures','ponds_aquacultures.ebi_aquacultures_id','ebi_aquacultures.id')
                            ->where('ponds_aquacultures.ebi_ponds_id',$currentPondId)
                            ->where('ebi_aquacultures.status',0)
                            //->orderBy('start_date','desc')
                            ->value('ebi_aquacultures_id');

        $pondBeingUsed = DB::table('ponds_aquacultures')->where('status',0)->pluck('ebi_ponds_id')->toArray();
                
        $pondsAquacultures = DB::table('ponds_aquacultures')
                        ->rightJoin('ebi_ponds', function ($join) use ($aquaculturesId){
                            $join->on('ponds_aquacultures.ebi_ponds_id', '=', 'ebi_ponds.id')
                                ->where('ponds_aquacultures.ebi_aquacultures_id',$aquaculturesId);            
                        })
                        ->where('ebi_ponds.farm_id',$farmId)
                        ->orWhereIn('pond_image_area',AppConst::SPECIAL_POND)
                        ->select('ponds_aquacultures.shrimp_num','ebi_ponds.pond_name','ebi_ponds.id as pondId','ponds_aquacultures.ebi_remaining','ponds_aquacultures.status','ponds_aquacultures.takeover_ponds_id')
                        ->get();
        $data['currentPondsAquacultures'] = [];
        foreach($pondsAquacultures as $key=>$value){
            //hidden currentPondsAquacultures and get value shrimp_remaining and status of currentPondsAquacultures
            if($value->pondId == $currentPondId){
                $data['currentPondsAquacultures']['shrimp_remaining'] = $value->ebi_remaining;
                $data['currentPondsAquacultures']['status'] = $value->status;
                unset($pondsAquacultures[$key]);
                continue;
            }

            if(($value->takeover_ponds_id && ($value->takeover_ponds_id != $currentPondId)) || (!$value->takeover_ponds_id && $value->shrimp_num )){
                unset($pondsAquacultures[$key]);
                continue;
            }

            if(in_array($value->pondId, $pondBeingUsed) && $value->takeover_ponds_id !=  $currentPondId){
                unset($pondsAquacultures[$key]);
                continue;
            }
        }
        $data['pondsAquacultures'] = $pondsAquacultures;
        $data['issetPondsAquacultures'] = $aquaculturesId;
        
        return view("frontend/ponds_aquacultures", $data);
    }

    public function shrimpMigrationRegistration(Request $request){
        $shrimpMigration = $request['shrimp-migration'];
        $pondBeforeTakingOver = $request['pondId'];
        try{
            $parentPond = DB::table('ponds_aquacultures')
                                ->select('target_weight','ebi_kind_id','aquaculture_method_id','ebi_aquacultures_id','id')
                                ->where('ebi_ponds_id',$pondBeforeTakingOver)
                                ->where('status',0)
                                ->first();
            $arrPondsAquacultures = [];
            if($shrimpMigration){
                $arrPondsAquaculturesIsset =  DB::table('ponds_aquacultures')
                        ->where('ebi_aquacultures_id',$parentPond->ebi_aquacultures_id)
                        ->whereIn('ebi_ponds_id',array_keys($shrimpMigration))
                        ->where('status',0)
                        ->get()->toArray();

                foreach($arrPondsAquaculturesIsset as $pond){
                    $arrPondsAquacultures[$pond->ebi_ponds_id] = [
                        'ebi_remaining' => $pond->ebi_remaining,
                        'shrimp_num' => $pond->shrimp_num,
                        'id' => $pond->id
                    ];
                }
                if($parentPond){
                    foreach($shrimpMigration as $key=>$value){
                        $shipment = DB::table('shipment')
                            ->where('ponds_aquacultures_id', $arrPondsAquacultures[$key]['id'])
                            ->first();
                        if ($shipment && $value != $arrPondsAquacultures[$key]['shrimp_num']) {
                            $pondName = DB::table('ebi_ponds')
                                ->select('pond_name')
                                ->where('id', $pond->ebi_ponds_id)
                                ->first();
                            CRUDBooster::redirect(CRUDBooster::adminPath('shrimpMigration?pondId='.$pondBeforeTakingOver), trans("ebi.池").$pondName->pond_name.trans("ebi.の変更を受け付けられません。"), 'danger');
                        } else {
                            if(array_key_exists($key, $arrPondsAquacultures)){

                                if($value == null || $value == ''){
                                    CRUDBooster::redirect(CRUDBooster::adminPath('shrimpMigration?pondId='.$pondBeforeTakingOver), trans("ebi.alert_shrimp_migration_failed"), 'danger');
                                }
                                $shrimp_remaining = $arrPondsAquacultures[$key]['ebi_remaining'] - ($arrPondsAquacultures[$key]['shrimp_num'] - $value);
                                DB::table('ponds_aquacultures')
                                    ->where('ebi_aquacultures_id',$parentPond->ebi_aquacultures_id)
                                    ->where('ebi_ponds_id',$key)
                                    ->update([
                                        'shrimp_num'=>$value,
                                        'ebi_remaining'=>$shrimp_remaining,
                                    ]);
                            }else{
                                if($value == null || $value == ''){
                                    continue;
                                }
                                //計算　ナノバブルと1m3の量
                                $pond=DB::table('ebi_ponds')->where('id',$key)->first();
			
                                if(!($pond->pond_width*$pond->water_dept*$pond->pond_vertical_width==0)){
                                    $rateShrimpPerM3 = $value/($pond->pond_width*$pond->water_dept*$pond->pond_vertical_width);
                                }else{
                                    $rateShrimpPerM3 =0;
                                }

                                $arrPondsAquaculturesNew[] = [
                                    'takeover_ponds_id' => $pondBeforeTakingOver,
                                    'ebi_ponds_id' => $key,
                                    'shrimp_num' => $value,
                                    'target_weight' => $parentPond->target_weight,
                                    'ebi_remaining' => $value,
                                    'ebi_kind_id' => $parentPond->ebi_kind_id,
                                    'status' => 0,
                                    'aquaculture_method_id' => $parentPond->aquaculture_method_id,
                                    'ebi_aquacultures_id' => $parentPond->ebi_aquacultures_id,
                                    'nano_bubble' =>$pond->nano_bubble,
                                    'cubic_meter_num' =>$rateShrimpPerM3,
                                    'start_date' => date("Y/m/d"),
                                    'created_at' => Carbon::now(),
                                ];
                            }
                        }
                    }
                }
            }

            DB::table('ponds_aquacultures')
                    ->where('ebi_ponds_id',$pondBeforeTakingOver)
                    ->where('ebi_aquacultures_id',$parentPond->ebi_aquacultures_id)
                    ->update([
                        'status'=>$request['pond-aquacultures-status'],
                        'ebi_remaining'=>$request['shrimp-remaining']
                    ]);
            if(count($arrPondsAquaculturesNew)){
                DB::table('ponds_aquacultures')->insert($arrPondsAquaculturesNew);

            }

            $record = DB::table('ponds_aquacultures')
                                ->where('ebi_aquacultures_id',$parentPond->ebi_aquacultures_id)
                                ->where('status',0)
                                ->count();
            
            if($record==0){

                DB::table('ebi_aquacultures')
                    ->where('id',$parentPond->ebi_aquacultures_id)
                    ->update([
                        'status'=>$request['pond-aquacultures-status'],
                    ]);

            }

            
            CRUDBooster::redirect(CRUDBooster::adminPath('shrimpMigration?pondId='.$pondBeforeTakingOver), trans("ebi.alert_shrimp_migration_success"), 'success');

        }catch(\Exception $e){
            Log::error($e->getMessage().$e->getTraceAsString());
            CRUDBooster::redirect(CRUDBooster::adminPath('shrimpMigration?pondId='.$pondBeforeTakingOver), trans("ebi.alert_shrimp_migration_failed"), 'danger');
        }
    }
}
