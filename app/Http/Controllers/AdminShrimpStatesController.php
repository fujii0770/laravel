<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDBooster;

class AdminShrimpStatesController extends AdminBaseController {

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
        $this->button_delete = true;
        $this->button_detail = false;
        $this->button_show = false;
        $this->button_filter = false;
        $this->button_import = false;
        $this->button_export = false;
        $this->button_addmore = false;
        $this->table = "ebi_shrimp_states";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ['visible'=>false, "label"=>trans("ebi.養殖池"),"name"=>"pond_id","join"=>"ebi_ponds,id"];
        $this->col[] = ["label"=>trans("ebi.養殖場"),"name"=>"ebi_ponds.farm_id","join"=>"ebi_farms,farm_name"];
        $this->col[] = ["label"=>trans("ebi.養殖池"),"name"=>"ebi_ponds.pond_name"];
        $this->col[] = ["label"=>trans("ebi.測定日"),"name"=>"ebi_shrimp_states.date_target"];
        $this->col[] = ["label"=>trans("ebi.サイズ"),"name"=>"size"];
        $this->col[] = ["label"=>trans("ebi.重量"),"name"=>"weight"];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $id = $this->getItemId();
        if (!$id && !Session::get('current_pond')) {
            $this->form[] = ["label" =>trans("ebi.養殖場"), "name" => "farm_id", "type" => "select2", 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
            $this->form[] = ['label' =>trans("ebi.養殖池"), 'name' => 'pond_id', 'type' => 'select2', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
        }else{
            $this->form[] = ['label' =>trans("ebi.養殖池"), 'name' => 'pond_id', 'type' => 'hidden', 'validation' => 'required|integer|min:0', 'width' => 'col-sm-10'];
        }
        $this->form[] = ['label'=>trans("ebi.測定日"),'name'=>'date_target','type'=>'date','validation'=>'required|date_format:Y.m.d','width'=>'col-sm-10','callback_php' => 'App\Http\Controllers\Helper::formatDate($row->date_target)'];
        //	$this->form[] = ['label'=>'Time Target','name'=>'time_target','type'=>'time','validation'=>'required|date_format:H:i:s','width'=>'col-sm-10'];
        $this->form[] = ['label'=>trans("ebi.サイズ"),'name'=>'size','type'=>'number', 'step'=>'any','validation'=>'numeric|min:0.01|max:999999.99','width'=>'col-sm-10'];
        $this->form[] = ['label'=>trans("ebi.重量"),'name'=>'weight','type'=>'number', 'step'=>'any','validation'=>'required|numeric|min:0.01|max:999999.99','width'=>'col-sm-10'];
        $this->form[] = ['label'=>trans("ebi.エビ写真"),'name'=>'photo','type'=>'upload',"help"=>trans('ebi.photo_resolution'),'validation'=>'image','width'=>'col-sm-10'];
        //	$this->form[] = ['label'=>'生存率(%)','name'=>'survival_rate','type'=>'number','validation'=>'required|numeric|min:0|max:100','width'=>'col-sm-10'];
        //	$this->form[] = ['label'=>'養殖日数(日)','name'=>'cultured_days','type'=>'number','validation'=>'required|numeric|max:2147483647','width'=>'col-sm-10'];
        //	$this->form[] = ['label'=>'エビ総量(匹)','name'=>'shrimp_total','type'=>'number','validation'=>'required|numeric|max:2147483647','width'=>'col-sm-10'];
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
        if ($this->isListView){
            $this->script_js = null;
        }else{
            $id = $this->getItemId();
            $selectedPondId = null;
            $selectedFarmId = null;
            if ($id){
                $pond = DB::table('ebi_ponds')->join('ebi_shrimp_states', 'ebi_shrimp_states.pond_id', '=', 'ebi_ponds.id')->select('ebi_ponds.*')->where('ebi_shrimp_states.id', $id )->first();
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
                            $('#pond_id').empty().trigger('change');
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
                                        $('#pond_id').append(newOption);
                                    }
                                    $('#pond_id').trigger('change');
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log(textStatus, errorThrown);
                                }
                            });
                            }
                        });
                        $('.box-footer a.btn-default').attr('href', '".url('admin/viewShrimpMeasure?pondId='.$selectedPondId)."');
                        
                        $( '#rdShrimp' ).change(function() {
                            $('.f1 input').prop('checked', false);
                            $(this).prop('checked', true);
                        }).change();
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

    /*public function getIndex(){
        if (Session::get('current_pond')){
            CRUDBooster::redirect(CRUDBooster::adminPath('ponds/edit/'.Session::get('current_pond')), '');
        }else{
            CRUDBooster::redirect(CRUDBooster::adminPath(), '');
        }
    }*/


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
        if (Session::get('current_pond')){
            $postdata['pond_id'] = Session::get('current_pond');
            $postdata['ponds_aquacultures_id'] = Helper::getCurrentAquaculture($postdata['pond_id'])->ponds_aquacultures_id;
        }
        $this->processPostDataBeforeSave($postdata);
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
        $this->processPostDataBeforeSave($postdata);
    }

    private function processPostDataBeforeSave(&$postdata){
        unset($postdata['farm_id']);
        $pondId = $postdata['pond_id'];
        $pond_aquaculture = Helper::getCurrentAquaculture($pondId);
        $start_date = Helper::formatDate($pond_aquaculture->start_date);
        if (!$this->checkFarmPermissionOnPond($pondId)){
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }
        if (!$postdata['date_target']){
            $this->fireErrorMessage('date_target', trans('ebi.測定日が有りません'));
        }
        if ($postdata['date_target'] < $start_date) {
            $this->fireErrorMessage('date_target', trans('ebi.date_validation'));
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
        return trans("ebi.エビ状況");
    }


    protected function hook_after_edit_before_redirect($postdata)
    {
        CRUDBooster::redirect(CRUDBooster::adminPath('viewShrimpMeasure?pondId='.Session::get('current_pond')), '', 'success');
        return false;
    }

    protected function hook_after_add_before_redirect($postdata)
    {
        CRUDBooster::redirect(CRUDBooster::adminPath('viewShrimpMeasure?pondId='.Session::get('current_pond')), '', 'success');
        return false;
    }

    public function getDelete($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! CRUDBooster::isDelete() && $this->global_privilege == false || $this->button_delete == false) {
            CRUDBooster::insertLog(trans("crudbooster.log_try_delete", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }

        //insert log
        CRUDBooster::insertLog(trans("crudbooster.log_delete", ['name' => $row->{$this->title_field}, 'module' => CRUDBooster::getCurrentModule()->name]));

        $this->hook_before_delete($id);

        if (CRUDBooster::isColumnExists($this->table, 'deleted_at')) {
            DB::table($this->table)->where($this->primary_key, $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table($this->table)->where($this->primary_key, $id)->delete();
        }

        $this->hook_after_delete($id);

        $url = g('return_url') ?: CRUDBooster::referer();

        CRUDBooster::redirect($url, '', 'success');
    }

    //By the way, you can still create your own method in here... :)


}