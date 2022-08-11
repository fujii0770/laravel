@extends('crudbooster::admin_template_with_left_sidebar')

@section('content')

@push('bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            var $window = $(window);

            var elements = document.getElementsByClassName('column-title');
            var firstElement = elements[0];
            $(firstElement).focus();

            function checkWidth() {
                var windowsize = $window.width();
                if (windowsize > 500) {
                    console.log(windowsize);
                    $('#box-body-table').removeClass('table-responsive');
                } else {
                    console.log(windowsize);
                    $('#box-body-table').addClass('table-responsive');
                }
            }

            checkWidth();
            $(window).resize(checkWidth);

        });
    </script>
@endpush
@push('head')
    <style>
        .column-title {
            width: 100%;
            border: none;
            background-color: unset;
        }
        .box-action{
            display: flex; 
            justify-content: space-between;
        }
        .panel-heading {
            padding-left: 0;
            background-color: white;
            color: #9bbce0;
            border-color: white;
        }
        .tr-error {
            border: 1px solid red;
        }
    </style>
@endpush
<div class="box">
    <div class="panel-heading">
        <strong> {{trans('ebi.エビ設定')}} </strong>
        <hr class="bottom-hr w-100">
    </div>
    <div class="box-body no-padding">
        <form id='form-table' method="post" action="{{ $postUrl }}">
            <input type='hidden' name='button_name' value=''/>
            <input type='hidden' name='_token' value='{{csrf_token()}}'/>
            <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                <thead>
                <tr class="active">
                    <?php
                    foreach ($columns as $col) {
                        if ($col['visible'] === FALSE) continue;

                        $sort_column = Request::get('filter_column');
                        $colname = $col['label'];
                        $name = $col['name'];
                        $field = $col['field_with'];
                        $width = ($col['width']) ?: "auto";
                        $mainpath = trim(CRUDBooster::mainpath(), '/').$build_query;
                        echo "<th width='$width'>".$colname. "</th>";
                    }
                    ?>
                    <?php if($button_bulk_action):?>
                        <th width='6%'>{{trans('ebi.削除')}}</th>
                    <?php endif;?>
                </tr>
                </thead>
                <tbody>
                    @if(count($result)==0)
                        <tr class='warning table-data-not-found'>
                            <?php if($button_bulk_action && $show_numbering):?>
                            <td colspan='{{count($columns)+3}}' align="center">
                            <?php elseif( ($button_bulk_action && ! $show_numbering) || (! $button_bulk_action && $show_numbering) ):?>
                            <td colspan='{{count($columns)+2}}' align="center">
                            <?php else:?>
                            <td colspan='{{count($columns)+1}}' align="center">
                                <?php endif;?>

                                <i class='fa fa-search'></i> {{trans("crudbooster.table_data_not_found")}}
                            </td>
                        </tr>
                    @endif
                    @foreach($html_contents['data'] as $index => $row)
                        @php
                            $id = $row->id;
                            unset($row->id);
                            $row->id = $id;  
                        @endphp
                        <tr>
                            @foreach((array)$row as $i => $column)
                                @php
                                    $inputOldName = "shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]";
                                @endphp
                                @if($i == 'id')
                                    <td><input class="column-title" name="shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]" type="checkbox" value="{!! $column !!}"></td>
                                @elseif($i == 'ebi_kind_id')
                                    <td>
                                        <select class="column-title" name="shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]">
                                            @foreach($ebiKindList as $list)
                                                <option value='{{ $list->id }}' {!! ($list->id ==  $row->ebi_kind_id) ? 'selected':'' !!}  >{{ $list->kind }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @elseif($i == 'aquaculture_method_id')
                                    <td>
                                        <select class="column-title method_id" name="shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]">
                                            @foreach($aquacultureMethods as $list)
                                                <option value='{{ $list->id }}' {!! ($list->id ==  $row->aquaculture_method_id) ? 'selected':'' !!}  >{{ $list->method }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @elseif($i == 'weight' || $i == 'ebi_price' || $i == 'price')
                                    <td><input class="column-title" name="shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]" type="number" step="0.01" min="0.01" max="999999.99" required value= "{{ old($inputOldName, $column) }}"></td>
                                @elseif($i == 'grade' || $i == 'training_period')
                                    <td><input class="column-title" name="shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]" type="number" min="0" max="2147483647" step="1" required value= "{{ old($inputOldName, $column) }}"></td>
                                @elseif($i == 'method')
                                    <td><input class="column-title method" name="shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]" type="text" minlength="1" maxlength="50" required value= "{{ old($inputOldName, $column) }}"></td>
                                @else
                                    <td><input class="column-title kind" name="shrimp-setting[{!! $i !!}][{!! $index .'-'. $id !!}]" type="text" minlength="1" maxlength="50" required value= "{{ old($inputOldName, $column) }}"></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="box-action">
                <div>
                </div>
    
                <div>
                    <div id="add_tab" class="btn btn-success">{{trans("ebi.タブ追加")}}</div>
                    <input id="post_data" class="btn btn-primary" type="submit" value="{{trans("ebi.登録")}}">
                </div>
            </div>

        </form><!--END FORM TABLE-->

                
       
    </div>
</div>
@if($columns)
    @push('bottom')
        <script>
            $(function () {
                $("#table_dashboard .checkbox").click(function () {
                    var is_any_checked = $("#table_dashboard .checkbox:checked").length;
                    if (is_any_checked) {
                        $(".btn-delete-selected").removeClass("disabled");
                    } else {
                        $(".btn-delete-selected").addClass("disabled");
                    }
                })

                $("#post_data").click(function () {
                    $('#table_dashboard tr.add-new td input[type="checkbox"]').each(function (index, item){
                        if($(item).is(':checked')) $(item).closest('tr').remove();
                    })
                    const err_method = $('#table_dashboard tbody tr input.tr-error.method');
                    const err_kind = $('#table_dashboard tbody tr input.tr-error.kind');
                    if (err_method.length > 0) {
                        alert("{{ trans('ebi.duplicate_method') }}");
                        return false;
                    }
                    if (err_kind.length > 0) {
                        alert("{{ trans('ebi.duplicate_kind') }}");
                        return false;
                    }
                })

                $("#add_tab").click(function() {
                    var rowCount = $('#table_dashboard tr').length  - 1;
 
                    $("#table_dashboard").each(function () {
                        var addTr = '{!! $templateHtml !!}';
                        var newTr = addTr.replace(/index-shrimp-setting/g, +rowCount+'-0').replace(/index-shrimp-id/g,+rowCount+'-0'); 
                        $(this).append(newTr);
                        $(".shrimp-setting").focus();
                        $(".table-data-not-found").hide();
                       
                    });

                })

                function validateTableRow() {
                    const datas = $('#table_dashboard tbody tr').map(function () {
                        return {
                            name: $(this).find('td:first-child .kind').val(),
                            method: $(this).find('td:nth-child(4) .method_id').val(),
                        }
                    }).get();
                    console.log(datas);
                    $('#table_dashboard tbody tr').each(function (index, element) {
                        var name = $(element).find('td:first-child .kind').val();
                        var method = $(element).find('td:nth-child(4) .method_id').val();
                        console.log(name, method);
                        if (datas.some(function (item,_index) {return item.name === name && item.method === method && _index !== index})) {
                            $(element).find('input.kind').addClass('tr-error');
                            $(element).find('select.method_id').addClass('tr-error');
                        }else {
                            $(element).find('input.kind').removeClass('tr-error');
                            $(element).find('select.method_id').removeClass('tr-error');
                        }
                    });
                }
                $(document).on('change', '#table_dashboard .kind, #table_dashboard .method_id', function () {
                    validateTableRow();
                });

                function validateMethod() {
                    const datas = $('#table_dashboard tbody tr').map(function (input) {
                        return $(this).find('td:first-child .method').val();
                    }).get();
                    console.log(datas);

                    $('#table_dashboard tbody tr input.method').each(function (index, element) {
                        var method = $(element).val();
                        console.log(method);
                        if (datas.some(function (item,_index) {return item === method && _index !== index})) {
                            $(element).addClass('tr-error');
                        }else {
                            $(element).removeClass('tr-error');
                        }
                    });
                }
                $(document).on('change', '#table_dashboard .method', function () {
                    validateMethod();
                });

            })
        </script>

    @endpush
@endif



@endsection
