<?php namespace App\Http\Controllers;

	use Illuminate\Support\Facades\Artisan;
    use Session;
    use Illuminate\Http\Request;
	use DB;
    use Excel;
	use CRUDBooster;
    use Validator;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;

	class AdminImportLogsController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "file_name";
			$this->limit = "20";
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
			$this->table = "ebi_import_state_logs";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans("ebi.ファイル名"),"name"=>"file_name"];
			$this->col[] = ["label"=>trans("ebi.作業者"),"name"=>"created_user","join"=>"cms_users,name"];
			$this->col[] = ["label"=>trans("ebi.取込日時"),"name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>trans('ebi.ファイル名'),'name'=>'file_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.作業者'),'name'=>'created_user','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'cms_users,name'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"File Name","name"=>"file_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Created User","name"=>"created_user","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
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
	        $this->script_js = "$(document).ready(function () {
                $('#btn-upload-file, #txt-file-name').on('click', function() {
                    $('#btn-browser-file').trigger('click');
                });
                $('#btn-browser-file').change(function() {
                    var file_name = this.value.replace(/\\\\/g, '/').replace(/.*\//, '');
                    $('#txt-file-name').val(file_name);
                });
            });";


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
	        if(\Request::is('admin/import_bait')){
                $query->where('import_type', AppConst::IMPORT_TYPE_BAIT);
            }else if(\Request::is('admin/import_drug')){
                $query->where('import_type', AppConst::IMPORT_TYPE_DRUG);
            }else{
                $query->where('import_type', AppConst::IMPORT_TYPE_WATER);
            }
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
            if(\Request::is('admin/import_bait') || \Request::is('admin/import_drug')){
                return 'admin_import_bait_drug_index';
            }else{
                return 'admin_import_state_index';
            }
        }

        //By the way, you can still create your own method in here... :)
        public function getImportDone(Request $request)
        {
            $page_title = trans('ebi.インポート');
            $import_type = 'import_water_quality';
            $result = $unmapping  = null;

            try {

                $file = $request->file('f5file');
                $fileName = $file->getClientOriginalName();
                $ammonia = $request->get('ammonia');
                $ion = $request->get('ion');
                $outTemp = $request->get('out_temp');

                // check if the file is imported
                $countFileName = DB::table('ebi_import_state_logs')->where('import_type', AppConst::IMPORT_TYPE_WATER)->where('file_name', $fileName)->count();
                if ($countFileName){
                    $this->fireErrorMessage('f5file', trans('ebi.このファイル名を既にインポートされました。'));
                }

                    // Hoapx: indentify pond by file name 20191121
                    // $pond_id = 0;
                    // if (strlen($fileName) > 6){
                    //     $pondName = substr($fileName, 5, 2);
                    //     $pond = DB::table('ebi_ponds')->select('id')->where('pond_name', strtoupper($pondName))->first();
                    //     if ($pond){
                    //         $pond_id = $pond->id;
                    //     }
                    // }

                    // if (!$pond_id){
                    //     $result = null;
                    //     $error = trans('ebi.ファイル名と池が一致しません。');
                    //     return view('pondstates_import_done', compact('result', 'page_title', 'error' ));
                    // }
                
                $path = $file->getRealPath();
                $sheets = Excel::load($path)->get();

                if ($sheets->count() > 1){
                    $data = $sheets[1];
                }else{
                    $data = $sheets[0];
                }
                // var_dump($data);
                $row = 1;
                $result = $unmapping = array();
                $imported = false;

                if (count($data)) {
                    $batch = array();
                    $batch_length = 0;
                    $BATCH_SIZE = 1000;
                    // select last id
                    $lastId = DB::table('ebi_pond_states')->max('id');

                    if (!$lastId){
                        $lastId = 0;
                    }

                    foreach ($data as $key => $value) {
                        $row++;

                       /* if (count($value) != 20){
                            CRUDBooster::redirectBack('エクセルファイルが正しくありません。取り込みできませんでした。');
                        }*/

                        if ($value[0]){
                            $lat = trim($value[16]);
                            $long = trim($value[17]);
                            //$lat = str_replace(' ', '', $lat);
                            //$long = str_replace(' ', '', $long);
                            if($long){
                                $long = explode('.', $long);
                                if(count($long) == 2){
                                    $long_f = $long[0];
                                    $long_r = $long[1];
                                    $long_rf = preg_replace('/\D/', '', $long_r);
                                    $long = $long_f . '.' . $long_rf;
                                }else{
                                    $long = "";
                                }
                            }

                            if($lat){
                                $lat = explode('.', $lat);
                                if(count($lat) == 2){
                                    $lat_f = $lat[0];
                                    $lat_r = $lat[1];
                                    $lat_rf = preg_replace('/\D/', '', $lat_r);
                                    $lat = $lat_f . '.' . $lat_rf;
                                }else{
                                    $lat = "";
                                }
                            }


                            $time = $value[1];
                            $timec = date("H:i:s", strtotime($time));
                            $date = $value[0];
                            $datec = date("Y-m-d", strtotime($date));
                            $arr = [
                                'pond_id' => 0,
                                'date_target' => $datec,
                                'time_target' => $timec,
                                'tmp_values' => $value[2],
                                'ph_values' => $value[3],
                                'mv_values' => $value[4],
                                'orp_values' => $value[5],
                                'ec_values' => $value[6],
                                'ec_abs_values' => $value[7],
                                'res_values' => $value[8],
                                'tds_values' => $value[9],
                                'sal_values' => $value[10],
                                'sigma_t_values' => $value[11],
                                'press_values' => $value[12],
                                'do_values' =>$value[13],
                                'do_ppm_values' => $value[14],
                                'turb_fnu_values' => $value[15],
                                'ammonia_values' => $ammonia,
                                'copper_ion_values' => $ion,
                                'out_temp_values' => $outTemp,
                                'location' => $value[18],

                                'gps_lat_values' => $lat,
                                'gps_long_values' => $long,
                                'created_at' => Carbon::now(),
                                'created_user' => CRUDBooster::myid(),
                                'line_no' => $row,
                            ];

                            
                            if (count($value) > 20){
                                // has TagID
                                $arr['tag_id'] = $value[19];
                                $arr['remarks'] = $value[20];
                            }else{
                                // no TagID
                                $arr['remarks'] = $value[19];
                                $arr['tag_id'] = null;
                            }
                            if (!trim($arr['remarks'])){
                                $arr['remarks'] = 'A';
                            }

                            $rule = [
                                'date_target' => 'required',
                                'time_target' => 'required',
                                'ph_values' => 'required',
                                'mv_values' => 'required',
                                'orp_values' => 'required',
                                'ec_values' => 'required',
                                'ec_abs_values' => 'required',
                                'res_values' => 'required',
                                'tds_values' => 'required',
                                'sal_values' => 'required',
                                'press_values' => 'required',
                                'do_values' => 'required',
                                'do_ppm_values' => 'required',
                                'turb_fnu_values' => 'required',
                                'tmp_values' => 'required',
                                'sigma_t_values' => 'required',
                               // 'gps_lat_values' => 'required',
                              //  'gps_long_values' => 'required',
                            ];
                            $validator = Validator::make($arr, $rule);

                            $data = array();
                            if ($validator->fails()) {
                                //$result = $validator->errors(0);
                                $error = $validator->errors()->first();
                                $stt = '0';
                                $kq = $error;
                            } else {
                                $batch[] = $arr;
                                $batch_length++;
                                if ($batch_length == $BATCH_SIZE) {
                                    DB::table('ebi_pond_states')->insert($batch);
                                    $batch = array();
                                    $batch_length = 0;
                                    $imported = true;
                                }
                                $success = "";
                                $kq = $success;
                                $stt = '1';
                            }

                            array_push($data, $row, $stt, $result);
                            $result[] = [
                                'row' => $row,
                                'stt' => $stt,
                                'kq' => $kq,
                            ];
                        }
                    }

                    if ($batch_length > 0) {
                        DB::table('ebi_pond_states')->insert($batch);
                        $imported = true;
                    }

                    if ($imported) {
                        // TODO check farm permission on mapping pond
                        // map pond by GPS
                        /*
                         * UPDATE ebi_pond_states SET POND_ID = COALESCE((SELECT id FROM ebi_ponds WHERE CONTAINS(ST_GEOMFROMTEXT(CONCAT('POLYGON((',
                            CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0, ' ',
                             CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0 ),
                              ',',
                            CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_sw, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0, ' ',
                             CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_sw, ',', -1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0 ),
                             ',',
                            CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_nw, ',', 1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0, ' ',
                             CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_nw, ',', -1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0 ),
                            ',',
                            CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_ne, ',', 1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0, ' ',
                             CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_ne, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0 ),
                             ',',
                            CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0, ' ',
                             CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*0.00001 AS CHAR) + 0 )
                            ,'))'
                            )),
                            ST_GEOMFROMTEXT(CONCAT('POINT(', ebi_pond_states.gps_lat_values,' ', ebi_pond_states.gps_long_values, ')')))), 0)
                         */
                        // map by GPS and remarks
                        DB::update(DB::raw("UPDATE ebi_pond_states SET pond_id = COALESCE((SELECT id FROM ebi_ponds WHERE ST_Contains(ST_GEOMFROMTEXT(CONCAT('POLYGON((', " .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' ', " .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),',', " .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_sw, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' '," .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_sw, ',', -1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),','," .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_nw, ',', 1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' '," .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_nw, ',', -1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),','," .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_ne, ',', 1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' ', " .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_ne, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),','," .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' '," .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),'))'))," .
                            " ST_GEOMFROMTEXT(CONCAT('POINT(', ebi_pond_states.gps_lat_values,' ', ebi_pond_states.gps_long_values, ')')))), 0)  where ebi_pond_states.pond_id = 0 AND ebi_pond_states.gps_lat_values <> '' AND ebi_pond_states.gps_long_values <> '' and ebi_pond_states.id > $lastId"));

                        DB::update(DB::raw("UPDATE ebi_pond_states SET pond_id = COALESCE((SELECT id FROM ebi_ponds WHERE ST_TOUCHES(ST_GEOMFROMTEXT(CONCAT('POLYGON((', " .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' ', " .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),',', " .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_sw, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' '," .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_sw, ',', -1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),','," .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_nw, ',', 1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' '," .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_nw, ',', -1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),','," .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_ne, ',', 1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' ', " .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_ne, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),','," .
                            " CONCAT(CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', 1) AS DECIMAL(12,6)) - IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0, ' '," .
                            " CAST(CAST(SUBSTRING_INDEX(ebi_ponds.lat_long_se, ',', -1) AS DECIMAL(12,6)) + IFNULL(CAST(ebi_ponds.delta_measure AS DECIMAL(12,6)), 0)*" . Helper::DELTA_MEASURE_UNIT . " AS CHAR) + 0 ),'))'))," .
                            " ST_GEOMFROMTEXT(CONCAT('POINT(', ebi_pond_states.gps_lat_values,' ', ebi_pond_states.gps_long_values, ')')))), 0)  where ebi_pond_states.pond_id = 0 AND ebi_pond_states.gps_lat_values <> '' AND ebi_pond_states.gps_long_values <> '' and ebi_pond_states.id > $lastId"));

                        // map by Tag and remark for pond_id = 0
                        $query = "UPDATE ebi_pond_states a, ebi_ponds b "
                                ." SET a.pond_id = b.id"
                                ." WHERE a.tag_id != '' "
                                ." AND a.id > $lastId "
                                ." AND a.gps_lat_values = '' "
                                ." AND a.remarks != '' "
                                ." AND a.gps_long_values = '' "
                                ." AND a.pond_id = 0 "
                                ." AND ( a.tag_id = b.tag1 OR a.tag_id = b.tag2 OR a.tag_id = b.tag3 OR a.tag_id = b.tag4 ) ";
                        DB::update(DB::raw($query));

                        // map by GPS and remarks
                        $query = "UPDATE ebi_pond_states a, ebi_measure_point_tag b"
                                . " SET a.remarks = b.measure_point"
                                . " WHERE a.pond_id != 0 "
                                . " AND a.id > $lastId "
                                . " AND (a.remarks = '' OR a.remarks IS NULL)"
                                . " AND a.tag_id != '' "
                                . " AND a.tag_id = b.tag_id"
                                . " AND a.gps_lat_values != '' "
                                . " AND a.gps_long_values != '' ";
                        DB::update(DB::raw($query));

                        // get row unmapping pond state (by GPS, Tag, remark)
                        $unmapping = DB::table('ebi_pond_states')
                                ->select('id')
                                ->where('pond_id', '=', 0)
                                ->where('id', '>', $lastId)
                                ->count();
                        if ($unmapping){
                            $unmappingItems = DB::table('ebi_pond_states')
                                ->select('line_no', 'gps_lat_values', 'gps_long_values')
                                ->where('pond_id', '=', 0)
                                ->where('id', '>', $lastId)
                                ->get();
                        }else{
                            $unmappingItems = array();
                        }
                    //    DB::update(DB::raw("DELETE FROM ebi_pond_states WHERE pond_id = 0"));
                        
                        // import log file
                        DB::table('ebi_import_state_logs')->insert(['import_type' => AppConst::IMPORT_TYPE_WATER,'created_user' => CRUDBooster::myId(), 'created_at' => now(), 'file_name' => $fileName,'updated_at' => now()]);

                        // trigger command Scan Alert
                        Artisan::call('scan_alert:pond_state', ['fromId' => $lastId, 'sendMail' => 1]);
                    }
                }

            } catch (\Throwable $e) {
                Log::error($e->getMessage() . $e->getTraceAsString());
                if (method_exists($e, "failures")) {
                    $failures = $e->failures();
                    foreach ($failures as $failure) {
                        $failure->row(); // row that went wrong
                        $failure->attribute(); // either heading key (if using heading row concern) or column index
                        $failure->errors(); // Actual error messages from Laravel validator
                    }
                }
                $result = null;
            }  
            return view('pondstates_import_done', compact('result', 'page_title', 'unmapping', 'unmappingItems', 'import_type' ));
                   
        }

        public function getImportBait(){
	        return parent::getIndex();
        }

        public function getImportDrug(){
            return parent::getIndex();
        }

        public function getImportBaitDone(Request $request)
        {
            //餌import
            $page_title = trans('ebi.インポート');
            $import_type = 'import_bait';
            $result = [];
            $total = [];
            $maxSize = (int)str_replace('M', '', ini_get('upload_max_filesize')) * 1048576;
            try {
                $file = $request->file('f5file');

                if(!$file){
                    $this->fireErrorMessage('f5file', trans('ebi.ファイルを選択してください。'));
                }

                if(!filesize($file) || filesize($file) > $maxSize){
                    $this->fireErrorMessage('f5file', trans('ebi.ファイルサイズが大きすぎで、').ini_get('upload_max_filesize').trans('ebi.以下にしてください。'));
                }
                $fileName = $file->getClientOriginalName();

                // don't check if the file is imported
             //   $countFileName = DB::table('ebi_import_state_logs')->where('import_type', AppConst::IMPORT_TYPE_BAIT)->where('file_name', $fileName)->count();
            //    if ($countFileName){
            //        $this->fireErrorMessage('f5file', trans('ebi.このファイル名を既にインポートされました。'));
            //    }

                $path = $file->getRealPath();
                $sheets = Excel::load($path)->get();
                if ($sheets->count()){
                    $data = $sheets[0]->toArray();
                }

                if (count($data)) {
                    $totalmedeicineDifference=0;
                    $pondsAquacultures = null;
                    $pondId = (int)$data[1][2];
                    $pond = DB::table('ebi_ponds')->select('id','farm_id')->where('id', $pondId)->first();
                    if($pond){
                        $pondsAquacultures = DB::table('ponds_aquacultures')
                                        ->select('id','created_at','completed_date','ebi_aquacultures_id','feed_cumulative','income_and_expenditure')
                                        ->where('ebi_ponds_id',$pondId)
                                        ->where('status',0)
                                        ->orderBy('id','desc')
                                        ->get()
                                        ->toArray();

                        //年越すまでの暫定対応 前回import時の餌合計費用
                        $oldfeedcost = DB::table('ponds_aquacultures')->where('ebi_ponds_id',$pondId)->where('status',0)->value('feed_cumulative');
                        
                    }else{
                        $this->fireErrorMessage('f5file', trans('ebi.池名が存在しません'));
                    }  
                    
                    $baitInventories = DB::table('ebi_bait_inventories')
                                        ->where('kind', 0)
                                        ->select('id','bait_name','amount_per_bag','stock')
                                        ->orderBy('id','desc')
                                        ->get();

                    $ebiBaitInventories = $baitInventories->keyBy('bait_name')->toArray();
                    $arrBaitInventories = $baitInventories->keyBy('id')->toArray();

                    $totalInsertSuccess = 0;
                    $totalInsertFail = 0;
                    $arrBait = [];
                    $sumAmountByBaitInventoriesNew = [];
                    $feed_price = DB::table('feed_price')->distinct()->pluck('price','ebi_bait_inventories_id')->toArray();
                    $arrCostByYearNew = [];
                    $dataCostFeddCumulativeNew = [];
                    $arrAdg = [];
                    $arrFcr = [];
                    $insertLog = 0;
                    
                    //餌のレコードを抽出
                    $ebi_bait_inventories_ids=DB::table('ebi_bait_inventories')->where('kind',0)->get();
                    foreach($ebi_bait_inventories_ids as $id){

                        $feed_id[]=$id->id;
                        Log::debug("$id->id");
                    }
                    
                    //養殖ID
                    $pond_aquaculture = DB::table('ponds_aquacultures')->where('ebi_ponds_id',$pondId)->where('status',0)->value('id');
                    DB::beginTransaction();
                    $last_bait = DB::table('ebi_baits')->select('id')->latest('id')->first();
                    $last_fcr = DB::table('fcr')->select('id')->latest('id')->first();
                    $last_adg = DB::table('adg')->select('id')->latest('id')->first();
                    //ebi_bait_inventories_idはkind=1のレコードを算出 改修依頼
                    $ebiBaits = DB::table('ebi_baits')
                                ->select('ponds_aquacultures_id','ebi_bait_inventories_id', DB::raw('sum(ebi_baits.amount) as sumAmount, DATE_FORMAT(ebi_baits.bait_at, "%Y-%m-%d") as formatted_bait_at'))
                                ->whereIn('ebi_bait_inventories_id',$feed_id)
                                ->where('ponds_aquacultures_id',$pond_aquaculture)
                                ->select('ponds_aquacultures_id','ebi_bait_inventories_id', DB::raw('sum(ebi_baits.amount) as sumAmount, sum(ebi_baits.bait_cost) as sumBaitCost, DATE_FORMAT(ebi_baits.bait_at, "%Y-%m-%d") as formatted_bait_at'))
                                ->groupBy('formatted_bait_at','ponds_aquacultures_id','ebi_bait_inventories_id')
                                ->get()
                                ->toArray();
                    //->whereIn('ebi_bait_inventories_id',$feed_id) 追加　藤井     
                    $amountByDate = [];
                    //get amount and cost old by bait_at and bait_inventories
                    // cost += (sumAmount / amount_per_bag)*fedd_price
                    //ここ
                    foreach($ebiBaits as $bait){
                        $amountByBaitInventories = $bait->ponds_aquacultures_id . '-' . $bait->ebi_bait_inventories_id;
                        $amountByPondsAquacultures = $bait->ponds_aquacultures_id;
                        $amountByDate[$bait->formatted_bait_at][$amountByBaitInventories]['amount'] += $bait->sumAmount;
                        $amountByDate[$bait->formatted_bait_at][$amountByBaitInventories]['cost'] += $bait->sumBaitCost;
                    }
                    // filter old amount according to pond aqua id
                    $sumCostByPondAquaIdOld = [];
                    $sumDataFeddCumulativeOld = [];
                    $arrCostByYearOld = [];
                    $sumAmountByBaitInventoriesOld = [];
                    //ループの中処理されていない。
                    foreach($amountByDate as $date => $valueByDate){
                        $yearUpdate = date('Y', strtotime($date)); 
                        foreach($valueByDate as $key=>$value){
                            $dataKey= explode("-", $key);
                            $pondAquaId = $dataKey[0];
                            $baitInventoriesId = $dataKey[1];
                            $sumCostByPondAquaIdOld[$pondAquaId] += $value['cost'];
                            $sumDataFeddCumulativeOld[$key]['amount'] += $value['amount'];
                            $sumDataFeddCumulativeOld[$key]['cost'] += $value['cost'];
                            $arrCostByYearOld[$yearUpdate] += $value['cost'];
                            $test=$value['cost'];
                            if($yearUpdate==2022){
                            Log::debug("前回2022 $date ".$test);
                            }
                            $sumAmountByBaitInventoriesOld[$baitInventoriesId] += $value['amount'];
                        }
                        //合計値
                      
                        
                    }
                    Log::debug("amountByDate完了");
                    Log::debug(" 2021年　$arrCostByYearOld[2021]");
                    Log::debug(" 2022年　$arrCostByYearOld[2022]");
                    
                    $arrDateUpdateFcr = [];
                    $arrDateUpdateAdg = [];
                    $arrDateUpdateBaitInventories = [];

                    foreach ($data as $key => $value) {
                        $bait_inventories_id1 = null;
                        $bait_inventories_id2 = null;
                        $ponds_aquacultures_id = null;
                        $bait1 = null;
                        $bait2 = null;
                        $adg = null;
                        $fcr = null;

                        $checkInsertFail = false;
                        if (is_numeric($value[0])){
                            $row = trans('ebi.この行のDOCは') . $value[0];
                            if($value[10] || $value[11] || $value[12] || $value[13] || $value[14] || $value[15] || $value[20] || $value[21]){
                                if($value[1]){
                                    $dateFormat = date("Y-m-d", strtotime($value[1]));
                                    $year = $value[1]->year;
                                    if($pondsAquacultures){
                                        foreach($pondsAquacultures as $pondAqua){
                                            if($value[1] >= $pondAqua->created_at && ($value[1] <= $pondAqua->completed_date || !$pondAqua->completed_date)){
                                                $ponds_aquacultures_id = $pondAqua->id;
                                                $ebi_aquacultures_id = $pondAqua->ebi_aquacultures_id;
                                                break;
                                            }
                                        }
                                    }

                                    if(!$ponds_aquacultures_id){
                                        $checkInsertFail = true;
                                        $result[] = [
                                            'row' => $row,
                                            'stt' => '0',
                                            'kq' => trans('ebi.養殖') .$value[1].trans('ebi.が存在しません。'),
                                        ]; 
                                        $totalInsertFail++;
                                        continue;
                                    }

                                    if(array_key_exists((string)$value[10], $ebiBaitInventories)){
                                        $bait_inventories_id1 = $ebiBaitInventories[$value[10]]->id;
                                    }

                                    //check insert Feed1
                                    $ruleBait = [
                                        'ebi_bait_inventories_id' => 'required',
                                        'amount' => 'required|max:9|regex:/^-?[0-9]+(?:\.[0-9]{1,3})?$/',
                                        'baits_amount' => 'required|max:12|regex:/^-?[0-9]+(?:\.[0-9]{1,3})?$/',
                                    ];

                                    Log::debug($value[11]);
                                    Log::debug($ebiBaitInventories[$value[10]]->amount_per_bag);
                                    Log::debug($ebiBaitInventories[$value[10]]->id);
                                    Log::debug($value[10]);
                                    
                                    $bait1 = [
                                        'ponds_aquacultures_id' => $ponds_aquacultures_id,
                                        'ebi_bait_inventories_id' => $bait_inventories_id1,
                                        'bait_at' => $value[1],
                                        'amount' => $value[11],
                                        'baits_amount' => $value[12],
                                        'created_at' => Carbon::now(),
                                        'bait_cost' => ($value[11]/$ebiBaitInventories[$value[10]]->amount_per_bag)*$feed_price[$bait_inventories_id1]
                                    ];

                                    $validatorBait1 = Validator::make($bait1, $ruleBait);
                                    if ($validatorBait1->fails()) {
                                        $error = $validatorBait1->errors()->first();
                                        $checkInsertFail = true;
                                        $result[] = [
                                            'row' => $row,
                                            'stt' => '0',
                                            'kq' => "Feed 1 : ".$error,
                                        ];
                                        
                                    } else {
                                        $sumAmountByBaitInventoriesNew[$bait_inventories_id1]  +=  $value[11];
                                        $arrCostByYearNew[$year] += ($value[11]/$ebiBaitInventories[$value[10]]->amount_per_bag)*$feed_price[$bait_inventories_id1];
                                       
                                        $dataCostFeddCumulativeNew[$ponds_aquacultures_id.'-'.$bait_inventories_id1]['cost'] += ($value[11]/$ebiBaitInventories[$value[10]]->amount_per_bag)*$feed_price[$bait_inventories_id1];
                                        $dataCostFeddCumulativeNew[$ponds_aquacultures_id.'-'.$bait_inventories_id1]['amount'] += $value[11];
                                        array_push($arrBait, $bait1);
                                        if( !isset($arrDateUpdateBaitInventories[$dateFormat]) ||   (isset($arrDateUpdateBaitInventories[$dateFormat]) && !in_array($ebiBaitInventories[$value[10]]->id, $arrDateUpdateBaitInventories[$dateFormat]))){
                                            $arrDateUpdateBaitInventories[$dateFormat][] = $ebiBaitInventories[$value[10]]->id;
                                        }
                                    }

                                    //check insert Feed2
                                    if($value[13] || $value[14] || $value[15]){
                                        if(array_key_exists((string)$value[13], $ebiBaitInventories)){
                                            $bait_inventories_id2 = $ebiBaitInventories[$value[13]]->id;
                                        }
                                        $bait2 = [
                                            'ponds_aquacultures_id' => $ponds_aquacultures_id,
                                            'ebi_bait_inventories_id' => $bait_inventories_id2,
                                            'bait_at' => $value[1],
                                            'amount' => $value[14],
                                            'baits_amount' => $value[15],
                                            'created_at' => Carbon::now(),
                                            'bait_cost' => ($value[14]/$ebiBaitInventories[$value[13]]->amount_per_bag)*$feed_price[$bait_inventories_id2]
                                        ];
                                        $validatorBait2 = Validator::make($bait2, $ruleBait);
                                        if ($validatorBait2->fails()) {
                                            $error = $validatorBait2->errors()->first();
                                            $checkInsertFail = true;
                                            $result[] = [
                                                'row' => $row,
                                                'stt' => '0',
                                                'kq' => "Feed 2 : ".$error,
                                            ]; 
                                        } else {
                                            $sumAmountByBaitInventoriesNew[$bait_inventories_id2]  +=  $value[14];
                                            $arrCostByYearNew[$year] += ($value[14]/$ebiBaitInventories[$value[13]]->amount_per_bag)*$feed_price[$bait_inventories_id2];
                                            
                                            $dataCostFeddCumulativeNew[$ponds_aquacultures_id.'-'.$bait_inventories_id2]['cost'] += ($value[14]/$ebiBaitInventories[$value[13]]->amount_per_bag)*$feed_price[$bait_inventories_id2];
                                            $dataCostFeddCumulativeNew[$ponds_aquacultures_id.'-'.$bait_inventories_id2]['amount'] += $value[14];
                                            array_push($arrBait, $bait2);
                                            if( !isset($arrDateUpdateBaitInventories[$dateFormat]) ||   (isset($arrDateUpdateBaitInventories[$dateFormat]) && !in_array($ebiBaitInventories[$value[13]]->id, $arrDateUpdateBaitInventories[$dateFormat]))){
                                                $arrDateUpdateBaitInventories[ $dateFormat][] = $ebiBaitInventories[$value[13]]->id;
                                            }
                                        }
                                    } 

                                    if( $value[21] && (!$validatorBait1->fails() || !$validatorBait2->fails())){
                                        $ruleAdg = [
                                            'adg' => 'integer|min:1|max:2147483647',
                                        ];

                                        $adg = [
                                            'ponds_aquacultures_id' => $ponds_aquacultures_id,
                                            'adg' => $value[21],
                                            'adg_date' => $value[1],
                                            'created_at' => Carbon::now()
                                        ];

                                        $validatorAdg = Validator::make($adg, $ruleAdg);
                                        if ($validatorAdg->fails()) {
                                            $error = $validatorAdg->errors()->first();
                                            $checkInsertFail = true;
                                            $result[] = [
                                                'row' => $row,
                                                'stt' => '0',
                                                'kq' => $error,
                                            ];
                                        } else {
                                            $result[] = [
                                                'row' => $row,
                                                'stt' => '1',
                                                'kq' => '',
                                            ]; 
                                            array_push($arrAdg, $adg);

                                            if(!in_array( $dateFormat, $arrDateUpdateAdg)){
                                                $arrDateUpdateAdg[] =  $dateFormat;
                                            }
                                        }
                                    }
                                    
                                    if( $value[20] && (!$validatorBait1->fails() || !$validatorBait2->fails())){
                                        $ruleFcr = [
                                            'fcr' => 'integer|min:1|max:2147483647',
                                        ];

                                        $fcr = [
                                            'ponds_aquacultures_id' => $ponds_aquacultures_id,
                                            'fcr' => $value[20],
                                            'fcr_date' => $value[1],
                                            'created_at' => Carbon::now()
                                        ];
                                        $validatorFcr = Validator::make($fcr, $ruleFcr);
                                        if ($validatorFcr->fails()) {
                                            $error = $validatorFcr->errors()->first();
                                            $checkInsertFail = true;
                                            $result[] = [
                                                'row' => $row,
                                                'stt' => '0',
                                                'kq' => $error,
                                            ];
                                        } else {
                                            $result[] = [
                                                'row' => $row,
                                                'stt' => '1',
                                                'kq' => '',
                                            ]; 
                                            array_push($arrFcr, $fcr);
                                            if(!in_array( $dateFormat, $arrDateUpdateFcr)){
                                                $arrDateUpdateFcr[] =  $dateFormat;
                                            }
                                        }
                                    }
                                   
                                }else{
                                    $result[] = [
                                        'row' => $row,
                                        'stt' => '0',
                                        'kq' => trans('ebi.日付を入力しなければなりません'),
                                    ];
                                    $checkInsertFail = true; 
                                }

                                if($checkInsertFail){
                                    $totalInsertFail++;
                                }else{
                                    $totalInsertSuccess++;
                                }
                            }else{
                                if($value[1]){
                                    $result[] = [
                                        'row' => $row,
                                        'stt' => '0',
                                        'kq' => trans('ebi.データが不明です'),
                                    ]; 
                                    $totalInsertFail++;
                                    continue;
                                }
                            }
                        }
                        if(count($arrAdg) >= 100){
                            DB::table('adg')->insert($arrAdg);
                            $arrAdg = [];
                        }
                        
                        if(count($arrFcr) >= 100){
                            DB::table('fcr')->insert($arrFcr);
                            $arrFcr = [];
                        }
                        
                        if(count($arrBait) >= 100){
                            DB::table('ebi_baits')->insert($arrBait);
                            $arrBait = [];
                            $insertLog ++;
                        }
                    }
                    $sumCostByPondAquaIdNew = [];
                    //import new : sum cost by pondAquacultures
                    foreach($dataCostFeddCumulativeNew as $key => $value){
                        $arrKey = explode("-", $key);
                        $pondAquaId = $arrKey[0];
                        $sumCostByPondAquaIdNew[$pondAquaId]  += $value['cost'];
                    }

                    //compute the cost by pondAquacultures after updating of the new import versus the old one
                    // costUpdate = costNew - costOld;
                    $sumCostByPondAqua = [];
                    foreach($sumCostByPondAquaIdNew as $key => $value){
                        if(!isset($sumCostByPondAquaIdOld[$key])){
                            $sumCostByPondAquaIdOld[$key] = 0;
                        }
                        //前回の金額と今回の金額の差分
                       //$sumCostByPondAqua[$key] =$value - $sumCostByPondAquaIdOld[$key]; ←元々はこれ
                        $sumCostByPondAqua[$key] =$value-$oldfeedcost;
                        
                        //デバッグ　$keyと 計算式
                        Log::debug("key=".$key);
                        Log::debug("sumCostByPondAqua(前回と今回の差分)".$sumCostByPondAqua[$key]);
                    }
                    $arrAquacultures = DB::table('ebi_aquacultures')
                                        ->select('id','feed_cumulative','income_and_expenditure')
                                        ->orderBy('id','desc')
                                        ->get()
                                        ->keyBy('id')
                                        ->toArray();

                    //update pondAqua and ebiAqua
                    foreach($pondsAquacultures as $pondAqua){
                        if(array_key_exists($pondAqua->id, $sumCostByPondAqua)){
                            $incomeAndExpenditurePondAqua = $pondAqua->income_and_expenditure - $sumCostByPondAqua[$pondAqua->id];  
                            $feedCumulativePondAqua = $pondAqua->feed_cumulative + $sumCostByPondAqua[$pondAqua->id];
                            DB::table('ponds_aquacultures')
                                ->where('id',$pondAqua->id)
                                ->update([
                                    'income_and_expenditure' => $incomeAndExpenditurePondAqua,
                                    'feed_cumulative' => $feedCumulativePondAqua,
                                    'updated_user' => CRUDBooster::myId(),
                                    'updated_at' => Carbon::now()
                                ]);

                            $incomeAndExpenditureEbiAqua = $arrAquacultures[$pondAqua->ebi_aquacultures_id]->income_and_expenditure - $sumCostByPondAqua[$pondAqua->id];
                            $feedCumulativeEbiAqua = $arrAquacultures[$pondAqua->ebi_aquacultures_id]->feed_cumulative + $sumCostByPondAqua[$pondAqua->id];
                            DB::table('ebi_aquacultures')
                                ->where('id',$pondAqua->ebi_aquacultures_id)
                                ->update([
                                    'income_and_expenditure' => $incomeAndExpenditureEbiAqua,
                                    'feed_cumulative' => $feedCumulativeEbiAqua,
                                    'updated_user' => CRUDBooster::myId(),
                                    'updated_at' => Carbon::now()
                                ]);
                        }
                    }

                    //insert 3 table Adg, Fcr, ebi_baits 
                    if(count($arrAdg)){
                        DB::table('adg')->insert($arrAdg);
                    }
                    
                    if(count($arrFcr)){
                        DB::table('fcr')->insert($arrFcr);
                    }
                    
                    if(count($arrBait)){
                        DB::table('ebi_baits')->insert($arrBait);
                        $insertLog ++;
                    }

                    //delete record in 3 table Adg, Fcr, ebi_baits 
                    if($last_fcr && count($arrDateUpdateFcr)){
                        DB::table('fcr')
                        ->where('id', '<=',  $last_fcr->id)
                        ->whereIn('fcr_date',$arrDateUpdateFcr)
                        ->delete();
                    }

                    if($last_adg && count($arrDateUpdateAdg)){
                        DB::table('adg')
                            ->where('id', '<=',  $last_adg->id)
                            ->whereIn('adg_date',$arrDateUpdateAdg)
                            ->delete();
                    }

                    if($last_bait && count($amountByDate)){
                        foreach($arrDateUpdateBaitInventories as $key => $value){
                            if(array_key_exists($key, $amountByDate)){
                               DB::table('ebi_baits')
                                    ->whereDate('bait_at','=',$key)
                                    ->where('id', '<=',  $last_bait->id)
                                    ->whereIn('ebi_bait_inventories_id', $arrDateUpdateBaitInventories[$key])
                                    ->delete();
                             }
                        }
                    }
                     
                    
                    //update feed_cumulative
                    // cumulative = feed_cumulative.Cumulative + (amount new - amount old)
                    // cost = feed_cumulative.cost + (cost new - cost old)
                    $feedCumulative = DB::table('feed_cumulative')->get()->keyBy('id')->toArray(); 
                    $dataFeddCumulative = [];
                    foreach($dataCostFeddCumulativeNew as $key => $value){
                        $countUpdate =0;
                        $data = explode("-", $key);
                        foreach( $feedCumulative as $feed){
                            if($feed->ponds_aquacultures_id == $data[0] && $feed->ebi_bait_inventories_id == $data[1]){
                                if(!isset($sumDataFeddCumulativeOld[$key]['amount'])){
                                    $sumDataFeddCumulativeOld[$key]['amount'] = 0;
                                }                                
                                if(!isset($sumDataFeddCumulativeOld[$key]['cost'])){
                                    $sumDataFeddCumulativeOld[$key]['cost'] = 0;
                                }
                                //計算　正しい計算
                                DB::table('feed_cumulative')
                                    ->where('ponds_aquacultures_id',$data[0])
                                    ->where('ebi_bait_inventories_id',$data[1])
                                    ->update([
                                        'cumulative' => ($feed->cumulative + ($value['amount'] - $sumDataFeddCumulativeOld[$key]['amount']) ),
                                        'cost' => ($feed->cost + ($value['cost'] - $sumDataFeddCumulativeOld[$key]['cost']) ),
                                        'updated_at' => Carbon::now()
                                    ]);
                                $countUpdate ++;
                                break;
                            }
                        }

                        if(!$countUpdate){
                            $dataFeddCumulative[] = [
                                'ebi_aquacultures_id' => $ebi_aquacultures_id,
                                'ponds_aquacultures_id' => $data[0],
                                'ebi_bait_inventories_id' => $data[1],
                                'cumulative' => $value['amount'],
                                'cost' => $value['cost'],
                                'created_at' => Carbon::now()
                            ];
                        }
                    }

                    if(count($dataFeddCumulative)){
                        DB::table('feed_cumulative')->insert($dataFeddCumulative);
                    }

                    //insert years_report
                    if ($arrCostByYearNew) {
                        $yearsReport = DB::table('years_report')
                            ->select('feed_cumulative','year', 'income_and_expenditure')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->keyBy('year')
                            ->toArray();

                        $insertYearReport = [];

                        
                        foreach ($arrCostByYearNew as $year => $costNew) {
                            if (array_key_exists($year, $yearsReport)) {
                                // 本来はcost_new Log::debug("餌費用合計".$costNew);
                                Log::debug("餌費用合計".$feedCumulativePondAqua);
                                Log::debug("旧餌費用".$oldfeedcost);
                                log::debug("前回費用(年)".$arrCostByYearOld[$year]);
                                log::debug("今回費用(年)".$arrCostByYearNew[$year]);
                                //$costUpdate = $costNew - $arrCostByYearOld[$year];　//年毎の餌費用更新 cost_new　importデータ　年度別にコストを算出　
                                //年末までの暫定対応　
                                //年越すまでの暫定対応 前回import時の餌合計費用 年度別算出の計算が正しくないため(原因はわからない) 759～773 value[cost]に計算すべきでない値が入っている。
                                //元々 $costUpdate = $costNew - $arrCostByYearOld[$yearUpdate];
                                //$costUpdate = $feedCumulativePondAqua - $oldfeedcost
                                $costUpdate = $arrCostByYearNew[$year]- $arrCostByYearOld[$year]; // now, this calculation is correct
                                DB::table('years_report')
                                    ->where('year', $year)
                                    ->update([
                                        'feed_cumulative' => ($yearsReport[$year]->feed_cumulative + $costUpdate),
                                        'income_and_expenditure' => ($yearsReport[$year]->income_and_expenditure - $costUpdate),
                                        'updated_user' => CRUDBooster::myId(),
                                        'updated_at' => Carbon::now()
                                    ]);
                            } else {
                                $insertYearReport[] = [
                                    'year' => $year,
                                    'created_at' => Carbon::now(),
                                    'feed_cumulative' => $costNew,
                                    'income_and_expenditure' => 0 - $costNew,
                                    'created_user' => CRUDBooster::myId()
                                ];
                            }
                        }
                        if (count($insertYearReport)) {
                            DB::table('years_report')->insert($insertYearReport);
                        }
                    }
                    $totalInsert = $totalInsertSuccess + $totalInsertFail;

                    $total['totalInsertSuccess'] = $totalInsertSuccess;
                    $total['totalInsertFail'] = $totalInsertFail;
                    $total['totalInsert'] = $totalInsert;

                    //check insert/update feed_inventory_remaining
                    $feedInventoryRemaining = DB::table('feed_inventory_remaining')->distinct()->pluck('remaining_amount','ebi_bait_inventories_id')->toArray();               
                    
                    $arrInsertFeedInventoryRemaining = [];

                    foreach( $sumAmountByBaitInventoriesNew as $index => $amount){
                        if ($amount != 0) {
                            $stockUpdate = 0;
                            if (array_key_exists($index, $feedInventoryRemaining)) {
                                $sumAmountOld = $arrBaitInventories[$index]->stock * $arrBaitInventories[$index]->amount_per_bag + $sumAmountByBaitInventoriesOld[$index] + $feedInventoryRemaining[$index];
                                $stockOld = floor($sumAmountOld / $arrBaitInventories[$index]->amount_per_bag );
                                $remainingOld =  $sumAmountOld % $arrBaitInventories[$index]->amount_per_bag;
                                $amountRemainingOld = $remainingOld ? ($feedInventoryRemaining[$index] - $remainingOld) : 0;

                                $sumAmountNew = $stockOld * $arrBaitInventories[$index]->amount_per_bag + $amountRemainingOld - $amount;
                                $stockUpdate = floor($sumAmountNew / $arrBaitInventories[$index]->amount_per_bag );
                                $amountRemainingNew = $sumAmountNew % $arrBaitInventories[$index]->amount_per_bag;

                                DB::table('feed_inventory_remaining')
                                    ->where('ebi_bait_inventories_id',$index)
                                    ->update([
                                        'remaining_amount' => $amountRemainingNew,
                                        'updated_at'  => Carbon::now()
                                    ]);
                            }else{
                                $actualNumberBagsUsedNew = $amount/$arrBaitInventories[$index]->amount_per_bag;
                                $roundUpNumberBagsUsed = floor($actualNumberBagsUsedNew) + 1;
                                $remaining_amount = ($roundUpNumberBagsUsed - $actualNumberBagsUsedNew) * $arrBaitInventories[$index]->amount_per_bag;
                                $stockUpdate = $arrBaitInventories[$index]->stock - $roundUpNumberBagsUsed;
                                $arrInsertFeedInventoryRemaining[] =[
                                    'ebi_bait_inventories_id' => $index,
                                    'remaining_amount' => $remaining_amount,
                                    'created_at'  => Carbon::now()
                                ];
                            }
                            if($stockUpdate){
                                DB::table('ebi_bait_inventories')
                                    ->where('id',$index)
                                    ->update([
                                        'stock' => $stockUpdate,
                                        'updated_at'  => Carbon::now()
                                    ]);
                            }
                        }
                    }

                    if(count($arrInsertFeedInventoryRemaining)){
                        DB::table('feed_inventory_remaining')->insert($arrInsertFeedInventoryRemaining);
                    }

                    if ($insertLog !== 0) {
                        $countFileName = DB::table('ebi_import_state_logs')->where('import_type', AppConst::IMPORT_TYPE_BAIT)->where('file_name', $fileName)->count();
                        if ($countFileName) {
                            DB::table('ebi_import_state_logs')
                                ->where('import_type', AppConst::IMPORT_TYPE_BAIT)
                                ->where('file_name', $fileName)
                                ->update(['updated_user' => CRUDBooster::myId(), 'updated_at' => now()]);
                        } else {
                            DB::table('ebi_import_state_logs')->insert(['import_type' => AppConst::IMPORT_TYPE_BAIT,
                                'created_user' => CRUDBooster::myId(), 'created_at' => now(), 'file_name' => $fileName]);
                        }
                    }
                    DB::commit();
                }
               
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error($e->getMessage() . $e->getTraceAsString());
                if (method_exists($e, "failures")) {
                    $failures = $e->failures();
                    foreach ($failures as $failure) {
                        $failure->row(); // row that went wrong
                        $failure->attribute(); // either heading key (if using heading row concern) or column index
                        $failure->errors(); // Actual error messages from Laravel validator
                    }
                }
                $result = null;
            }
            return view('pondstates_import_done', compact('result', 'page_title', 'unmapping', 'unmappingItems', 'total', 'import_type' ));

        }

        public function getImportDrugDone(Request $request)
        {
            //薬
            $page_title = trans('ebi.インポート');
            $import_type = 'import_drug';
            $result = [];
            $total = [];
            try {
                $file = $request->file('f5file');
                if(!$file){
                    $this->fireErrorMessage('f5file', trans('ebi.ファイルを選択してください。'));
                }
                $fileName = $file->getClientOriginalName();
                $ini_file_size = (trim(ini_get("upload_max_filesize")))*1024*1024;
                if (filesize($file->getRealPath()) > $ini_file_size) {
                    $this->fireErrorMessage('f5file', (trans('ebi.max_size')).ini_get("upload_max_filesize")."B");
                }

                // don't check if the file is imported
              /*  $countFileName = DB::table('ebi_import_state_logs')->where('import_type', AppConst::IMPORT_TYPE_DRUG)->where('file_name', $fileName)->count();
                if ($countFileName){
                    $this->fireErrorMessage('f5file', trans('ebi.このファイル名を既にインポートされました。'));
                }*/

                $sheets = Excel::load($file->getRealPath())->get();
                if ($sheets->count()){
                    $data = $sheets[0]->toArray();
                }                

                if (count($data)) {
                    $pondsAquaculture = null;
                    $pondId = (int)$data[1][2];
                    $pondId1 = (int)$data[1][3];
                    $pondId2 = (int)$data[1][4];
                    $pondId3 = (int)$data[1][5];
                    
                    $pond = DB::table('ebi_ponds')->select('id','farm_id')->where('id', $pondId)->first();
                    Log::debug("$pondId");
                    if($pond){
                        $pondsAquaculture = DB::table('ponds_aquacultures')
                            ->select('id','created_at','completed_date','ebi_aquacultures_id','medicine_cumulative','income_and_expenditure')
                            ->where('ebi_ponds_id',$pondId)
                            ->where('status',0)
                            ->orderBy('id','desc')
                            ->get()
                            ->toArray();
                    }else{
                        $this->fireErrorMessage('f5file', trans('ebi.池名が存在しません'));
                    }

                    $baitInventories = DB::table('ebi_bait_inventories')
                        ->where('kind', 1)
                        ->select('id','bait_name','amount_per_bag','stock')
                        ->orderBy('id','desc')
                        ->get();
                    $ebiBaitInventories = $baitInventories->keyBy('bait_name')->toArray();
    
                    //養殖ID
                    $pond_aquaculture = DB::table('ponds_aquacultures')->where('ebi_ponds_id',$pondId)->where('status',0)->value('id');
                    $ebi_bait_inventories_ids=DB::table('ebi_bait_inventories')->where('kind',1)->get();
                    foreach($ebi_bait_inventories_ids as $id){

                        $drug_id[]=$id->id;
        
                    }
                    $ebiBaits = DB::table('ebi_baits')
                        ->select('ponds_aquacultures_id','ebi_bait_inventories_id', DB::raw('sum(ebi_baits.amount) as sumAmount, sum(ebi_baits.bait_cost) as sumBaitCost, DATE_FORMAT(ebi_baits.bait_at, "%Y-%m-%d") as formatted_bait_at'))
                        ->whereIn('ebi_bait_inventories_id',$drug_id)
                        ->where('ponds_aquacultures_id',$pond_aquaculture)
                        ->groupBy('formatted_bait_at','ponds_aquacultures_id','ebi_bait_inventories_id')
                        ->get()
                        ->toArray();

                    $ebiBaitsByDate = [];
                    foreach($ebiBaits as $bait){
                        $key = $bait->ponds_aquacultures_id . '-' . $bait->ebi_bait_inventories_id;
                        $ebiBaitsByDate[$bait->formatted_bait_at][$key]['amount'] += $bait->sumAmount;
                        $ebiBaitsByDate[$bait->formatted_bait_at][$key]['bait_cost'] += $bait->sumBaitCost;
                    }

                    $totalInsertSuccess = 0;
                    $totalInsertFail = 0;
                    $arrBait = [];
                    $feed_price = DB::table('feed_price')->distinct()->pluck('price','ebi_bait_inventories_id')->toArray();
                    $arrCost = [];
                    $dataCostMedicineCumulative = []; // cost and amount in import file, will be insert in db
                    $subtractMedicineCumulative = []; // cost and amount in database until now, before inserted
                    $arrBaitToDel = [];
                    $insertLog = 0;

                    DB::beginTransaction();
                    // Calculating and insert into ebi_baits
                    foreach ($data as $key => $value) {
                        $bait_inventories_id1 = null;
                        $bait_inventories_id2 = null;
                        $bait_inventories_id3 = null;
                        $bait_inventories_id4 = null;
                        $bait_inventories_id5 = null;
                        $ponds_aquaculture_id = null;
                        $year = null;
                        $checkInsertFail = false;
                        $arrBaitInventoriesId = [];

                        if (is_numeric($value[0])) {
                            $row = trans('ebi.この行のDOCは') . $value[0];
                            if ($value[2] || $value[3] || $value[4] || $value[5] || $value[6] || $value[7] || $value[8] || $value[9] || $value[10] || $value[11]) {
                                if ($value[1]) {
                                    $year = $value[1]->year;
                                    if($pondsAquaculture){
                                        foreach($pondsAquaculture as $pondAqua){
                                            if($value[1] >= $pondAqua->created_at && ($value[1] <= $pondAqua->completed_date || !$pondAqua->completed_date)){
                                                $ponds_aquaculture_id = $pondAqua->id;
                                                $ebi_aquaculture_id = $pondAqua->ebi_aquacultures_id;
                                                break;
                                            }
                                        }
                                    }

                                    if(!$ponds_aquaculture_id){
                                        $result[] = [
                                            'row' => $row,
                                            'stt' => '0',
                                            'kq' => trans('ebi.養殖') .$value[1].trans('ebi.が存在しません。'),
                                        ];
                                        $totalInsertFail++;
                                        continue;
                                    }

                                    $ruleBait = [
                                        'ebi_bait_inventories_id' => 'required',
                                        'amount' => 'required|max:9|regex:/^-?[0-9]+(?:\.[0-9]{1,3})?$/',
                                        'baits_amount' => 'required|max:9|regex:/^-?[0-9]+(?:\.[0-9]{1,3})?$/',
                                    ];
                                    $arrMedicine = [];
                                    for ($i = 2; $i <=10; $i +=2 ) {
                                        if ($value[$i] || $value[$i+1]){
                                            $val = [
                                                'bait_name' => $value[$i],
                                                'amount' => $value[$i+1]
                                            ];
                                            array_push($arrMedicine, $val);
                                        }
                                    }
                                    foreach ($arrMedicine as $medicine) {
                                        if (array_key_exists((string)$medicine['bait_name'], $ebiBaitInventories)) {
                                            $bait_inventories_id = $ebiBaitInventories[$medicine['bait_name']]->id;
                                            $bait_cost = ($medicine['amount']/$ebiBaitInventories[$medicine['bait_name']]->amount_per_bag)*$feed_price[$bait_inventories_id];
                                        } else {
                                            $bait_inventories_id = null;
                                            $bait_cost = 0;
                                        }
                                        $bait = [
                                            'ponds_aquacultures_id' => $ponds_aquaculture_id,
                                            'ebi_bait_inventories_id' => $bait_inventories_id,
                                            'bait_at' => $value[1],
                                            'amount' => $medicine['amount'],
                                            'baits_amount' => 0,
                                            'created_at' => Carbon::now(),
                                            'bait_cost' => $bait_cost
                                        ];
                                        $validatorBait = Validator::make($bait, $ruleBait);
                                        if ($validatorBait->fails()) {
                                            $error = $validatorBait->errors()->first();
                                            $checkInsertFail = true;
                                            $result[] = [
                                                'row' => $row,
                                                'stt' => '0',
                                                'kq' => "Medicine: ".$error,
                                            ];
                                        } else {
                                            $arrCost[$year] += $bait_cost;
                                            $dataCostMedicineCumulative[$ponds_aquaculture_id.'-'.$bait_inventories_id]['cost'] += $bait_cost;
                                            $dataCostMedicineCumulative[$ponds_aquaculture_id.'-'.$bait_inventories_id]['amount'] += $medicine['amount'];
                                            array_push($arrBait, $bait);
                                            array_push($arrBaitInventoriesId, $bait_inventories_id);
                                        }
                                    }
                                    if ($arrBaitInventoriesId) {
                                        $arrBaitInventoriesIds = array_unique($arrBaitInventoriesId);
                                        foreach ($arrBaitInventoriesIds as $id) {
                                            $arrBaitToDel[$ponds_aquaculture_id.'_'.$id.'_'.$value[1]] += 1;
                                        }
                                    }
                                } else {
                                    $result[] = [
                                        'row' => $row,
                                        'stt' => '0',
                                        'kq' => trans('ebi.日付を入力しなければなりません'),
                                    ];
                                    $checkInsertFail = true;
                                }
                                if($checkInsertFail){
                                    $totalInsertFail++;
                                }else{
                                    $totalInsertSuccess++;
                                }
                            } else {
                                if($value[1]){
                                    $result[] = [
                                        'row' => $row,
                                        'stt' => '0',
                                        'kq' => trans('ebi.データが不明です'),
                                    ];
                                    $totalInsertFail++;
                                    continue;
                                }
                            }
                        }
                    }
                    $subCostYears = [];
                    $subCostPond = [];
                    $subAmountBait = [];
                    foreach ($arrBaitToDel as $key => $value) {
                        $data = explode("_", $key);
                        $formatBaitAt = date("Y-m-d", strtotime($data[2]));
                        $key = $data[0] . '-' . $data[1];
                        $oldBaitAmount = $ebiBaitsByDate[$formatBaitAt][$key]['amount'];
                        $oldBaitCost = $ebiBaitsByDate[$formatBaitAt][$key]['bait_cost'];

                        if ($oldBaitAmount) {
                            $subtractMedicineCumulative[$data[0].'_'.$data[1]]['amount'] += $oldBaitAmount;
                            $subtractMedicineCumulative[$data[0].'_'.$data[1]]['bait_cost'] += $oldBaitCost;
                            $y = date("Y", strtotime($data[2]));
                            foreach ($baitInventories as $baitInventory) {
                                if ($baitInventory->id == $data[1]) {
                                    $subCostYears[$y] += $oldBaitCost;
                                    $subCostPond[$data[0]] += $oldBaitCost;
                                    $subAmountBait[$data[1]] += $oldBaitAmount;
                                }
                            }
                            DB::table('ebi_baits')
                                ->where('bait_at', $data[2])
                                ->where('ponds_aquacultures_id', $data[0])
                                ->where('ebi_bait_inventories_id', $data[1])
                                ->delete();
                        }
                    }
                    if(count($arrBait)){
                        $baitInsert = array_chunk($arrBait, 100);
                        foreach ($baitInsert as $bait) {
                            DB::table('ebi_baits')->insert($bait);
                            $insertLog ++;
                        }
                    }

                    // Calculating and insert into medicine_cumulative
                    $insertMedicineCumulative = [];
                    $medicineCumulative = DB::table('medicine_cumulative')->get()->keyBy('id')->toArray();
                    foreach($dataCostMedicineCumulative as $key => $value){
                        $countUpdate =0;
                        $data = explode("-", $key);
                        if ($value['amount'] != 0) {
                            foreach($medicineCumulative as $medicine){
                                if($medicine->ponds_aquacultures_id == $data[0] && $medicine->ebi_bait_inventories_id == $data[1]){
                                    $subCost = 0;
                                    $subAmount = 0;
                                    if ($subtractMedicineCumulative[$data[0].'_'.$data[1]]) {
                                        $subCost = $subtractMedicineCumulative[$data[0].'_'.$data[1]]['bait_cost'];
                                        $subAmount = $subtractMedicineCumulative[$data[0].'_'.$data[1]]['amount'];
                                    }
                                    DB::table('medicine_cumulative')
                                        ->where('ponds_aquacultures_id',$data[0])
                                        ->where('ebi_bait_inventories_id',$data[1])
                                        ->update([
                                            'cumulative' => ($medicine->cumulative + $value['amount'] - $subAmount),
                                            'cost' => ($medicine->cost + $value['cost'] - $subCost),
                                            'updated_at' => Carbon::now()
                                        ]);
                                    $countUpdate ++;
                                    break;
                                }
                            }
                            if(!$countUpdate){
                                $insertMedicineCumulative[] = [
                                    'ebi_aquacultures_id' => $ebi_aquaculture_id,
                                    'ponds_aquacultures_id' => $data[0],
                                    'ebi_bait_inventories_id' => $data[1],
                                    'cumulative' => $value['amount'],
                                    'cost' => $value['cost'],
                                    'created_at' => Carbon::now()
                                ];
                            }
                        }
                    }
                    if(count($insertMedicineCumulative)){
                        DB::table('medicine_cumulative')->insert($insertMedicineCumulative);
                    }

                    // Calculating and insert into years_report
                    if ($arrCost) {
                        $year_reports = DB::table('years_report')
                            ->select('medicine_cumulative', 'income_and_expenditure','year')
                            ->orderBy('id', 'desc')
                            ->get()->keyBy('year')->toArray();
                        $insert_year_report = [];
                        foreach ($arrCost as $year => $cost) {
                            if ($cost != 0) {
                                //薬yersreport
                                if (array_key_exists($year, $year_reports)) {
                                    DB::table('years_report')
                                        ->where('year', $year)
                                        ->update([
                                            'medicine_cumulative' => ($year_reports[$year]->medicine_cumulative + $cost - $subCostYears[$year]),
                                            'income_and_expenditure' => ($year_reports[$year]->income_and_expenditure - $cost + $subCostYears[$year]),
                                            'updated_user' => CRUDBooster::myId(),
                                            'updated_at' => Carbon::now()
                                        ]);
                                } else {
                                    $insert_year_report[] = [
                                        'farm_id' => $pond->farm_id,
                                        'year' => $year,
                                        'created_at' => Carbon::now(),
                                        'medicine_cumulative' => $cost,
                                        'income_and_expenditure' => 0 - $cost,
                                        'created_user' => CRUDBooster::myId()
                                    ];
                                }
                            }
                        }
                        if (count($insert_year_report)) {
                            DB::table('years_report')->insert($insert_year_report);
                        }
                    }

                    $sumCost = [];
                    $sumAmount = [];
                    //餌
                    foreach($dataCostMedicineCumulative as $key => $value){
                        $data = explode("-", $key);
                        $sumCost[$data[0]]  += $value['cost'];
                        $sumAmount[$data[1]] += $value['amount'];
                    }

                    //Calculating and insert into ebi_aquacultures and ponds_aqualcultures
                    $ebi_aquaculture = DB::table('ebi_aquacultures')
                        ->select('id','medicine_cumulative','income_and_expenditure')
                        ->where('farm_id',$pond->farm_id)
                        ->orderBy('id', 'desc')
                        ->get()
                        ->keyBy('id')
                        ->toArray();
                    foreach ($pondsAquaculture as $pondAqua) {
                        if (array_key_exists($pondAqua->id, $sumCost)) {
                            if ($sumCost[$pondAqua->id] != 0) {
                                $incomeAndExpenditurePondAqua = $pondAqua->income_and_expenditure - $sumCost[$pondAqua->id] + $subCostPond[$pondAqua->id];
                                $medicineCumulativePondAqua = $pondAqua->medicine_cumulative + $sumCost[$pondAqua->id] - $subCostPond[$pondAqua->id];
                                DB::table('ponds_aquacultures')
                                    ->where('id',$pondAqua->id)
                                    ->update([
                                        'income_and_expenditure' => $incomeAndExpenditurePondAqua,
                                        'medicine_cumulative' => $medicineCumulativePondAqua,
                                        'updated_user' => CRUDBooster::myId(),
                                        'updated_at' => Carbon::now()
                                    ]);
                                $incomeAndExpenditureEbiAqua = $ebi_aquaculture[$pondAqua->ebi_aquacultures_id]->income_and_expenditure - $sumCost[$pondAqua->id] + $subCostPond[$pondAqua->id];
                                $medicineCumulativeEbiAqua = $ebi_aquaculture[$pondAqua->ebi_aquacultures_id]->medicine_cumulative + $sumCost[$pondAqua->id] - $subCostPond[$pondAqua->id];
                                DB::table('ebi_aquacultures')
                                    ->where('id',$pondAqua->ebi_aquacultures_id)
                                    ->update([
                                        'income_and_expenditure' => $incomeAndExpenditureEbiAqua,
                                        'medicine_cumulative' => $medicineCumulativeEbiAqua,
                                        'updated_user' => CRUDBooster::myId(),
                                        'updated_at' => Carbon::now()
                                    ]);
                            }
                        }
                    }

                    //Calculating and insert into feed_inventory_remaining
                    $feedInventoryRemaining = DB::table('feed_inventory_remaining')->distinct()->pluck('remaining_amount','ebi_bait_inventories_id')->toArray();
                    $arrBaitInventories = $baitInventories->keyBy('id')->toArray();
                    $arrInsertFeedInventoryRemaining = [];
                    foreach($sumAmount as $index => $amount){
                        if ($amount != 0) {
                            if (array_key_exists($index, $feedInventoryRemaining)) {
                                if (array_key_exists($index, $subAmountBait)) {
                                    $finalAmount = $arrBaitInventories[$index]->stock * $arrBaitInventories[$index]->amount_per_bag
                                        + $feedInventoryRemaining[$index] + $subAmountBait[$index] - $amount;
                                } else {
                                    $finalAmount = $arrBaitInventories[$index]->stock * $arrBaitInventories[$index]->amount_per_bag
                                        + $feedInventoryRemaining[$index] - $amount;
                                }
                                $stock = intval($finalAmount/$arrBaitInventories[$index]->amount_per_bag);
                                $remaining_amount = $finalAmount - $stock*$arrBaitInventories[$index]->amount_per_bag;
                                DB::table('feed_inventory_remaining')
                                    ->where('ebi_bait_inventories_id',$index)
                                    ->update([
                                        'remaining_amount' => $remaining_amount,
                                        'updated_at'  => Carbon::now()
                                    ]);
                            }else{
                                if (array_key_exists($index, $subAmountBait)) {
                                    $finalAmount = $arrBaitInventories[$index]->stock * $arrBaitInventories[$index]->amount_per_bag
                                        + $subAmountBait[$index] - $amount;
                                } else {
                                    $finalAmount = $arrBaitInventories[$index]->stock * $arrBaitInventories[$index]->amount_per_bag
                                        - $amount;
                                }
                                $stock = intval($finalAmount/$arrBaitInventories[$index]->amount_per_bag);
                                $remaining_amount = $finalAmount - $stock*$arrBaitInventories[$index]->amount_per_bag;
                                $arrInsertFeedInventoryRemaining[] =[
                                    'ebi_bait_inventories_id' => $index,
                                    'remaining_amount' => $remaining_amount,
                                    'created_at'  => Carbon::now()
                                ];
                            }

                            if($stock){
                                DB::table('ebi_bait_inventories')
                                    ->where('id',$index)
                                    ->update([
                                        'stock' => $stock,
                                        'updated_at'  => Carbon::now()
                                    ]);
                            }
                        }
                    }

                    if(count($arrInsertFeedInventoryRemaining)){
                        DB::table('feed_inventory_remaining')->insert($arrInsertFeedInventoryRemaining);
                    }

                    $totalInsert = $totalInsertSuccess + $totalInsertFail;

                    $total['totalInsertSuccess'] = $totalInsertSuccess;
                    $total['totalInsertFail'] = $totalInsertFail;
                    $total['totalInsert'] = $totalInsert;

                    if ($insertLog != 0) {
                        $countFileName = DB::table('ebi_import_state_logs')->where('import_type', AppConst::IMPORT_TYPE_DRUG)->where('file_name', $fileName)->count();
                        if ($countFileName) {
                            DB::table('ebi_import_state_logs')
                                ->where('import_type', AppConst::IMPORT_TYPE_DRUG)
                                ->where('file_name', $fileName)
                                ->update(['updated_user' => CRUDBooster::myId(), 'updated_at' => now()]);
                        } else {
                            DB::table('ebi_import_state_logs')->insert(['import_type' => AppConst::IMPORT_TYPE_DRUG,
                                'created_user' => CRUDBooster::myId(), 'created_at' => now(), 'file_name' => $fileName]);
                        }
                    }
                    DB::commit();
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error($e->getMessage() . $e->getTraceAsString());
                if (method_exists($e, "failures")) {
                    $failures = $e->failures();
                    foreach ($failures as $failure) {
                        $failure->row(); // row that went wrong
                        $failure->attribute(); // either heading key (if using heading row concern) or column index
                        $failure->errors(); // Actual error messages from Laravel validator
                    }
                }
                $result = null;
            }
            return view('pondstates_import_done', compact('result', 'page_title', 'unmapping', 'unmappingItems', 'total', 'import_type' ));
                   
        }
    }