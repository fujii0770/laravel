<?php namespace App\Http\Controllers;

    use Illuminate\Support\Facades\Log;
    use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminEbiKindController extends AdminBaseController {

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
			$this->table = "ebi_kind";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans('ebi.エビ名'),"name"=>"kind"];
			$this->col[] = ["label"=>trans('ebi.育成日数'),"name"=>"training_period"];
			$this->col[] = ["label"=>trans('ebi.単価'),"name"=>"price"];
			$this->col[] = ["label"=>trans('ebi.養殖方法'),"name"=>"aquaculture_method_id","join"=>"aquaculture_method,method"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>trans('ebi.エビ名'),'name'=>'kind','type'=>'text', 'minlength'=>'1', 'maxlength'=>'50','validation'=>'required|min:1|max:50','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.育成日数'),'name'=>'training_period','type'=>'number',"min"=>"1","step"=>"1",'validation'=>'required|integer|min:1|max:2147483647','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.単価'),'name'=>'price','type'=>'number',"min"=>"0.01", 'step'=>'0.01','validation'=>'required|min:0.01|max:999999.99','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.養殖方法'),'name'=>'aquaculture_method_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'aquaculture_method,method'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Kind","name"=>"kind","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Training Period","name"=>"training period","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Price","name"=>"price","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Aquaculture Method Id","name"=>"aquaculture_method_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"aquaculture_method,id"];
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
                $this->data['aquacultureMethods'] = DB::table('aquaculture_method')->select('id','method')->get();
                $this->data['templateHtml'] = '<tr class="add-new">'.
                    '<td><input class="column-title shrimp-setting kind" name="shrimp-setting[kind][index-shrimp-setting]" type="text" minlength="1" maxlength="50" required value= ""></td>'.
                    '<td><input class="column-title" name="shrimp-setting[training_period][index-shrimp-setting]" type="number" min="1" max="2147483647" required value= ""></td>'.
                    '<td><input class="column-title" name="shrimp-setting[price][index-shrimp-setting]" type="number" step="0.01" min="0.01" max="999999.99" required value= ""></td>'.
                    '<td>'.
                        '<select class="column-title method_id" name="shrimp-setting[aquaculture_method_id][index-shrimp-setting]">';
                            foreach($this->data['aquacultureMethods'] as $item){
                                $this->data['templateHtml'] .= '<option value="'.$item->id.'">'.$item->method.'</option>';
                            }
                            $this->data['templateHtml'] .='</select>'.
                    '</td>'.
                    '<td><input class="column-title" name="shrimp-setting[id][index-shrimp-id]" type="checkbox" value="0"></td>'.
                    '</tr>';
                $this->data['postUrl'] = route('postEbiKind');
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

            $result->select(['ebi_kind.kind','ebi_kind.training_period','ebi_kind.price','ebi_kind.aquaculture_method_id','ebi_kind.id'])->orderBy('kind', 'asc');
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

        private function validatePostData($kind, $trainingPeriod, $price, $aquacultureMethod){
            if(trim($kind) === '') {
                $this->fireErrorMessage('kind', trans('crudbooster.this_field_is_required'));
            } elseif (mb_strlen($kind) > 50) {
                $this->fireErrorMessage('kind', trans('ebi.text_max_length'));
            }
            if(trim($trainingPeriod) === '') {
                $this->fireErrorMessage('training_period', trans('crudbooster.this_field_is_required'));
            } elseif ($trainingPeriod > 2147483647) {
                $this->fireErrorMessage('training_period', trans('ebi.number_max_value'));
            }elseif($trainingPeriod < 1){
				$this->fireErrorMessage('training_period', trans('ebi.育成日数は０以上を記入してください。'));
			}
            if(trim($price) === '') {
                $this->fireErrorMessage('price', trans('crudbooster.this_field_is_required'));
            } elseif ($price < 0.01 || $price > 999999.99) {
                $this->fireErrorMessage('price', trans('ebi.number_max_value'));
            }
            if(trim($aquacultureMethod) === '') {
                $this->fireErrorMessage('aquaculture_method_id', trans('crudbooster.this_field_is_required'));
            } elseif ($aquacultureMethod < 1 || $aquacultureMethod > 1073741823) {
                $this->fireErrorMessage('aquaculture_method_id', trans('ebi.number_max_value'));
            }
        }
        private function checkDuplicate($request) {
            $kinds = $request['kind'];
            $aquacultureMethods = $request['aquaculture_method_id'];
            $kind_appearances = array_count_values($kinds);
            if (!empty($kind_appearances)) {
                foreach($kind_appearances as $item=>$kind_appearance){
                    if ($kind_appearance > 1) {
                        $methods = [];
                        foreach ($kinds as $index=>$kind) {
                            if ($kind == $item) {
                                $methods[] = $aquacultureMethods[$index];
                            }
                        }
                        if (!empty($methods)) {
                            $count = array_count_values($methods);
                            foreach ($count as $i=>$method) {
                                if ($method > 1) {
                                    $this->fireErrorMessage('kind', trans('ebi.duplicate_kind'));
                                }
                            }
                        }
                    }
                }
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

	    //By the way, you can still create your own method in here... :) 
        public function postEbiKind(\Illuminate\Http\Request $request) {
            $kinds = $request['shrimp-setting']['kind'];
            $trainingPeriods = $request['shrimp-setting']['training_period'];
            $prices = $request['shrimp-setting']['price'];
            $aquacultureMethods = $request['shrimp-setting']['aquaculture_method_id'];

            $ebiKindInsert = [];
            if (empty($kinds)) {
                CRUDBooster::redirect(CRUDBooster::adminPath('ebi_kind'), '');
            } else {
                foreach($kinds as $index=>$kind){
                    $this->validatePostData($kind, $trainingPeriods[$index], $prices[$index], $aquacultureMethods[$index]);
                    $this->checkDuplicate($request['shrimp-setting']);
                    if(isset($request['shrimp-setting']['id']) && array_key_exists($index, $request['shrimp-setting']['id'])){
                        continue;
                    }
                    $id= end(explode('-',$index));
                    if($id == 0){
                        $ebiKindInsert[] = [
                            'kind' => $kind,
                            'training_period' => $trainingPeriods[$index],
                            'price' => $prices[$index],
                            'aquaculture_method_id' => $aquacultureMethods[$index]
                        ];
                        continue;
                    }
                    DB::table('ebi_kind')
                        ->where('id',$id)
                        ->update([
                            'kind' => $kind,
                            'training_period' => $trainingPeriods[$index],
                            'price' => $prices[$index],
                            'aquaculture_method_id' => $aquacultureMethods[$index]
                        ]);
                }
                if(count($ebiKindInsert)){
                    DB::table('ebi_kind')->insert($ebiKindInsert);
                }
                if(isset($request['shrimp-setting']['id'])){
                    foreach ($request['shrimp-setting']['id'] as $i) {
                        try {
                            DB::table('ebi_kind')
                                ->whereIn('id',$request['shrimp-setting']['id'])
                                ->delete();
                        } catch (\Exception $ex) {
                            CRUDBooster::redirect(CRUDBooster::adminPath('ebi_kind'), trans("crudbooster.alert_delete_data_failed"), 'danger');
                        }
                    }
                }
                CRUDBooster::redirect(CRUDBooster::adminPath('ebi_kind'), trans("crudbooster.alert_update_data_success"), 'success');
            }
        }

	}