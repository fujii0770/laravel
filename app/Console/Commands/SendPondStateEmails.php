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

class SendPondStateEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_email:pond_state {fromId}';

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
        $fromId = $this->argument('fromId');
        Log::debug("SendPondStateEmails: from id = $fromId");
        try {
            if (!$fromId){
                $fromId = 0;
            }
            // get alert
            $newAlerts = DB::table('ebi_ponds as P')
                            ->rightJoin('ebi_pond_alerts as PA', 'P.id', '=', 'PA.pond_id')
                            ->leftJoin('ebi_farms as F', 'P.farm_id', '=', 'F.id')
                            ->where('PA.id','>',$fromId)
                            ->selectRaw('P.id, P.pond_name, P.farm_id, F.farm_name, PA.alert_date, PA.alert_time, PA.alert_detail')
                            ->orderByDesc('PA.alert_date')
                            ->orderByDesc('PA.alert_time')
                            ->get();                
            if(!count($newAlerts)) return;
            $arrFarmID = array();
            $arrNew = array();
            foreach($newAlerts as $newAlert){
                $arrFarmID[$newAlert->farm_id] = $newAlert->farm_id;
                $newAlert->alert_detail = json_decode($newAlert->alert_detail);
                $arrNew[$newAlert->farm_id]["$newAlert->id-$newAlert->alert_date"] = $newAlert;
            }
            $newAlerts = $arrNew;

            // get user where enable_email_alert = 1
            $listEnableMailUser = DB::table('cms_users')->select(['id','name','email','id_cms_privileges'])
                        ->where("enable_email_alert",'=',1)
                        ->get();
            if(!count($listEnableMailUser)) return;
            $arrSuperUser = array();
            $arrNew = array();
            foreach($listEnableMailUser as $user){
                if ($user->id_cms_privileges == 1) {
                    $arrSuperUser[] = $user->id;
                }
                $arrNew[$user->id] = $user;
            }
            $listEnableMailUser = $arrNew;

            // get user farm
            $arrUserFarm = DB::table('ebi_user_farms')->select(['farm_id','user_id'])
                        ->whereIn("farm_id", $arrFarmID)
                        ->get();        
            if(count($arrUserFarm)){
                $arrNew = array();
                foreach($arrUserFarm as $item){
                    $arrNew[$item->farm_id][] = $item->user_id;
                }
                $arrUserFarm = $arrNew;
            }

            // send email
            $arrLabel = Helper::getAllWaterCriteriaLabel();
            $fromEmailAddress = \Config::get('mail.from.address');
            $fromEmailName = \Config::get('mail.from.name');
         //   $bccEmailAddress = \Config::get('mail.bcc');

            foreach($newAlerts as $farmID => $farmAlerts){
                // list user
                $usersFarm = isset($arrUserFarm[$farmID])?$arrUserFarm[$farmID]: null;
                $pond_html_alerts = array();
                $farmName = null;
                foreach($farmAlerts as $pondDateAlert) {
                    $farmName = $pondDateAlert->farm_name;
                    $pond_html_alert = trans('ebi.mail_alert_pond_state_pond_name', ['pond_name' => $pondDateAlert->pond_name, 'time_alert' => date_format(Carbon::createFromFormat('Y-m-d H:i:s', $pondDateAlert->alert_date.' '.$pondDateAlert->alert_time), 'Y年m月d日H：i')]);
                    foreach ($pondDateAlert->alert_detail as $alert_detail) {
                        $pond_html_alert .= " &nbsp; &nbsp; " . $arrLabel[$alert_detail->name] . "…<b>$alert_detail->value</b><br />";
                    }
                    $pond_html_alerts[] = $pond_html_alert;
                }
                $ponds_state_body = implode("<br />", $pond_html_alerts);

                if(count($arrSuperUser)){
                    $sendingUsers = $arrSuperUser;
                }else{
                    $sendingUsers = array();
                }

                if($usersFarm){
                    foreach($usersFarm as $userID){
                        if(!isset($listEnableMailUser[$userID])) continue;
                        if(isset($arrSuperUser[$userID])) continue;
                        $sendingUsers[] = $userID;
                    }
                }
                if(count($sendingUsers)){
                    $subject = trans('ebi.mail_alert_subject',['farm_name' => $farmName]);

                    foreach($sendingUsers as $userID){
                        $user = $listEnableMailUser[$userID];

                        $html_body_mail = trans('ebi.mail_alert_pond_state_body',
                                ['user_name'=>$user->name,
                                    'farm_name'=>$farmName,
                                    'ponds_state_body'=>$ponds_state_body]);

                        Log::debug("SendPondStateEmails: send mail to - $user->email");
                        \Mail::send("emails.blank", ['content' => $html_body_mail],
                            function ($message) use ($fromEmailAddress, $fromEmailName, $subject, $user) {
                                $message->priority(1);
                                $message->from($fromEmailAddress, $fromEmailName);
                                $message->subject($subject);
                                $message->to($user->email,$user->name);
                           //     $message->bcc($bccEmailAddress,$bccEmailAddress);
                        });
                    }
                }
            }
        } catch(\Exception $e){
            Log::error($e->getMessage().$e->getTraceAsString());
        }
        Log::debug("SendPondStateEmails: finish");
    }
}
