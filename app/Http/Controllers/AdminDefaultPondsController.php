<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Validator;

	class AdminDefaultPondsController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
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
			$this->button_cancel = false;
			$this->form_layout_two = true;
			$this->table = "default_ponds";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans("ebi.水量"),"name"=>"water_amount"];
			$this->col[] = ["label"=>trans("ebi.横幅"),"name"=>"pond_width"];
			$this->col[] = ["label"=>trans("ebi.水深"),"name"=>"water_dept"];
			$this->col[] = ["label"=>trans("ebi.縦幅"),"name"=>"pond_vertical_width"];
			$this->col[] = ["label"=>trans("ebi.緯度経度").trans("ebi.南東"),"name"=>"lat_long_se"];
			$this->col[] = ["label"=>trans("ebi.緯度経度").trans("ebi.北東"),"name"=>"lat_long_ne"];
			$this->col[] = ["label"=>trans("ebi.池用途"),"name"=>"ponds_kind_id","join"=>"ponds_kind,id"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			$this->form[] = ['label'=>trans("ebi.水量"),'name'=>'water_amount','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.北西"),'name'=>'lat_long_nw','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

			$this->form[] = ['label'=>trans("ebi.横幅"),'name'=>'pond_width','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.北東"),'name'=>'lat_long_ne','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

			$this->form[] = ['label'=>trans("ebi.水深"),'name'=>'water_dept','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.南西"),'name'=>'lat_long_sw','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

			$this->form[] = ['label'=>trans("ebi.縦幅"),'name'=>'pond_vertical_width','type'=>'text','validation'=>'required|numeric|min:0|max:99999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.緯度経度").trans("ebi.南東"),'name'=>'lat_long_se','type'=>'text','validation'=>'max:50','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.養殖方法"),'name'=>'pond_method','type'=>'select2','width'=>'col-sm-8', 'label_width' => 'col-sm-4','dataenum'=>"0|".trans('ebi.レースウェイ').";1|".trans('ebi.クラシック').";2|".trans('ebi.バケツ').";3|".trans('ebi.バイオフロック'), 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.タグ").'1','name'=>'tag1','type'=>'text','validation'=>'max:255|unique:default_ponds,tag1|unique:default_ponds,tag2|unique:default_ponds,tag3|unique:default_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

			$this->form[] = ['label'=>trans("ebi.測定範囲のバッファ"),'name'=>'delta_measure','type'=>'text','validation'=>'numeric|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.タグ").'2','name'=>'tag2','type'=>'text','validation'=>'max:255|unique:default_ponds,tag1|unique:default_ponds,tag2|unique:default_ponds,tag3|unique:default_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

			$this->form[] = ['label'=>trans("ebi.稚エビ投入数"),'name'=>'ebi_amount','type'=>'number',"min"=>"0",'validation'=>'integer|min:0|max:2147483647','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.タグ").'3','name'=>'tag3','type'=>'text','validation'=>'max:255|unique:default_ponds,tag1|unique:default_ponds,tag2|unique:default_ponds,tag3|unique:default_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

			$this->form[] = ['label'=>trans("ebi.池用途"),'name'=>'ponds_kind_id','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'datatable'=>'ponds_kind,pond_kind'];
			$this->form[] = ['label'=>trans("ebi.タグ").'4','name'=>'tag4','type'=>'text','validation'=>'max:255|unique:default_ponds,tag1|unique:default_ponds,tag2|unique:default_ponds,tag3|unique:default_ponds,tag4','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>trans("ebi.ナノバブル"),'name'=>'nano_bubble','type'=>'select2','validation'=>'integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'datatable'=>'nano_bubble,nano_bubble'];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//
			//$this->form[] = ['label'=>'水量','name'=>'water_amount','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'緯度経度（南東）','name'=>'lat_long_se','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'横幅','name'=>'pond_width','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'緯度経度（北東）','name'=>'lat_long_ne','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'水深','name'=>'water_dept','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'緯度経度（北西）','name'=>'lat_long_nw','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'縦幅','name'=>'pond_vertical_width','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'緯度経度（南西）','name'=>'lat_long_sw','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'養殖場id','name'=>'farm_id','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'datatable'=>'ebi_farms,id', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'タグ1','name'=>'tag1','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'タグ2','name'=>'tag2','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'養殖方法','name'=>'pond_method','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'タグ3','name'=>'tag3','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'測定範囲のバッファ','name'=>'delta_measure','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'タグ4','name'=>'tag4','type'=>'text','validation'=>'min:1|max:255','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			//$this->form[] = ['label'=>'稚エビ投入数','name'=>'ebi_amount','type'=>'number','validation'=>'integer|min:0','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
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
				$('input[name=\"submit\"]').click(function(){
					var yourString  = $('input[name=\"ebi_amount\"]').val();
					yourString = Number(yourString).toString();
					$('input[name=\"ebi_amount\"]').val(yourString);
				  });
			})";


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
			//Your code here
			if($postdata['delta_measure']){
				$postdata['delta_measure'] = (float)$postdata['delta_measure'];
			}
			$this->validatePostData($postdata, null);

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
			if($postdata['delta_measure']){
				$postdata['delta_measure'] = (float)$postdata['delta_measure'];
			}
            $this->validatePostData($postdata, $id);
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
            return trans("ebi.基本情報設定");
        }


        protected function hook_query_edit(&$row)
        {

        }

        public function getAdd(){
            $defaultPond = DB::table('default_ponds')->first();
            if ($defaultPond) {
                CRUDBooster::redirect(CRUDBooster::mainPath("edit/$defaultPond->id"), '');
            }else{
                return parent::getAdd();
            }
        }

        public function getIndex(){
            CRUDBooster::redirect(CRUDBooster::mainPath("setting"), '');
        }


	    //By the way, you can still create your own method in here... :)
        public function getSetting(){
            $defaultPond = DB::table('default_ponds')->first();
            if ($defaultPond){
                CRUDBooster::redirect(CRUDBooster::mainPath("edit/$defaultPond->id"), '');
            }else{
                CRUDBooster::redirect(CRUDBooster::mainPath("add"), '');
            }
		}
		
		private function validatePostData($postdata, $id){    
			$arrTag1 = [$postdata['tag2'],$postdata['tag3'],$postdata['tag4']];
			$arrTag2 = [$postdata['tag1'],$postdata['tag3'],$postdata['tag4']];
			$arrTag3 = [$postdata['tag1'],$postdata['tag2'],$postdata['tag4']];
			$arrTag4 = [$postdata['tag1'],$postdata['tag2'],$postdata['tag3']];

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
			
            $this->validateLatLongGPS($postdata['lat_long_nw'] , 'lat_long_nw', trans('ebi.North_west'));
            $this->validateLatLongGPS($postdata['lat_long_ne'], 'lat_long_ne', trans('ebi.North_east'));
            $this->validateLatLongGPS($postdata['lat_long_sw'], 'lat_long_sw', trans('ebi.South_west'));
			$this->validateLatLongGPS($postdata['lat_long_se'], 'lat_long_se', trans('ebi.South_east'));
			$this->validateSpace($postdata['delta_measure'], 'delta_measure', trans('ebi.測定範囲のバッファ'));
            $this->validateSpace($postdata['water_amount'], 'water_amount', trans('ebi.水量'));
            $this->validateSpace($postdata['pond_width'], 'pond_width', trans('ebi.横幅'));
            $this->validateSpace($postdata['water_dept'], 'water_dept', trans('ebi.水深'));
            $this->validateSpace($postdata['pond_vertical_width'], 'pond_vertical_width', trans('ebi.縦幅'));
		}
		
		private function validateLatLongGPS($lat_long, $name = "", $title = ""){
			if(trim($lat_long)== "") return true;

            $arr_lat_long = explode(",", trim($lat_long));
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