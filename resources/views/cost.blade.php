@extends('crudbooster::admin_template')

@push('head')
    <link href="{{ asset("css/backend.css")}}?v=6" rel="stylesheet" type="text/css"/>
@endpush
@section('sidebar')
    @php
        $contentWrapperClass = 'content-wrapper';
        $ignoreFrontendCss = true;
    @endphp
    <!-- Sidebar -->
    @include('crudbooster::sidebar')
@endsection

@section('content')
<div>
    <div class="panel panel-default">
            <div class="panel-heading">
                <strong></i>{{trans('ebi.費用登録')}}</strong>
                <hr class="bottom-hr w-100">
            </div>

            <div class="panel-body" style="padding:20px 0px 0px 10px">
                <form class='form-horizontal' method='post' id="form"  action="cost_add">
                @csrf
    <div class="box-body" id="parent-form-area">     
        <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
        <label class='control-label col-sm-2'>{{trans('ebi.日付')}}
                        <span class='text-danger' title='この項目は必須です。'>*</span>
        </label>

        <div class="col-sm-10">
            <input type='date' step="any" title="year" min=0 
                required    class='form-control'
                name="date" id="date" value=''/>
            <div class="text-danger"></div>
            <p class='help-block'></p>
        </div>
    </div>     
    <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
        <label class='control-label col-sm-2'>{{trans('ebi.費用名')}}
        <span class='text-danger' title='この項目は必須です。'>*</span>
        </label>

        <div class="col-sm-10">
            <input type='text' step="any" title="名称" min=0 
                required    class='form-control'
                name="kind" id="kind" value=''/>
            <div class="text-danger"></div>
            <p class='help-block'></p>
        </div>
    </div> 
    <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
        <label class='control-label col-sm-2'>{{trans('ebi.金額')}}
        <span class='text-danger' title='この項目は必須です。'>*</span>
        </label>

        <div class="col-sm-10">
            <input type='number' step="any" title="費用" min=0 
                required    class='form-control'
                name="cost" id="cost" value='0'/>
            <div class="text-danger"></div>
            <p class='help-block'></p>
        </div>
    </div>                                                                                               
</div><!-- /.box-body -->
</div><!-- /.box-body -->

                    <div class="box-footer">

                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-10">   
                             <input type="submit" name="submit" value="{{trans('ebi.保存')}}" class='btn btn-success'>
                                
                             </div>

                        </div>


                    </div><!-- /.box-footer-->

                </form>
            </div>
        </div>
    </div>
@endsection