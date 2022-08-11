<?php namespace App\Http\Controllers;

	use CRUDBooster;
    use DB;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Route;
    use Request;
    use Session;

    class AdminAquaculturesController extends AdminBaseController {
	
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_text";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = false;
			$this->button_addmore = false;
            $this->button_cancel = false;
			$this->table = "ebi_aquacultures";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			if((app()->getLocale() == 'en')){
				$this->col[] = ["label"=>trans('ebi.養殖場名'),"name"=>"ebi_aquacultures.farm_id","join"=>"ebi_farms,farm_name_en"];
			}else{
				$this->col[] = ["label"=>trans('ebi.養殖場名'),"name"=>"ebi_aquacultures.farm_id","join"=>"ebi_farms,farm_name"];
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
            $farm_disabled = '';
		//	if (!$id && !Session::get('current_pond')){
        //        $farm_disabled = '';
        //    }else{
                //$this->form[] = ['label'=>'養殖池','name'=>'pond_id','type'=>'hidden','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
        //    }


            // start aquaculture
            $this->form[] = ["label"=>trans('ebi.養殖場'), "name"=>"farm_id","type"=>"select2",'disabled'=> $farm_disabled,'validation'=>'required|integer|min:0', 'width'=>'col-sm-10'];
            $this->form[] = ['label'=>trans('ebi.初期投入養殖池'),'name'=>'first_pond_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10'];
			$defaults=DB::table('default_farm')->get();
			foreach($defaults as $default){

			}
            $this->form[] = ['label'=>trans('ebi.養殖開始日'),'name'=>'start_date','type'=>'date','validation'=>'required|date_format:Y.m.d','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>trans('ebi.出荷予定日'),'name'=>'estimate_shipping_date','type'=>'date','validation'=>'required|date_format:Y.m.d|after:start_date','width'=>'col-sm-10'];
            $this->form[] = ['label'=>trans('ebi.目標サイズ'),'name'=>'target_size','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'numeric|between:0.00,999999.99','width'=>'col-sm-10','value'=>$default->target_size];
            $this->form[] = ['label'=>trans('ebi.目標重量'),'name'=>'target_weight','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'required|numeric|between:0.00,999999.99','width'=>'col-sm-10','value'=>(int)$default->target_weight];
			$this->form[] = ['label'=>trans('ebi.餌費用'),'name'=>'food_amount','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'|numeric|between:0.00,999999.99','width'=>'col-sm-10','value'=>$default->food_amount];
			$this->form[] = ['label'=>trans('ebi.電力費用'),'name'=>'power_consumption','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'|numeric|between:0.00,999999.99','width'=>'col-sm-10','value'=>$default->power_consumption];
            $this->form[] = ['label'=>trans('ebi.薬費用'),'name'=>'medicine_amount','type'=>'number','min'=> 0.00, 'step'=>'any','validation'=>'|numeric|between:0.00,999999.99','width'=>'col-sm-10','value'=>$default->medicine_amount];
            $this->form[] = ['label'=>trans('ebi.稚エビ量'),'name'=>'shrimp_num','type'=>'number','min'=> 0,'max'=> 9999999.00,'validation'=>'required|integer|min:0|max:1000000','width'=>'col-sm-10','value'=>$default->shrimp_num];
            $this->form[] = ['label'=>trans('ebi.稚エビ種類'),'name'=>'ebi_kind','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'ebi_kind,kind','value'=>$default->ebi_kind_id];
            //$this->form[] = ['label'=>trans('ebi.養殖方法'),'name'=>'aquaculture_method_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'aquaculture_method,method','value'=>$default->aquaculture_method_id];
            $this->form[] = ['label'=>trans('ebi.生存率'),'name'=>'servival_rate','type'=>'number','min'=> 0.00,'max'=> 100.00, 'step'=>'any','validation'=>'required|numeric|between:0.00,999999.99','width'=>'col-sm-10','value'=>$default->servival_rate];

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
                    $pond = DB::table('ebi_ponds')->join('ebi_aquacultures', 'ebi_aquacultures.farm_id', '=', 'ebi_ponds.id')->select('ebi_ponds.*')->where('ebi_aquacultures.id', $id )->first();
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
                            $('#first_pond_id').empty().trigger('change');
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
                                        $('#first_pond_id').append(newOption);
                                    }
                                    $('#first_pond_id').trigger('change');
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
            return trans('ebi.養殖開始設定');
		}
				
        protected function hook_query_select(&$result) {
            if(!CRUDBooster::isSuperadmin()){
                $myFarms = $this->getMyFarms();
				
                $result->whereIn('ebi_farms.id', $myFarms);
            }
            $currentPondId = Session::get('current_pond');
            if ($currentPondId){
                $result->where('ebi_aquacultures.farm_id', $currentPondId);
            }
        }

        public function postAddSave(){
            $this->cbLoader();
            if (! CRUDBooster::isCreate() && $this->global_privilege == false) {
                CRUDBooster::insertLog(trans('crudbooster.log_try_add_save', [
                    'name' => Request::input($this->title_field),
                    'module' => CRUDBooster::getCurrentModule()->name,
                ]));
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
            }

            $this->validation();
            $this->input_assignment();
			$request=$this->arr;

            if (!$request['start_date']){
                $this->fireErrorMessage('start_date', trans("ebi.養殖開始日が有りません"));
			}
			$id= DB::table('ponds_aquacultures')
			->where('status',0)
			->where('ebi_ponds_id',$request["first_pond_id"])
			->value("id");

			if($id){
				CRUDBooster::redirect(CRUDBooster::adminPath('aquacultures/start'), trans("crudbooster.alert_culuture_failed"), 'error');
				exit;
			}
			$method=DB::table('ebi_kind')->where('id',$request["ebi_kind"])->value('aquaculture_method_id');
            $ebiprice=DB::table('ebi_kind')->where('id',$request["ebi_kind"])->value('price');
            $price = $request["shrimp_num"]*$ebiprice/200000;
			$day=DB::table('ebi_kind')->where('id',$request["ebi_kind"])->value('training_period');
			$s=$request["start_date"];
			$start="$s 00:00:00";
			$start = str_replace(".", "-", $start);
			$end=strtotime($start)+($day*60*60*24);
			$tanka=DB::table('ebi_price')->where('weight',(int)$request["target_weight"])->value('ebi_price');
                if(!$tanka){
                    if((int)$request["target_weight"]<=25){
                        for($i=(int)$request["target_weight"];$i<=25;$i++){
                            $tanka=DB::table('ebi_price')->where('weight',$i)->value('ebi_price');
                            if($tanka){
                                break;
                            }
                        }
                    }else{
                        $tanka=DB::table('ebi_price')->where('weight',25)->value('ebi_price');
                        $over=((int)$request["target_weight"]-25)*10;
                        $tanka=$tanka+$over;
                    }
                }
			$target_syusi=($request["target_weight"]*$tanka/1000*$request["shrimp_num"]) - $price - $request["power_consumption"]-$request["food_amount"]-$request["medicine_amount"];

			$pond=DB::table('ebi_ponds')->where('id',$request["first_pond_id"])->first();
			
			if(!($pond->pond_width*$pond->water_dept*$pond->pond_vertical_width==0)){
				$rateShrimpPerM3 = $request["shrimp_num"]/($pond->pond_width*$pond->water_dept*$pond->pond_vertical_width);
			}else{
				$rateShrimpPerM3 =0;
			}
			

            $aquaId = DB::table('ebi_aquacultures')->insertGetId([
                'start_date' =>$request["start_date"],
                'estimate_shipping_date' =>date('Y.m.d ', $end),
				'food_amount' =>$request["food_amount"] , 
				'medicine_amount' =>$request["medicine_amount"] ,
                'power_consumption' => $request["power_consumption"],
                'target_weight' => $request["target_weight"],
                'shrimp_num' => $request["shrimp_num"],
                'farm_id' => $request["farm_id"],
                'servival_rate' => $request["servival_rate"],
                'ebi_kind_id' => $request["ebi_kind"],
                'status' => 0,
                'ebi_price' => $price,
				'income_and_expenditure' =>(-1)*$price,
                'aquaculture_method_id' => $method,

            ]);
            DB::table('ponds_aquacultures')->insert([
                'created_at' =>$request["start_date"],
                'shrimp_num' => $request["shrimp_num"],
                'ebi_remaining' => $request["shrimp_num"],
				'ebi_ponds_id' => $request["first_pond_id"],
				'target_weight' => $request["target_weight"],
				'ebi_kind_id' => $request["ebi_kind"],
                'status' => 0,
                'aquaculture_method_id' =>$method,
                'ebi_aquacultures_id' => $aquaId,
				'income_and_expenditure' =>(-1)*$price,
				'cubic_meter_num' =>$rateShrimpPerM3,
				'nano_bubble' =>$pond->nano_bubble,

			]);

			
			$year=substr($start,0,4);
			
			$report=DB::table('years_report')->where('year',$year)->value('year');
			if(!$report){
				$syusi=$price*(-1);
				DB::table('years_report')->insert([
					'shrimp_num' => $request["shrimp_num"],
					'year' =>$year,
					'ebi_price' => $price,
					'target_syusi'=>$target_syusi,
					'income_and_expenditure'=>$syusi,
				]);

			}else{
				$years_report=DB::table('years_report')->where('year',$year)->get();
				foreach($years_report as $year_report){
						$num +=$request["shrimp_num"]+$year_report->shrimp_num;
						$years_ebi_price +=$price+$year_report->ebi_price;
						$target_syusi +=$target_syusi+$year_report->target_syusi;
						$syusi +=$year_report->income_and_expenditure;

				}		
				$syusi=$syusi+(($price)*(-1));
				
				DB::table('years_report')->where('year',$year)->update([
					'shrimp_num' => $num,
					'ebi_price' => $years_ebi_price,
					'target_syusi'=>$target_syusi,
					'income_and_expenditure'=>$syusi,
	
				]);
				
			}
			CRUDBooster::redirect(CRUDBooster::adminPath('aquacultures/start'), trans("crudbooster.alert_add_data_success"), 'success');
        }
		
	    //By the way, you can still create your own method in here... :)

        public function getStart()
        {
            $this->cbLoader();
            if (!CRUDBooster::isCreate() && $this->global_privilege == false ) {
                CRUDBooster::insertLog(trans('crudbooster.log_try_add', ['module' => CRUDBooster::getCurrentModule()->name]));
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
            }

            $page_title = trans("crudbooster.add_data_page_title", ['module' => CRUDBooster::getCurrentModule()->name]);
            $page_menu = Route::getCurrentRoute()->getActionName();
            $command = 'add';
            $page_title = $this->hook_page_title($page_title);

            return view($this->getAddViewName(), compact('page_title', 'page_menu', 'command', 'page_sub_title'));
        }
	}