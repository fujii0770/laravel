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


       
<form class='form-horizontal' method='post' id="form"  action="sell_add">
    @csrf
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong></i>売上登録  {{$pond_name}}</strong>
            <hr class="bottom-hr w-100">
        </div>

        <div class="panel-body" style="padding:20px 0px 0px 10px">                               
            <div class="box-body" id="parent-form-area">
                <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.池名')}}
                        <span class='text-danger' title='この項目は必須です。'>*</span>
                </label>

                <div class="col-sm-10">
                    <select name=pond_id id=pond_id class='form-control' value=<?php echo $pond_id   ?>>
                                <option style="color: #000000;" value="<?php echo $pond_id   ?>"><p><?php echo $pond_name   ?></p></option>
                    </select>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
            <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.出荷量(kg)')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="amount" min=0 
                        required    class='form-control'
                        name="amount" id="amount" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
            <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.エビ重量(g)')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="weight" min=0 
                        required    class='form-control'
                        name="weight" id="weight" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>          
            <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.売上')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                </label>

                <div class="col-sm-10">
                    <input type='text' step="any" title="売上" min=0 
                        required    class='form-control'
                        name="sell" id="sell" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div> 
            <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.日付')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='date' step="any" title="日付" min=0 
                        required    class='form-control'
                        name="date" id="date" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
            <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.輸出')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                </label>

                <div class="col-sm-10">
                    <select name="trade" class='form-control'>
                        <option value="0">国内</option>
                        <option value="1">海外</option>
                    </select>
                    <p class='help-block'></p>
                </div>
            </div>
            <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>status
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                        </label>

                <div class="col-sm-10">
                    <select name=status id=status class='form-control' value="0">
                                <option style="color: #000000;" value="0"><p>{{trans('ebi.養殖中')}}</p></option>
                                <option style="color: #000000;" value="1"><p>{{trans('ebi.完了')}}</p></option>
                    </select>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
        </div><!-- /.box-body -->
        <div class="box-footer">
            <div class="form-group">
                <label class="control-label col-sm-2"></label>
                <div class="col-sm-10">
                <input type="submit" name="submit" value='保存' class='btn btn-success' value= <?php echo $pond_id  ?>>  
                </div>
            </div>
        </div><!-- /.box-footer-->
            </div>
        </div>
    </div>
</form>
@endsection