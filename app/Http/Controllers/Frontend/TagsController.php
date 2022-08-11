<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 1/4/2019
 * Time: 9:22 AM
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\DB;
use Request;
use App\Http\Controllers\Helper;
use Illuminate\Support\Facades\Validator;
 

class TagsController extends FrontendBaseController
{
    public function viewTags()
    {        
        $currentPondId = \Session::get('current_pond');
        $data = array();
        
        $items = DB::table('ebi_measure_point_tag')->get();
        $items_tag = new \stdClass();
        $items_tag->A = array();
        $items_tag->B = array();
        $items_tag->C = array();
        $items_tag->D = array();
        if(count($items)){
            foreach($items as $item){
                $items_tag->{$item->measure_point}[] = $item;
            }
        }     
       
        $data['items_tag'] = $items_tag;
        
        return view("frontend/view_tag", array('page_title' => trans('ebi.Tag_registration')))->with($data);
    }
    
    public function saveTags(){
        $request = Request::instance();
        
        $tag_A = $request->input('measure_point_A');
        $tag_B = $request->input('measure_point_B');
        $tag_C = $request->input('measure_point_C');
        $tag_D = $request->input('measure_point_D');
        
        $items = $items_check = array();
        
        $this->validateTag("A", $tag_A, $items, $items_check);
        $this->validateTag("B", $tag_B, $items, $items_check);
        $this->validateTag("C", $tag_C, $items, $items_check);
        $this->validateTag("D", $tag_D, $items, $items_check);
       
        DB::table('ebi_measure_point_tag')->delete();
        
        DB::table('ebi_measure_point_tag')->insert($items);
        
        return redirect(route('getViewTags'))->withSuccess(trans('crudbooster.save_changes'));
    }
    
    private function validateTag($point = "A", $items, &$items_insert, &$items_check){
        if(!$items || !count($items)) return;
        foreach($items as $item){
            if(mb_strlen($item)>12){
                $this->fireErrorMessage("measure_point_".$point,   trans('ebi.msg_tag_over_maxlength'));
            }
            $items_insert[] = array('tag_id'=> $item, 'measure_point'=>$point);
            $key = strtolower($item);
            if(array_key_exists($key, $items_check)){
                $this->fireErrorMessage("measure_point_".$point,   trans('validation.different', ['attribute'=>$point,'other'=>$items_check[$key]]));
            }
            $items_check[$key] = $point;
        }
    }
   
}