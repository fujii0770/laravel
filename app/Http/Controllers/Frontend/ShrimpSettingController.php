<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendBaseController;
use Illuminate\Support\Facades\DB;

class ShrimpSettingController extends FrontendBaseController
{
    public function ViewAquacultureMethod()
    {
        $aquaMethods = DB::table('aquaculture_method')->get();
        $page_title = trans('ebi.養殖方法登録');
        return view("frontend/aquaculture_method", compact('aquaMethods', 'page_title'));
    }
}
