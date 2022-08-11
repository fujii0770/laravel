<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Guard;
use CRUDBooster;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helper;
use Mail;
use Illuminate\Support\Facades\Log;

class YearsRecalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'years_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email pond state';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $reports=DB::table('years_report')->get();
        $drug_id=DB::table('ebi_bait_inventories')->where("kind",1)->get()->toArray();
        $feed_id=DB::table('ebi_bait_inventories')->where("kind",0)->get()->toArray();

        foreach($reports as  $report){

            $first_year="$report->year-01-01 00:00:00";
            $last_year="$report->year-12-31 23:59:59";
            
            //薬費用
            $drug_cost=DB::table('ebi_baits')->whereIn("ebi_bait_inventories_id",$drug_id['id'])
            ->where('bait_at','>', $first_year)
            ->where('bait_at','<', $last_year )
            ->sum('bait_cost');

            //餌費用
            $feed_cost=DB::table('ebi_baits')->whereIn("ebi_bait_inventories_id",$feed_id['id'])
            ->where('bait_at', '>=', $first_year)
            ->where('bait_at', '<=', $last_year )
            ->sum('bait_cost');

            //稚エビ費用
            $baby_shrimp=DB::table('ebi_aquacultures')
            ->where('start_date', '>=', $first_year)
            ->where('start_date', '<=', $last_year )
            ->sum('ebi_price');

            //稚エビ投入数
            $shrimp_num=DB::table('ebi_aquacultures')
            ->where('start_date', '>=', $first_year)
            ->where('start_date', '<=', $last_year )
            ->sum('shrimp_num');

            // 通常ハーベスト売上
            $sell=DB::table('shipment')
            ->where('harvest_date', '>=', $first_year)
            ->where('harvest_date', '<=', $last_year )
            ->where('cold_flg',0)
            ->sum('sell');

            //冷凍ハーベスト売上
            $cold_sell=DB::table('cold_sell')
            ->where('trade_date', '>=', $first_year)
            ->where('trade_date', '<=', $last_year)
            ->sum('sell');

            //ハーベストされたエビの数
            $num=DB::table('shipment')
            ->where('harvest_date', '>=', $first_year)
            ->where('harvest_date', '<=', $last_year)
            ->sum('num');

            //その他費用の合計
            $cost=DB::table('cost')
            ->where('date', '>=', $first_year)
            ->where('date', '<=', $last_year)
            ->sum('cost');

            $syusi=$cold_sell+$sell-$feed_cost-$medicine_cost-$ebi_price-$cost;
           
            DB::table('years_report')->where('year',$report->year)->update([
                'income_and_expenditure' => $syusi,
                'sell' =>$sell+$cold_sell ,
                'shrimp_num' => $shrimp_num,
                'shipment_count' => $num,
                'feed_cumulative' => $feed_cost,
                'medicine_cumulative' => $medicine_cost,
                'ebi_price' => $ebi_price,
            ]);

            echo "完了";

        }


    }

}
