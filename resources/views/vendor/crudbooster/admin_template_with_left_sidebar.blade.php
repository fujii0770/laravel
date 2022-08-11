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

@section('content-header')
    @if (Request::is('admin/import_logs') || Request::is('admin/import-pond-states-completed'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans('ebi.測定データ')}}
            </li>
        </ol>
    @elseif (Request::is('admin/import_bait') || Request::is('admin/import-bait-completed'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans('ebi.餌運用')}}
            </li>
        </ol>
    @elseif (Request::is('admin/import_drug') || Request::is('admin/import-drug-completed'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans('ebi.薬運用')}}
            </li>
        </ol>
    @elseif (Request::is('admin/solution/edit*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans('ebi.解決の変更')}}
            </li>
        </ol>
    @elseif (Request::is('admin/solution/add'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans('ebi.解決の登録')}}
            </li>
        </ol>
    @elseif(Request::is('admin/minmax*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.水質基準値設定")}}
            </li>
        </ol>
    @elseif(Request::is('admin/tags*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.Tag_registration")}}
            </li>
        </ol>
    @elseif(Request::is('admin/aquacultures*') && !Request::is('admin/aquacultures/start*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.養殖履歴検索")}}
            </li>
        </ol>
    @elseif(Request::is('admin/aquacultures/start*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.養殖開始設定")}}
            </li>
        </ol>
    @elseif(Request::is('admin/users*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.プロファイル")}}
            </li>
        </ol>
    @elseif(Request::is('admin/default_farm*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.養殖サイクルデフォルト")}}
            </li>
        </ol>
    @elseif(Request::is('admin/shrimp_states*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.養殖状況登録")}}
            </li>
        </ol>
    @elseif(Request::is('admin/feed_price*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.餌、薬単価設定")}}
            </li>
        </ol>
    @elseif(Request::is('admin/month_report*'))
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
            <li class="active">
                {{trans("ebi.池状況(養殖場全体)")}}
            </li>
        </ol>
    @elseif(Request::is('admin/default_ponds*') || Request::is('admin/pond*') || Request::is('admin/farm*'))
        <div class="breadcrumb f5_page_nav">
            <div class="f5_tab_top">
                <div class="f1 f1_page_nav">
                    <label class="f5_radio">{{trans('ebi.デフォルト値設定')}}
                        <input id="rdDefaultPond" type="radio" name="radio" {{Request::is('admin/default_ponds*')?'checked':'' }}>
                        <span class="checkmark"></span>
                    </label>

                    <label class="f5_radio">{{trans('ebi.池別設定')}}
                        <input id="rdPondSetting" type="radio" name="radio" {{Request::is('admin/pond*')?'checked':'' }} >
                        <span class="checkmark"></span>
                    </label>
                    <label class="f5_radio">{{trans('ebi.養殖場別設定')}}
                        <input id="rdPondFarm" type="radio" name="radio" {{Request::is('admin/farm/settingByFarm')?'checked':'' }} >
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
        </div>
    @elseif(Request::is('admin/ebi_bait_inventories') || Request::is('admin/ebi_bait_inventories/add') || Request::is('admin/ebi_bait_inventories/edit/*'))
       <div class="breadcrumb f5_page_nav">
            <div class="f5_tab_top">
                <div class="f1 f1_page_nav">
                    <label class="f5_radio">{{trans('ebi.履歴検索')}}
                        <input id="rdBaitInventory" type="radio" name="radio" {{!Request::is('admin/ebi_bait_inventories/add')?'checked':'' }}>
                        <span class="checkmark"></span>
                    </label>

                    <label class="f5_radio">{{trans('ebi.発注登録')}}
                        <input id="rdAddBaitInventory" type="radio" name="radio" {{Request::is('admin/ebi_bait_inventories/add')?'checked':'' }} >
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
        </div>
    @elseif(Request::is('admin/aquaculture_method*') || Request::is('admin/ebi_price*') || Request::is('admin/ebi_kind*'))
        <div class="breadcrumb f5_page_nav">
            <div class="f5_tab_top">
                <div class="f1 f1_page_nav">
                    <label class="f5_radio">{{trans('ebi.養殖方法登録')}}
                        <input id="rdAquaCultureMethod" type="radio" name="radio" {{Request::is('admin/aquaculture_method*')?'checked':'' }}>
                        <span class="checkmark"></span>
                    </label>
                    <label class="f5_radio">{{trans('ebi.エビ登録')}}
                        <input id="rdShrimpRegistration" type="radio" name="radio" {{Request::is('admin/ebi_kind*')?'checked':'' }} >
                        <span class="checkmark"></span>
                    </label>
                    <label class="f5_radio">{{trans('ebi.単価登録')}}
                        <input id="rdUnitPrice" type="radio" name="radio" {{Request::is('admin/ebi_price')?'checked':'' }} >
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
        </div>
    @elseif(Request::is('admin/all*'))
       <div class="breadcrumb f5_page_nav">
                <div class="f1 f1_page_nav">    
                <button onclick="location.href='./cost_add'">費用登録</button>
                </div>
        </div>
    @elseif(Request::is('admin/report_pond*'))
    <div class="breadcrumb f5_page_nav">
        <div class="f5_tab_top">
             <div class="f1 f1_page_nav">
                    <label class="f5_radio"> {{trans("ebi.養殖池状況")}}
                        <input id="rdWater" type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>
                    <label class="f5_radio"> {{trans("ebi.エビ管理")}}
                        <input id="rdShrimp" type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>
                   
                    <label class="f5_radio"> {{trans("ebi.餌状況")}}
                        <input id="rdFeeding" type="radio" name="radio" >
                        <span class="checkmark"></span>
                    </label>

                    <label class="f5_radio">{{trans("ebi.収支")}}
                        <input id="rdBalance" type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>
            </div>
        </div>
    </div>
            
    @else
        <ol class="breadcrumb">
            <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
        </ol>
    @endif
@endsection


@push('bottom')
    <script>
        $('document').ready(function() {
            var pondId = '{{Session::get('current_pond')}}';
            //console.log(pondId);
            $(".f1 input").click(function () {
                $('.f1 input').prop('checked', false);
                $(this).prop('checked', true);
                if (pondId){
                    if ($("#rdWater").is(":checked")) {
                        location.href = '{{CRUDBooster::adminPath("viewPond")}}?pondId=' + pondId;
                    }
                    if ($("#rdShrimp").is(":checked")) {
                        location.href = '{{CRUDBooster::adminPath("viewShrimpMeasure")}}?pondId=' + pondId;
                    }
                    if ($("#rdFeeding").is(":checked")) {
                        location.href = '{{CRUDBooster::adminPath("viewShrimpFeed")}}?pondId=' + pondId;
                    }
                    if ($("#rdBalance").is(":checked")) {
                        location.href = '{{CRUDBooster::adminPath("report_pond")}}?&now=1&pond_id='+ pondId;
                    }
                }

                if ($("#rdBaitInventory").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('ebi_bait_inventories')}}';
                }
                if ($("#rdAddBaitInventory").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('ebi_bait_inventories/add')}}';
                }
                if ($("#rdDefaultPond").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('default_ponds/setting')}}';
                }
                if ($("#rdPondSetting").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('pond/setting')}}';
                }                 
                if ($("#rdPondFarm").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('farm/settingByFarm')}}';
                }                
                if ($("#rdAquaCultureMethod").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('aquaculture_method')}}';
                }                
                if ($("#rdUnitPrice").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('ebi_price')}}';
                }                
                if ($("#rdShrimpRegistration").is(":checked")) {
                    location.href = '{{CRUDBooster::adminPath('ebi_kind')}}';
                }
            });
        });
    </script>
@endpush