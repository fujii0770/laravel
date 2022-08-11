<?php namespace App\Http\Controllers;

	use CRUDBooster;
    use DB;
    use Illuminate\Support\Facades\App;
    use Request;
    use Session;

    class AdminAquaculturesController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_text";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = false;
			$this->button_addmore = false;
			$this->table = "ebi_aquacultures";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ['visible'=>false, "label"=>trans('ebi.養殖池'),"name"=>"pond_id","join"=>"ebi_ponds,id"];
			if((app()->getLocale() == 'en')){
				$this->col[] = ["label"=>trans('ebi.養殖場名'),"name"=>"ebi_ponds.farm_id","join"=>"ebi_farms,farm_name_en"];
			}else{
				$this->col[] = ["label"=>trans('ebi.養殖場名'),"name"=>"ebi_ponds.farm_id","join"=>"ebi_farms,farm_name"];
			}
            $this->col[] = ["label"=>trans('ebi.池名'),"name"=>"ebi_ponds.pond_name"];
			$this->col[] = ["label"=>trans('ebi.養殖期間'),"name"=>"ebi_aquacultures.estimate_shipping_date", 'callback_php' => 'App\Http\Controllers\Helper::toAquaculturePeriodLabel($row)'];
            $this->col[] = ["label"=>trans('ebi.養殖開始日'),"name"=>"ebi_aquacultures.start_date",'callback_php' => 'app()->getLocale() == "en" ?date("m.d.yy", strtotime($row->start_date)):str_replace("-",".", $row->start_date)'];
            $this->col[] = ["label"=>trans('ebi.出荷日'),"name"=>"completed_date",'callback_php' => '$row->completed_date?(app()->getLocale() == "en" ?date("m.d.yy", strtotime($row->completed_date)):str_replace("-",".", $row->completed_date)):"-"'];
			/*$this->col[] = ["label"=>"餌量(kg/ 時間)","name"=>"food_amount"];
			$this->col[] = ["label"=>"餌撒方法(回 / 日)","name"=>"feeding_num"];
			$this->col[] = ["label"=>"消費電力(Wh)","name"=>"power_consumption"];
			$this->col[] = ["label"=>"稚エビ量(匹)","name"=>"shrimp_num"];*/
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$id = $this->getItemId();
            $farm_disabled = 'disabled';
			if (!$id && !Session::get('current_pond')){
                $farm_disabled = '';
            }else{
                //$this->form[] = ['label'=>'養殖池','name'=>'pond_id','type'=>'hidden','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
            }

            $this->form[] = ["label"=>trans('ebi.養殖場'), "name"=>"farm_id","type"=>"select2",'disabled'=> $farm_disabled,'validation'=>'required|integer|min:0', 'width'=>'col-sm-10'];
            $this->form[] = ['label'=>trans('ebi.養殖池'),'name'=>'pond_id','type'=>'select2','disabled'=> $farm_disabled,'validation'=>'required|integer|min:0','width'=>'col-sm-10'];

            $this->form[] = ['label'=>trans('ebi.養殖開始日'),'name'=>'start_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.出荷予定日'),'name'=>'estimate_shipping_date','type'=>'date','validation'=>'required|date|after:start_date','width'=>'col-sm-10'];
            $this->form[] = ['label'=>trans('ebi.目標サイズ'),'name'=>'target_size','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'required|numeric|between:0.00,999999.99','width'=>'col-sm-10'];
            $this->form[] = ['label'=>trans('ebi.目標重量'),'name'=>'target_weight','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'required|numeric|between:0.00,999999.99','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.餌量'),'name'=>'food_amount','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'|numeric|between:0.00,999999.99','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.消費電力'),'name'=>'power_consumption','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'|numeric|between:0.00,999999.99','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.餌撒方法'),'name'=>'feeding_num','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.稚エビ量'),'name'=>'shrimp_num','type'=>'number','min'=> 0,'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];

			/*if ($this->getItemId()){
                $this->form[] = ['label'=>trans('ebi.出荷日'),'name'=>'completed_date','type'=>'date','validation'=>'date|after:start_date','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷数'),'name'=>'shipment_count','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'20','name'=>'shipment_standard_20','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'30','name'=>'shipment_standard_30','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'40','name'=>'shipment_standard_40','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'50','name'=>'shipment_standard_50','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'60','name'=>'shipment_standard_60','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'70','name'=>'shipment_standard_70','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'80','name'=>'shipment_standard_80','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'90','name'=>'shipment_standard_90','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'100','name'=>'shipment_standard_100','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'110','name'=>'shipment_standard_110','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'120','name'=>'shipment_standard_120','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'130','name'=>'shipment_standard_130','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'140','name'=>'shipment_standard_140','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'150','name'=>'shipment_standard_150','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'160','name'=>'shipment_standard_160','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'170','name'=>'shipment_standard_170','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'180','name'=>'shipment_standard_180','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'190','name'=>'shipment_standard_190','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
                $this->form[] = ['label'=>trans('ebi.エビ出荷規格').'200','name'=>'shipment_standard_200','type'=>'number','min'=> 0,'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-10'];
            }*/
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'','name'=>'farm_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'ebi_ponds,pond_name'];
			//$this->form[] = ['label'=>'養殖場','name'=>'pond_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'養殖開始日','name'=>'start_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'出荷予定日','name'=>'estimate_shipping_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'餌の量/日','name'=>'bait_qty_day','type'=>'money','validation'=>'min:-999999.99|max:999999.99','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'餌の撒き方','name'=>'bait_feed','type'=>'text','validation'=>'max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'稚エビの量','name'=>'power_consumption_day','type'=>'money','validation'=>'min:-999999.99|max:999999.99','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'消費電力/日','name'=>'amount_juvenile_shrimp','type'=>'number','validation'=>'max:2147483647','width'=>'col-sm-10'];
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
                $this->script_js = "
                    $(function () {
                        $('input.custom-datepicker').datepicker({
                            format: 'yyyy.mm.dd',
                            clearBtn: true,
                            autoclose: true,
                            language: 'ja',
                            isRTL: false,
                        });
                        $('#btn_add_new_data').focus();
                    });
                ";
            }else{
	            $id = $this->getItemId();
	            $selectedPondId = null;
                $selectedFarmId = null;
	            if ($id){
                    $pond = DB::table('ebi_ponds')->join('ebi_aquacultures', 'ebi_aquacultures.pond_id', '=', 'ebi_ponds.id')->select('ebi_ponds.*')->where('ebi_aquacultures.id', $id )->first();
                    if ($pond){
                        $selectedPondId = $pond->id;
                        $selectedFarmId = $pond->farm_id;
                    }
                }else {
                    if (Session::get('current_pond')) {
                        $selectedPondId = Session::get('current_pond');
                        $selectedPond = DB::table('ebi_ponds')->select('farm_id')->where('id', $selectedPondId)->first();
                        if ($selectedPond){
                            $selectedFarmId = $selectedPond->farm_id;
                        }
                    }
                }
                $this->script_js = "
                    var selectedFarmId = '".$selectedFarmId."';
                    var selectedPondId = '".$selectedPondId."';
                    $(function () {
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
                                
                        $('#farm_id').on('select2:select', function (e) {
                            $('#pond_id').empty().trigger('change');
                            if ($('#farm_id').val()) {
                            var farmId = $('#farm_id').val();
                            $.ajax({
                                url: '".CRUDBooster::adminPath('listPondByFarm?farm=')."' + farmId,
                                type: 'get',
                                dataType: 'json',
                                success: function (data) {
                                    for (var i = 0; i < data.length; i++) {
                                        var newOption = null;
                                        if (selectedPondId == data[i].id){
                                            newOption = new Option(data[i].name, data[i].id, false, true);
                                        }else{
                                            newOption = new Option(data[i].name, data[i].id, false, false);
                                        }
                                        $('#pond_id').append(newOption);
                                    }
                                    $('#pond_id').trigger('change');
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log(textStatus, errorThrown);
                                }
                            });
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
            $this->load_js = array(asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/locales/bootstrap-datepicker.'.App::getLocale().'.js'));
	        
	        
	        
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

        protected function getIndexViewName()
        {
            return 'admin_aquacultures_index';
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
	        //Your code here
            if (Session::get('current_pond')){
                $postdata['pond_id'] = Session::get('current_pond');
            }
            $this->processPostDataBeforeSave($postdata);
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
            unset($postdata['pond_id']);
            $this->processPostDataBeforeSave($postdata,$id);
	    }

	    private function processPostDataBeforeSave(&$postdata,$id = null){
            unset($postdata['farm_id']);
            if (Session::get('current_pond')){
                $postdata['pond_id'] = Session::get('current_pond');
            }
            $pondId = $postdata['pond_id'];
            if (!$this->checkFarmPermissionOnPond($pondId)){
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
            }
            if (!$postdata['start_date']){
                $this->fireErrorMessage('start_date', trans("ebi.養殖開始日が有りません"));
            }

            if (!$postdata['estimate_shipping_date']){
                $this->fireErrorMessage('estimate_shipping_date', trans("ebi.出荷予定日が有りません"));
            }

            $currentPondId = Session::get('current_pond');

            // Check overlap time (StartA <= EndB) and (EndA >= StartB)
            $overlapTimes = DB::table('ebi_aquacultures')->whereDate('start_date', '<=', $postdata['estimate_shipping_date'])
                ->whereDate('estimate_shipping_date', '>=', $postdata['start_date'])
                ->where('id', '!=', $id)
                ->where('pond_id', $currentPondId)
                ->count();
            if ($overlapTimes){
                $this->fireErrorMessage('start_date', trans("ebi.既に登録済の養殖開始日です。"));
            }
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

        protected function hook_page_title($page_title) {
            return trans('ebi.養殖環境登録');
        }


        protected function hook_query_select(&$result) {
            if(!CRUDBooster::isSuperadmin()){
                $myFarms = $this->getMyFarms();

                $result->whereIn('ebi_farms.id', $myFarms);
            }
            $currentPondId = Session::get('current_pond');
            if ($currentPondId){
                $result->where('ebi_aquacultures.pond_id', $currentPondId);
            }
        }

        protected function hook_after_add_before_redirect($postdata)
        {
            if ($this->return_url) {
                if (Request::get('submit') == trans('crudbooster.button_save_more')) {
                    CRUDBooster::redirect(Request::server('HTTP_REFERER'), trans("crudbooster.alert_add_data_success"), 'success');
                } else {
                    CRUDBooster::redirect($this->return_url, trans("crudbooster.alert_add_data_success"), 'success');
                }
            } else {
                if (Request::get('submit') == trans('crudbooster.button_save_more')) {
                    CRUDBooster::redirect(CRUDBooster::mainpath('add'), trans("crudbooster.alert_add_data_success"), 'success');
                } else {
                    CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_data_success"), 'success');
                }
            }
            return false;
        }

        protected function hook_after_edit_before_redirect($postdata)
        {
            if ($this->return_url) {
                CRUDBooster::redirect($this->return_url, trans("crudbooster.alert_update_data_success"), 'success');
            } else {
                if (Request::get('submit') == trans('crudbooster.button_save_more')) {
                    CRUDBooster::redirect(CRUDBooster::mainpath('add'), trans("crudbooster.alert_update_data_success"), 'success');
                } else {
                    CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_update_data_success"), 'success');
                }
            }
            return false;
        }

	    //By the way, you can still create your own method in here... :) 


	}