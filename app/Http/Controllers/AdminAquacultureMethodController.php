<?php namespace App\Http\Controllers;

    use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminAquacultureMethodController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "0";
			$this->orderby = "id,desc";
			$this->button_table_action = false;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_text";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "aquaculture_method";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans('ebi.養殖方法'),"name"=>"method"];
			$this->col[] = ["label"=>trans('ebi.移行回数'),"name"=>"grade"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>trans('ebi.養殖方法'),'name'=>'method','type'=>'text', 'minlength'=>'1', 'maxlength'=>'50', 'validation'=>'required|min:1|max:50','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.移行回数'),'name'=>'grade','type'=>'number', 'min'=>"0", 'step'=>'1','validation'=>'required|min:0|max:2147483647','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Method","name"=>"method","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Grade","name"=>"grade","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
                $this->data['templateHtml'] = '<tr class="add-new">'.
                    '<td><input class="column-title shrimp-setting method" name="shrimp-setting[method][index-shrimp-setting]" required type="text" minlength="1" maxlength="50" value= ""></td>'.
                    '<td><input class="column-title" name="shrimp-setting[grade][index-shrimp-setting]" type="number" step="1" min="0" max="2147483647" required value= ""></td>'.
                    '<td><input class="column-title" name="shrimp-setting[id][index-shrimp-id]" type="checkbox" value="0"></td>'.
                    '</tr>';
                $this->data['postUrl'] = route('postAquacultureMethod');
            }
	        
	    }

        protected function getIndexViewName()
        {
            return 'admin_unit_price_table';
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
        public function hook_query_select(&$result) {
            $result->select(['aquaculture_method.method','aquaculture_method.grade','aquaculture_method.id'])->orderBy('method', 'asc');
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

        private function validatePostData($method, $grade, $methods){
            if(trim($method) === '') {
                $this->fireErrorMessage('method', trans('crudbooster.this_field_is_required'));
            } elseif (mb_strlen($method) > 50) {
                $this->fireErrorMessage('method', trans('ebi.text_max_length'));
            }
            if($methods) {
                $count = 0;
                foreach ($methods as $item) {
                    if ($item === trim($method)){
                        $count++;
                    }
                }
                if ($count > 1) {
                    $this->fireErrorMessage('method', trans('ebi.duplicate_method'));
                }
            }
            if(trim($grade) === '') {
                $this->fireErrorMessage('grade', trans('crudbooster.this_field_is_required'));
            } elseif ($grade < 0 || $grade > 2147483647) {
                $this->fireErrorMessage('grade', trans('ebi.number_max_value'));
            }
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

        protected function hook_page_title($page_title) {
            return trans('ebi.養殖方法登録');
        }
        
	    //By the way, you can still create your own method in here... :) 
        public function postAquacultureMethod(\Illuminate\Http\Request $request) {
            $methods = $request['shrimp-setting']['method'];
            $grades = $request['shrimp-setting']['grade'];
            $AquacultureMethodInsert = [];
            if (empty($methods)) {
                CRUDBooster::redirect(CRUDBooster::adminPath('aquaculture_method'), '');
            } else {
                foreach($methods as $index=>$method){
                    $this->validatePostData($method, $grades[$index], $methods);
                    if(isset($request['shrimp-setting']['id']) && array_key_exists($index, $request['shrimp-setting']['id'])){
                        continue;
                    }
                    $id= end(explode('-',$index));
                    if($id == 0){
                        $AquacultureMethodInsert[] = [
                            'method' => $method,
                            'grade' => $grades[$index]
                        ];
                        continue;
                    }
                    DB::table('aquaculture_method')
                        ->where('id',$id)
                        ->update([
                            'method' => $method,
                            'grade' => $grades[$index]
                        ]);
                }
                if(count($AquacultureMethodInsert)){
                    DB::table('aquaculture_method')->insert($AquacultureMethodInsert);
                }
                if(isset($request['shrimp-setting']['id'])){
                    foreach ($request['shrimp-setting']['id'] as $i) {
                        try {
                            DB::table('aquaculture_method')
                                ->where('id',$i)
                                ->delete();
                        } catch (\Exception $ex) {
                            CRUDBooster::redirect(CRUDBooster::adminPath('aquaculture_method'), trans("crudbooster.alert_delete_data_failed"), 'danger');
                        }
                    }
                }
                CRUDBooster::redirect(CRUDBooster::adminPath('aquaculture_method'), trans("crudbooster.alert_update_data_success"), 'success');
            }
        }

	}