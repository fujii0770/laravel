<?php namespace App\Http\Controllers;

    use Carbon\Carbon;
    use Illuminate\Support\Facades\Route;
    use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminEbiBaitInventoriesController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "bait_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->button_table_action = true;
			$this->button_bulk_action = false;
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
			$this->table = "ebi_bait_inventories";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans( 'ebi.餌,薬名称'),"name"=>"bait_name"];
            $this->col[] = ["label"=>trans( 'ebi.在庫数'),"name"=>"stock",'callback_php' =>'App\Http\Controllers\Helper::toStockLabel($row->stock)'];
            $this->col[] = ["label"=>trans( 'ebi.1袋辺り餌量'),"name"=>"amount_per_bag",'callback_php' =>'App\Http\Controllers\Helper::toAmountPerBagLabel($row->amount_per_bag)'];
            $this->col[] = ["label"=>trans( 'ebi.ステータス'),"name"=>"status",'callback_php' => 'App\Http\Controllers\Helper::toStatusBaitInventoriesLabel($row->status)'];
            $this->col[] = ["label"=>trans( 'ebi.次回購入予定数'),"name"=>"next_purchase"];
            $this->col[] = ["label"=>trans( 'ebi.発注ステータス'),"name"=>"order_status",'callback_php' => 'App\Http\Controllers\Helper::toOderStatusBaitInventoriesLabel($row->order_status)'];
            $this->col[] = ["label"=>trans( 'ebi.発注数'),"name"=>"order_qty",'callback_php' =>'App\Http\Controllers\Helper::toStockLabel($row->order_qty)'];
            $this->col[] = ["label"=>trans( 'ebi.発注日'),"name"=>"order_date","callback_php" =>'$row->order_date?App\Http\Controllers\Helper::formatDate($row->order_date):"-"'];
            $this->col[] = ["label"=>trans( 'ebi.到着予定日'),"name"=>"arrival_date","callback_php" =>'$row->arrival_date?App\Http\Controllers\Helper::formatDate($row->arrival_date):"-"'];
            $this->col[] = ["label"=>trans( 'ebi.分類(餌/薬)'),"name"=>"kind", 'callback_php' => 'App\Http\Controllers\Helper::toKindBaitInventoriesLabel($row->kind)'];

			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
            if(Request::is('admin/ebi_bait_inventories/edit/*')){
                $this->form[] = ["label"=> trans('ebi.餌ID'),"name"=>"id","disabled"=>"disabled",'width'=>'col-sm-10'];
            }
            $this->form[] = ["label"=> trans('ebi.基本情報'),"name"=>"header-1","type"=>"custom","html"=>""];
            $this->form[] = ['label'=> trans('ebi.餌,薬名称'),'name'=>'bait_name','type'=>'text', 'minlength'=>'1', 'maxlength'=>'255', 'validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
            $this->form[] = ['label'=> trans('ebi.在庫数'),'name'=>'stock','type'=>'number',"min"=>"0", "max"=>"99999.999", 'step'=>'0.001','validation'=>'numeric|min:0|max:99999.999','width'=>'col-sm-10'];
            $this->form[] = ['label'=> trans('ebi.分類(餌/薬)'),'name'=>'kind','type'=>'select','validation'=>'required|integer|min:0','dataenum'=>'0|'.trans("ebi.餌").';1|'.trans("ebi.薬"),'width'=>'col-sm-10'];
            $this->form[] = ['label'=> trans('ebi.1袋辺り餌量'),'name'=>'amount_per_bag','type'=>'number',"min"=>"0.01", "max"=>"999999.99", 'step'=>'0.01','validation'=>'required|numeric|min:0.01|max:999999.99','width'=>'col-sm-10'];
            if(Request::is('admin/ebi_bait_inventories/edit/*')){
                $this->form[] = ['label'=> trans('ebi.ステータス'),'name'=>'status',"disabled"=>"disabled",'type'=>'select','validation'=>'required','dataenum'=>'0|'.trans("ebi.正常").';1|'.trans("ebi.在庫不足"),'width'=>'col-sm-10'];
            }else{
                $this->form[] = ['label'=> trans('ebi.ステータス'),'name'=>'status','type'=>'select','validation'=>'required','dataenum'=>'0|'.trans("ebi.正常").';1|'.trans("ebi.在庫不足"),'width'=>'col-sm-10'];
            }
            $this->form[] = ['label'=> trans('ebi.在庫不足閾値'),'name'=>'threshold','type'=>'number',"min"=>"0", "max"=>"2147483647", 'step'=>'1', 'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];
            $this->form[] = ['label'=> trans('ebi.次回購入予定数'),'name'=>'next_purchase','type'=>'number',"min"=>"0", "max"=>"2147483647", 'step'=>'1' ,'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];
            $this->form[] = ["label"=> trans('ebi.発注状況'),"name"=>"header-2","type"=>"custom","html"=>""];
            $this->form[] = ['label'=> trans('ebi.発注ステータス'),'name'=>'order_status','type'=>'select','validation'=>'integer','dataenum'=>'0|-;1|'.trans("ebi.未発注").';2|'.trans("ebi.発注済"),'width'=>'col-sm-10'];
            $this->form[] = ['label'=> trans('ebi.発注数'),'name'=>'order_qty','type'=>'number',"min"=>"0", "max"=>"2147483647", 'step'=>'1', 'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];
            $this->form[] = ['label'=> trans('ebi.発注日'),'name'=>'order_date','type'=>'date','validation'=>'required|date_format:Y.m.d','width'=>'col-sm-10',"callback_php" =>'App\Http\Controllers\Helper::formatDate($row->order_date)'];
            $this->form[] = ['label'=> trans('ebi.到着予定日'),'name'=>'arrival_date','type'=>'date','validation'=>'required|date_format:Y.m.d|after_or_equal:order_date','width'=>'col-sm-10',"callback_php" =>'App\Http\Controllers\Helper::formatDate($row->arrival_date)'];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Bait Name","name"=>"bait_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Stock","name"=>"stock","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Amount Per Bag","name"=>"amount_per_bag","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Threshold","name"=>"threshold","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Next Purchase","name"=>"next_purchase","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Order Qty","name"=>"order_qty","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Order Date","name"=>"order_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Arrival Date","name"=>"arrival_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Order Status","name"=>"order_status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Created User","name"=>"created_user","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Updated User","name"=>"updated_user","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Kind","name"=>"kind","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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
                $this->script_js = "
                    $(function () {						                       
                        $('#stock').change(function (){
                            checkStatus();
                        });
                        
                        $('#threshold').change(function (){
                            checkStatus();
                        });
                        
                        checkStatus();

					});
					
					function checkStatus(){
                        var stock = getFloatValue('stock');
                        var threshold = getFloatValue('threshold');
                        if (stock < threshold){
                            $('#status').val(1);
                        }else{
                            $('#status').val(0);
                        }
                    }
                    
                    function getFloatValue(fieldId){
                        if ( $('#' + fieldId).length ) {
                            var value = $('#' + fieldId).val().trim().replace(/\,/g, '');
                            if (value){
                                return isNaN(value)?0:parseFloat(value);
                            }
                        }
                        return 0;
                    }
					
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
	        $this->style_css = "
				#form-group-header-1{ 
					border-bottom: solid;
					margin: 25px 0;
				}
				#form-group-header-1 label{
					padding: 0px;
				}
				#form-group-header-2{ 
					border-bottom: solid;
					margin: 25px 0;
				}
				#form-group-header-2 label{
					padding: 0px;
				}
			";



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
            return 'admin_bait_inventories_index';
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
            unset($postdata['header-1']);
            unset($postdata['header-2']);

            if (floatval($postdata['stock']) < floatval($postdata['threshold'])){
                $postdata['status'] = 1;
            }else{
                $postdata['status'] = 0;
            }
            $this->validatePostData($postdata, null);
            $existBait = DB::table('ebi_bait_inventories')
                ->where('bait_name', $postdata['bait_name'])
                ->first();
            if ($existBait) {
                if ($existBait->amount_per_bag == $postdata['amount_per_bag']) {
                    $stock = $postdata['stock'] + $existBait->stock;
                    DB::table('ebi_bait_inventories')
                        ->where('bait_name', $postdata['bait_name'])
                        ->where('amount_per_bag', $postdata['amount_per_bag'])
                        ->update([
                            'stock' => $stock,
                            'kind' => $postdata['kind'],
                            'threshold' => $postdata['threshold'],
                            'next_purchase' => $postdata['next_purchase'],
                            'order_status' => $postdata['order_status'],
                            'order_qty' => $postdata['order_qty'],
                            'order_date' => $postdata['order_date'],
                            'arrival_date' => $postdata['arrival_date'],
                        ]);
                    CRUDBooster::redirect(CRUDBooster::adminPath('ebi_bait_inventories'), trans("crudbooster.alert_update_data_success"), 'success');
                } else {
                    $this->fireErrorMessage('bait_name', trans('ebi.その名称はすでに使用されています。'));
                }
            }
	    }

        private function validatePostData($postdata){
            if(trim($postdata['bait_name']) === '') {
                $this->fireErrorMessage('bait_name', trans('crudbooster.this_field_is_required'));
            }
            if(trim($postdata['order_date']) === '') {
                $this->fireErrorMessage('order_date', trans('crudbooster.this_field_is_required'));
            }
            if(trim($postdata['arrival_date'] === '')) {
                $this->fireErrorMessage('arrival_date', trans('crudbooster.this_field_is_required'));
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
            unset($postdata['header-1']);
            unset($postdata['header-2']);

            if (floatval($postdata['stock']) < floatval($postdata['threshold'])){
                $postdata['status'] = 1;
            }else{
                $postdata['status'] = 0;
            }
            $this->validatePostData($postdata, null);
            $existBait = DB::table('ebi_bait_inventories')
                ->where('bait_name', $postdata['bait_name'])
                ->where('id', '<>', $id)
                ->first();
            if ($existBait) {
                $this->fireErrorMessage('bait_name', trans('ebi.その名称はすでに使用されています。'));
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
            try {
				$countFeedCumulative = DB::table('feed_cumulative')->where('ebi_bait_inventories_id', $id)->count();
				$countBaits = DB::table('ebi_baits')->where('ebi_bait_inventories_id', $id)->count();
				$countFeedInventoryRemaining = DB::table('feed_inventory_remaining')->where('ebi_bait_inventories_id', $id)->count();
				$countMedicineCumulative = DB::table('medicine_cumulative')->where('ebi_bait_inventories_id', $id)->count();
				if($countFeedCumulative || $countBaits || $countFeedInventoryRemaining || $countMedicineCumulative){
					CRUDBooster::redirect(CRUDBooster::adminPath('ebi_bait_inventories'), trans("ebi.使用されている餌/薬は削除できません。"), 'danger');
				}
                DB::table('feed_price')->where('ebi_bait_inventories_id', $id)->delete();
            } catch (\Exception $exception) {
                CRUDBooster::redirect(CRUDBooster::adminPath('ebi_bait_inventories'), trans("crudbooster.alert_delete_data_failed"), 'danger');
            }
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



	    //By the way, you can still create your own method in here... :)
        protected function hook_after_add_before_redirect($postdata)
        {
            $bait = DB::table('ebi_bait_inventories')->where('bait_name', $postdata['bait_name'])->latest()->first();
            DB::table('feed_price')->insert(['ebi_bait_inventories_id' => $bait->id, 'price' => 0, 'created_at'=>Carbon::now()]);
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

        public function getAdd()
        {
            $this->cbLoader();
            if (!CRUDBooster::isCreate() && $this->global_privilege == false || $this->button_add == false) {
                CRUDBooster::insertLog(trans('crudbooster.log_try_add', ['module' => CRUDBooster::getCurrentModule()->name]));
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
            }

            $page_title = trans('ebi.発注登録');
            $page_menu = Route::getCurrentRoute()->getActionName();
            $command = 'add';
            $page_title = $this->hook_page_title($page_title);

            return view($this->getAddViewName(), compact('page_title', 'page_menu', 'command', 'page_sub_title'));
        }
	}