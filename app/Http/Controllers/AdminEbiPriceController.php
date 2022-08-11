<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use Carbon\Carbon;
	use CRUDBooster;
	use Illuminate\Http\Request;

	class AdminEbiPriceController extends AdminBaseController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "0";
			$this->orderby = "id,desc";
			$this->button_table_action = false;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "ebi_price";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans('ebi.1匹辺りの重さ(g)'),"name"=>"weight"];
			$this->col[] = ["label"=>trans('ebi.1kg辺りの価格(ペソ)'),"name"=>"ebi_price"];
			$this->col[] = ["label"=>trans('ebi.エビ種類'),"name"=>"ebi_kind_id","join"=>"ebi_kind,kind"];

			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'1匹辺りの重さ(g)','name'=>'weight','type'=>'number',"min"=>"0.01", 'step'=>'0.01','validation'=>'required|numeric|min:0.01|max:999999.99','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'1kg辺りの価格(ペソ)','name'=>'ebi_price','type'=>'number',"min"=>"0.01", 'step'=>'0.01','validation'=>'required|numeric|min:0.01|max:999999.99','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'品種ID','name'=>'ebi_kind_id','type'=>'select2','width'=>'col-sm-9','datatable'=>'ebi_kind,kind'];
			$this->form[] = ['label'=>'作成日時','name'=>'created_at','type'=>'datetime','width'=>'col-sm-9'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'1匹辺りの重さ(g)','name'=>'weight','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'1kg辺りの価格(ペソ)','name'=>'ebi_price','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'品種ID','name'=>'ebi_kind_id','type'=>'select2','width'=>'col-sm-9', 'datatable'=>'ebi_kind,kind'];
			//$this->form[] = ['label'=>'作成日時','name'=>'created_at','type'=>'datetime','width'=>'col-sm-9'];
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
				$this->data['ebiKindList'] = DB::table('ebi_kind')->select('id','kind')->get();
				$this->data['templateHtml'] = '<tr class="add-new">'.
					'<td><input class="column-title shrimp-setting" name="shrimp-setting[weight][index-shrimp-setting]" type="number" step="0.01" min="0.01" max="999999.99" required value= ""></td>'.
					'<td><input class="column-title" name="shrimp-setting[ebi_price][index-shrimp-setting]" type="number" step="0.01" min="0.01" max="999999.99" required value= ""></td>'.
					'<td>'.
						'<select class="column-title" name="shrimp-setting[ebi_kind_id][index-shrimp-setting]">';
							foreach($this->data['ebiKindList'] as $item){
								$this->data['templateHtml'] .= '<option value="'.$item->id.'">'.$item->kind.'</option>';
							}
							$this->data['templateHtml'] .='</select>'.
					'</td>'.
					'<td><input class="column-title newbie" name="shrimp-setting[id][index-shrimp-id]" type="checkbox" value="0"></td>'.
				'</tr>';
				$this->data['postUrl'] = route('postPriceSave');
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

			$result->select(['ebi_price.weight','ebi_price.ebi_price','ebi_price.ebi_kind_id','ebi_price.id'])->orderBy('weight', 'asc');
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

        private function validatePostData($weight, $price, $kind){
            if(trim($weight) === '') {
                $this->fireErrorMessage('weight', trans('crudbooster.this_field_is_required'));
            } elseif ($weight < 0.01 || $weight > 999999.99) {
                $this->fireErrorMessage('weight', trans('ebi.number_max_value'));
            }
            if(trim($price) === '') {
                $this->fireErrorMessage('price', trans('crudbooster.this_field_is_required'));
            } elseif ($price < 0.01 || $price > 999999.99) {
                $this->fireErrorMessage('price', trans('ebi.number_max_value'));
            }
            if(trim($kind) === '') {
                $this->fireErrorMessage('ebi_kind_id', trans('crudbooster.this_field_is_required'));
            } elseif ($kind < 1 || $kind > 1073741823) {
                $this->fireErrorMessage('ebi_kind_id', trans('ebi.number_max_value'));
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
		
		public function postPriceSave(Request $request) {
			$weights = $request['shrimp-setting']['weight'];
			$prices = $request['shrimp-setting']['ebi_price'];
			$kinds = $request['shrimp-setting']['ebi_kind_id'];

			$ebiPriceInsert = [];
            if (empty($weights)) {
                CRUDBooster::redirect(CRUDBooster::adminPath('ebi_price'), '');
            } else {
                foreach($weights as $index=>$weight){
                    $this->validatePostData($weight, $prices[$index], $kinds[$index]);
                    if(isset($request['shrimp-setting']['id']) && array_key_exists($index, $request['shrimp-setting']['id'])){
                        continue;
                    }
                    $id= end(explode('-',$index));
                    if($id == 0){
                        $ebiPriceInsert[] = [
                            'weight' => $weight,
                            'ebi_price' => $prices[$index],
                            'ebi_kind_id' => $kinds[$index],
                            'created_at' => Carbon::now()
                        ];
                        continue;
                    }
                    DB::table('ebi_price')
                        ->where('id',$id)
                        ->update([
                            'weight' => $weight,
                            'ebi_price' => $prices[$index],
                            'ebi_kind_id' => $kinds[$index],
                            'updated_at' => Carbon::now()
                        ]);
                }
                if(count($ebiPriceInsert)){
                    DB::table('ebi_price')->insert($ebiPriceInsert);
                }
                if(isset($request['shrimp-setting']['id'])){
                    DB::table('ebi_price')
                        ->whereIn('id',$request['shrimp-setting']['id'])
                        ->delete();
                }
                CRUDBooster::redirect(CRUDBooster::adminPath('ebi_price'), trans("crudbooster.alert_update_data_success"), 'success');
            }
		}

	    //By the way, you can still create your own method in here... :) 


	}