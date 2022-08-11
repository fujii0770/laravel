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

class PondRecalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ponds_report';

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
        $pond_reports=DB::table('ponds_aquacultures')->where('status',0)->get();

        foreach($pond_reports as $pond_report){
            $syusi=0;
            $id=$pond_report->ebi_aquacultures_id;

            if($pond_report->takeover_ponds_id==""){

                $ebi_price=DB::table('ebi_aquacultures')->where('id',$id)->value("ebi_price");
                $syusi-=$ebi_price;

            }

            $syusi+=$pond_report->sell-$pond_report->feed_cumulative-$pond_report->medicine_cumulative;


            DB::table('ponds_aquacultures')
            ->where('id',$pond_report->id)
            ->update([
                'income_and_expenditure' => $syusi
            ]);

            echo "$pond_report->id 完了";



        }

       

            
    }


}
