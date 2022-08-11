@extends('crudbooster::admin_template')

@push('head')
<link href="{{ asset("css/backend.css")}}?v=6" rel="stylesheet" type="text/css"/>
<style>
    #showData {
        padding: 0 20px 0 25px;
    }
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
        color: #9bbce0;
    }
    .aquacultures{
        background-color: white;
        padding: 15px;
    }
    .bottom-hr{
        margin-top: 0px;
        border-top-width: 2px;
        border-color: #dbe6f5;
    }

    .content {
        padding:0;
    }

    .information-pond-aquacultures{
        display: flex;
        align-items: center;
        padding: 0;
    }
    .pond-aquacultures{
        margin: 30px 0 15px 0;
    }
</style>
@endpush
@section('content')
    @include('partials/page_nav')
    <div class="row">
        @include('partials/frontend_sidebar')

        <div class="col-md-10 content-radio">
            @if (Session::get('message')!='')
                <div class='alert alert-{{ Session::get("message_type") }}'>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> {{ trans("crudbooster.alert_".Session::get("message_type")) }}</h4>
                    @php
                        $messages = Session::get('message');
                        $messages = explode(',', $messages);
                    @endphp
                    @foreach($messages as $message)
                        ・{!!trim($message)!!}<br/>
                    @endforeach
                </div>
            @endif
            <div class="aquacultures">
                {{-- <div class="panel-heading">
                    <strong> {{trans('ebi.エビ移行登録')}} </strong>
                </div>
                <hr class="bottom-hr w-100"> --}}
                <div class="box-body no-padding">
                    <form id='form-table' method="post" action="{{ route('shrimpMigrationRegistration').'?pondId='. Request::get('pondId').'&aquaId='.$issetPondsAquacultures }}">
                        <input type='hidden' name='button_name' value=''/>
                        <input type='hidden' name='_token' value='{{csrf_token()}}'/>
                        <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr class="active">
                                    <th width="50%">{{trans('ebi.エビ数')}}*</th>
                                    <th width="50%">{{trans('ebi.池名')}}*</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pondsAquacultures as $pond)
                                    {{-- @if(!$pond->status || (!$pond->status && $pond->ebi_remaining && $pond->takeover_ponds_id)) --}}
                                        <tr class='table-data-not-found'>
                                            <td width="50%">
                                                <input class="column-title" {!! $issetPondsAquacultures ? ($currentPondsAquacultures['status'] ? 'disabled' : '') : 'disabled' !!}
                                                    id="shrimp_migration[{!! $pond->pondId !!}]" name="shrimp-migration[{!! $pond->pondId !!}]" type="number" value= "{{$pond->shrimp_num}}"
                                                    min="0"></td>
                                            <td width="50%">{{$pond->pond_name}}</td>
                                        </tr>
                                    {{-- @endif --}}
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row pond-aquacultures">
                            <div class="form-group information-pond-aquacultures col-sm-6">
                                <label class="control-label col-sm-4"> {{trans('ebi.出荷ステータス')}} <span class="text-danger" title="This field is required">*</span> </label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="pond-aquacultures-status" {!! !$issetPondsAquacultures ? 'disabled' : '' !!}>
                                        <option {{($currentPondsAquacultures['status'] == 0)?'selected':''}} value='0' >{{trans('ebi.養殖中')}}</option>
                                        <option {{($currentPondsAquacultures['status'] == 1)?'selected':''}} value="1" >{{trans('ebi.完了')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group information-pond-aquacultures col-sm-6">
                                <label class="control-label col-sm-4"> {{trans('ebi.エビ残数')}} </label>
                                <div class="col-sm-8">
                                <input class="form-control" id="shrimp_remaining" name="shrimp-remaining" type="text" readonly value="{{$currentPondsAquacultures['shrimp_remaining']}}">
                                    
                                </div>
                            </div>
                        </div>

                        <div class="box-action">
                            <div>
                            </div>
        
                            <div>
                                <button class="btn btn-primary btn-submit" type="submit">{{trans("ebi.登録")}}</button>
                            </div>
                        </div>
                    </form><!--END FORM TABLE-->
                </div>
            </div>
        </div>
    </div>
@endsection
@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/highstock.js') }}?v=1"></script>
    <script src="{{ asset('js/highcharts-more.js') }}?v=1"></script>
@endpush
@push('bottom')
    <script>
        $('document').ready(function(){
            $('.column-title:not(:disabled)').first().focus();
            $('.column-title').map(function (index, item){
                if($(item).val()){
                    $(this).attr('data-old-shrimp-num',$(item).val());
                }else{
                    $(this).remove('data-old-shrimp-num');
                }
            })

            function calc_table_total() {
                var sum = $('.column-title').map(function(index, element){
                    return +$(element).val() || 0;
                }).toArray()
                .reduce(function(a,b){
                    return a + b;
                }, 0);
                return sum;
            }

            $('#shrimp_remaining').attr('data-total',(+$('#shrimp_remaining').val() + calc_table_total()));

            var issetPondsAquacultures = '{!! $issetPondsAquacultures !!}';
            if(issetPondsAquacultures){
                $('.box-action').show();
            }else{
                $('.box-action').hide();
            }

            var  arrShrimpNum = [];
            $('.column-title').change(function(){
                if($(this).data('old-shrimp-num')){
                    var max_value = Number($(this).data('old-shrimp-num')) + Number($('#shrimp_remaining').val());
                }else{
                    var max_value =  $('#shrimp_remaining').val();
                }

                $(".column-title").attr({
                    "max" : max_value,
                });
                
                var max = parseInt($(this).attr('max'));
                var min = parseInt($(this).attr('min'));
                var num_value = Number($(this).val()).toString();
                $(this).val(num_value);

                if ($(this).val() > max || $(this).val() == max)
                {
                    $(this).val(max);
                }else if ($(this).val() < min)
                {
                    $(this).val(min);
                }
                if( $(this).val() == 0){
                    $(this).val('');
                }
                $(".column-title").removeAttr("max");

                var sum = calc_table_total();
                $('#shrimp_remaining').val((+$('#shrimp_remaining').data('total') - sum));
            });
            
            var part = window.location.pathname.split("/").pop();
            if (part == "report_pond") {
                $( "#rdBalance" ).change(function() {
                    $('.f1 input').prop('checked', false);
                    $(this).prop('checked', true);
                }).change();
            }
            if (part == "shrimpMigration") {
                $( "#rdShrimp" ).change(function() {
                    $('.f1 input').prop('checked', false);
                    $(this).prop('checked', true);
                }).change();
            }
        });
    </script>
    <script src="{{ asset('js/front_end.js') }}?v=3"></script>
@endpush