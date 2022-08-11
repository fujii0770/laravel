<?php

namespace App\Http\Controllers;

use CRUDBooster;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Session;

class AdminMonthreport extends Controller
{
    public function month_report(Request $request){

        $today =now();
        if($request->has("month")){

            $year=$request->get("year");
            $month2=$request->get("month");
            if($month2<10){
                $month2="0$month2";
            }
            $month="$year-$month2";
            log::debug("確認 $month");

        }else{
            $month=$today->format('Y-m');
            $month2=$today->format('m');
            $year=$today->format('Y');
            
        }
        

        
        $shipments=DB::table('shipment')
        ->where('harvest_date', 'LIKE',"%$month%")
        ->where('cold_flg',0)
        ->get();
        log::debug("確認 $month");

        $amount=array();
        $num=array();
        $ebi_weight=array();
        $i=0;
        $total_sell=0;
        $totalnum=0;
        $totalamount=0;
        log::debug('確認1');
        if($shipments){

            foreach($shipments as $shipment){

                $amount[$shipment->ebi_weight] +=$shipment->amount;
                $num[$shipment->ebi_weight] +=$shipment->num;
                $sell[$shipment->ebi_weight] +=$shipment->sell;
                
                if(!array_keys($ebi_weight,$shipment->ebi_weight)){
                    $ebi_weight[$i]=$shipment->ebi_weight;
                    $i++;
                    
                }
                

                $total_sell+=$shipment->sell;
                $totalamount+=$shipment->amount;
                $totalnum+=$shipment->num;

            }
           
        }
        
        
        $ebi_price=DB::table('ebi_aquacultures')
        ->where('start_date', 'LIKE',"%$month%")
        ->sum('ebi_price');

        $cost=DB::table('cost')
        ->where('date', 'LIKE',"%$month%")
        ->sum('cost');

        $ebi_bait_inventories_ids=DB::table('ebi_bait_inventories')->get();
    
        $feed_ids=array();
        $drug_ids=array();
        
        foreach($ebi_bait_inventories_ids as $id){
            if($id->kind==1){

                $drug_ids[]=$id->id;
                $total_drug+=DB::table('ebi_baits')
                ->where('bait_at', 'LIKE',"%$month%")
                ->where('ebi_bait_inventories_id',$id->id)
                ->sum('bait_cost');
                


            }else{
    
                $feed_ids[]=$id->id;
                $total_feed+=DB::table('ebi_baits')
                ->where('bait_at', 'LIKE',"%$month%")
                ->where('ebi_bait_inventories_id',$id->id)
                ->sum('bait_cost');

            }

        }
  
        $cold_shipments=DB::table('cold_sell')
        ->where('trade_date', 'LIKE',"%$month%")
        ->get();

        $cold_amount=array();
        $cold_num=array();
        $cold_ebi_weight=array();
        $cold_sell=array();
        $b=0;

        foreach($cold_shipments as $cold_shipment){

            $cold_amount[$cold_shipment->ebi_weight] +=$cold_shipment->amount;
            $cold_num[$cold_shipment->ebi_weight] +=(($cold_shipment->amount*1000)/$cold_shipment->ebi_weight);
            $cold_sell[$cold_shipment->ebi_weight] +=$cold_shipment->sell;
          
            if(!array_keys($cold_ebi_weight,$cold_shipment->ebi_weight)){
                $cold_ebi_weight[$b]=$cold_shipment->ebi_weight;
                $b++;
                
            }

            $total_sell+=$cold_shipment->sell;
            $totalamount+=$cold_shipment->amount;
            $totalnum+=(($cold_shipment->amount*1000)/$cold_shipment->ebi_weight);
                
        }
        
        Log::debug($b);

        if($totalnum==0){

            $mbw=0;

        }else{

            $mbw=$totalamount*1000/$totalnum;
        }
        


        return view("admin_pond_aquacultures_index",['total_sell'=>$total_sell,'totalamount'=>$totalamount,'totalnum'=>$totalnum,'sell'=>$sell,'ebi_weight'=>$ebi_weight,'total_drug'=>$total_drug,'total_feed'=>$total_feed,
        'cold_amount'=>$cold_amount,'cold_num'=>$cold_num,'cold_sell'=>$cold_sell,'ebi_price'=>$ebi_price,'cost'=>$cost,'mbw'=>$mbw,
        'i'=>$i,'b'=>$b,'year'=>$year,'month'=>$month2,'amount'=>$amount,'cold_ebi_weight'=>$cold_ebi_weight]);
    }

}
