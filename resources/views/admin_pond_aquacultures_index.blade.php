@extends('crudbooster::admin_template')
@push('head')
    <style>
        .skin-blue-light_{
            overflow: hidden;
        }
        .panel-default > .panel-heading {
            background-color: white;
            color: #9bbce0;
            border-color: white;
        }
        .panel-heading .bottom-hr {
            margin-bottom: 0px;
            border-top-width: 2px;
            border-color: #dbe6f5;
        }
        .box {
            border-top: none;
            padding: 15px;
        }
        .table > tfoot > tr.active > td, .table > tfoot > tr.active > th, .table > tfoot > tr > td.active, .table > tfoot > tr > th.active, .table > thead > tr.active > td, .table > thead > tr.active > th, .table > thead > tr > td.active, .table > thead > tr > th.active {
            background-color: #9ebbe2;
            color: white;
        }
        .table > tfoot > tr.active > td a, .table > tfoot > tr.active > th a, .table > tfoot > tr > td.active a, .table > tfoot > tr > th.active a, .table > thead > tr.active > td a, .table > thead > tr.active > th a, .table > thead > tr > td.active a, .table > thead > tr > th.active a {
            color: white;
        }
    </style>
@endpush
@section('content')
    <div class="row f5_page_nav">
        <div class="col-md-2">
            <div class="name-number text-center">
                <p  style="white-space: nowrap;  overflow: hidden; text-overflow: ellipsis;" title="{{(app()->getLocale() == 'en')?$pond_farm->farm_name_en :$pond_farm->farm_name}}">
                    @if(app()->getLocale() == 'en')
                        {{ $pond_farm->farm_name_en }}
                    @else
                        {{ $pond_farm->farm_name }}
                    @endif
                    &nbsp;
                </p>
            </div>
        </div>
        <div class="col-md-10">
           <div class="f5_tab_top">
                <div class="f1 f1_page_nav">
                    <ol class="breadcrumb" style="padding: 20.5px 0; background: transparent">
                       {{-- <li><a href="{{CRUDBooster::adminPath()}}">{{ trans('crudbooster.home') }}</a></li>
                        <li class="active">
                            {{trans("ebi.池状況(養殖場全体)")}}
                        </li>--}}
                        <li class="active">
                            &nbsp;
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-md-2 menu-sidebar-pong-water f5_page_sidebar">
            <div style="position: relative; height: calc(100vh - 130px)">
                <ul>
                    <li class="li-down" style="padding: 0px">
                
                    </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-10 content-radio" style="top: -88px">
{{--            <div class=" panel-default">--}}
{{--                <div class="panel-heading">--}}
{{--                    <strong>{{trans('ebi.池状況(養殖場全体)')}}</strong>--}}
{{--                    <hr class="bottom-hr w-100">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="box">--}}

                <div class="box-body no-padding">
                    @include('frontend/pond_revenue')
                </div>
{{--            </div>--}}

            @if(!is_null($post_index_html) && !empty($post_index_html))
                {!! $post_index_html !!}
            @endif
        </div>
    </div>

@endsection
@push('bottom')
    <script>
        $('document').ready(function() {
            $('.ftDate p').click(function () {
                var aquaId = $(this).attr('data-id');
                if (aquaId) {
                    $("#aquaId").val(aquaId);
                    $('form[name=ftDate]').submit();
                }
            });
        });
    </script>
@endpush
