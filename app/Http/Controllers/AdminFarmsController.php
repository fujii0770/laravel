<?php namespace App\Http\Controllers;

	use CRUDBooster;
    use DB;
    use Request;
    use Session;

    class AdminFarmsController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "farm_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = false;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "ebi_farms";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
            $this->col[] = ["label"=>trans('ebi.養殖場名'),"name"=>"farm_name"];
		//	$this->col[] = ["label"=>"養殖場ID","name"=>"id"];
			$this->col[] = ["label"=>trans('ebi.国'),"name"=>"country_id","join"=>"ebi_countries,country_name"];
		//	$this->col[] = ["label"=>"削除フラグ","name"=>"disable_flag", 'callback_php'=>'App\Http\Controllers\Helper::toDisableFlagLabel($row->disable_flag)'];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>trans('ebi.養殖場名'),'name'=>'farm_name','type'=>'text','validation'=>'required|min:1|max:50','width'=>'col-sm-10'];
			$this->form[] = ['label'=>trans('ebi.国'),'name'=>'country_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'ebi_countries,country_name'];

		/*	$pondColumns = array();
            $pondColumns[] = ['label'=>'ID','name'=>'id','type'=>'hidden','col_hide'=>true];
            $pondColumns[] = ['label'=>'養殖池名','name'=>'pond_name','type'=>'text','required'=>true,'validation'=>'required|min:1|max:50','width'=>'col-sm-10'];
            $pondColumns[] = ['label'=>'縦幅	','name'=>'pond_vertical_width','type'=>'decimal','required'=>true,'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];
            $pondColumns[] = ['label'=>'横幅	','name'=>'pond_width','type'=>'decimal','required'=>true,'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];
            $pondColumns[] = ['label'=>'水量','name'=>'water_amount','type'=>'decimal','required'=>true,'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];
            $pondColumns[] = ['label'=>'水深	','name'=>'water_dept','type'=>'decimal','required'=>true,'validation'=>'required|integer|min:0|max:2147483647','width'=>'col-sm-10'];
            $pondColumns[] = ['label'=>'緯度経度	','name'=>'pond_lonlat','type'=>'text','width'=>'col-sm-10'];

			$this->form[] = ['label'=>'養殖池','child_form_label' =>'養殖池の情報','child_table_label' =>'養殖場の養殖池','child_form_add_to'=>'養殖場に追加','child_form_validation_msg'=>'養殖池の情報が間違っている',
                    'name'=>'id','type'=>'child','validation'=>'required','width'=>'col-sm-10','table'=>'ebi_ponds','foreign_key'=>'farm_id','columns'=>$pondColumns];*/
			# END FORM DO NOT REMOVE THIS LINE

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

        public function getIndex(){
            if (Session::get('current_pond')){
                CRUDBooster::redirect(CRUDBooster::adminPath('ponds/edit/'.Session::get('current_pond')), '');
            }else{
                CRUDBooster::redirect(CRUDBooster::adminPath(), '');
            }
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
            // remove user farm
            DB::table('ebi_user_farms')->where('farm_id', $id)->delete();

            $ponds = DB::table('ebi_ponds')->select('id')->where('farm_id', $id)->get();
            $pondIds = array();
            foreach ($ponds as $pond){
                $pondIds[] = $pond->id;
            }
            // remove minmax
            DB::table('ebi_minmaxs')->whereIn('farm_id', $pondIds)->delete();

            // remove pond state
            DB::table('ebi_pond_states')->whereIn('farm_id', $pondIds)->delete();

            // remove pond
            DB::table('ebi_ponds')->whereIn('farm_id', $pondIds)->delete();
	    }

        protected function hook_query_select(&$result) {
            if(!CRUDBooster::isSuperadmin()){
                $myFarms = $this->getMyFarms();

                $result->whereIn('ebi_farms.id', $myFarms);
            }
        }

        public function getEdit($id){
            if (!$this->checkFarmPermission($id)){
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
            }
            return parent::getEdit($id);
        }

        public function postEditSave($id){
            if (!$this->checkFarmPermission($id)){
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
            }
            parent::postEditSave($id);
        }
	    //By the way, you can still create your own method in here... :) 


	}