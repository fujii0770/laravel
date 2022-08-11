<?php

namespace App\Http\Controllers\Frontend;

// use Illuminate\Http\Request;
use CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Request;
use Session;
use Carbon\Carbon;


class FarmController extends FrontendBaseController
{
    //
    public function settingByFarm(){
        $farmList = DB::table('ebi_farms')->select('id','farm_name','farm_name_en')->get();
        return view("frontend/setting_by_farm", compact('farmList'));
    }
    public function createFarm(){
        try{
            $farmName = Request::get('farm_name');
            $validator = Validator::make(Request::all(), ['farm_name' => 'required|unique:ebi_farms|max:50']);
            if ($validator->fails()) {
                $message = $validator->messages();
                $message_all = $message->all();
                CRUDBooster::redirect(CRUDBooster::adminPath('farm/settingByFarm'), $message);
            }
            $countFarm =  DB::table('ebi_farms')->count();
            if($countFarm >= 6){
                CRUDBooster::redirect(CRUDBooster::adminPath('farm/settingByFarm'), trans("ebi.養殖場の最大数は6つです、もっと追加できません。"));
            }
            DB::table('ebi_farms')->insert([
                'farm_name'=>$farmName,
                'farm_name_en'=>$farmName,
                'country_id'=>3
            ]);
            Session::forget('my_farm');
            return 1;
        }catch(\Exception $e){
            Log::error($e->getMessage(). $e->getTraceAsString());
            return 0;
        }
    }

    public function updateFarm()
    {
        try{
            $farmName = Request::get('farm_name');
            $farmNameCurrent =  DB::table('ebi_farms')->where('id',Request::get('id'))->value('farm_name');
            if($farmNameCurrent != $farmName){
                $validator = Validator::make(Request::all(), ['farm_name' => 'required|unique:ebi_farms|max:50']);
                if ($validator->fails()) {
                    $message = $validator->messages();
                    $message_all = $message->all();
                    CRUDBooster::redirect(CRUDBooster::adminPath('farm/settingByFarm'), $message);
                }
    
                DB::table('ebi_farms')
                ->where('id',Request::get('id'))
                ->update([
                    'farm_name'=>$farmName,
                    'farm_name_en'=>$farmName,
                    'country_id'=>2
                ]);
            }
            Session::forget('my_farm');
            return 1;
        }catch(\Exception $e){
            Log::error($e->getMessage(). $e->getTraceAsString());
            return 0;
        }
    }

    public function deleteFarm(){
        try{
            $farmId = Request::get('id');
            $countEbiAquacultures = DB::table('ebi_aquacultures')->where('farm_id',$farmId)->count();
            if($countEbiAquacultures){
                CRUDBooster::redirect(CRUDBooster::adminPath('farm/settingByFarm'), trans("ebi.養殖中なので、削除できません。"), 'danger');
            }  
            $countYearsReport = DB::table('years_report')->count();          
            if($countYearsReport){
                CRUDBooster::redirect(CRUDBooster::adminPath('farm/settingByFarm'), trans("ebi.使用中の養殖場は削除できません。"), 'danger');
            }

            $countPondAquacultures = DB::table('ebi_ponds')
                        ->join('ponds_aquacultures', 'ebi_ponds.id', '=', 'ponds_aquacultures.ebi_ponds_id')
                        ->where('ponds_aquacultures.status',0)
                        ->where('ebi_ponds.farm_id', $farmId)
                        ->count();
            if($countPondAquacultures){
                CRUDBooster::redirect(CRUDBooster::adminPath('farm/settingByFarm'), trans("ebi.養殖場は池が養殖中なので、削除できません。"), 'danger');
            }

            DB::table('ebi_ponds')
                ->where('farm_id',$farmId)
                ->update([
                    'farm_id'=>null,
                    'updated_user' => CRUDBooster::myId(),
                    'updated_at' => Carbon::now()
                ]);   
        
            DB::table('ebi_user_farms')
                ->where('farm_id',$farmId)
                ->delete();
                
            DB::table('ebi_farms')
                ->where('id',$farmId)
                ->delete();    
        
            return 1;
        }catch(\Exception $e){
            Log::error($e->getMessage(). $e->getTraceAsString());
            return 0;
        }
    }

    public function getFarmLastAdd(){
        $farmList = DB::table('ebi_farms')->select('id','farm_name','farm_name_en')->orderBy('id', 'DESC')->first();
        $countFarm = DB::table('ebi_farms')->count();
        $data['farmList'] = $farmList;
        $data['countFarm'] = $countFarm;
        echo json_encode($data);
    }
    
    public function addPondsByFarms(){
        try{
            $pondName = Request::get('name');
            DB::table('ebi_ponds')
                ->where('id',Request::get('pondId'))
                ->update(['farm_id'=>Request::get('farmId')]);
            return 1;
        }catch(\Exception $e){
            Log::error($e->getMessage(). $e->getTraceAsString());
            return 0;
        }
    }
}
