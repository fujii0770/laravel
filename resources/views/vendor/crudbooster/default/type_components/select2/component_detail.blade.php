<?php
$datatable = $form['datatable'];
if ($datatable && ! $form['relationship_table']) {
    $datatable = explode(',', $datatable);
    $table = $datatable[0];
    $field = $datatable[1];
    if (count($datatable)>2){
        $pk_field = $datatable[2];
    }else{
        $pk_field = 'id';
    }
    $obj = CRUDBooster::first($table, [$pk_field => $value]);
    if (count($datatable)>3){
        $other_field = $datatable[3];
        echo implode(' ', [$obj->$field, $obj->$other_field]);
    }else{
        echo $obj->$field;
    }
}

if ($datatable && $form['relationship_table']) {
    $datatable_table = explode(',', $datatable)[0];
    $datatable_field = explode(',', $datatable)[1];
    $foreignKey = CRUDBooster::getForeignKey($table, $form['relationship_table']);
    $foreignKey2 = CRUDBooster::getForeignKey($datatable_table, $form['relationship_table']);
    $ids = DB::table($form['relationship_table'])->where($foreignKey, $id)->pluck($foreignKey2)->toArray();

    $tableData = DB::table($datatable_table)->whereIn('id', $ids)->pluck($datatable_field)->toArray();

    echo implode(", ", $tableData);
}

if ($form['dataenum']) {
    $result = $value;
    $labels = explode(';', $form['dataenum']);
    foreach ($labels as $label){
        $keyValue = explode('|', $label);
        if (count($keyValue) > 1 && ($keyValue[0] == $value)){
            $result = $keyValue[1];
        }
    }
    echo $result;
}

?>