<?php

namespace App\Http\Controllers;

use CRUDBooster;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Session;

class AdominReportController extends Controller
{
    public function all(Request $request){
        $now=date("Y");
        $i=0;
        $data=array();
        $farm_name=array();
        $syusi=array();
        $feed=array();
        $medicine=array();
        $sell=array();
        $target_syusi=array();
        $achievement=array();
        $price=array();
        $day=array();
        $times=array();
        $cost_name=array();
        $hiyou=array();
        $a=0;
     

        if($request->get('year')){
            $aquacultures=DB::table('years_report')
            ->where('year',$request->get('year'))
            ->get();

            $now=($request->get('year'));
            $start="$now-01-01";
            $end="$now-12-31";

            $costs=DB::table('cost')
            ->whereBetween('date',[$start,$end])
            ->get();
        }elseif($request->get('month')){
            
            $now=($request->get('month'));
            $start="$now-01";
            $end="$now-31";
            $aquacultures=DB::table('ebi_aquacultures')
            ->whereBetween('completed_date',[$start,$end])
            ->where('status',1)
            ->get();

            $costs=DB::table('cost')
            ->whereBetween('date',[$start,$end])
            ->get();


        }else{
            $now=date('Y');
            $aquacultures=DB::table('years_report')
            ->where('year',$now)
            ->get();

            $start="$now-01-01";
            $end="$now-12-31";

            $costs=DB::table('cost')
            ->whereBetween('date',[$start,$end])
            ->get();

        }

        if((int)$request->get('month')){
            foreach($aquacultures as $aquaculture) {
                $farm_id[$aquaculture->farm_id]=$aquaculture->farm_id;
                $farm_name[$aquaculture->farm_id]=DB::table('ebi_farms')->where('id',$aquaculture->farm_id)->value('farm_name');
                $syusi[$aquaculture->farm_id] +=$aquaculture->income_and_expenditure;
                $feed[$aquaculture->farm_id] +=$aquaculture->feed_cumulative;
                $medicine[$aquaculture->farm_id] +=$aquaculture->medicine_cumulative;
                $sell[$aquaculture->farm_id] +=$aquaculture->sell;
                $tanka=DB::table('ebi_price')->where('weight',(int)$aquaculture->target_weight)->value('ebi_price');
                if(!$tanka){
                    if((int)$aquaculture->target_weigh<=25){
                        for($i=(int)$aquaculture->target_weight;$i<=25;$i++){
                            $tanka=DB::table('ebi_price')->where('weight',$i)->value('ebi_price');
                            if($tanka){
                                break;
                            }
                        }
                    }else{
                        $tanka=DB::table('ebi_price')->where('weight',25)->value('ebi_price');
                        $over=($aquaculture->target_weight-25)*10;
                        $tanka=$tanka+$over;
                    }
                }
                $yosou=$aquaculture->target_weight*$tanka/1000*$aquaculture->shrimp_num;
                $target_syusi[$aquaculture->farm_id] += $yosou-$aquaculture->feed_cumulative-$aquaculture->medicine_cumulative-$aquaculture->ebi_price-$aquaculture->power_consumption;
                $t_syusi=$yosou-$aquaculture->feed_cumulative-$aquaculture->medicine_cumulative-$aquaculture->ebi_price-$aquaculture->power_consumption;
                $ebi_price[$aquaculture->farm_id] +=$aquaculture->ebi_price;
                $day[$aquaculture->farm_id]= $start;
    
                $data['farm_name']=$farm_name;
                $data['syusi']=$syusi;
                $data['feed']=$feed;
                $data['medicine']=$medicine;
                $data['target_syusi']=$target_syusi;
                $data['farm_name']=$farm_name;
                $data['sell']=$sell;
                $data['ebi_price']=$ebi_price;
                $data['day']=$day;
                $data['i']=$i;
                $data['farm_id']=$farm_id;
                $data['month']=$request->get('month');
                $data['year']=$request->get('year');
    
                
            
            }


        }else{
            
            foreach($aquacultures as $aquaculture) {
                $farm_id[$i]=$aquaculture->farm_id;
                $farm_name[$i]=DB::table('ebi_farms')->where('id',$aquaculture->farm_id)->value('farm_name');
                $syusi[$i]=$aquaculture->income_and_expenditure;
                $feed[$i]=$aquaculture->feed_cumulative;
                $medicine[$i]=$aquaculture->medicine_cumulative;
                $sell[$i]=$aquaculture->sell;
                $target_syusi[$i]=$aquaculture->target_syusi;
                
                $ebi_price[$i]=$aquaculture->ebi_price;
                $day[$i]=$request->get('year');
                $i++;
            }
            $data['farm_name']=$farm_name;
            $data['syusi']=$syusi;
            $data['feed']=$feed;
            $data['medicine']=$medicine;
            $data['target_syusi']=$target_syusi;
            $data['sell']=$sell;
            $data['ebi_price']=$ebi_price;
            $data['day']=$day;
            $data['i']=$i;
            $data['farm_id']=$farm_id;
            $data['month']=$request->get('month');
            $data['year']=$request->get('year');

        }
         
            foreach($costs as $cost){
                $cost_name[$a]= $cost->kind;
                $hiyou[$a]= $cost->cost;
                $a++;
    
            }
            $data['cost_name']=$cost_name;
            $data['hiyou']=$hiyou;
            $data['a']=$a;
            return view("report")->with($data);
    }

    public function farm(Request $request){
        if($request->get('year')){
            $now = (string)$request->get('year');
            $start = "$now-01-01";
            $end = "$now-12-31";
            $aquacultures_ids=DB::table('ebi_aquacultures')
            ->where('farm_id', $request->get('farm_id')) //$request->get('farm_id')はfarm_nameのリンクにパラメーターを持たせる。
            ->where('status',1)
            ->whereBetween('completed_date',[$start,$end])
            ->get();

           
        }elseif($request->get('month')){
            $now = (string)$request->get('month');
            $start = "$now-01";
            $end = "$now-31";
            $aquacultures_ids=DB::table('ebi_aquacultures')
            ->where('farm_id', $request->get('farm_id')) //$request->get('farm_id')はfarm_nameのリンクにパラメーターを持たせる。
            ->where('status',1)
            ->whereBetween('completed_date',[$start,$end])
            ->get();
            
        }elseif((int)$request->get('now')){
            $now = (string)$request->get('now');
            $aquacultures_ids=DB::table('ebi_aquacultures')
            ->where('farm_id', $request->get('farm_id')) 
            ->where('status',0)
            ->get();
        }else{
            $now=date('Y');
            $start = "$now-01-01";
            $end = "$now-12-31";
            $aquacultures_ids=DB::table('ebi_aquacultures')
            ->where('farm_id', $request->get('farm_id')) //$request->get('farm_id')はfarm_nameのリンクにパラメーターを持たせる。
            ->where('status',1)
            ->whereBetween('completed_date',[$start,$end])
            ->get();

        }
        //$id=DB::table('ebi_farms')->get('id');
        $pond_id=array();
        $play=array();
        $i=0;
        $pond_name=array();
        $syusi=array();
        $feed=array();
        $medicine=array();
        $sell=array();
        $target_syusi=array();
        $achievement=array();
        $times=array();
        $f=0;
        $ebi_price=0;
        foreach($aquacultures_ids as $aquacultures_id) { //特定のebi_farom_idを持つebi_aquacuruture.idを取得
                if($request->get('now')){
                    $pond_aquacultures=DB::table('ponds_aquacultures')
                    ->where('ebi_aquacultures_id',$aquacultures_id->id)
                    ->where('status',0)
                    ->get();

                }else{
                    $pond_aquacultures=DB::table('ponds_aquacultures')
                    ->where('ebi_aquacultures_id',$aquacultures_id->id)
                    ->where('status',1)
                    ->get();
                }
                $ebi_price +=$aquacultures_id->ebi_price;

                foreach($pond_aquacultures as $pond_aquaculture) {
                    $pond_id[$pond_aquaculture->ebi_ponds_id]=$pond_aquaculture->ebi_ponds_id;
                    $ponds=DB::table('ebi_ponds')->where('id',$pond_aquaculture->ebi_ponds_id)->select('pond_name')->get();
                    foreach($ponds as $pond) {
                    $pond_name[$pond_aquaculture->ebi_ponds_id]=$pond->pond_name;
                    }
                    $syusi[$pond_aquaculture->ebi_ponds_id] +=$pond_aquaculture->income_and_expenditure;
                    $feed[$pond_aquaculture->ebi_ponds_id] +=$pond_aquaculture->feed_cumulative;
                    $medicine[$pond_aquaculture->ebi_ponds_id] +=$pond_aquaculture->medicine_cumulative;
                    $sell[$pond_aquaculture->ebi_ponds_id] +=$pond_aquaculture->sell;
                }

                //$achievement[$i]=$syusi[$i]/$target_syusi[$i]*100;
                
        }
   


       $ids=DB::table('ebi_farms')->get();
        $data['a']=$s;
        $data['s']=$s;
        $data['sell']=$sell;
        $data['i']=$i;
        $data['pond_name']=$pond_name;
        $data['syusi']=$syusi;
        $data['feed']=$feed;
        $data['medicine']=$medicine;
        $data['pond_name']=$pond_name;
        $data['play']=$play;
        $data['ebi_price']=$ebi_price;
        $data['pond_id']=$pond_id;
        $data['farm_id']=$request->get('farm_id');
        $data['month']=$request->get('month');
        $data['year']=$request->get('year');
       

        return view("farm_report",["data" =>$data]);
    }

    public function pond(Request $request){  //池別は現在のみ idはパラメーターから取得
        //$id=DB::table('ebi_farms')->get('id');
        if($request->get('year')){
            $now = $request->get('year');

            $end = "$now-12-31";
            $aquacultures_ids=DB::table('ponds_aquacultures')
            ->where('ebi_ponds_id', $request->get('pond_id')) //$request->get('ebi_ponds_id')はfarm_nameのリンクにパラメーターを持たせる。
            ->where('status',1)
            ->whereBetween('completed_date',[$start,$end])
            ->get();
        }elseif($request->get('month')){
            $now = $request->get('month');
            $start = "$now-01";
            $end = "$now-31";
            $aquacultures_ids=DB::table('ponds_aquacultures')
            ->where('ebi_ponds_id', $request->get('pond_id')) //$request->get('ebi_ponds_id')はfarm_nameのリンクにパラメーターを持たせる。
            ->where('status',1)
            ->whereBetween('completed_date',[$start,$end])
            ->get();
            $status=1;
        
        }elseif($request->get('now')){
            $aquacultures_ids=DB::table('ponds_aquacultures')
            ->where('ebi_ponds_id', $request->get('pond_id')) //$request->get('ebi_ponds_id')はfarm_nameのリンクにパラメーターを持たせる。
            ->where('status',0)
            ->get();
            $status=0;

        }else{
            
            $aquacultures_ids=DB::table('ponds_aquacultures')
            ->where('ebi_ponds_id', $request->get('pond_id')) //$request->get('ebi_ponds_id')はfarm_nameのリンクにパラメーターを持たせる。
            ->where('status',0)
            ->get();
            $status=0;
        

        }
        $play=array();
        $i=0;
        $pond_name=array();
        $syusi=array();
        $feed=array();
        $medicine=array();
        $sell=array();
        $target_syusi=array();
        $achievement=array();
        $times=array();
        $f=0;
        $pond_id=array();
        $pond_id=array();
        $shipment_sell=array();
        $shipment_ebi_weight=array();
        $shipment_amount=array();
      

                foreach($aquacultures_ids as $aquacultures_id) { //ebi_aquacuruture.idをもつponds_aquaculturesのレコード
                    $pond_id[$aquacultures_id->ebi_ponds_id]=$aquacultures_id->ebi_ponds_id;
                    $pond_name[$aquacultures_id->ebi_ponds_id]=DB::table('ebi_ponds')->where('id', $aquacultures_id->ebi_ponds_id)->value('pond_name');
                    $syusi[$aquacultures_id->ebi_ponds_id] +=$aquacultures_id->income_and_expenditure;
                    $feed[$aquacultures_id->ebi_ponds_id] +=$aquacultures_id->feed_cumulative;
                    $medicine[$aquacultures_id->ebi_ponds_id] +=$aquacultures_id->medicine_cumulative;
                    $sell[$aquacultures_id->ebi_ponds_id] +=$aquacultures_id->sell;
                    if($aquacultures_id->sell){
                        $price=DB::table('ebi_kind')->where('id',$aquacultures_id->$ebi_kind_id)->select('price')->get();
                        $ebi_price[$aquacultures_id->ebi_ponds_id] +=$aquacultures_id->shipment_count*$price/200000;
                    }
                    $shipments=DB::table('shipment')->where('ponds_aquacultures_id',$aquacultures_id->id)->get();
                    foreach($shipments as $shipment){
                        $shipment_sell[$f]=$shipment->sell;
                        $shipment_ebi_weight[$f]=$shipment->ebi_weight;
                        $shipment_amount[$f]=$shipment->amount;
                        $f++;
                    }

        
                }
        $data['sell']=$sell;
        $data['i']=$i;
        $data['pond_name']=$pond_name;
        $data['syusi']=$syusi;
        $data['feed']=$feed;
        $data['medicine']=$medicine;
        $data['farm_name']=$farm_name;
        $data['pond_id']=$pond_id;
        $data['status']=$status;
        $data['shipment_sell']=$shipment_sell;
        $data['shipment_ebi_weight']=$shipment_ebi_weight;
        $data['shipment_amount']=$shipment_amount;
        $data['f']=$f;
        return view("pond_report")->with($data);
        
    }
    public function cost(){

    return view("cost");

    }

    public function costadd(Request $request){
        DB::table('cost')->insert([
            'cost' =>$request->get("cost"),
            'kind' => $request->get("kind"),
            'date' => $request->get("date"),
            'created_at' => now(),

        ]);
        

        CRUDBooster::redirect(CRUDBooster::adminPath('cost_add'), trans("crudbooster.alert_add_data_success"), 'success');
    
    }

    public function sell(Request $request){
        $data["pond_id"]=$request->get("pond_id");
        $data["pond_name"]=$request->get("pond_name");
        return view("sell")->with($data);
    
    }
    
    public function selladd(Request $request){

        $id=DB::table('ponds_aquacultures')
            ->where('status',0)
            ->where('ebi_ponds_id',$request->get("pond_id"))
            ->value("id");

        $ebi_aquacultures_id=DB::table('ponds_aquacultures')
            ->where('status',0)
            ->where('ebi_ponds_id',$request->get("pond_id"))
            ->value("ebi_aquacultures_id");

            $farm_id=DB::table('ebi_aquacultures')
            ->where('status',0)
            ->where('id',$ebi_aquacultures_id)
            ->value("farm_id");


            
        $num=$request->get("amount")*1000/$request->get("weight");

        DB::table('shipment')->insert([
                'ponds_aquacultures_id' =>$id,
                'num' => $num,
                'sell' => $request->get("sell"),
                'amount' => $request->get("amount"),
                'ebi_weight' => $request->get("weight"),
                'harvest_date' => $request->get("date"),
                'cold_flg' => 0,
                'trade_flg' => $request->get("trade"),
                'created_at' => now(),
        ]);

        $year=substr($request->get("date"),0,4);


        $sell=DB::table('years_report')
            ->where("year", $year)
            ->value("sell");
            $sell +=$request->get("sell");

        $syusi=DB::table('years_report')
            ->where("year",$year)
            ->value("income_and_expenditure");
            $syusi +=$request->get("sell");



        $shipment_count=DB::table('years_report')
            ->where("year",$year)
            ->value("shipment_count");

            $shipment_count= $shipment_count+$num;

            DB::table('years_report')
            ->where("year", $year)
            ->update([
                'sell' => $sell,
                'income_and_expenditure'=> $syusi,
                'shipment_count'=> $shipment_count
            ]);

        $id=DB::table('ponds_aquacultures')->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->value("ebi_aquacultures_id");

        $sell=DB::table('ponds_aquacultures')->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->value("sell");
                $sell +=$request->get("sell");

        $syusi=DB::table('ponds_aquacultures')->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->value("income_and_expenditure");
                $syusi +=$request->get("sell");

        $shipment_count=DB::table('ponds_aquacultures')->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->value("shipment_count");
                $shipment_count=$shipment_count+$num;
           

            if($request->get("status")==1){

                DB::table('ponds_aquacultures')
                ->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->update([
                    'status' => 1,
                    'completed_date'=> $request->get("date"),
                    'sell' => $sell,
                    'income_and_expenditure'=> $syusi,
                    'shipment_count'=> $shipment_count,
                ]);
                

                $sell=DB::table('ebi_aquacultures')
                ->where('id',$id)
                ->value("sell");
                $sell +=$request->get("sell");

                $syusi=DB::table('ebi_aquacultures')
                ->where('id',$id)
                ->value("income_and_expenditure");
                $syusi +=$request->get("sell");

                $hantei=DB::table('ponds_aquacultures')->where('status',0)
                ->where('ebi_aquacultures_id',$id)
                ->value("id");

            

                if(!$hantei){

                    $shipment_count=DB::table('ebi_aquacultures')
                    ->where('id',$id)
                    ->value("shipment_count");
                    $shipment_count=$shipment_count+$num;

                    DB::table('ebi_aquacultures')->where('id',$id)
                    ->update([
                    'status' => 1,
                    'completed_date'=> $request->get("date"),
                    'sell' => $sell,
                    'income_and_expenditure'=> $syusi,
                    'shipment_count'=> $shipment_count,
                    ]);
                    

                }else{
                    $shipment_count=DB::table('ebi_aquacultures')
                    ->where('id',$id)
                    ->value("shipment_count");
                    $shipment_count=$shipment_count+$num;

                    DB::table('ebi_aquacultures')
                    ->where('id',$id)
                    ->update([
                        'sell' => $sell,
                        'income_and_expenditure'=> $syusi,
                        'shipment_count'=> $shipment_count,
                     ]);
                    
                }

            }else{

                DB::table('ponds_aquacultures')
                ->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->update([
                    'sell' => $sell,
                    'income_and_expenditure'=> $syusi,
                    'shipment_count'=> $shipment_count,
                ]);

                $sell=DB::table('ebi_aquacultures')
                ->where('id',$id)
                ->value("sell");
                $sell +=$request->get("sell");

                $syusi=DB::table('ebi_aquacultures')
                ->where('id',$id)
                ->value("income_and_expenditure");
                $syusi +=$request->get("sell");

                $shipment_count=DB::table('ebi_aquacultures')->where('status',0)
                    ->where('id',$id)
                    ->value("shipment_count");
                    $shipment_count=$shipment_count+$num;

                DB::table('ebi_aquacultures')
                    ->where('id',$id)
                    ->update([
                        'sell' => $sell,
                        'income_and_expenditure'=> $syusi,
                        'shipment_count'=> $shipment_count,
                    ]);
                                  
            }    
            
            $id=$request->get("pond_id");

            if($request->get("status")==0){
        
                CRUDBooster::redirect(CRUDBooster::adminPath("report_pond?pond_id=$id"), trans("crudbooster.alert_add_data_success"), 'success');
            }else{

                CRUDBooster::redirect(CRUDBooster::adminPath("map"), trans("crudbooster.alert_add_data_success"), 'success');

            }
        }

    function cold_stock(request $request){

        $data["pond_id"]=$request->get("pond_id");
        $data["pond_name"]=$request->get("pond_name");
        return view("cold_stock_add")->with($data);

    }

    function cold_stock_add(request $request){
        DB::beginTransaction();
        try{

            $id=DB::table('ponds_aquacultures')
            ->where('status',0)
            ->where('ebi_ponds_id',$request->get("pond_id"))
            ->value("id");


            $ebi_aquacultures_id=DB::table('ponds_aquacultures')
                ->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->value("ebi_aquacultures_id");

                $farm_id=DB::table('ebi_aquacultures')
                ->where('status',0)
                ->where('id',$ebi_aquacultures_id)
                ->value("farm_id");

            $num=$request->get("amount")*1000/$request->get("weight");

            DB::table('shipment')->insert([
                'ponds_aquacultures_id' =>$id,
                'num' => $num,
                'sell' => $request->get("sell"),
                'amount' => $request->get("amount"),
                'ebi_weight' => $request->get("weight"),
                'harvest_date' => $request->get("date"),
                'trade_flg' => $request->get("trade"),
                'cold_flg' => 1,
                'created_at' => now(),
            ]);

            $check= DB::table('cold_stock')->where('ebi_weight',$request->get("weight"))->value('id');
            //冷凍処理(ストック登録)
            // create_user CRUDbooster::myId()
            if(!$check){
                //  冷凍在庫　指定のエビの重さのレコードがなければinsert
                DB::table('cold_stock')->insert([
                    'amount' => $request->get("amount"),
                    'ebi_weight' => $request->get("weight"),
                    'create_at' => now(),
                    'create_user' => CRUDbooster::myId(),
                    'update_at' => now(),
                ]);

            }else{
                $records= DB::table('cold_stock')->where('ebi_weight',$request->get("weight"))->get();

                    $amount=$request->get("amount");

                foreach($records as $record){

                    $amount+=$record->amount;
                }
                
                    
                //  冷凍在庫　指定のエビの重さのレコードが存在すればupdate
                DB::table('cold_stock')->where('ebi_weight',$request->get("weight"))->update([ 
                    'amount' => $amount,
                    'update_at' => now(),
                ]);

            }
            
            log::debug($request->get("pond_id"));
            $pond_records=DB::table('ponds_aquacultures')->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->get();

            $sell=$request->get("sell");
            $syusi=$request->get("sell");
            $shipment_count=$num;

            foreach($pond_records as $pond_record){

                $sell+=$pond_record->sell;
                $syusi +=$pond_record->income_and_expenditure;
                $shipment_count += $pond_record->shipment_count;

            }

            $ebi_records=DB::table('ebi_aquacultures')
                ->where('id',$id)
                ->get();

                $ebi_sell +=$request->get("sell");
                $ebi_syusi +=$request->get("sell");
                $ebi_shipment_count=$num;

                foreach($ebi_records as $ebi_record){

                    $ebi_sell +=$ebi_record->sell;
                    $ebi_syusi +=$ebi_record->income_and_expenditure;
                    $ebi_shipment_count +=$ebi_record->shipment_count;

                }

            if($request->get("status")==1){

                DB::table('ponds_aquacultures')
                ->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->update([
                    'status' => 1,
                    'completed_date'=> $request->get("date"),
                    'sell' => $sell,
                    'income_and_expenditure'=> $syusi,
                    'shipment_count'=> $shipment_count,
                ]);
                
                if(!$hantei){

                    DB::table('ebi_aquacultures')->where('id',$id)
                    ->update([
                    'status' => 1,
                    'completed_date'=> $request->get("date"),
                    'sell' => $ebi_sell,
                    'income_and_expenditure'=> $ebi_syusi,
                    'shipment_count'=> $ebi_shipment_count,
                    
                    ]);
                    
                }else{
                    
                    DB::table('ebi_aquacultures')
                    ->where('id',$id)
                    ->update([
                        'sell' => $ebi_sell,
                        'income_and_expenditure'=> $ebi_syusi,
                        'shipment_count'=> $ebi_shipment_count,
                    ]);
                    
                }

            }else{

                DB::table('ponds_aquacultures')
                ->where('status',0)
                ->where('ebi_ponds_id',$request->get("pond_id"))
                ->update([
                    'sell' => $sell,
                    'income_and_expenditure'=> $syusi,
                    'shipment_count'=> $shipment_count,
                ]);

                DB::table('ebi_aquacultures')
                    ->where('id',$id)
                    ->update([
                        'sell' => $sell,
                        'income_and_expenditure'=> $syusi,
                        'shipment_count'=> $shipment_count,
                    ]);
                                
            }

            $id=$request->get("pond_id");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage().$e->getTraceAsString());
            CRUDBooster::redirect(CRUDBooster::adminPath("report_pond?pond_id=$id"), trans("crudbooster.alert_add_data_failed"), 'failed');

        }    
        DB::commit();
        if($request->get("status")==0){
    
            CRUDBooster::redirect(CRUDBooster::adminPath("report_pond?pond_id=$id"), trans("crudbooster.alert_add_data_success"), 'success');
        }else{

            CRUDBooster::redirect(CRUDBooster::adminPath("map"), trans("crudbooster.alert_add_data_success"), 'success');

        }

    }

    function cold_stock_list(request $request){

        //冷凍在庫一覧
        $cold_stocks=DB::table('cold_stock')->get();

        $i=0;
        $ebi_weight=array(); //g
        $num=array(); //匹
        $amount=array(); //kg
        $total_sell=0;
        
        foreach($cold_stocks as $cold_stock){

            $ebi_weight[$i]=$cold_stock->ebi_weight;
            $num[$i]=$cold_stock->num;
            $amount[$i]=$cold_stock->amount;
            
            $price=DB::table('ebi_price')->where('weight',$ebi_weight[$i])->value('ebi_price');

            if($price or $ebi_weight[$i]>25){
                if($ebi_weight[$i]>25){
                    $over=( $ebi_weight[$i]-25)*10;
                    $price=DB::table('ebi_price')->where('weight',25)->value('ebi_price');
                    $totalprice=$price+$over;
                    $total_sell += $totalprice*$amount[$i];
                }else{
                    $total_sell += $price*$amount[$i];
                }

            }else{
                $weight=$ebi_weight[$i];

                for($b=$ebi_weight;$b<=25;$b++){

                    $price=DB::table('ebi_price')->where('weight',$b)->value('ebi_price');
                    if($price){
                        //概算金額
                        $total_sell += $price*$amount[$i];
                        break;
                    }

                }
            }            
            $i++;
        }

        return view('cold_stock_list', ['total_sell' => $total_sell,'ebi_weight' => $ebi_weight,'amount' => $amount,'i' => ($i-1)]);
   
    }

    function cold_job(request $request){

        $ebi_weight=$request->get("ebi_weight");
        $amount=$request->get("amount");
        

        return view('cold_sell', ['ebi_weight' => $ebi_weight,'amount' => $amount]);

    }   

    function cold_job_add(request $request){
        try{
            DB::table('cold_sell')->insert([
                'sell' => $request->get("sell"),
                'amount' => $request->get("amount"),
                'ebi_weight' => $request->get("weight"),
                'trade_date' => $request->get("date"),
                'trade_flg' => $request->get("trade"),
                'create_at' => now(),
            ]);

            $cold_stocks=DB::table('cold_stock')->where('ebi_weight',$request->get("weight"))->get();

            foreach($cold_stocks as $cold_stock){

                DB::table('cold_stock')
                ->where('ebi_weight',$request->get("weight"))
                ->update([
                    'amount' => $cold_stock->amount-$request->get("amount"),
                ]);


            }

           
            $year=substr($request->get("date"),0,4);

            $reports=DB::table('years_report')->where('year',$year)->get();

            foreach($reports as  $report){
                $num=$request->get("amount")*1000/ $request->get("weight");
                DB::table('years_report')
                ->where('year',$year)
                ->update([
                    'shipment_count' => $report->income_and_expenditure+(int)$num,
                    'income_and_expenditure'=> $report->income_and_expenditure+$request->get("sell"),
                    'sell' => $report->sell+$request->get("sell"),
                ]);

            }
            
        
            $weight=$request->get("weight");

        }catch(\Exception $e){   

            CRUDBooster::redirect(CRUDBooster::adminPath("cold_sell?ebi_weight=$weight"), trans("crudbooster.alert_add_data_failed"), 'failed');
        }

        CRUDBooster::redirect(CRUDBooster::adminPath("map"), trans("crudbooster.alert_add_data_success"), 'success');



        


    }

}
