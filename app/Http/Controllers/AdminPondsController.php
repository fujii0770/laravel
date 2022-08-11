<?php namespace App\Http\Controllers;

	use CRUDBooster;
    use DB;
    use Illuminate\Support\Facades\Route;
    use Request;
    use Session;

    class AdminPondsController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "pond_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = false;
            $this->form_layout_two = true;
			$this->table = "ebi_ponds";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans("ebi.養殖場名"),"name"=>"farm_id","join"=>"ebi_farms,farm_name"];
			$this->col[] = ["label"=>trans("ebi.養殖池名"),"name"=>"pond_name"];
			$this->col[] = ["label"=>trans("ebi.水量"),"name"=>"water_amount"];
			$this->col[] = ["label"=>trans("ebi.横幅"),"name"=>"pond_width"];
			$this->col[] = ["label"=>trans("ebi.水深"),"name"=>"water_dept"];
			$this->col[] = ["label"=>trans("ebi.縦幅"),"name"=>"pond_vertical_width"];
			$this->col[] = ["label"=>trans("ebi.養殖方法"),"name"=>"pond_method"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$id = $this->getItemId();
			$default_ponds = DB::table('default_ponds')->first();
			$farm_disabled = '';
			if ($id){
                $farm_disabled = 'disabled';
            }
            // $this->form[] = ['label'=>trans("ebi.養殖場名"),'name'=>'farm_id','type'=>'select2','disabled'=> $farm_disabled,'validation'=>'required|integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			
			$this->form[] = ['label'=>trans("ebi.養殖池名"),'name'=>'pond_name','type'=>'text','validation'=>'string|required|regex:/[^\s　]/|min:1|max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.北西"),'name'=>'lat_long_nw','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'value'=>$id?$current_pond->lat_long_nw:$default_ponds->lat_long_nw];

			$this->form[] = ['label'=>trans("ebi.水量"),'name'=>'water_amount','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->water_amount:$default_ponds->water_amount];
            $this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.北東"),'name'=>'lat_long_ne','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->lat_long_ne:$default_ponds->lat_long_ne];

			$this->form[] = ['label'=>trans("ebi.横幅"),'name'=>'pond_width','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4','datatable'=>'ebi_farms,farm_name', 'form_layout' => 'two','value'=>$id?$current_pond->pond_width:$default_ponds->pond_width];
            $this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.南西"),'name'=>'lat_long_sw','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->lat_long_sw:$default_ponds->lat_long_sw];

			$this->form[] = ['label'=>trans("ebi.水深"),'name'=>'water_dept','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->water_dept:$default_ponds->water_dept];
            $this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.南東"),'name'=>'lat_long_se','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->lat_long_se:$default_ponds->lat_long_se];

            $this->form[] = ['label'=>trans("ebi.縦幅"),'name'=>'pond_vertical_width','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->pond_vertical_width:$default_ponds->pond_vertical_width];
            $this->form[] = ['label'=>trans("ebi.タグ").'1','name'=>'tag1','type'=>'text','validation'=>'max:255|unique:ebi_ponds,tag1|unique:ebi_ponds,tag2|unique:ebi_ponds,tag3|unique:ebi_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->tag1:$default_ponds->tag1];

            $this->form[] = ['label'=>trans("ebi.養殖方法"),'name'=>'pond_method','type'=>'select2','width'=>'col-sm-8', 'label_width' => 'col-sm-4','dataenum'=>"0|".trans('ebi.レースウェイ').";1|".trans('ebi.クラシック').";2|".trans('ebi.バケツ').";3|".trans('ebi.バイオフロック'), 'form_layout' => 'two','value'=>$id?$current_pond->pond_method:$default_ponds->pond_method];
            $this->form[] = ['label'=>trans("ebi.タグ").'2','name'=>'tag2','type'=>'text','validation'=>'max:255|unique:ebi_ponds,tag1|unique:ebi_ponds,tag2|unique:ebi_ponds,tag3|unique:ebi_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->tag2:$default_ponds->tag2];

            $this->form[] = ['label'=>trans("ebi.地図コード"),'name'=>'pond_image_area','type'=>'text','readonly'=>true,'validation'=>'max:50', 'width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'value'=>$id?$current_pond->pond_image_area:Request::get('elId')];
            $this->form[] = ['label'=>trans("ebi.タグ").'3','name'=>'tag3','type'=>'text','validation'=>'max:255|unique:ebi_ponds,tag1|unique:ebi_ponds,tag2|unique:ebi_ponds,tag3|unique:ebi_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->tag3:$default_ponds->tag3];

            $this->form[] = ['label'=>trans("ebi.測定範囲のバッファ"),'name'=>'delta_measure','type'=>'text','validation'=>'numeric|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->delta_measure:$default_ponds->delta_measure];
			$this->form[] = ['label'=>trans("ebi.タグ").'4','name'=>'tag4','type'=>'text','validation'=>'max:255|unique:ebi_ponds,tag1|unique:ebi_ponds,tag2|unique:ebi_ponds,tag3|unique:ebi_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->tag4:$default_ponds->tag4];

			$this->form[] = ['label'=>trans("ebi.稚エビ投入数"),'name'=>'ebi_amount','type'=>'number',"min"=>"0",'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two','value'=>$id?$current_pond->ebi_amount:$default_ponds->ebi_amount];
			$this->form[] = ['label'=>trans("ebi.図面の配置番号"),'name'=>'area','type'=>'number',"min"=>"0",'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			// $this->form[] = ['label'=>trans("ebi.稚エビ種類"),'name'=>'ebi_kind_id','type'=>'select','validation'=>'integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'datatable'=>'ebi_kind,kind'];			

			$this->form[] = ['label'=>trans("ebi.池用途"),'name'=>'ponds_kind_id','type'=>'select','validation'=>'integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'datatable'=>'ponds_kind,pond_kind','value'=>$id?$current_pond->ponds_kind_id:$default_ponds->ponds_kind_id];
			//$this->form[] = ['label'=>trans("ebi.ナノバブル"),'name'=>'nano_bubble','type'=>'select','validation'=>'integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'datatable'=>'ebi_ponds,nano_bubble','value'=>$id?$current_pond->nano_bubble:$default_ponds->nano_bubble];
			$this->form[] = ['label'=>trans("ebi.ナノバブル"),'name'=>'pond_method','type'=>'select2','width'=>'col-sm-8', 'label_width' => 'col-sm-4','dataenum'=>"0|".trans('ebi.なし').";1|".trans('ebi.ナノバブル'), 'form_layout' => 'two','value'=>$id?$current_pond->nano_bubble:$default_ponds->nano_bubble];
			
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
            if ($this->isListView){
                $this->script_js = null;
            }else{
                $id = $this->getItemId();
                $selectedFarmId = null;
                if ($id){
                    $pond = DB::table('ebi_ponds')->where('id', $id )->first();
                    if ($pond){
                        $selectedFarmId = $pond->farm_id;
                    }
                }
                $this->script_js = "
					var selectedFarmId = '".$selectedFarmId."';
                    $(function () {
						$('input[name=\"submit\"]').click(function(){
							var amountString  = $('input[name=\"ebi_amount\"]').val();
							amountString = Number(amountString).toString();	
							$('input[name=\"ebi_amount\"]').val(amountString);

							var areaString  = $('input[name=\"area\"]').val();						
							areaString = Number(areaString).toString();
							$('input[name=\"area\"]').val(areaString);
						});
                        $.ajax({
                            url: '".CRUDBooster::adminPath('myFarm')."',
                            type: 'get',
                            dataType: 'json',
                            success: function (data) {
                                for (var i = 0; i < data.length; i++) {
                                    var newOption = null;
                                    if (selectedFarmId == data[i].id){
                                        newOption = new Option(data[i].name, data[i].id, false, true);
                                    }else{
                                        newOption = new Option(data[i].name, data[i].id, false, false);
                                    }
                                    $('#farm_id').append(newOption);
                                }
                                $('#farm_id').trigger('change');
                                $('#farm_id').trigger('select2:select');
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        });
                        
                    });
                ";
            }


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {   
	        if($postdata['delta_measure']){
				$postdata['delta_measure'] = (float)$postdata['delta_measure'];
			}
			$this->validatePostData($postdata, null);
		}

	    private function validatePostData($postdata, $id){     
			$arrTag1 = [$postdata['tag2'],$postdata['tag3'],$postdata['tag4']];
			$arrTag2 = [$postdata['tag1'],$postdata['tag3'],$postdata['tag4']];
			$arrTag3 = [$postdata['tag1'],$postdata['tag2'],$postdata['tag4']];
			$arrTag4 = [$postdata['tag1'],$postdata['tag2'],$postdata['tag3']];
       
            if(strlen($postdata['delta_measure']) > 0 && trim($postdata['delta_measure']) === '') {
                $this->fireErrorMessage('delta_measure', trans('ebi.正しい形式の測定範囲のバッファを指定してください'));
			}

			if(trim($postdata['tag1']) && in_array($postdata['tag1'], $arrTag1)){
				$this->fireErrorMessage('tag1', trans('ebi.このタグが複製してはいけません'));
			}			
			if(trim($postdata['tag2']) && in_array($postdata['tag2'], $arrTag2)){
				$this->fireErrorMessage('tag2', trans('ebi.このタグが複製してはいけません'));
			}			
			if(trim($postdata['tag3']) && in_array($postdata['tag3'], $arrTag3)){
				$this->fireErrorMessage('tag3', trans('ebi.このタグが複製してはいけません'));
			}			
			if(trim($postdata['tag4']) && in_array($postdata['tag4'], $arrTag4)){
				$this->fireErrorMessage('tag4', trans('ebi.このタグが複製してはいけません'));
			}

			$this->validateSpace($postdata['delta_measure'], 'delta_measure', trans('ebi.測定範囲のバッファ'));
			$this->validateSpace($postdata['water_amount'], 'water_amount', trans('ebi.水量'));
            $this->validateSpace($postdata['pond_width'], 'pond_width', trans('ebi.横幅'));
            $this->validateSpace($postdata['water_dept'], 'water_dept', trans('ebi.水深'));
            $this->validateSpace($postdata['pond_vertical_width'], 'pond_vertical_width', trans('ebi.縦幅'));
            $this->validateSpace($postdata['pond_name'], 'pond_name', trans('ebi.縦幅'));
            $this->validateLatLongGPS($postdata['lat_long_nw'] , 'lat_long_nw', trans('ebi.North_west'));
            $this->validateLatLongGPS($postdata['lat_long_ne'], 'lat_long_ne', trans('ebi.North_east'));
            $this->validateLatLongGPS($postdata['lat_long_sw'], 'lat_long_sw', trans('ebi.South_west'));
            $this->validateLatLongGPS($postdata['lat_long_se'], 'lat_long_se', trans('ebi.South_east'));
            
            $overlappedPonds = $this->getOverlappedPondsByGPS($postdata, $id);
            $hasOvelappedWithoutDelta = false;
            $hasOvelappedWithDelta = false;
            $overlappedPondWithoutDeltaNames = array();
            $overlappedPondWithDeltaNames = array();
            foreach ($overlappedPonds as $overlappedPond){
                if ($overlappedPond->intersect1){
                    $hasOvelappedWithoutDelta = true;
                    $overlappedPondWithoutDeltaNames[] = $overlappedPond->pond_name;
                }else if ($overlappedPond->intersect2){
                    $hasOvelappedWithDelta = true;
                    $overlappedPondWithDeltaNames[] = $overlappedPond->pond_name;
                }
            }
			// 座標(緯度経度)の重複判定
           // if ($hasOvelappedWithoutDelta){
               // $this->fireErrorMessage('lat_long_nw', trans('ebi.msg_overlapped_pond_by_gps_without_delta', ['pond_names' => implode(',', $overlappedPondWithoutDeltaNames)]));
           // }else if($hasOvelappedWithDelta){
               // $this->fireErrorMessage('delta_measure', trans('ebi.msg_overlapped_pond_by_gps_with_delta', ['pond_names' => implode(',', $overlappedPondWithDeltaNames)]));
           // }
        }

        public function getIndex(){
            if (Session::get('current_pond')){
                CRUDBooster::redirect(CRUDBooster::adminPath('ponds/edit/'.Session::get('current_pond')), '');
            }else{
                CRUDBooster::redirect(CRUDBooster::adminPath(), '');
            }
        }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {    
	        //Your code here
            if (!$this->checkFarmPermissionOnPond($id)){
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
			}
			
			// dd($postdata);
			if($postdata['delta_measure']){
				$postdata['delta_measure'] = (float)$postdata['delta_measure'];
			}
            $this->validatePostData($postdata, $id);
            unset($postdata['farm_id']);
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

        protected function hook_query_select(&$result) {
            if(!CRUDBooster::isSuperadmin()){
                $myFarms = $this->getMyFarms();

                $result->whereIn('ebi_farms.id', $myFarms);
            }
        }

        public function getEdit($id){
            if (!$this->checkFarmPermissionOnPond($id)){
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
            }
			$this->cbLoader();
            $row = DB::table($this->table)->where($this->primary_key, $id)->first();

            if (!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_edit == false) {
                CRUDBooster::insertLog(trans("crudbooster.log_try_edit", [
                    'name' => $row->{$this->title_field},
                    'module' => CRUDBooster::getCurrentModule()->name,
                ]));
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
            }
            $this->hook_query_edit($row);

            $page_menu = Route::getCurrentRoute()->getActionName();
            $page_title = trans("ebi.養殖池に関する基本情報", ['module' => CRUDBooster::getCurrentModule()->name, 'name' => $row->{$this->title_field}]);
            $farm = DB::table('ebi_farms')->where('id', $row->farm_id)->first();
            $page_sub_title = $farm->farm_name.$row->pond_name;
            $command = 'edit';
            Session::put('current_row_id', $id);

            return view($this->getEditViewName(), compact('id', 'row', 'page_menu', 'page_title', 'command', 'page_sub_title'));
		}

	    //By the way, you can still create your own method in here... :)
        private function getOverlappedPondsByGPS($postdata, $id = null){
            $lat_long_nw = $postdata['lat_long_nw'];
            $lat_long_ne = $postdata['lat_long_ne'];
            $lat_long_sw = $postdata['lat_long_sw'];
            $lat_long_se = $postdata['lat_long_se'];
            $delta_measure_unit = Helper::DELTA_MEASURE_UNIT;
            $delta_measure = floatval($postdata['delta_measure']);
            
            $lat_long_nw = explode(",", $lat_long_nw);            
            $lat_long_ne = explode(",", $lat_long_ne);            
            $lat_long_sw = explode(",", $lat_long_sw);            
            $lat_long_se = explode(",", $lat_long_se);
            
            $command = DB::table('ebi_ponds');
            if($id) {
                $command->where("id","<>", $id);
            }
             
            $rawIntersectsWithoutDelta = "ST_Intersects(
                ST_GeomFromText(
                    concat('Polygon((',                                   
                        LEFT(lat_long_nw, POSITION(',' IN lat_long_nw) - 1) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit), ' ', 
                        RIGHT(lat_long_nw, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_nw) ) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,

                        LEFT(lat_long_ne, POSITION(',' IN lat_long_ne) - 1) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ' ',
                        RIGHT(lat_long_ne, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_ne) ) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,

                        LEFT(lat_long_se, POSITION(',' IN lat_long_se) - 1) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit), ' ',
                        RIGHT(lat_long_se, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_se) ) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,


                         LEFT(lat_long_sw, POSITION(',' IN lat_long_sw) - 1) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ' ',
                         RIGHT(lat_long_sw,LENGTH(lat_long_nw) - POSITION(',' IN lat_long_sw) ) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,

                        LEFT(lat_long_nw, POSITION(',' IN lat_long_nw) - 1) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit), ' ', 
                        RIGHT(lat_long_nw, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_nw) ) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit)
                        , '))'), 0 ),
                ST_GeomFromText('POLYGON((".
                    floatval($lat_long_nw[0])." ".floatval($lat_long_nw[1]).",".
                    floatval($lat_long_ne[0])." ".floatval($lat_long_ne[1]).",".
                    floatval($lat_long_se[0])." ".floatval($lat_long_se[1]).",".
                    floatval(  $lat_long_sw[0])." ".floatval($lat_long_sw[1]).",".
                    floatval($lat_long_nw[0])." ".floatval($lat_long_nw[1]).
                    " ))', 0 ) )";

            if($delta_measure){
                $delta_measure = $delta_measure * $delta_measure_unit;
                $lat_long_nw[2] = floatval($lat_long_nw[0]) + $delta_measure;
                $lat_long_nw[3] = floatval($lat_long_nw[1]) - $delta_measure;
                $lat_long_ne[2] = floatval($lat_long_ne[0]) + $delta_measure;
                $lat_long_ne[3] = floatval($lat_long_ne[1]) + $delta_measure;
                $lat_long_sw[2] = floatval($lat_long_sw[0]) - $delta_measure;
                $lat_long_sw[3] = floatval($lat_long_sw[1]) - $delta_measure;
                $lat_long_se[2] = floatval($lat_long_se[0]) - $delta_measure;
                $lat_long_se[3] = floatval($lat_long_se[1]) + $delta_measure;

                $rawIntersectsWithDelta = " ST_Intersects(
                ST_GeomFromText(
                    concat('Polygon((',                                   
                        LEFT(lat_long_nw, POSITION(',' IN lat_long_nw) - 1) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit), ' ', 
                        RIGHT(lat_long_nw, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_nw) ) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,

                        LEFT(lat_long_ne, POSITION(',' IN lat_long_ne) - 1) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ' ',
                        RIGHT(lat_long_ne, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_ne) ) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,

                        LEFT(lat_long_se, POSITION(',' IN lat_long_se) - 1) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit), ' ',
                        RIGHT(lat_long_se, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_se) ) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,


                         LEFT(lat_long_sw, POSITION(',' IN lat_long_sw) - 1) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ' ',
                         RIGHT(lat_long_sw,LENGTH(lat_long_nw) - POSITION(',' IN lat_long_sw) ) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit) , ',' ,

                        LEFT(lat_long_nw, POSITION(',' IN lat_long_nw) - 1) + IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit), ' ', 
                        RIGHT(lat_long_nw, LENGTH(lat_long_nw) - POSITION(',' IN lat_long_nw) ) - IF(`delta_measure` IS NULL, 0, delta_measure * $delta_measure_unit)
                        , '))'), 0 ),
                ST_GeomFromText('POLYGON((".
                    $lat_long_nw[2]." ".$lat_long_nw[3].",".
                    $lat_long_ne[2]." ".$lat_long_ne[3].",".
                    $lat_long_se[2]." ".$lat_long_se[3].",".
                    $lat_long_sw[2]." ".$lat_long_sw[3].",".
                    $lat_long_nw[2]." ".$lat_long_nw[3].
                    " ))', 0 ) )";
               
                $command->select(['id', 'pond_name', DB::raw($rawIntersectsWithoutDelta." as intersect1"), DB::raw($rawIntersectsWithDelta." as intersect2")]);
                $command->where(function ($query) use($rawIntersectsWithoutDelta, $rawIntersectsWithDelta){
                    $query->where(DB::raw($rawIntersectsWithoutDelta),">", 0);
                    $query->orWhere(DB::raw($rawIntersectsWithDelta),">", 0);
                });
            }else{
                $command->select(['id', 'pond_name', DB::raw($rawIntersectsWithoutDelta." as intersect1"), DB::raw("0 as intersect2")]);
                $command->where(DB::raw($rawIntersectsWithoutDelta),">", 0);
            }
            return $command->get();
        }
        
        private function validateLatLongGPS($lat_long, $name = "", $title = ""){
            if(trim($lat_long)== "") return true;

            $arr_lat_long = explode(",", $lat_long);
            if(count($arr_lat_long) != 2){
                $this->fireErrorMessage($name, trans('validation.regex',['attribute'=>$title]));
            }
            
            if(!preg_match("/^-?(?:\d+|\d*\.\d+)$/", $arr_lat_long[0])){ 
                $this->fireErrorMessage($name, trans('validation.regex',['attribute'=>$title]));
            }
            
            if(!preg_match("/^-?(?:\d+|\d*\.\d+)$/", $arr_lat_long[1])){ 
                $this->fireErrorMessage($name, trans('validation.regex',['attribute'=>$title]));
            }
		}
		
		private function validateSpace($column, $name = "", $title = ""){
			if( $name == 'delta_measure' && (!preg_match("/^\d{1,3}+(\.\d{1,8}+)?$/",$column))){ 
				if(trim($column)== "") return true;
                $this->fireErrorMessage($name, trans('validation.regex',['attribute'=>$title]));
			}
			
			if(trim($column) === '') {
                $this->fireErrorMessage($name, trans('validation.regex',['attribute'=>$title]));
			}
		}
        
    }
