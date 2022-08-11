<?php namespace App\Http\Controllers;

	use Carbon\Carbon;
    use CRUDBooster;
    use DB;
    use Request;
    use Session;

    class AdminPondsAquaculturesController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = -1;
			$this->orderby = "id,desc";
			$this->button_table_action = false;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "ponds_aquacultures";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
          //  $this->col[] = ['visible'=>false,"label"=>"total_feed_cost","name"=>"(select sum(feed_cumulative.cost) from feed_cumulative where feed_cumulative.ponds_aquacultures_id = ponds_aquacultures.id) as total_feed_cost"];
         //   $this->col[] = ['visible'=>false,"label"=>"total_medicine_cost","name"=>"(select sum(medicine_cumulative.cost) from medicine_cumulative where medicine_cumulative.ponds_aquacultures_id = ponds_aquacultures.id) as total_medicine_cost"];
            $this->col[] = ['visible'=>false,"label"=>"last_shrimp_weight","name"=>"(select ebi_shrimp_states.weight from ebi_shrimp_states where ebi_shrimp_states.ponds_aquacultures_id = ponds_aquacultures.id order by date_target desc limit 1) as last_shrimp_weight"];
            $this->col[] = ['visible'=>false,"label"=>"ebi_shrimp_num","name"=>"ebi_aquacultures_id","join"=>"ebi_aquacultures,shrimp_num"];
            $this->col[] = ['visible'=>false,"label"=>"ebi_price","name"=>"ebi_aquacultures.ebi_price"];
            $this->col[] = ['visible'=>false,"label"=>"ideal_shrimp_weight","name"=>"(select threshold_grow.weight from threshold_grow where threshold_grow.date <= DATEDIFF(NOW(), ebi_aquacultures_start_date) and threshold_grow.aquaculture_method_id = ebi_aquacultures_aquaculture_method_id order by threshold_grow.date desc limit 1) as ideal_shrimp_weight"];

            $this->col[] = ["label"=>"池名","name"=>"ponds_aquacultures.ebi_ponds_id","join"=>"ebi_ponds,pond_name"];
			$this->col[] = ["label"=>"エビ種類","name"=>"ebi_kind_id","join"=>"ebi_kind,kind"];
        //    $this->col[] = ['visible'=>false, "label"=>"ebi_remaining","name"=>"ebi_remaining"];

			$this->col[] = ["label"=>"餌投与量","name"=>"(select sum(feed_cumulative.cumulative) from feed_cumulative where feed_cumulative.ponds_aquacultures_id = ponds_aquacultures.id) as total_cumulative",
                            'callback'=>function($row) {return number_format($row->total_cumulative).'kg'; }];
        //    $this->col[] = ['visible'=>false,"label"=>"pond_shrimp_num","name"=>"ponds_aquacultures.shrimp_num as pond_shrimp_num"];
            // TODO DATEDIFF for completed
            $this->col[] = ["label"=>"成長度","name"=>"sell", 'callback'=>function($row) {return ($row->ideal_shrimp_weight?number_format($row->last_shrimp_weight*100/$row->ideal_shrimp_weight):'100').'%'; }];
			//$this->col[] = ["label"=>"水質ランク","name"=>"ebi_aquacultures_id","join"=>"ebi_aquacultures,id"];
            $this->col[] = ["label"=>"水質","name"=>"(select count(ebi_pond_alerts.id) from ebi_pond_alerts where ebi_pond_alerts.pond_id = ponds_aquacultures.ebi_ponds_id and ebi_pond_alerts.alert_date >= ebi_aquacultures_start_date and ebi_pond_alerts.disable_flag = 0 and (ponds_aquacultures.completed_date is null || ebi_pond_alerts.alert_date <= ponds_aquacultures.completed_date)) as count_alert", 'callback'=>function($row) {return ($row->count_alert?'異常あり':'異常なし'); }];
			$this->col[] = ["label"=>"収支","name"=>"income_and_expenditure", 'callback'=>function($row) {return number_format($row->income_and_expenditure).trans('ebi.円'); }];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Aquaculture Method Id','name'=>'aquaculture_method_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'aquaculture_method,id'];
			$this->form[] = ['label'=>'Average Price','name'=>'average_price','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Completed Date','name'=>'completed_date','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Created User','name'=>'created_user','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Ebi Aquacultures Id','name'=>'ebi_aquacultures_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'ebi_aquacultures,id'];
			$this->form[] = ['label'=>'Ebi Kind Id','name'=>'ebi_kind_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'ebi_kind,id'];
			$this->form[] = ['label'=>'Ebi Ponds Id','name'=>'ebi_ponds_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'ebi_ponds,pond_name'];
			$this->form[] = ['label'=>'Ebi Remaining','name'=>'ebi_remaining','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Feed Cumulative','name'=>'feed_cumulative','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Income And Expenditure','name'=>'income_and_expenditure','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Medicine Cumulative','name'=>'medicine_cumulative','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Sell','name'=>'sell','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Shipment Count','name'=>'shipment_count','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Shrimp Num','name'=>'shrimp_num','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Status','name'=>'status','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Takeover Ponds Id','name'=>'takeover_ponds_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'takeover_ponds,id'];
			$this->form[] = ['label'=>'Target Size','name'=>'target_size','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Target Weight','name'=>'target_weight','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Updated User','name'=>'updated_user','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Aquaculture Method Id","name"=>"aquaculture_method_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"aquaculture_method,id"];
			//$this->form[] = ["label"=>"Average Price","name"=>"average_price","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Completed Date","name"=>"completed_date","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"Created User","name"=>"created_user","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Ebi Aquacultures Id","name"=>"ebi_aquacultures_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"ebi_aquacultures,id"];
			//$this->form[] = ["label"=>"Ebi Kind Id","name"=>"ebi_kind_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"ebi_kind,id"];
			//$this->form[] = ["label"=>"Ebi Ponds Id","name"=>"ebi_ponds_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"ebi_ponds,pond_name"];
			//$this->form[] = ["label"=>"Ebi Remaining","name"=>"ebi_remaining","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Feed Cumulative","name"=>"feed_cumulative","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Income And Expenditure","name"=>"income_and_expenditure","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Medicine Cumulative","name"=>"medicine_cumulative","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Sell","name"=>"sell","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Shipment Count","name"=>"shipment_count","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Shrimp Num","name"=>"shrimp_num","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Takeover Ponds Id","name"=>"takeover_ponds_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"takeover_ponds,id"];
			//$this->form[] = ["label"=>"Target Size","name"=>"target_size","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Target Weight","name"=>"target_weight","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Updated User","name"=>"updated_user","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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
	        $this->script_js = NULL;


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

            if (CRUDBooster::getCurrentMethod() == 'getIndex') {
                $farmId = Request::get('farmId');

                $this->data['pond_farm'] = DB::table('ebi_farms')->where('id', $farmId)->select(['farm_name_en', 'farm_name'])->first();

                $aquacultures = DB::table('ebi_aquacultures')
                    ->where('ebi_aquacultures.farm_id', $farmId)
                    ->orderBy('ebi_aquacultures.start_date', 'desc')
                    ->select('ebi_aquacultures.start_date', 'ebi_aquacultures.completed_date', 'ebi_aquacultures.estimate_shipping_date', 'ebi_aquacultures.id')
                    ->get();

                $aquaId = Request::get('aquaId');
                $current_aquaculture = null;
                if ($aquaId){
                    $current_aquaculture = DB::table('ebi_aquacultures')->where('id', $aquaId)->first();
                }else{
                    $date_now = Carbon::now()->toDateString();

                    $time_line = DB::table('ebi_aquacultures')
                        ->where([['ebi_aquacultures.farm_id', '=', $farmId], ['ebi_aquacultures.start_date', '<=', $date_now]])
                        ->where(function ($query) use ($date_now) {
                            $query->where('ebi_aquacultures.completed_date', '>=', $date_now)
                                ->orWhereNull('ebi_aquacultures.completed_date');
                        })
                        ->first();
                    if ($time_line) {
                        $current_aquaculture = $time_line;
                    } else {
                        $time_line_up = DB::table('ebi_aquacultures')->where([['ebi_aquacultures.farm_id', '=', $farmId], ['ebi_aquacultures.start_date', '>=', $date_now]])
                            ->orderBy('ebi_aquacultures.start_date', 'asc')
                            ->first();
                        if ($time_line_up) {
                            $current_aquaculture = $time_line_up;
                        } else {
                            $time_line_down = DB::table('ebi_aquacultures')->where([['ebi_aquacultures.farm_id', '=', $farmId], ['ebi_aquacultures.completed_date', '<=', $date_now]])
                                ->orderBy('ebi_aquacultures.completed_date', 'desc')
                                ->first();
                            if ($time_line_down) {
                                $current_aquaculture = $time_line_down;
                            }
                        }
                    }
                }

                $this->data['farmId'] = $farmId;
                $this->data['aquacultures'] = $aquacultures;
                $this->data['current_aquaculture'] = $current_aquaculture;
                $this->data['aquaId'] = $current_aquaculture?$current_aquaculture->id:null;
                Request::merge(['aquaId' => $this->data['aquaId']]);
            }
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

	    public function getIndex()
        {
            if (!Request::get('farmId')){
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
            }
            return parent::getIndex();
        }

        public function getAdd()
        {
            return parent::getIndex();
        }

        public function getEdit()
        {
            return parent::getIndex();
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
            $aquaId = Request::get('aquaId');
            $query->where('ebi_aquacultures_id', $aquaId);
            $query->where('ebi_aquacultures.status', AppConst::STATUS_OPEN);
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

        protected function getIndexViewName()
        {
            return 'admin_pond_aquacultures_index';
        }

	    //By the way, you can still create your own method in here... :) 


	}