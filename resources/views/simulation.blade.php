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
        <?php
        $ebi_kinds= $aquacultures=DB::table('ebi_kind')->get();
        $i=0;
        foreach($ebi_kinds as $ebi_kind){
            $id[$i]=$ebi_kind->id;
            $kind[$i]=$ebi_kind->kind;
            $i++;
        }
        ?>
        <div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong></i>{{trans('ebi.シミュレーション')}}</strong>
                            <hr class="bottom-hr w-100">
                        </div>

                        <div class="panel-body" style="padding:20px 0px 0px 10px">
                                            <form class='form-horizontal' method='post' id="form"  action="simulation">
                                            @csrf
                                                    <div class="box-body" id="parent-form-area">

                                    <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.目標重量(g)')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="目標重量(g)" min=0 
                        required    class='form-control'
                        name="target_weight" id="target_weight" value='0'/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                <div class='form-group header-group-0 ' id='form-group-food_amount' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.餌費用')}}
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="餌費用" min=0 
                            class='form-control'
                        name="food_amount" id="food_amount" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                <div class='form-group header-group-0 ' id='form-group-power_consumption' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.電力費用')}}
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="電力費用" min=0 
                            class='form-control'
                        name="power_consumption" id="power_consumption" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                <div class='form-group header-group-0 ' id='form-group-medicine_amount' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.薬費用')}}
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="薬費用" min=0 
                            class='form-control'
                        name="medicine_amount" id="medicine_amount" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                
            <div class='form-group header-group-0 ' id='form-group-shrimp_num' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.稚エビ量(匹)')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="1" title="稚エビ量(匹)" min=0 
                        required    class='form-control'
                        name="shrimp_num" id="shrimp_num" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>             

            <div class="form-group header-group-0 " id="kind" style="">
                                <label class="control-label col-sm-2">{{trans('ebi.エビ種類')}}<span class='text-danger' title='この項目は必須です。'>*</span></label>
                                <div class="col-sm-10">
                                    <select style='width:100%' class='form-control' name=kind id=kind required  value="">
                                    @for($c=0 ; $c <= $i-1 ; $c++)
                                    <option style="color: #000000;" value=<?php echo $id[$c] ?>><p><?php echo $kind[$c] ?></p></option>
                                    @endfor
                                    </select>
                                </div> 
                            </div>
                
                            <div class='form-group header-group-0 ' id='form-group-servival_rate' style="">
                <label class='control-label col-sm-2'>{{trans('ebi.生存率')}}
                                <span class='text-danger' title='この項目は必須です。'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="生存率" min=0 
                        required    class='form-control'
                        name="servival_rate" id="servival_rate" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>  
            <div class='form-group header-group-0'>
                                        <div class="col-sm-2">
                                            <label class='control-label'>{{trans('ebi.結果')}}</label>
                                           
                                        </div>
                                        <div class="col-sm-10">
                                            <p class="form-control"><?php echo $sell ?></p>
                                        </div>
                                    </div>
             </div><!-- /.box-body -->

                                <div class="box-footer">

                                    <div class="form-group">
                                        <label class="control-label col-sm-2"></label>       
                                        <input type="submit" name="submit" value="{{trans('ebi.計算')}}" class='btn btn-success'>
                                       
                                    </div>
                                    
                                   

                                </div><!-- /.box-footer-->

                            </form>
            </div>
        </div>
    </div>
@endsection