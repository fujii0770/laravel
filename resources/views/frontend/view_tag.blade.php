@extends('crudbooster::admin_template_with_left_sidebar')

@section('content')
    @include('crudbooster::sidebar')
    
    
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="panel panel-default">
            <div class='panel-heading'>
                <strong>@php echo trans('ebi.tag_registration_title'); @endphp</strong>
                <hr class="bottom-hr w-100">
            </div>
            <div class='panel-body' style="padding:20px 0px 0px 10px;">
                <form method="post" action="{{ route('postSaveTags') }}" class="form-horizontal">
                    <div class="tag-manager box-body">
                        <div class='row display-flex'>
                            @foreach($items_tag as $key => $items)
                            <div class='col-md-6 tag-box'>   
                               
                                <div class='form-group'>
                                    <label class='control-label col-sm-4'>@php echo trans('ebi.Measurement_point') @endphp {{ $key }}</label>

                                    <div class='col-sm-8'>
                                        <div class="{{ $errors->has('measure_point_'.$key) ? ' has-error' : '' }}">
                                            <select class="form-control gen-select2" multiple="multiple" name="measure_point_{{ $key }}[]">
                                                @if(count($items))
                                                    @foreach($items as $item)
                                                    <option selected="">{{ $item->tag_id }}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @if ($errors->has('measure_point_'.$key))
                                            <div class="text-danger">
                                                <span class="help-block">
                                                    <i class="fa fa-info-circle"></i>
                                                    <strong>{{ $errors->first('measure_point_'.$key) }}</strong>
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                            @endforeach  
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <div class='box-footer'>
                        <div class='form-group'>
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-10">
                                <?php $currentPondId = Session::get('current_pond'); ?>
                                <a href="{{ \crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('viewPond').'?pondId='.$currentPondId }}" class="btn btn-default"><i class="fa fa-chevron-circle-left"></i>{{trans("ebi.戻る")}}</a>
                                <input type="submit" name="submit" value="@php echo trans('crudbooster.button_save') @endphp" class="btn btn-success">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
@endsection

@push('head')
    <link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
    <style type="text/css">
        .select2-container--default .select2-selection--single {
            border-radius: 0px !important
        }

        .select2-container .select2-selection--single {
            height: 35px
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3c8dbc !important;
            border-color: #367fa9 !important;
            color: #fff !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }
        .tag-manager .select2-no-results { display: none !important; }
        .tag-manager .tag-box:nth-child(2n+1) { clear: left; }
    </style>
@endpush

@push('bottom')
    <script src="{{ asset('js/front_end.js') }}?v=3"></script>
    <script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
    <script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/i18n/ja.js")?>'></script>
    
    <script>
        $('document').ready(function(){
            setTimeout(function(){
                @if(app()->getLocale() == 'en')
                    var lang = 'en';
                @else
                    var lang = 'ja';
                @endif
                $('.gen-select2').select2({ language: lang, tags: true, tokenSeparators: [",", " "]});
            }, 500)
        });
    </script>
@endpush