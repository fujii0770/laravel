<?php namespace App\Http\Controllers;

use CRUDbooster;
use DB;
use Request;
use Session;

class AdminCmsUsersController extends AdminBaseController {

    protected $farm_ids;

	public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table = 'cms_users';
        $this->primary_key = 'id';
        $this->title_field = "name";
        $this->button_action_style = 'button_icon';
        $this->button_import = FALSE;
        $this->button_export = FALSE;
        $this->button_detail = false;
        $this->button_filter = false;
        $this->button_show = false;
        $this->button_addmore = false;
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = array();
        $this->col[] = array("label" => trans('ebi.ユーザ名'), "name" => "name");
        $this->col[] = array("label" => trans('ebi.メールアドレス'), "name" => "email");
        //	$this->col[] = array("label"=>"アクセス権","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
        //	$this->col[] = array("label"=>"画像","name"=>"photo","image"=>1);
        # END COLUMNS DO NOT REMOVE THIS LINE

        $id = $this->getItemId();
        # START FORM DO NOT REMOVE THIS LINE
        $this->form = array();
        $this->form[] = array("label" => trans('ebi.ユーザ名'), "name" => "name", 'required' => true, 'validation' => 'required|regex:/^[A-Za-z0-9 ]+$/|min:3|max:255');
        $this->form[] = array("label" => trans('ebi.メールアドレス'), "name" => "email", 'autocomplete'=>false,'required' => true, 'type' => 'email', 'validation' => 'required|regex:/^[a-zA-Z0-9\._]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-\.]+$/i|max:255|unique:cms_users,email,' . CRUDBooster::getCurrentId());
        $this->form[] = array("label" => trans('ebi.画像'), "name" => "photo", "type" => "upload", "help" => trans('ebi.photo_resolution'), 'validation' => 'image|max:1000', 'resize_width' => 90, 'resize_height' => 90);

        if ($id || Request::is('admin/users/profile')) {
            $this->form[] = array("label" => trans('ebi.パスワード'), "name" => "password", "type" => "password", 'validation' => 'max:255', "help" => trans('ebi.パスワードを変更する場合は、入力してください'));
        } else {
            $this->form[] = array("label" => trans('ebi.パスワード'), "name" => "password", 'required' => true, 'validation' => 'required|max:255|confirmed', "type" => "password");
            $this->form[] = array("label" => trans('ebi.パスワードの確認'), "name" => "password_confirmation", 'required' => true, 'validation' => 'required|max:255', "type" => "password");
        }
        $this->form[] = array("label" => trans('ebi.パスワード'), "name" => "password_hidden", 'required' => true, 'validation' => 'required|max:255', "type" => "hidden");
        # END FORM DO NOT REMOVE THIS LINE

        /*  if (!Request::is('admin/users/profile')) {
              if ($id) {
                  $farms = DB::table('ebi_farms')->join('ebi_user_farms', 'ebi_farms.id', '=', 'ebi_user_farms.farm_id')->select('ebi_farms.*')->where('user_id', $id)->get();
                  $selectedFarms = array();
                  foreach ($farms as $farm) {
                      $selectedFarms[] = array('value' => $farm->id, 'label' => $farm->farm_name);
                  }
                  $this->form[] = array("label" => "養殖場", "name" => "farm_ids", "type" => "select2", "datatable" => "ebi_farms,farm_name", "datatable_ajax" => "true", 'multiple' => true, 'selectedValues' => $selectedFarms);
              } else {
                  $this->form[] = array("label" => "養殖場", "name" => "farm_ids", "type" => "select2", "datatable" => "ebi_farms,farm_name", "datatable_ajax" => "true", 'multiple' => true);
              }
          }*/
        if ($id || Request::is('admin/users/profile')){
            $this->form[] = array("label" => trans('ebi.アラートのメール通知'), "name" => "enable_email_alert", "type" => "checkbox", 'dataenum' => '1|');
        }
       /* if(CRUDBooster::isSuperadmin()){
            $this->form[] = array("label"=>"管理者権限の有無","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true);
        }else{
            $this->form[] = array("label"=>"管理者権限の有無","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true,'filter_condition'=>'is_superadmin!=1');
        }*/

        $this->script_js = "$(document).ready(function () {
            $('#password').change(function() {
                $('input[name=\"password_hidden\"]').val($(this).val());
                
            });
        })";
	}

	public function getProfile() {
		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',intval(CRUDBooster::myId()));
		$data['id'] = intval(CRUDBooster::myId());
		$this->cbView('crudbooster::default.form',$data);				
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
        if(trim($postdata['name']) === '') {
            CRUDBooster::redirectBack(trans("ebi.正しい形式のユーザ名を指定してください。"));
        }

       /* if(trim($postdata['password_hidden']) === '') {
            CRUDBooster::redirectBack(trans("ebi.正しい形式のパスワードを指定してください。"));
        }*/
        if (!\crocodicstudio\crudbooster\helpers\CRUDBooster::isSuperadmin() && ($postdata['id_cms_privileges'] == 1)){
            $post['id_cms_privileges'] = null;
        }

        if (isset($postdata['farm_ids'])){
            $this->farm_ids = $postdata['farm_ids'];
        }else{
            $this->farm_ids = $this->getMyFarms();
        }

        if(!isset($postdata['enable_email_alert'])){
            $postdata['enable_email_alert'] = 0;
        }

        unset($this->arr['farm_ids']);
        unset($postdata['password_hidden']);
        unset($postdata['password_confirmation']);
        $postdata['id_cms_privileges'] = 2;
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
        if ($this->farm_ids){
            $userFarms = array();
            foreach ($this->farm_ids as $farmId){
                $userFarms[] = ['user_id' => $id, 'farm_id' => $farmId, 'created_at' => now()];
            }
            if (count($userFarms)){
                DB::table('ebi_user_farms')->insert($userFarms);
            }
        }
    }

    public function hook_before_edit(&$post, $id)
    {
        //print_r($post);exit;
        if (!\crocodicstudio\crudbooster\helpers\CRUDBooster::isSuperadmin() && ($post['id_cms_privileges'] == 1)){
            $post['id_cms_privileges'] = null;
        }
        if ($id == 1){
            $post['id_cms_privileges'] = 1;
        }
        if (isset($post['farm_ids'])){
            $this->farm_ids = $post['farm_ids'];
        }else{
            $this->farm_ids = null;
        }
        unset($this->arr['farm_ids']);
        
        if(!isset($post['enable_email_alert'])) $post['enable_email_alert'] = 0;

        if(trim($post['name']) === '') {
            CRUDBooster::redirectBack(trans("ebi.正しい形式のユーザ名を指定してください。"));
        }
        //print_r($post['password_hidden']);exit;
      /*  if($post['password'] && trim($post['password_hidden']) === '') {
            CRUDBooster::redirectBack(trans("ebi.正しい形式のパスワードを指定してください。"));
        }*/

        unset($post['password_hidden']);
    }

    public function hook_after_edit($id)
    {
        if ($this->farm_ids != null) {
            DB::table('ebi_user_farms')->where('user_id', $id)->delete();
            $userFarms = array();
            foreach ($this->farm_ids as $farmId) {
                $userFarms[] = ['user_id' => $id, 'farm_id' => $farmId, 'created_at' => now(), 'created_user' => CRUDbooster::myId()];
            }
            if (count($userFarms)) {
                DB::table('ebi_user_farms')->insert($userFarms);
            }
        }
        if ($id == \crocodicstudio\crudbooster\helpers\CRUDBooster::myId()){
            // update profile
            $me = CRUDBooster::me();
            Session::put('admin_name',$me ->name);
            $photo = ($me->photo) ? asset($me->photo) : asset('vendor/crudbooster/avatar.jpg');
            Session::put('admin_photo', $photo);
        }

    }

    protected function hook_after_add_before_redirect($postdata)
    {
        CRUDBooster::redirect(CRUDBooster::mainpath('add').'?return_url='.urlencode(CRUDBooster::adminpath('users/profile/')), trans("crudbooster.alert_add_data_success"), 'success');
        return false;
    }

    public function hook_before_delete($id)
    {
    }

    public function hook_after_delete($id)
    {
    }

    protected function hook_query_select(&$result) {
        if(!CRUDBooster::isSuperadmin()){
            $myFarms = $this->getMyFarms();

            $result->whereIn('cms_users.id', function($query) use ($myFarms){
                $query->select('user_id')
                    ->from('ebi_user_farms')
                    ->whereIn('farm_id', $myFarms);
            });
        }
    }

    protected function hook_query_find_data(&$query){
        if(!CRUDBooster::isSuperadmin()){
            $myFarms = $this->getMyFarms();
            $query->whereIn('id', $myFarms);
        }
    }

    protected function hook_page_title($page_title) {
        return trans('ebi.新規ユーザー登録');
    }
}
