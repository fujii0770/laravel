<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 1/4/2019
 * Time: 9:22 AM
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Helper;

class SimulationController extends Controller
{
    public function index () {
       
        return view('simulation')->with($data);
    }
    public function calculation (Request $request){
        $cost=0;
        $cost += $request->get("food_amount");
        $cost += $request->get("medicine_amount");
        $cost += $request->get("power_consumption");
        $cost += $request->get("anothercost");
        $ebiprice=DB::table('ebi_kind')->where('id', $request->get("kind"))->select('price')->get();//稚エビ単価

        
        $cost += $request->get("shrimp_num")*$ebiprice/200000;
        $amount=$request->get("shrimp_num");
        $sell=0;
        for($i=1;$i<=$request->get("target_weight"); $i++){
            $total=$i * $amount;
            if($total>=4200000){
                $price=DB::table('ebi_price')->where('weight',$i)->value('ebi_price');
                if($price or $i>25){
                    if($i>25){
                        $over=($i-25)*10;
                        $price=DB::table('ebi_price')->where('weight',25)->value('ebi_price');
                        $totalprice=$price+$over;
                        $sell += $totalprice*1000;
                    }else{
                        $price=DB::table('ebi_price')->where('weight',$i)->value('ebi_price');
                        $sell += $price*1000;
                    }

                    (int)$num=1000*1000/$i;
                    $amount=$amount-$num;
                }
            }
        
        }
        if($i>25){
            $over=($i-25)*10;
            $price=DB::table('ebi_price')->where('weight',25)->value('ebi_price');
            $totalprice=$price+$over;
            $sell += $totalprice*$amount*$i/1000;
        }else{
            for($c=$i;$c<=25;$c++){
                $price=DB::table('ebi_price')->where('weight',$c)->value('ebi_price');
                if($price){

                    $sell += $price*$amount*$c/1000;
                    break;
                    
                }
            }
        }

        (int)$sell=$sell*(int)$request->get("servival_rate")/100-$cost;

        
        
        $data['sell']= (int)$sell;
        return view('simulation')->with($data);
    }
    
}