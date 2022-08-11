<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 8/8/2018
 * Time: 9:35 AM
 */

namespace App\Http\Controllers;

use App\Rules\NotSpecialCharRule;
use crocodicstudio\crudbooster\helpers\CB;
use CRUDBooster;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Request;
use Session;

class AdminBaseController extends \crocodicstudio\crudbooster\controllers\CBController
{
    protected $default_where = null;

    protected $isListView = false;

    protected $form_layout_two = false;

    public function cbLoader()
    {
        $this->cbInit();

        $this->checkHideForm();

        $this->primary_key = CB::pk($this->table);
        $this->columns_table = $this->col;
        $this->data_inputan = $this->form;
        $this->global_privilege = true;
        $this->button_addmore = false;
        $this->data['pk'] = $this->primary_key;
        $this->data['forms'] = $this->data_inputan;
        $this->data['hide_form'] = $this->hide_form;
        $this->data['addaction'] = ($this->show_addaction) ? $this->addaction : null;
        $this->data['table'] = $this->table;
        $this->data['title_field'] = $this->title_field;
        $this->data['appname'] = CRUDBooster::getSetting('appname');
        $this->data['alerts'] = $this->alert;
        $this->data['index_button'] = $this->index_button;
        $this->data['show_numbering'] = $this->show_numbering;
        $this->data['button_detail'] = $this->button_detail;
        $this->data['button_edit'] = $this->button_edit;
        $this->data['button_show'] = $this->button_show;
        $this->data['button_add'] = $this->button_add;
        $this->data['button_delete'] = $this->button_delete;
        $this->data['button_filter'] = $this->button_filter;
        $this->data['button_export'] = $this->button_export;
        $this->data['button_addmore'] = $this->button_addmore;
        $this->data['button_cancel'] = $this->button_cancel;
        $this->data['button_save'] = $this->button_save;
        $this->data['button_table_action'] = $this->button_table_action;
        $this->data['button_bulk_action'] = $this->button_bulk_action;
        $this->data['button_import'] = $this->button_import;
        $this->data['button_action_width'] = $this->button_action_width;
        $this->data['button_selected'] = $this->button_selected;
        $this->data['index_statistic'] = $this->index_statistic;
        $this->data['index_additional_view'] = $this->index_additional_view;
        $this->data['table_row_color'] = $this->table_row_color;
        $this->data['pre_index_html'] = $this->pre_index_html;
        $this->data['post_index_html'] = $this->post_index_html;
        $this->data['load_js'] = $this->load_js;
        $this->data['load_css'] = $this->load_css;
        $this->data['script_js'] = $this->script_js;
        $this->data['style_css'] = $this->style_css;
        $this->data['sub_module'] = $this->sub_module;
        $this->data['parent_field'] = (g('parent_field')) ?: $this->parent_field;
        $this->data['parent_id'] = (g('parent_id')) ?: $this->parent_id;
        $this->data['form_layout_two'] = $this->form_layout_two;
        $this->data['global_privilege'] = $this->global_privilege;

        if ($this->sidebar_mode == 'mini') {
            $this->data['sidebar_mode'] = 'sidebar-mini';
        } elseif ($this->sidebar_mode == 'collapse') {
            $this->data['sidebar_mode'] = 'sidebar-collapse';
        } elseif ($this->sidebar_mode == 'collapse-mini') {
            $this->data['sidebar_mode'] = 'sidebar-collapse sidebar-mini';
        } else {
            $this->data['sidebar_mode'] = '';
        }

        if (CRUDBooster::getCurrentMethod() == 'getProfile') {
            Session::put('current_row_id', CRUDBooster::myId());
            $this->data['return_url'] = Request::fullUrl();
        }

        view()->share($this->data);
    }

    private function checkHideForm()
    {
        if ($this->hide_form && count($this->hide_form)) {
            foreach ($this->form as $i => $f) {
                if (in_array($f['name'], $this->hide_form)) {
                    unset($this->form[$i]);
                }
            }
        }
    }

    protected function getItemId()
    {
        $id = Route::current()->parameters()['one'];

        return $id;
    }

    protected function getPondByAqua($aquaId)
    {
        $myAquacultures = Session::get('my_aquaculture');
        if ($myAquacultures && !array_key_exists($aquaId, array_keys($myAquacultures))){
            // not found aqua, get again my aqua from db to avoid new aqua
            $myAquacultures = null;
        }
        if (!$myAquacultures) {
            $myPonds = $this->getMyPonds();
            $aqualcutures = DB::table('ponds_aquacultures')->select(['id', 'ebi_ponds_id'])->whereIn('ebi_ponds_id', $myPonds)->get();

            $myAquacultures = array();
            foreach ($aqualcutures as $aqualcuture) {
                $myAquacultures[$aqualcuture->id] = $aqualcuture->ebi_ponds_id;
            }
            Session::put('my_aquaculture', $myAquacultures);
        }
        if (array_key_exists($aquaId, $myAquacultures)){
            return $myAquacultures[$aquaId];
        }else{
            return null;
        }
    }

    protected function checkFarmPermissionOnPond($pondId)
    {
       /* if (!CRUDBooster::isSuperadmin()) {
            $myPonds = $this->getMyPonds();
            if (!in_array($pondId, $myPonds)) {
                return false;
            }

        }*/
        return true;
    }

    protected function checkFarmPermission($farmId)
    {
        if (!CRUDBooster::isSuperadmin()) {
            $myFarms = $this->getMyFarms();
            if (!in_array($farmId, $myFarms)) {
                return false;
            }
        }
        return true;
    }

    public function getIndex()
    {
        $this->isListView = true;

        $this->cbLoader();

        $module = CRUDBooster::getCurrentModule();

        if (!CRUDBooster::isView() && $this->global_privilege == false) {
            CRUDBooster::insertLog(trans('crudbooster.log_try_view', ['module' => $module->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }

        if (Request::get('parent_table')) {
            $parentTablePK = CB::pk(g('parent_table'));
            $data['parent_table'] = DB::table(Request::get('parent_table'))->where($parentTablePK, Request::get('parent_id'))->first();
            if (Request::get('foreign_key')) {
                $data['parent_field'] = Request::get('foreign_key');
            } else {
                $data['parent_field'] = CB::getTableForeignKey(g('parent_table'), $this->table);
            }

            if ($parent_field) {
                foreach ($this->columns_table as $i => $col) {
                    if ($col['name'] == $parent_field) {
                        unset($this->columns_table[$i]);
                    }
                }
            }
        }

        $data['table'] = $this->table;
        $data['table_pk'] = CB::pk($this->table);
        $data['page_title'] = $module->name;
        $data['page_description'] = trans('crudbooster.default_module_description');
        $data['date_candidate'] = $this->date_candidate;
        $data['limit'] = $limit = (Request::get('limit')) ? Request::get('limit') : $this->limit;

        $tablePK = $data['table_pk'];
        $table_columns = CB::getTableColumns($this->table);
        $result = DB::table($this->table)->select(DB::raw($this->table . "." . $this->primary_key));

        if (Request::get('parent_id')) {
            $table_parent = $this->table;
            $table_parent = CRUDBooster::parseSqlTable($table_parent)['table'];
            $result->where($table_parent . '.' . Request::get('foreign_key'), Request::get('parent_id'));
        }

        $this->hook_query_index($result);

        if (in_array('deleted_at', $table_columns)) {
            $result->where($this->table . '.deleted_at', null);
        }

        $alias = [];
        $join_alias_count = 0;
        $join_table_temp = [];
        $table = $this->table;
        $columns_table = $this->columns_table;
        foreach ($columns_table as $index => $coltab) {

            $join = @$coltab['join'];
            $join_where = @$coltab['join_where'];
            $join_id = @$coltab['join_id'];
            $field = @$coltab['name'];
            $join_table_temp[] = $table;

            if (!$field) {
                die('Please make sure there is key `name` in each row of col');
            }

            if (strpos($field, ' as ') !== false) {
                $field = substr($field, strpos($field, ' as ') + 4);
                $field_with = (array_key_exists('join', $coltab)) ? str_replace(",", ".", $coltab['join']) : $field;
                $result->addselect(DB::raw($coltab['name']));
                $columns_table[$index]['type_data'] = 'varchar';
                $columns_table[$index]['field'] = $field;
                $columns_table[$index]['field_raw'] = $field;
                $columns_table[$index]['field_with'] = $field_with;
                $columns_table[$index]['is_subquery'] = true;
                continue;
            }

            if (strpos($field, '.') !== false) {
                $result->addselect($field);
            } else {
                $result->addselect($table . '.' . $field);
            }

            $field_array = explode('.', $field);

            if (isset($field_array[1])) {
                $field = $field_array[1];
                $table = $field_array[0];
            } else {
                $table = $this->table;
            }

            if ($join) {

                $join_exp = explode(',', $join);

                $join_table = $join_exp[0];
                $joinTablePK = CB::pk($join_table);
                $join_column = $join_exp[1];
                $join_alias = str_replace(".", "_", $join_table);

                if (in_array($join_table, $join_table_temp)) {
                    $join_alias_count += 1;
                    $join_alias = $join_table . $join_alias_count;
                }
                $join_table_temp[] = $join_table;

                $result->leftjoin($join_table . ' as ' . $join_alias, $join_alias . (($join_id) ? '.' . $join_id : '.' . $joinTablePK), '=', DB::raw($table . '.' . $field . (($join_where) ? ' AND ' . $join_where . ' ' : '')));
                $result->addselect($join_alias . '.' . $join_column . ' as ' . $join_alias . '_' . $join_column);

                $join_table_columns = CRUDBooster::getTableColumns($join_table);
                if ($join_table_columns) {
                    foreach ($join_table_columns as $jtc) {
                        $result->addselect($join_alias . '.' . $jtc . ' as ' . $join_alias . '_' . $jtc);
                    }
                }

                $alias[] = $join_alias;
                $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($join_table, $join_column);
                $columns_table[$index]['field'] = $join_alias . '_' . $join_column;
                $columns_table[$index]['field_with'] = $join_alias . '.' . $join_column;
                $columns_table[$index]['field_raw'] = $join_column;

                @$join_table1 = $join_exp[2];
                @$joinTable1PK = CB::pk($join_table1);
                @$join_column1 = $join_exp[3];
                @$join_alias1 = $join_table1;

                if ($join_table1 && $join_column1) {

                    if (in_array($join_table1, $join_table_temp)) {
                        $join_alias_count += 1;
                        $join_alias1 = $join_table1 . $join_alias_count;
                    }

                    $join_table_temp[] = $join_table1;

                    $result->leftjoin($join_table1 . ' as ' . $join_alias1, $join_alias1 . '.' . $joinTable1PK, '=', $join_alias . '.' . $join_column);
                    $result->addselect($join_alias1 . '.' . $join_column1 . ' as ' . $join_column1 . '_' . $join_alias1);
                    $alias[] = $join_alias1;
                    $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($join_table1, $join_column1);
                    $columns_table[$index]['field'] = $join_column1 . '_' . $join_alias1;
                    $columns_table[$index]['field_with'] = $join_alias1 . '.' . $join_column1;
                    $columns_table[$index]['field_raw'] = $join_column1;
                }
            } else {

                $result->addselect($table . '.' . $field);
                $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($table, $field);
                $columns_table[$index]['field'] = $field;
                $columns_table[$index]['field_raw'] = $field;
                $columns_table[$index]['field_with'] = $table . '.' . $field;
            }
        }

        $this->hook_query_select($result);

        if (Request::get('q')) {
            $result->where(function ($w) use ($columns_table, $request) {
                foreach ($columns_table as $col) {
                    if (!$col['field_with']) {
                        continue;
                    }
                    if ($col['is_subquery']) {
                        continue;
                    }
                    $w->orwhere($col['field_with'], "like binary", "%" . Request::get("q") . "%");
                }
            });
        }

        if ($this->default_where) {
            $default_where = explode(";", $this->default_where);
            foreach ($default_where as $o) {
                $o = explode(",", $o);
                $key = $o[0];
                $operator = $o[1];
                $value = $o[2];
                if (strpos($key, '.') !== false) {
                    $where_table = explode(".", $key)[0];
                } else {
                    $where_table = $this->table;
                }
                $result->where($where_table . '.' . $key, $operator, $value);
            }
        }

        if (Request::get('where')) {
            foreach (Request::get('where') as $k => $v) {
                $result->where($table . '.' . $k, $v);
            }
        }

        $filter_is_orderby = false;
        if (Request::get('filter_column')) {

            $filter_column = Request::get('filter_column');
            $this->hook_before_filter_column($filter_column);
            $result->where(function ($w) use ($filter_column, $fc) {
                foreach ($filter_column as $key => $fc) {

                    $value = @$fc['value'];
                    $type = @$fc['type'];

                    if ($type == 'empty') {
                        $w->whereNull($key)->orWhere($key, '');
                        continue;
                    }

                    if ($value == '' || $type == '') {
                        continue;
                    }

                    if ($type == 'between') {
                        continue;
                    }

                    switch ($type) {
                        default:
                            if ($key && $type && ($value || $value === '0' || $value === 0)) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'like':
                        case 'not like':
                            $value = '%' . $value . '%';
                            if ($key && $type && $value) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'in':
                        case 'not in':
                            if ($value) {
                                $value = explode(',', $value);
                                if ($key && $value) {
                                    $w->whereIn($key, $value);
                                }
                            }
                            break;
                    }
                }
            });

            foreach ($filter_column as $key => $fc) {
                $value = @$fc['value'];
                $type = @$fc['type'];
                $sorting = @$fc['sorting'];

                if ($sorting != '') {
                    if ($key) {
                        $result->orderby($key, $sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    if ($key && $value) {
                        if (is_array($value) && (count($value) > 1)) {
                            if ($value[0] && $value[1]) {
                                $result->whereBetween($key, $value);
                            } else if ($value[0]) {
                                $result->whereDate($key, '>=', $value[0]);
                            } else if ($value[1]) {
                                $result->whereDate($key, '<=', $value[1]);
                            }
                        }
                    }
                } else {
                    continue;
                }
            }
        }
        if ($filter_is_orderby == true) {
        } else {
            if ($this->orderby) {
                if (is_array($this->orderby)) {
                    foreach ($this->orderby as $k => $v) {
                        if (strpos($k, '.') !== false) {
                            $orderby_table = explode(".", $k)[0];
                            $k = explode(".", $k)[1];
                        } else {
                            $orderby_table = $this->table;
                        }
                        $result->orderby($orderby_table . '.' . $k, $v);
                    }
                } else {
                    $this->orderby = explode(";", $this->orderby);
                    foreach ($this->orderby as $o) {
                        $o = explode(",", $o);
                        $k = $o[0];
                        $v = $o[1];
                        if (strpos($k, '.') !== false) {
                            $orderby_table = explode(".", $k)[0];
                        } else {
                            $orderby_table = $this->table;
                        }
                        $result->orderby($orderby_table . '.' . $k, $v);
                    }
                }
            } else {
                $result->orderby($this->table . '.' . $this->primary_key, 'desc');
            }
        }
        if($limit > 0){
            $data['result'] = $result->paginate($limit);
        }else{
            $data['result'] = $result->get();
        }

        $data['columns'] = $columns_table;

        if ($this->index_return) {
            return $data;
        }

        //LISTING INDEX HTML
        $addaction = $this->data['addaction'];

        if ($this->sub_module) {
            foreach ($this->sub_module as $s) {
                $table_parent = CRUDBooster::parseSqlTable($this->table)['table'];
                $addaction[] = [
                    'label' => $s['label'],
                    'icon' => $s['button_icon'],
                    'url' => CRUDBooster::adminPath($s['path']) . '?parent_table=' . $table_parent . '&parent_columns=' . $s['parent_columns'] . '&parent_columns_alias=' . $s['parent_columns_alias'] . '&parent_id=[' . (!isset($s['custom_parent_id']) ? "id" : $s['custom_parent_id']) . ']&return_url=' . urlencode(Request::fullUrl()) . '&foreign_key=' . $s['foreign_key'] . '&label=' . urlencode($s['label']),
                    'color' => $s['button_color'],
                    'showIf' => $s['showIf'],
                ];
            }
        }

        $this->hook_before_render_index($data);
        $mainpath = CRUDBooster::mainpath();
        $orig_mainpath = $this->data['mainpath'];
        $title_field = $this->title_field;
        $html_contents = [];
        $page = (Request::get('page')) ? Request::get('page') : 1;
        $number = ($page - 1) * $limit + 1;
        foreach ($data['result'] as $row) {
            $html_content = [];

            if ($this->button_bulk_action) {

                $html_content[] = "<input type='checkbox' class='checkbox' name='checkbox[]' value='" . $row->{$tablePK} . "'/>";
            }

            if ($this->show_numbering) {
                $html_content[] = $number . '. ';
                $number++;
            }

            foreach ($columns_table as $col) {
                if ($col['visible'] === false) {
                    continue;
                }

                $value = @$row->{$col['field']};
                $title = @$row->{$this->title_field};
                $label = $col['label'];

                if (isset($col['image'])) {
                    if ($value == '') {
                        $value = "<a  data-lightbox='roadtrip' rel='group_{{$table}}' title='$label: $title' href='" . asset('vendor/crudbooster/avatar.jpg') . "'><img width='40px' height='40px' src='" . asset('vendor/crudbooster/avatar.jpg') . "'/></a>";
                    } else {
                        $pic = (strpos($value, 'http://') !== false) ? $value : asset($value);
                        $value = "<a data-lightbox='roadtrip'  rel='group_{{$table}}' title='$label: $title' href='" . $pic . "'><img width='40px' height='40px' src='" . $pic . "'/></a>";
                    }
                }

                if (@$col['download']) {
                    $url = (strpos($value, 'http://') !== false) ? $value : asset($value) . '?download=1';
                    if ($value) {
                        $value = "<a class='btn btn-xs btn-primary' href='$url' target='_blank' title='Download File'><i class='fa fa-download'></i> Download</a>";
                    } else {
                        $value = " - ";
                    }
                }

                if ($col['str_limit']) {
                    $value = trim(strip_tags($value));
                    $value = str_limit($value, $col['str_limit']);
                }

                if ($col['nl2br']) {
                    $value = nl2br($value);
                }

                if ($col['callback_php']) {
                    foreach ($row as $k => $v) {
                        $col['callback_php'] = str_replace("[" . $k . "]", $v, $col['callback_php']);
                    }
                    @eval("\$value = " . $col['callback_php'] . ";");
                }

                //New method for callback
                if (isset($col['callback'])) {
                    $value = call_user_func($col['callback'], $row);
                }

                $datavalue = @unserialize($value);
                if ($datavalue !== false) {
                    if ($datavalue) {
                        $prevalue = [];
                        foreach ($datavalue as $d) {
                            if ($d['label']) {
                                $prevalue[] = $d['label'];
                            }
                        }
                        if (count($prevalue)) {
                            $value = implode(", ", $prevalue);
                        }
                    }
                }

                $html_content[] = $value;
            } //end foreach columns_table

            if ($this->button_table_action):

                $button_action_style = $this->button_action_style;
                $html_content[] = "<div class='button_action' style='text-align:right'>" . view('crudbooster::components.action', compact('addaction', 'row', 'button_action_style', 'parent_field'))->render() . "</div>";

            endif;//button_table_action

            foreach ($html_content as $i => $v) {
                $this->hook_row_index($i, $v);
                $html_content[$i] = $v;
            }

            $html_contents[] = $html_content;
        } //end foreach data[result]

        $html_contents = ['html' => $html_contents, 'data' => $data['result']];

        $data['html_contents'] = $html_contents;
        return view($this->getIndexViewName(), $data);
    }

    public function getAdd()
    {
        $this->cbLoader();
        if ((!CRUDBooster::isCreate() && $this->global_privilege == false) || $this->button_add == false) {
            CRUDBooster::insertLog(trans('crudbooster.log_try_add', ['module' => CRUDBooster::getCurrentModule()->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans("crudbooster.denied_access"));
        }

        $page_title = trans("crudbooster.add_data_page_title", ['module' => CRUDBooster::getCurrentModule()->name]);
        $page_menu = Route::getCurrentRoute()->getActionName();
        $command = 'add';
        $page_title = $this->hook_page_title($page_title);

        return view($this->getAddViewName(), compact('page_title', 'page_menu', 'command', 'page_sub_title'));
    }

    public function postAddSave()
    {
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

        if (Schema::hasColumn($this->table, 'created_at')) {
            $this->arr['created_at'] = date('Y-m-d H:i:s');
        }

        if (Schema::hasColumn($this->table, 'created_user')) {
            $this->arr['created_user'] = CRUDBooster::myId();
        }

        $this->hook_before_add($this->arr);

//         $this->arr[$this->primary_key] = $id = CRUDBooster::newId($this->table); //error on sql server
        $lastInsertId = $id = DB::table($this->table)->insertGetId($this->arr);

        //Looping Data Input Again After Insert
        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];
            if (! $name) {
                continue;
            }

            $inputdata = Request::get($name);

            //Insert Data Checkbox if Type Datatable
            if ($ro['type'] == 'checkbox') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];
                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        $relationship_table_pk = CB::pk($ro['relationship_table']);
                        foreach ($inputdata as $input_id) {
                            DB::table($ro['relationship_table'])->insert([
//                                 $relationship_table_pk => CRUDBooster::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'select2') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];
                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = CB::pk($row['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
//                                 $relationship_table_pk => CRUDBooster::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'child') {
                $name = str_slug($ro['label'], '');
                $columns = $ro['columns'];
                $getColName = Request::get($name.'-'.$columns[0]['name']);
                $count_input_data = ($getColName)?(count($getColName) - 1):0;
                $child_array = [];

                for ($i = 0; $i <= $count_input_data; $i++) {
                    $fk = $ro['foreign_key'];
                    $column_data = [];
                    $column_data[$fk] = $id;
                    foreach ($columns as $col) {
                        $colname = $col['name'];
                        $column_data[$colname] = Request::get($name.'-'.$colname)[$i];
                    }
                    $child_array[] = $column_data;
                }

                $childtable = CRUDBooster::parseSqlTable($ro['table'])['table'];
                DB::table($childtable)->insert($child_array);
            }
        }

        $this->hook_after_add($lastInsertId);

        $this->return_url = ($this->return_url) ? $this->return_url : Request::get('return_url');

        //insert log
//        CRUDBooster::insertLog(trans("crudbooster.log_add", ['name' => $this->arr[$this->title_field], 'module' => CRUDBooster::getCurrentModule()->name]));

        if ($this->hook_after_add_before_redirect($this->arr)){
            CRUDBooster::redirect(CRUDBooster::mainpath('edit').'/'.$lastInsertId, trans("crudbooster.alert_add_data_success"), 'success');
        }

        /*if ($this->return_url) {
            if (Request::get('submit') == trans('crudbooster.button_save_more')) {
                CRUDBooster::redirect(Request::server('HTTP_REFERER'), trans("crudbooster.alert_add_data_success"), 'success');
            } else {
                CRUDBooster::redirect($this->return_url, trans("crudbooster.alert_add_data_success"), 'success');
            }
        } else {
            if (Request::get('submit') == trans('crudbooster.button_save_more')) {
                CRUDBooster::redirect(CRUDBooster::mainpath('add'), trans("crudbooster.alert_add_data_success"), 'success');
            } else {
                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_add_data_success"), 'success');
            }
        }*/
    }

    public function getDelete($id)
    {
        $this->cbLoader();
        $row = \Illuminate\Support\Facades\DB::table($this->table)->where($this->primary_key, $id)->first();

        if ((! CRUDBooster::isDelete() && $this->global_privilege == false) || $this->button_delete == false) {
            CRUDBooster::insertLog(trans("crudbooster.log_try_delete", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }

        //insert log
//        CRUDBooster::insertLog(trans("crudbooster.log_delete", ['name' => $row->{$this->title_field}, 'module' => CRUDBooster::getCurrentModule()->name]));

        $this->hook_before_delete($id);

        if (CRUDBooster::isColumnExists($this->table, 'deleted_at')) {
            DB::table($this->table)->where($this->primary_key, $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table($this->table)->where($this->primary_key, $id)->delete();
        }

        $this->hook_after_delete($id);

        $url = g('return_url') ?: CRUDBooster::referer();

        CRUDBooster::redirect($url, trans("crudbooster.alert_delete_data_success"), 'success');
    }

    public function getDetail($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if ((!CRUDBooster::isRead() && $this->global_privilege == false) || $this->button_detail == false) {
            CRUDBooster::insertLog(trans("crudbooster.log_try_view", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }
        $this->hook_query_edit($row);

        $module = CRUDBooster::getCurrentModule();

        $page_menu = Route::getCurrentRoute()->getActionName();
        $page_title = trans("crudbooster.detail_data_page_title", ['module' => $module->name, 'name' => $row->{$this->title_field}]);
        $command = 'detail';

        Session::put('current_row_id', $id);

        return view('crudbooster::default.form', compact('row', 'page_menu', 'page_title', 'command', 'id', 'page_sub_title'));
    }

    public function getEdit($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if ((!CRUDBooster::isRead() && $this->global_privilege == false) || $this->button_edit == false) {
            CRUDBooster::insertLog(trans("crudbooster.log_try_edit", [
                'name' => $row->{$this->title_field},
                'module' => CRUDBooster::getCurrentModule()->name,
            ]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }
        $this->hook_query_edit($row);

        $page_menu = Route::getCurrentRoute()->getActionName();
        $page_title = trans("crudbooster.edit_data_page_title", ['module' => CRUDBooster::getCurrentModule()->name, 'name' => $row->{$this->title_field}]);

        $command = 'edit';
        $page_title = $this->hook_page_title($page_title);
        Session::put('current_row_id', $id);

        return view($this->getEditViewName(), compact('id', 'row', 'page_menu', 'page_title', 'command', 'page_sub_title'));
    }

    public function postEditSave($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! CRUDBooster::isUpdate() && $this->global_privilege == false) {
            CRUDBooster::insertLog(trans("crudbooster.log_try_add", ['name' => $row->{$this->title_field}, 'module' => CRUDBooster::getCurrentModule()->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }

        $this->validation($id);
        $this->input_assignment($id);

        if (Schema::hasColumn($this->table, 'updated_at')) {
            $this->arr['updated_at'] = date('Y-m-d H:i:s');
        }

        if (Schema::hasColumn($this->table, 'updated_user')) {
            $this->arr['updated_user'] = CRUDBooster::myId();
        }

        $this->hook_before_edit($this->arr, $id);
        DB::table($this->table)->where($this->primary_key, $id)->update($this->arr);

        //Looping Data Input Again After Insert
        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];
            if (! $name) {
                continue;
            }

            $inputdata = Request::get($name);

            //Insert Data Checkbox if Type Datatable
            if ($ro['type'] == 'checkbox') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];

                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = CB::pk($ro['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
//                                 $relationship_table_pk => CRUDBooster::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'select2') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];

                    $foreignKey2 = CRUDBooster::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = CRUDBooster::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = CB::pk($ro['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
//                                 $relationship_table_pk => CRUDBooster::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'child') {
                $name = str_slug($ro['label'], '');
                $columns = $ro['columns'];
                $getColName = Request::get($name.'-'.$columns[0]['name']);
                $count_input_data = ($getColName)?(count($getColName) - 1):0;
                $child_array = [];
                $childtable = CRUDBooster::parseSqlTable($ro['table'])['table'];
                $fk = $ro['foreign_key'];

                DB::table($childtable)->where($fk, $id)->delete();
                $lastId = CRUDBooster::newId($childtable);
                $childtablePK = CB::pk($childtable);

                for ($i = 0; $i <= $count_input_data; $i++) {

                    $column_data = [];
                    $column_data[$childtablePK] = $lastId;
                    $column_data[$fk] = $id;
                    foreach ($columns as $col) {
                        $colname = $col['name'];
                        $column_data[$colname] = Request::get($name.'-'.$colname)[$i];
                    }
                    $child_array[] = $column_data;

                    $lastId++;
                }

                $child_array = array_reverse($child_array);

                DB::table($childtable)->insert($child_array);
            }
        }

        $this->hook_after_edit($id);

        $this->return_url = ($this->return_url) ? $this->return_url : Request::get('return_url');

        //insert log
       /* $old_values = json_decode(json_encode($row), true);
        CRUDBooster::insertLog(trans("crudbooster.log_update", [
            'name' => $this->arr[$this->title_field],
            'module' => CRUDBooster::getCurrentModule()->name,
        ]), LogsController::displayDiff($old_values, $this->arr));*/

        if ($this->hook_after_edit_before_redirect($this->arr)){
            CRUDBooster::redirectBack(trans("crudbooster.alert_update_data_success"), 'success');
        }

       /* if ($this->return_url) {
            CRUDBooster::redirect($this->return_url, trans("crudbooster.alert_update_data_success"), 'success');
        } else {
            if (Request::get('submit') == trans('crudbooster.button_save_more')) {
                CRUDBooster::redirect(CRUDBooster::mainpath('add'), trans("crudbooster.alert_update_data_success"), 'success');
            } else {
                CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_update_data_success"), 'success');
            }
        }*/
    }

    protected function hook_after_edit_before_redirect($postdata)
    {
        return true;
    }

    protected function hook_after_add_before_redirect($postdata)
    {
        return true;
    }

    protected function hook_page_title($page_title)
    {
        return $page_title;
    }

    protected function hook_query_edit(&$row)
    {
        //Your code here
    }

    protected function getIndexViewName()
    {
        return "crudbooster::default.index";
    }

    protected function getEditViewName()
    {
        return "crudbooster::default.form";
    }

    protected function getAddViewName()
    {
        return "crudbooster::default.form";
    }

    protected function hook_before_filter_column(&$filter_column)
    {
        //Your code here
    }

    protected function hook_query_select(&$result)
    {
        //Your code here
    }

    protected function hook_before_render_index(&$data)
    {
        //Your code here
    }

    protected function fireErrorMessage($key, $message)
    {
        $messageBag = new MessageBag(array($key => $message));
        $res = redirect()->back()->with("errors", $messageBag)->with([
            'message' => trans('crudbooster.alert_validation_error', ['error' => $message]),
            'message_type' => 'warning',
        ])->withInput();
        \Session::driver()->save();
        $res->send();
        exit;
    }

    public function input_assignment($id = null)
    {

        $hide_form = (Request::get('hide_form')) ? unserialize(Request::get('hide_form')) : [];

        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];

            if (!$name) {
                continue;
            }

            if ($ro['exception']) {
                continue;
            }

            if ($name == 'hide_form') {
                continue;
            }

            if (count($hide_form)) {
                if (in_array($name, $hide_form)) {
                    continue;
                }
            }

            if ($ro['type'] == 'checkbox' && $ro['relationship_table']) {
                continue;
            }

            if ($ro['type'] == 'select2' && $ro['relationship_table']) {
                continue;
            }

            $inputdata = Request::get($name);

            if ($ro['type'] == 'money') {
                $inputdata = preg_replace('/[^\d-]+/', '', $inputdata);
            }

            if ($ro['type'] == 'child') {
                continue;
            }
            if ($name) {
                if ($inputdata != '') {
                    if (is_string($inputdata)){
                        $this->arr[$name] = trim($inputdata);
                    }else{
                        $this->arr[$name] = $inputdata;
                    }
                } else {
                    if (CB::isColumnNULL($this->table, $name) && $ro['type'] != 'upload') {
                        if ($ro['type'] == 'number') {
                            $this->arr[$name] = null;
                        } elseif ($ro['type'] == 'text') {
                            $this->arr[$name] = null;
                        } elseif ($ro['type'] == 'select') {
                            $this->arr[$name] = null;
                        }elseif ($ro['type'] == 'select2') {
                            $this->arr[$name] = null;
                        }else{
                            continue;
                        }
                    } else {
                        $this->arr[$name] = null;
                    }
                }
            }

            $password_candidate = explode(',', config('crudbooster.PASSWORD_FIELDS_CANDIDATE'));
            if (in_array($name, $password_candidate)) {
                if (!empty($this->arr[$name])) {
                    $this->arr[$name] = Hash::make($this->arr[$name]);
                } else {
                    unset($this->arr[$name]);
                }
            }

            if ($ro['type'] == 'checkbox') {

                if (is_array($inputdata)) {
                    if ($ro['datatable'] != '') {
                        $table_checkbox = explode(',', $ro['datatable'])[0];
                        $field_checkbox = explode(',', $ro['datatable'])[1];
                        $table_checkbox_pk = CB::pk($table_checkbox);
                        $data_checkbox = DB::table($table_checkbox)->whereIn($table_checkbox_pk, $inputdata)->pluck($field_checkbox)->toArray();
                        $this->arr[$name] = implode(";", $data_checkbox);
                    } else {
                        $this->arr[$name] = implode(";", $inputdata);
                    }
                }
            }

            //multitext colomn
            if ($ro['type'] == 'multitext') {
                $name = $ro['name'];
                $multitext = "";

                for ($i = 0; $i <= count($this->arr[$name]) - 1; $i++) {
                    $multitext .= $this->arr[$name][$i] . "|";
                }
                $multitext = substr($multitext, 0, strlen($multitext) - 1);
                $this->arr[$name] = $multitext;
            }

            if ($ro['type'] == 'googlemaps') {
                if ($ro['latitude'] && $ro['longitude']) {
                    $latitude_name = $ro['latitude'];
                    $longitude_name = $ro['longitude'];
                    $this->arr[$latitude_name] = Request::get('input-latitude-' . $name);
                    $this->arr[$longitude_name] = Request::get('input-longitude-' . $name);
                }
            }

            if ($ro['type'] == 'select' || $ro['type'] == 'select2') {
                if ($ro['datatable']) {
                    if ($inputdata === '') {
                        $this->arr[$name] = 0;
                    }
                }
            }

            if (@$ro['type'] == 'upload') {

                $this->arr[$name] = CRUDBooster::uploadFile($name, $ro['encrypt'], $ro['resize_width'], $ro['resize_height'], CB::myId());

                if (!$this->arr[$name]) {
                    $this->arr[$name] = Request::get('_' . $name);
                }
            }

            if (@$ro['type'] == 'filemanager') {
                $filename = str_replace('/' . config('lfm.prefix') . '/' . config('lfm.files_folder_name') . '/', '', $this->arr[$name]);
                $url = 'uploads/' . $filename;
                $this->arr[$name] = $url;
            }
        }
    }

    public function validation($id = null)
    {

        $request_all = Request::all();
        $array_input = [];
        foreach ($this->data_inputan as $di) {
            $ai = [];
            $name = $di['name'];

            if (!isset($request_all[$name])) {
                continue;
            }

            if ($di['type'] != 'upload') {
                if (@$di['required']) {
                    $ai[] = 'required';
                }
            }

            if ($di['type'] == 'upload') {
                if ($id) {
                    $row = DB::table($this->table)->where($this->primary_key, $id)->first();
                    if ($row->{$di['name']} == '') {
                        $ai[] = 'required';
                    }
                }
            }

            if (@$di['min']) {
                $ai[] = 'min:' . $di['min'];
            }
            if (@$di['max']) {
                $ai[] = 'max:' . $di['max'];
            }
            if (@$di['image']) {
                $ai[] = 'image';
            }
            if (@$di['mimes']) {
                $ai[] = 'mimes:' . $di['mimes'];
            }
            $name = $di['name'];
            if (!$name) {
                continue;
            }

            if ($di['type'] == 'money') {
                $request_all[$name] = preg_replace('/[^\d-]+/', '', $request_all[$name]);
            }

            if ($di['type'] == 'child') {
                $slug_name = str_slug($di['label'], '');
                foreach ($di['columns'] as $child_col) {
                    if (isset($child_col['validation'])) {
                        //https://laracasts.com/discuss/channels/general-discussion/array-validation-is-not-working/
                        if (strpos($child_col['validation'], 'required') !== false) {
                            $array_input[$slug_name . '-' . $child_col['name']] = 'required';

                            str_replace('required', '', $child_col['validation']);
                        }

                        $array_input[$slug_name . '-' . $child_col['name'] . '.*'] = $child_col['validation'];
                    }
                }
            }

            if (@$di['validation']) {

                $exp = explode('|', $di['validation']);
                if (count($exp)) {
                    foreach ($exp as &$validationItem) {
                        if (substr($validationItem, 0, 6) == 'unique') {
                            $parseUnique = explode(',', str_replace('unique:', '', $validationItem));
                            $uniqueTable = ($parseUnique[0]) ?: $this->table;
                            $uniqueColumn = ($parseUnique[1]) ?: $name;
                            $uniqueIgnoreId = ($parseUnique[2]) ?: (($id) ?: '');

                            //Make sure table name
                            $uniqueTable = CB::parseSqlTable($uniqueTable)['table'];

                            //Rebuild unique rule
                            $uniqueRebuild = [];
                            $uniqueRebuild[] = $uniqueTable;
                            $uniqueRebuild[] = $uniqueColumn;
                            if ($uniqueIgnoreId) {
                                $uniqueRebuild[] = $uniqueIgnoreId;
                            } else {
                                $uniqueRebuild[] = 'NULL';
                            }

                            //Check whether deleted_at exists or not
                            if (CB::isColumnExists($uniqueTable, 'deleted_at')) {
                                $uniqueRebuild[] = CB::findPrimaryKey($uniqueTable);
                                $uniqueRebuild[] = 'deleted_at';
                                $uniqueRebuild[] = 'NULL';
                            }
                            $uniqueRebuild = array_filter($uniqueRebuild);
                            $validationItem = 'unique:' . implode(',', $uniqueRebuild);
                        }
                    }
                } else {
                    $exp = [];
                }

                $validation = implode('|', $exp);

                $array_input[$name] = $validation;
            } else {
                $array_input[$name] = implode('|', $ai);
            }
        }

        $validator = Validator::make($request_all, $array_input);
        $attributeLabels = $this->getAttributeLabel();
        if ($attributeLabels) {
            $validator->setAttributeNames($attributeLabels);
        }

        if ($validator->fails()) {
            $message = $validator->messages();
            $message_all = $message->all();

            if (Request::ajax()) {
                $res = response()->json([
                    'message' => trans('crudbooster.alert_validation_error', ['error' => implode(', ', $message_all)]),
                    'message_type' => 'warning',
                ])->send();
                exit;
            } else {
                $res = redirect()->back()->with("errors", $message)->with([
                    'message' => trans('crudbooster.alert_validation_error', ['error' => implode(', ', $message_all)]),
                    'message_type' => 'warning',
                ])->withInput();
                \Session::driver()->save();
                $res->send();
                exit;
            }
        }

        // validate not special character fields
        $fields = $this->getNotSpecialCharFields();
        if (count($fields)) {
            $fieldValidators = array_fill_keys($fields, new NotSpecialCharRule());
            $validator = Validator::make($request_all, $fieldValidators);
            if ($attributeLabels) {
                $validator->setAttributeNames($attributeLabels);
            }

            if ($validator->fails()) {
                $message = $validator->messages();
                $message_all = $message->all();

                if (Request::ajax()) {
                    $res = response()->json([
                        'message' => trans('crudbooster.alert_validation_error', ['error' => implode(', ', $message_all)]),
                        'message_type' => 'warning',
                    ])->send();
                    exit;
                } else {
                    $res = redirect()->back()->with("errors", $message)->with([
                        'message' => trans('crudbooster.alert_validation_error', ['error' => implode(', ', $message_all)]),
                        'message_type' => 'warning',
                    ])->withInput();
                    \Session::driver()->save();
                    $res->send();
                    exit;
                }
            }
        }
    }

    public function getFindData()
    {
        $q = Request::get('q');
        $id = Request::get('id');
        $limit = Request::get('limit') ?: 10;
        $format = Request::get('format');

        $table1 = (Request::get('table1')) ?: $this->table;
        $table1PK = CB::pk($table1);
        $column1 = (Request::get('column1')) ?: $this->title_field;

        @$table2 = Request::get('table2');
        @$column2 = Request::get('column2');

        @$table3 = Request::get('table3');
        @$column3 = Request::get('column3');

        $where = Request::get('where');

        $fk = Request::get('fk');
        $fk_value = Request::get('fk_value');

        if ($q || $id || $table1) {
            $rows = DB::table($table1);
            $rows->select($table1 . '.*');
            $rows->take($limit);

            if (CRUDBooster::isColumnExists($table1, 'deleted_at')) {
                $rows->where($table1 . '.deleted_at', null);
            }

            if ($fk && $fk_value) {
                $rows->where($table1 . '.' . $fk, $fk_value);
            }

            if ($table1 && $column1) {

                $orderby_table = $table1;
                $orderby_column = $column1;
            }

            if ($table2 && $column2) {
                $table2PK = CB::pk($table2);
                $rows->join($table2, $table2 . '.' . $table2PK, '=', $table1 . '.' . $column1);
                $columns = CRUDBooster::getTableColumns($table2);
                foreach ($columns as $col) {
                    $rows->addselect($table2 . "." . $col . " as " . $table2 . "_" . $col);
                }
                $orderby_table = $table2;
                $orderby_column = $column2;
            }

            if ($table3 && $column3) {
                $table3PK = CB::pk($table3);
                $rows->join($table3, $table3 . '.' . $table3PK, '=', $table2 . '.' . $column2);
                $columns = CRUDBooster::getTableColumns($table3);
                foreach ($columns as $col) {
                    $rows->addselect($table3 . "." . $col . " as " . $table3 . "_" . $col);
                }
                $orderby_table = $table3;
                $orderby_column = $column3;
            }

            if ($id) {
                $rows->where($table1 . "." . $table1PK, $id);
            }

            if ($where) {
                $rows->whereraw($where);
            }

            if ($format) {
                $format = str_replace('&#039;', "'", $format);
                $rows->addselect(DB::raw("CONCAT($format) as text"));
                if ($q) {
                    $rows->whereraw("CONCAT($format) like '%" . $q . "%'");
                }
            } else {
                $rows->addselect($orderby_table . '.' . $orderby_column . ' as text');
                if ($q) {
                    $rows->where($orderby_table . '.' . $orderby_column, 'like', '%' . $q . '%');
                }
                $rows->orderBy($orderby_table . '.' . $orderby_column, 'asc');
            }
            $this->hook_query_find_data($rows);
            $result = [];
            $result['items'] = $rows->get();
        } else {
            $result = [];
            $result['items'] = [];
        }

        return response()->json($result);
    }

    protected function hook_query_find_data(&$query)
    {

    }

    protected function getAttributeLabel()
    {
        $names = array();
        foreach ($this->form as $att) {
            $names[$att['name']] = $att['label'];
        }
        return $names;
    }

    protected function getNotSpecialCharFields()
    {
        return array();
    }

    protected function getMyFarms()
    {
        $myFarms = Session::get('my_farm');
        if (!$myFarms) {
            $userFarms = null;
            if (app()->getLocale() == 'en') {
                $userFarms = DB::table('ebi_farms')->select('id as farm_id')->orderBy('farm_name_en')->get();
            } else {
                $userFarms = DB::table('ebi_farms')->select('id as farm_id')->orderBy('farm_name')->get();
              //  $userFarms = DB::table('ebi_user_farms')->select('farm_id')->where('user_id', intval(CRUDBooster::myId()))->get();
            }
            $myFarms = array();
            foreach ($userFarms as $farm) {
                $myFarms[] = $farm->farm_id;
            }
            Session::put('my_farm', $myFarms);
        }
        return $myFarms;
    }

    protected function getMyPonds()
    {
        $myFarms = $this->getMyFarms();
        $userPonds = DB::table('ebi_ponds')->select('id')->whereIn('farm_id', $myFarms)->get();

        $myPonds = array();
        foreach ($userPonds as $pond) {
            $myPonds[] = $pond->id;
        }
        return $myPonds;
    }
}
