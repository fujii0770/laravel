<?php

namespace App\Http\Controllers;

use CRUDBooster;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Log;

class AdminCulutureController extends Controller{


    public function pond(Request $request){  //池別は現在のみ idはパラメーターから取得
      
       
        $pond_records=DB::table('ebi_ponds')
        ->get();

        foreach($pond_records as $pond_record){

            $pond_name[$pond_record->id]=$pond_record->pond_name;

        }

            

        

        if($request->has('status')){

                $ponds=DB::table('ponds_aquacultures')
                ->where('status',$request->get('status'))
                ->get();

        }else{

            if($request->has('pond_name')|| !$request->get('pond_name')==""){

                $pond_id=DB::table('ebi_ponds')
                ->where('pond_name',$request->get('pond_name'))
                ->value("id");
    
                $ponds=DB::table('ponds_aquacultures')
                ->where('ebi_ponds_id',$pond_id)
                ->get();
                
            }else{

            $ponds=DB::table('ponds_aquacultures')
                ->get();
            
            }

        }

        
        $id=array();
        $a=0;
        foreach($ponds as $pond){
            $id[]=$pond->id;
            $ebi_pond[$pond->id]=$pond_name[$pond->ebi_ponds_id];
            $completed_date[$pond->id] =$pond->completed_date;
            $start_date[$pond->id] =$pond->start_date;
            $sell[$pond->id] =$pond->sell;
            $status[$pond->id] =$pond->status;
            
            $shipments=DB::table('shipment')
            ->where('ponds_aquacultures_id',$pond->id)
            ->get();

            
            $i=0;
            foreach($shipments as $shipment){
                $shipment_id[$pond->id][$i]=$shipment->id;     
                $i++;     
            }
            $shipment_times[$pond->id]=$i;
            $a++;
                        
        }
    
        return view("admin_culuture",['pond_name' =>$ebi_pond,'completed_date' =>$completed_date,'start_date' =>$start_date,'sell' =>$sell,'status' =>$status,'shipment_id' =>$shipment_id,'id' =>$id,'a' =>$a,'shipment_times' =>$shipment_times,
        'ponds' =>$pond_name]);
        //return view("admin_culuture")->with($pond_name,$completed_date,$start_date,$sell,$status,$shipment_id,$id,$a);
        
    }

    public function shipment_report(Request $request){

        $pond_records=DB::table('shipment')
        ->where('id',$request->shipment_id)
        ->get();

        foreach($pond_records as $pond_record){
           $num=$pond_record->num;
           $sell=$pond_record->sell;
           $trade_flg=$pond_record->trade_flg;

        }

        $record=DB::table('shipment')
        ->where('id',$request->shipment_id)
        ->first();
        
        $pond=DB::table('ponds_aquacultures')
        ->where('id',$record->ponds_aquacultures_id)
        ->first();

        $shipments=DB::table('shipment')
            ->where('ponds_aquacultures_id',$pond->id)
            ->get();

            
        $a=0;
        foreach($shipments as $shipment){
            $shipment_id[$a]=$shipment->id;     
            $a++;     
        }

        return view("shipment_session",['sell' =>$sell,'num' =>$num,'trade_flg' =>$trade_flg,'ebi_ponds_id' =>$pond->ebi_ponds_id,'harvest_date' =>$record->harvest_date,'ebi_weight' =>$record->ebi_weight,'amount' =>$record->amount,'pond_name' =>$pond_name,'shipment_id' =>$shipment_id,'a' =>$a,'trueship' =>$request->shipment_id,'pond_id' =>$pond->id]);
    
    }

    public function total(Request $request){

        $amount=array();
        $num=array();
        $ebi_weight=array();
        $i=0;
        $total_sell=0;
        $totalnum=0;
        $totalamount=0;

        //$request->shipment_id 養殖ID
        $records=DB::table('ponds_aquacultures')
        ->where('id', $request->id)
        ->get();

        

        foreach($records as $record){

            $start_date=$record->start_date;
            $completed_date=$record->completed_date;
            $sell=$record->sell;
            $profit=$record->sell-$record->feed_cumulative-$record->medicine_cumulative;
            $cost=$record->feed_cumulative+$record->medicine_cumulative;
            $suv=$record->shipment_count/$record->shrimp_num*100;
            $cubic_meter_num=$record->cubic_meter_num;
            $pond_buble=$record->nano_bubble;
        }

        $shipments=DB::table('shipment')
        ->where('ponds_aquacultures_id', $request->id)
        ->get();
        
        $a=0;
        $shipment_id=array();
        $shipment_sell=array();
        $cold_flg=array();
        foreach($shipments as $shipment){

            $shipment_id[$a]=$shipment->id;
            $shipment_sell[$a]=$shipment->sell;
            $cold_flg[$a]=$shipment->cold_flg;
            $a++;

        }

        $fcr=DB::table('fcr')
        ->where('ponds_aquacultures_id', $request->id)
        ->orderby('fcr_date', 'desc')
        ->first();
        
        return view("shipment_total",['sell' =>$sell,'profit' =>$profit,'start_date' =>$start_date,'completed_date' =>$completed_date,'profit' =>$profit,'cost' =>$cost,'suv' =>$suv,'shipment_id' =>$shipment_id,'shipment_sell' =>$shipment_sell,'cold_flg' =>$cold_flg,'fcr' =>$fcr->fcr,'a' =>$a,
        'ebi_ponds_id'=>$record->ebi_ponds_id,'pond_buble'=>$pond_buble,'pond_buble'=>$pond_buble,'cubic_meter_num'=>$cubic_meter_num]);

    }

}