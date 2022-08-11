@extends('crudbooster::admin_template_with_left_sidebar')

@section('content')

    @if($index_statistic)
        <div id='box-statistic' class='row'>
            @foreach($index_statistic as $stat)
                <div class="{{ ($stat['width'])?:'col-sm-3' }}">
                    <div class="small-box bg-{{ $stat['color']?:'red' }}">
                        <div class="inner">
                            <h3>{{ $stat['count'] }}</h3>
                            <p>{{ $stat['label'] }}</p>
                        </div>
                        <div class="icon">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if(!is_null($pre_index_html) && !empty($pre_index_html))
        {!! $pre_index_html !!}
    @endif


    @if(g('return_url'))
        <p><a href='{{g("return_url")}}'><i class='fa fa-chevron-circle-{{ trans('crudbooster.left') }}'></i>
                &nbsp; {{trans('crudbooster.form_back_to_list',['module'=>urldecode(g('label'))])}}</a></p>
    @endif

    @if($parent_table)
        <div class="box box-default">
            <div class="box-body table-responsive no-padding">
                <table class='table table-bordered'>
                    <tbody>
                    <tr class='active'>
                        <td colspan="2"><strong><i class='fa fa-bars'></i> {{ ucwords(urldecode(g('label'))) }}</strong></td>
                    </tr>
                    @foreach(explode(',',urldecode(g('parent_columns'))) as $c)
                        <tr>
                            <td width="25%"><strong>
                                    @if(urldecode(g('parent_columns_alias')))
                                        {{explode(',',urldecode(g('parent_columns_alias')))[$loop->index]}}
                                    @else
                                        {{  ucwords(str_replace('_',' ',$c)) }}
                                    @endif
                                </strong></td>
                            <td> {{ $parent_table->$c }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="box">
        <div class="box-header">
            @if($button_bulk_action && ( ($button_delete && CRUDBooster::isDelete()) || $button_selected) )
                <div class="pull-{{ trans('crudbooster.left') }}">
                    <div class="selected-action" style="display:inline-block;position:relative;">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                                    class='fa fa-check-square-o'></i> {{trans("crudbooster.button_selected_action")}}
                            <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu">
                            @if($button_delete && CRUDBooster::isDelete())
                                <li><a href="javascript:void(0)" data-name='delete' title='{{trans('crudbooster.action_delete_selected')}}'><i
                                                class="fa fa-trash"></i> {{trans('crudbooster.action_delete_selected')}}</a></li>
                            @endif

                            @if($button_selected)
                                @foreach($button_selected as $button)
                                    <li><a href="javascript:void(0)" data-name='{{$button["name"]}}' title='{{$button["label"]}}'><i
                                                    class="fa fa-{{$button['icon']}}"></i> {{$button['label']}}</a></li>
                                @endforeach
                            @endif

                        </ul><!--end-dropdown-menu-->
                    </div><!--end-selected-action-->
                </div><!--end-pull-left-->
            @endif
            <div class="box-tools pull-{{ trans('crudbooster.right') }}" style="position: relative;margin-top: -5px;margin-right: -10px">
                @if($button_filter)
                    <a style="margin-top:-23px" href="javascript:void(0)" id='btn_advanced_filter' data-url-parameter='{{$build_query}}'
                       title='{{trans('crudbooster.filter_dialog_title')}}' class="btn btn-sm btn-default {{(Request::get('filter_column'))?'active':''}}">
                        <i class="fa fa-filter"></i> {{trans("crudbooster.button_filter")}}
                    </a>
                @endif

               {{-- <form method='get' id='form-limit-paging' style="display:inline-block" action='{{Request::url()}}'>
                    {!! CRUDBooster::getUrlParameters(['limit']) !!}
                    <div class="input-group">
                        <select onchange="$('#form-limit-paging').submit()" name='limit' style="width: 56px;" class='form-control input-sm'>
                            <option {{($limit==5)?'selected':''}} value='5'>5</option>
                            <option {{($limit==10)?'selected':''}} value='10'>10</option>
                            <option {{($limit==20)?'selected':''}} value='20'>20</option>
                            <option {{($limit==25)?'selected':''}} value='25'>25</option>
                            <option {{($limit==50)?'selected':''}} value='50'>50</option>
                            <option {{($limit==100)?'selected':''}} value='100'>100</option>
                            <option {{($limit==200)?'selected':''}} value='200'>200</option>
                        </select>
                    </div>
                </form>--}}

            </div>

        </div>
        <div class="box-body table-responsive no-padding">
            @include("crudbooster::default.table")
        </div>
        <div class="box-footer">
            <form method='post' id="form" enctype="multipart/form-data" action='{{Request::is('admin/import_bait')?route('import_bait_completed'):route('import_drug_completed')}}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-sm-9 col-md-10">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="btn-upload-file">{{trans('ebi.Excelファイル (.xls）')}}</button>
                        </span>
                        <input type="file" id="btn-browser-file" name="f5file" style="display: none" accept=".xls,.xlsx"  onchange="return fileValidation()">
                        <input type="text" class="form-control" id="txt-file-name" readonly>
{{--
                        <input id="f5file" name='f5file' style="height:auto" accept=".xls,.xlsx" class='form-control' readonly=""/>--}}
                    </div>
                </div>
                <div class="col-sm-3 col-md-2 text-right">
                    <button class="btn btn-primary btn-submit" type="submit">{{trans('ebi.アップロード')}}</button>
                </div>
            </form>
        </div>
    </div>
    @if(!is_null($post_index_html) && !empty($post_index_html))
        {!! $post_index_html !!}
    @endif

@endsection
@push('head')
    <style>
        form#form {
            padding: 10px;
        }
        .f5form {
            width: 50%!important;
        }
        .box-footer {
            padding: 10px 0;
        }
    </style>
@endpush
@push('bottom')
    <script>
        function fileValidation(file){
            var fileInput = document.getElementById('btn-browser-file');           
            var filePath = fileInput.value;
            var allowedExtensions = /(\.(xls|xlsx))$/i;
            if(!allowedExtensions.exec(filePath)){
                alert('Excelファイル（拡張子 (.xls，.xlsx）をインポートしてください。');
                fileInput.value = '';
                return false;
            }else{
                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }
        }
    </script>
    <script>
        @php
            $max_upload = ini_get('upload_max_filesize');
            $max_upload = str_replace('M', '', $max_upload);
            $max_upload = $max_upload * 1024;
        @endphp
            var Maxvalue = {{$max_upload}};
        $(function () {
            $('input[type=file]').change(function () {
                var size = parseFloat($("#btn-browser-file")[0].files[0].size).toFixed(0);
                var fileSize = size/(1024);
                var fileSize = fileSize/(1024);
                var fileInput = document.getElementById('btn-browser-file');
                if (fileSize > Maxvalue) {
                    alert('ファイルサイズが大きすぎで、'+Maxvalue+'MB 以下にしてください。');
                    fileInput.value = '';
                    return false;
                }
                else if (size = 0){
                    alert("0MB以上をインポートしてください");
                } else {
                }

            });
        });
    </script>
@endpush
