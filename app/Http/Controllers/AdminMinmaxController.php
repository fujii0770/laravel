<?php namespace App\Http\Controllers;

	use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Route;
    use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminMinmaxController extends AdminBaseController {

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
			$this->table = "ebi_minmaxs";
            $this->button_addmore = false;
            $this->form_layout_two = true;
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>trans("ebi.ユーザ"),"name"=>"ebi_minmaxs.user_id","join"=>"cms_users,name"];
			$this->col[] = ["label"=>trans("ebi.発効日"),"name"=>"start_date"];
			# END COLUMNS DO NOT REMOVE THIS LINE

            $id = Route::current()->parameters()['one'];

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
            $this->form[] = ['label'=>'pH'.trans("ebi.下限"),'name'=>'ph_values_min','type'=>'number', 'step'=>0.01 , 'min'=> 0, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>'pH'.trans("ebi.上限"),'name'=>'ph_values_max','type'=>'number', 'step'=>0.01, 'min'=> 0, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:ph_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>'mV(pH)'.trans("ebi.下限"),'name'=>'mv_values_min','type'=>'number', 'min'=> -999999.99, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:-999999.99,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>'mV(pH)'.trans("ebi.上限"),'name'=>'mv_values_max','type'=>'number','min'=> -999999.99, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:-999999.99,999999.99|gte:mv_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.酸化還元電位").trans("ebi.下限"),'name'=>'orp_values_min','type'=>'number','min'=> -999999.99, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:-999999.99,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.酸化還元電位").trans("ebi.上限"),'name'=>'orp_values_max','type'=>'number','min'=> -999999.99, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:-999999.99,999999.99|gte:orp_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.導電率").trans("ebi.下限"),'name'=>'ec_values_min','type'=>'number', 'step'=>0.01, 'min'=> 0, 'max'=>999999.99,'validation'=>'between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.導電率").trans("ebi.上限"),'name'=>'ec_values_max','type'=>'number', 'step'=>0.01, 'min'=> 0, 'max'=>999999.99,'validation'=>'between:0,999999.99|gte:ec_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.絶対EC分解単位").trans("ebi.下限"),'name'=>'ec_abs_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.絶対EC分解単位").trans("ebi.上限"),'name'=>'ec_abs_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:ec_abs_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.低効率").trans("ebi.下限"),'name'=>'res_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.低効率").trans("ebi.上限"),'name'=>'res_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:res_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.全溶解度").trans("ebi.下限"),'name'=>'tds_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.全溶解度").trans("ebi.上限"),'name'=>'tds_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:tds_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.塩分").trans("ebi.下限"),'name'=>'sal_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999.99999,'validation'=>'numeric|between:0,999.99999','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.塩分").trans("ebi.上限"),'name'=>'sal_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999.99999,'validation'=>'numeric|between:0,999.99999|gte:sal_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.海水比重").trans("ebi.下限"),'name'=>'sigma_t_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.海水比重").trans("ebi.上限"),'name'=>'sigma_t_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:sigma_t_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.溶存酸素").'%'.trans("ebi.下限"),'name'=>'do_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.溶存酸素").'%'.trans("ebi.上限"),'name'=>'do_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:do_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.溶存酸素").'ppm'.trans("ebi.下限"),'name'=>'do_ppm_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.溶存酸素").'ppm'.trans("ebi.上限"),'name'=>'do_ppm_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:do_ppm_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.濁度").trans("ebi.下限"),'name'=>'turb_fnu_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.濁度").trans("ebi.上限"),'name'=>'turb_fnu_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:0,999999.99|gte:turb_fnu_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.水温").trans("ebi.下限"),'name'=>'tmp_values_min','type'=>'number','min'=> -999999.99, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:-999999.99,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.水温").trans("ebi.上限"),'name'=>'tmp_values_max','type'=>'number','min'=> -999999.99, 'step'=>0.01, 'max'=>999999.99,'validation'=>'numeric|between:-999999.99,999999.99|gte:tmp_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.気圧").trans("ebi.下限"),'name'=>'press_values_min','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>99999.999,'validation'=>'numeric|between:0,99999.999','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
			$this->form[] = ['label'=>trans("ebi.気圧").trans("ebi.上限"),'name'=>'press_values_max','type'=>'number','min'=> 0, 'step'=>0.01, 'max'=>99999.999,'validation'=>'numeric|between:0,99999.999|gte:press_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.アンモニア").trans("ebi.下限"),'name'=>'ammonia_values_min','type'=>'number','min'=> 0, 'step'=>'any','validation'=>'numeric|between:0,99999.999','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.アンモニア").trans("ebi.上限"),'name'=>'ammonia_values_max','type'=>'number','min'=> 0, 'step'=>'any','validation'=>'numeric|between:0,99999.999|gte:ammonia_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.銅イオン濃度").trans("ebi.下限"),'name'=>'copper_ion_values_min','type'=>'number','min'=> 0, 'step'=>'any','validation'=>'numeric|between:0,99999.999','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.銅イオン濃度").trans("ebi.上限"),'name'=>'copper_ion_values_max','type'=>'number','min'=> 0, 'step'=>'any','validation'=>'numeric|between:0,99999.999|gte:copper_ion_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            $this->form[] = ['label'=>trans("ebi.気温(℃)").trans("ebi.下限"),'name'=>'out_temp_values_min','type'=>'number','min'=> -999999.99, 'step'=>'any','validation'=>'numeric|between:-999999.99,999999.99','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];
            $this->form[] = ['label'=>trans("ebi.気温(℃)").trans("ebi.上限"),'name'=>'out_temp_values_max','type'=>'number','min'=> -999999.99, 'step'=>'any','validation'=>'numeric|between:-999999.99,999999.99|gte:out_temp_values_min','width'=>'col-sm-8', 'label_width' => 'col-sm-4', 'form_layout' => 'two'];

            # END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
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
            $this->script_js = null;


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
        | Hook for manipulate query of index result
        | ----------------------------------------------------------------------
        | @query = current sql query
        |
        */
        public function hook_query_edit(&$row) {
            //Your code here
            if ($row->disable_flag){
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
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
            $postdata['user_id'] = CRUDBooster::myId();
            $postdata['start_date'] = now();
            $postdata['disable_flag'] = 0;
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
            $newMinmax = DB::table('ebi_minmaxs')->where('id', $id)->first();

            // disable old minmax
            if ($newMinmax){
                DB::table('ebi_minmaxs')->where('disable_flag', 0)->where('id', '!=', $id)->update(['disable_flag' => 1, 'end_date' => now()]);
            }
	    }

	    private $newPostdata;
	    private $lastId;
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

            unset($postdata['farm_id']);
            $oldMinmax = DB::table('ebi_minmaxs')->where('id', $id)->first();

            if ($oldMinmax){
                $hasDiffData = false;
                if ((float)$postdata['ph_values_min'] !== (float)$oldMinmax->ph_values_min || (float)$postdata['ph_values_max'] !== (float)$oldMinmax->ph_values_max
                    || (float)$postdata['mv_values_min'] !== (float)$oldMinmax->mv_values_min || (float)$postdata['mv_values_max'] !== (float)$oldMinmax->mv_values_max
                    ||(float)$postdata['orp_values_min'] !== (float)$oldMinmax->orp_values_min || (float)$postdata['orp_values_max'] !== (float)$oldMinmax->orp_values_max
                    || (float)$postdata['ec_values_min'] !== (float)$oldMinmax->ec_values_min || (float)$postdata['ec_values_max'] !== (float)$oldMinmax->ec_values_max
                    || (float)$postdata['ec_abs_values_min'] !== (float)$oldMinmax->ec_abs_values_min || (float)$postdata['ec_abs_values_max'] !== (float)$oldMinmax->ec_abs_values_max
                    || (float)$postdata['res_values_min'] !== (float)$oldMinmax->res_values_min || (float)$postdata['res_values_max'] !== (float)$oldMinmax->res_values_max
                    || (float)$postdata['tds_values_min'] !== (float)$oldMinmax->tds_values_min || (float)$postdata['tds_values_max'] !== (float)$oldMinmax->tds_values_max
                    || (float)$postdata['sal_values_min'] !== (float)$oldMinmax->sal_values_min || (float)$postdata['sal_values_max'] !== (float)$oldMinmax->sal_values_max
                    || (float)$postdata['press_values_min'] !== (float)$oldMinmax->press_values_min || (float)$postdata['press_values_max'] !== (float)$oldMinmax->press_values_max
                    || (float)$postdata['do_values_min'] !== (float)$oldMinmax->do_values_min || (float)$postdata['do_values_max'] !== (float)$oldMinmax->do_values_max
                    || (float)$postdata['do_ppm_values_min'] !== (float)$oldMinmax->do_ppm_values_min || (float)$postdata['do_ppm_values_max'] !== (float)$oldMinmax->do_ppm_values_max
                    || (float)$postdata['turb_fnu_values_min'] !== (float)$oldMinmax->turb_fnu_values_min || (float)$postdata['turb_fnu_values_max'] !== (float)$oldMinmax->turb_fnu_values_max
                    || (float)$postdata['tmp_values_min'] !== (float)$oldMinmax->tmp_values_min || (float)$postdata['tmp_values_max'] !== (float)$oldMinmax->tmp_values_max
                    || (float)$postdata['sigma_t_values_min'] !== (float)$oldMinmax->sigma_t_values_min || (float)$postdata['sigma_t_values_max'] !== (float)$oldMinmax->sigma_t_values_max
                    || (float)$postdata['ammonia_values_min'] !== (float)$oldMinmax->ammonia_values_min || (float)$postdata['ammonia_values_max'] !== (float)$oldMinmax->ammonia_values_max
                    || (float)$postdata['copper_ion_values_min'] !== (float)$oldMinmax->copper_ion_values_min || (float)$postdata['copper_ion_values_max'] !== (float)$oldMinmax->copper_ion_values_max
                    || (float)$postdata['out_temp_values_min'] !== (float)$oldMinmax->out_temp_values_min || (float)$postdata['out_temp_values_max'] !== (float)$oldMinmax->out_temp_values_max) {
                    $hasDiffData = true;
                }
                if ($hasDiffData){
                    // has data change, disable old data and make new record
                    $this->newPostdata = $postdata;

                    unset($postdata['ph_values_min']);
                    unset($postdata['ph_values_max']);
                    unset($postdata['mv_values_min']);
                    unset($postdata['mv_values_max']);
                    unset($postdata['orp_values_min']);
                    unset($postdata['orp_values_max']);
                    unset($postdata['ec_values_min']);
                    unset($postdata['ec_values_max']);
                    unset($postdata['ec_abs_values_min']);
                    unset($postdata['ec_abs_values_max']);
                    unset($postdata['res_values_min']);
                    unset($postdata['res_values_max']);
                    unset($postdata['tds_values_min']);
                    unset($postdata['tds_values_max']);
                    unset($postdata['sal_values_min']);
                    unset($postdata['sal_values_max']);
                    unset($postdata['press_values_min']);
                    unset($postdata['press_values_max']);
                    unset($postdata['do_values_min']);
                    unset($postdata['do_values_max']);
                    unset($postdata['do_ppm_values_min']);
                    unset($postdata['do_ppm_values_max']);
                    unset($postdata['turb_fnu_values_min']);
                    unset($postdata['turb_fnu_values_max']);
                    unset($postdata['tmp_values_min']);
                    unset($postdata['tmp_values_max']);
                    unset($postdata['sigma_t_values_min']);
                    unset($postdata['sigma_t_values_max']);
                    unset($postdata['ammonia_values_min']);
                    unset($postdata['ammonia_values_max']);
                    unset($postdata['copper_ion_values_min']);
                    unset($postdata['copper_ion_values_max']);
                    unset($postdata['out_temp_values_min']);
                    unset($postdata['out_temp_values_max']);

                    $postdata['end_date'] = now();
                    $postdata['disable_flag'] = 1;
                }else{
                    $this->newPostdata = null;
                }
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
            if ($this->newPostdata){
                $oldMinmax = DB::table('ebi_minmaxs')->where('id', $id)->first();

                // disable old minmax
                if ($oldMinmax){
                    $this->newPostdata['user_id'] = CRUDBooster::myId();
                    $this->newPostdata['start_date'] = now();
                    $this->newPostdata['disable_flag'] = 0;

                    $this->lastId = DB::table('ebi_minmaxs')->insertGetId($this->newPostdata);

                    $this->newPostdata = null;
                }
            }
	    }


        protected function hook_after_edit_before_redirect($postdata)
        {
            if ($this->lastId){
                CRUDBooster::redirect(CRUDBooster::mainpath('edit').'/'.$this->lastId, trans("crudbooster.alert_update_data_success"), 'success');
                $lastId = null;
                return false;
            }else{
                return true;
            }
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
            return trans("ebi.水質基準値設定");
        }

        protected function hook_query_select(&$result) {
            $result->where('ebi_minmaxs.disable_flag', 0);
        }

	    //By the way, you can still create your own method in here... :) 

        public function getIndex(){
            CRUDBooster::redirect(CRUDBooster::mainPath("setting"), '');
        }

        //By the way, you can still create your own method in here... :)
        public function getSetting(){
            $minmax = DB::table('ebi_minmaxs')->where('disable_flag', 0)->first();
            if ($minmax){
                CRUDBooster::redirect(CRUDBooster::mainPath("edit/$minmax->id"), '');
            }else{
                CRUDBooster::redirect(CRUDBooster::mainPath("add"), '');
            }
        }
	}