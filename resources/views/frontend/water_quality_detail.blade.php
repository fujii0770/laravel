@extends('crudbooster::admin_template')
@push('head')
<style>
    .pr-10 {
        padding-right: 10px !important;
    }

    #modal-confirm-alert .control .btn:hover{ color: #fff;  }
</style>
@endpush
@section('content')
    @include('partials.page_nav')
    <div class="row">
        @include('partials/frontend_sidebar')
        <div class="col-md-10 content-radio">
            <div class="page_wrap water_wrap_completed icon_title" style="min-height: calc(100vh - 190px)">
                <div class="row">
                    <div class="detail_title">
                        <p class="title" style="padding-left: 5px"><img src="{{asset('/img/svg/icon_line.svg')}}">{{trans('ebi.水質状況')}} </p>
                    </div>
                    <div class="detail_nav">
                        <ul>
                            <li>［</li>
                            @foreach(App\Http\Controllers\Helper::getAllWaterCriteriaLabel() as $criteria => $label)
                                <li class="{{($activeCriteria==$criteria)?'active':''}}">
                                    <a href="javascript:void(0);" onclick="changeCriteria('{{$criteria}}')">{{$label}}</a>
                                </li>
                            @endforeach
                            <li>]</li>
                        </ul>
                    </div>
                    <div class="detail_change">
                        <p class="p-button-change-chart_"><img src="{{asset('./img/icon/icon_back_arrow.svg')}}"  style="width: 15px;margin-top: -7px;"><a href="{{ route('viewWaterQuality').'?pondId='.$pondId.'&aquaId='.$aquaId.'&month='.$activeYear.$activeMonth}}" class="btn btn-link change-chart-ph p-0">{{trans('ebi.一覧へ戻る')}} </a></p>
                    </div>
                </div>
                <div style="position: relative">
                    <div class="chart-ph-1 chart-detail-criteria" id="chart-detail-criteria">
                    </div>
                    <div class="hidden chart-ph-2" style="padding: 0 10px 40px 10px">
                        <table class="table-navigation table" style="margin-bottom: 0">
                            <tr>
                                <td  class="fixed-side" style="min-width: 141px !important;"></td>
                                <td class="text-left text-blue-chart" width="33%">
                                    @if ($current_aquaculture && (substr($current_aquaculture->start_date, 0, 7) <= ($previousYear.'-'.str_pad($previousMonth, 2, '0', STR_PAD_LEFT))))
                                        <a href="{{CRUDBooster::adminPath('viewQualityDetail?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$previousYear.'-'.$previousMonth.'-01&t=2&c='.$activeCriteria)}}"><<&nbsp;&nbsp;{{ $previousYear }}.{{ $previousMonth }}</a>
                                    @endif
                                </td>
                                <td class="text-center no-border"><span class="text-bold" style="font-size: 16px">{{ $activeYear }}.{{ $activeMonth }}</span></td>
                                <td class="text-right text-blue-chart" width="33%">
                                    @if ($current_aquaculture && (($current_aquaculture->completed_date?substr($current_aquaculture->completed_date, 0, 7):date_format(now(), 'Y-m')) >= ($nextYear.'-'.str_pad($nextMonth, 2, '0', STR_PAD_LEFT))))
                                        <a href="{{CRUDBooster::adminPath('viewQualityDetail?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$nextYear.'-'.$nextMonth.'-01&t=2&c='.$activeCriteria)}}">{{ $nextYear }}.{{ $nextMonth }}&nbsp;&nbsp;>></a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <div class="table-scroll">
                            @php
                                $fullDayPrefix = $activeYear.'-'.str_pad($activeMonth, 2, '0', STR_PAD_LEFT).'-';
                            @endphp
                            <div class="zui-scroll">
                                <table class="zui-table chart-detail-criteria water-detail-table chart-detail-table table table-hover table-striped table-bordered text-center" width="100%" style="overflow: auto;">
                                    <thead>
                                        <tr>
                                            <td class="fixed-side zui-sticky-col" style="min-width: 140px">{{$activeYear.'/'.$activeMonth}}</td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                <td class="highcharts-axis-labels highcharts-xaxis-labels" style="border-top: 1px solid #a7a9ac"><text>{{$i}}</text></td>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_A}}">
                                            <td class="fixed-side col-title zui-sticky-col">{{trans('ebi.地点A')}} &nbsp;&nbsp; <img src="{{asset('./img/svg/a.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_A}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_A_PLUS}}">
                                            <td class="fixed-side col-title zui-sticky-col ">{{trans('ebi.地点A+')}} <img src="{{asset('./img/svg/a+.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_A_PLUS}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_B}}">
                                            <td class="fixed-side col-title zui-sticky-col">{{trans('ebi.地点B')}}&nbsp;&nbsp; <img src="{{asset('./img/svg/b.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_B}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_B_PLUS}}">
                                            <td class="fixed-side col-title zui-sticky-col">{{trans('ebi.地点B+')}} <img src="{{asset('./img/svg/b+.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_B_PLUS}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_C}}">
                                            <td class="fixed-side col-title zui-sticky-col">{{trans('ebi.地点C')}} &nbsp;&nbsp; <img src="{{asset('./img/svg/c.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_C}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_C_PLUS}}">
                                            <td class="fixed-side col-title zui-sticky-col">{{trans('ebi.地点C+')}} <img src="{{asset('./img/svg/c+.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_C_PLUS}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_D}}">
                                            <td class="fixed-side col-title zui-sticky-col">{{trans('ebi.地点D')}} &nbsp;&nbsp; <img src="{{asset('./img/svg/d.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_D}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::WATER_POINT_D_PLUS}}">
                                            <td class="fixed-side col-title zui-sticky-col">{{trans('ebi.地点D+')}} <img src="{{asset('./img/svg/d+.svg')}}"  ></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::WATER_POINT_D_PLUS}}-data-{{$i}}">&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="position: absolute; left: 160px; right: 10px; top: 0px; margin: auto" class="chart-ph-1">
                        <table class="table" style="margin-bottom: 0">
                            <tr>
                                <td class="text-left text-blue-chart" width="33%" style="padding-top: 4px; border-top: none">
                                    @if ($current_aquaculture && (substr($current_aquaculture->start_date, 0, 7) <= ($previousYear.'-'.str_pad($previousMonth, 2, '0', STR_PAD_LEFT))))
                                        <a href="{{CRUDBooster::adminPath('viewQualityDetail?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$previousYear.'-'.$previousMonth.'-01&c='.$activeCriteria)}}"><<&nbsp;&nbsp;{{ $previousYear }}.{{ $previousMonth }}</a>
                                    @endif
                                </td>
                                <td class="text-center" style="padding-top: 4px; border-top: none"><span class="text-bold" style="font-size: 16px">{{ $activeYear }}.{{ $activeMonth }}</span></td>
                                <td class="text-right text-blue-chart" style="padding-top: 4px; border-top: none" width="33%">
                                    @if ($current_aquaculture && (($current_aquaculture->completed_date?substr($current_aquaculture->completed_date, 0, 7):date_format(now(), 'Y-m')) >= ($nextYear.'-'.str_pad($nextMonth, 2, '0', STR_PAD_LEFT))))
                                        <a href="{{CRUDBooster::adminPath('viewQualityDetail?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$nextYear.'-'.$nextMonth.'-01&c='.$activeCriteria)}}">{{ $nextYear }}.{{ $nextMonth }}&nbsp;&nbsp;>></a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <p style="position: absolute; right: 0; bottom: 10px" class="p-button-change-chart_"><button  id="switchChart" class=" btn-link change-chart-ph pr-10 button-icon"><img src="{{asset('./img/icon/icon_refresh.svg')}}"  style="width: 20px;"><span class="icon-text">{{trans('ebi.表表示')}} </span></button></p>
                </div>

                <div style="position: relative">
                    @switch($pond_farm->pond_method)
                        @case(0)
                            @include("frontend/measure_point/four_measure_point")
                            @break
                        @case(1)
                            @include("frontend/measure_point/four_measure_point")
                            @break
                        @case(2)
                            @include("frontend/measure_point/four_measure_point")
                            @break
                        @case(3)
                            @include("frontend/measure_point/four_measure_point")
                            @break
                        @default:
                            @include("frontend/measure_point/four_measure_point")
                            @break
                    @endswitch

                    <p style="position: absolute; right: 20px; top: 0px;">
                        <button class="btn btn-default btn-change-alert-confirm" data-toggle="modal" data-target="#modal-confirm-alert" style="background: #f79646; color: #fff; display: none">
                            <span class="icon-text">{{ trans('ebi.btn_alert_confirm') }}</span>
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-confirm-alert" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body" style="text-align: center;">
                    <p style="font-size: 40px; border: 1px solid #6eb0ce; width: 60px; height: 60px; text-align: center; line-height: 60px; border-radius: 50%; color: #6eb0ce; margin: 0 auto;">
                        <i class="fa fa-info"></i>
                    </p>
                    <p style="margin: 15px 0; font-size: 16px; font-weight: bold;">{{trans('ebi.データを更新します。')}} <br/> {{trans('ebi.よろしいですか？')}}	</p>
                    <div class="control" style="color: #fff;">
                        <div class="btn btn-ok" style="background: #9bbb59; margin-right: 10px;">{{ trans('crudbooster.confirmation_yes') }}</div>
                        <div class="btn btn-cancel" style="background: #a6a6a6; margin-left: 10px;" data-dismiss="modal">{{ trans('crudbooster.confirmation_no') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
    <script src="{{ asset('js/highstock.js') }}?v=1"></script>
    <script src="{{ asset('js/highcharts-more.js') }}?v=1"></script>

    <script>
        var yRangeMin = null;
        var yRangeMax = null;
        var chart = null;
        var yMin = null;
        var yMax = null;
        var yInterval = null;
        var decimalPlace = {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria($activeCriteria)}};
        var yFormat = '{value:,.' + decimalPlace + 'f}';
        var showYMinLabel = true;
        var showYMaxLabel = true;

        @switch($activeCriteria)
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_PH)
                yRangeMin = 0;
                yRangeMax = 14;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_MV)
                yRangeMin = -600;
                yRangeMax = 600;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_ORP)
                yRangeMin = -2000;
                yRangeMax = 2000;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_EC)
                yRangeMin = 0;
                yRangeMax = 400000;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_EC_ABS)
                yRangeMin = 0;
                yRangeMax = 400000;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_TDS)
                yRangeMin = 0;
                yRangeMax = 40000;
                @break;
            @case(\App\Http\Controllers\Helper::WATER_CRITERIA_RES)
                yRangeMin = 0;
                yRangeMax = 999999;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_SAL)
                yRangeMin = 0;
                yRangeMax = 70;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_SIGMA)
                yRangeMin = 0;
                yRangeMax = 50;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_DO)
                yRangeMin = 0;
                yRangeMax = 300;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_DO_PPM)
                yRangeMin = 0;
                yRangeMax = 30;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_TURB)
                yRangeMin = 0;
                yRangeMax = 99.9;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_TMP)
                yRangeMin = -5;
                yRangeMax = 55;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_PRESS)
                yRangeMin = 8.702;
                yRangeMax = 16.436;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_AMMONIA)
                yRangeMin = 8.702;
                yRangeMax = 16.436;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_ION)
                yRangeMin = 8.702;
                yRangeMax = 16.436;
                @break;
            @case (\App\Http\Controllers\Helper::WATER_CRITERIA_OUT_TEMP)
                yRangeMin = -5;
                yRangeMax = 55;
                @break;
        @endswitch
        drawChartLines = function () {

            var d = null, arr = null, bottomPoint = null, rightPoint = null;
            // draw top of chart
            if( $('.highcharts-axis-line:not(.lineTopChart)').length){
                d = $('.highcharts-axis-line:not(.lineTopChart)').attr('d');
                arr = d.split(' ');
                arr[1] = 160;
                arr[2] = 26.5;
                arr[5] = 26.5;
                rightPoint = parseFloat(arr[4]) + 0.5;

                $('.lineTopChart').attr('d',arr.join(' '));
                $('.lineTopChart').attr('visibility', 'visible');
            }

            // draw left of chart
            if( $('.lineLeftChart').length) {
                d = $('.lineLeftChart').attr('d');
                if (d){
                    arr = d.split(' ');
                    arr[1] = 9.5;
                    arr[2] = 0;
                    arr[4] = 9.5;
                    bottomPoint = arr[5];
                    $('.lineLeftChart').attr('d', arr.join(' '));
                }else if ($('.highcharts-axis.highcharts-yaxis .highcharts-tick').length){
                    $( ".highcharts-axis.highcharts-yaxis .highcharts-tick" ).each(function( index ) {
                        var d1 = $(this).attr('d');
                        var arr1 = d1.split(' ');
                        if (!bottomPoint || parseFloat(bottomPoint) < parseFloat(arr1[5])){
                            bottomPoint = arr1[5];
                        }
                    });
                    if (bottomPoint){
                        $('.lineLeftChart').attr('d', 'M 9.5 0 L 9.5 ' + bottomPoint);
                        $('.lineLeftChart').attr('visibility', 'visible');
                    }
                }

                // draw bottom of chart
                if( $('.lineBottomChart').length && bottomPoint && rightPoint){
                    $('.lineBottomChart').attr('d','M 9.5 ' + bottomPoint + ' L ' + rightPoint + ' ' + bottomPoint);
                    $('.lineBottomChart').attr('visibility', 'visible');
                }
            }

            // draw left of navigation
            if( $('.highcharts-xaxis .highcharts-tick').length) {
                d = $('.highcharts-xaxis .highcharts-tick').attr('d');
                arr = d.split(' ');
                arr[1] = 159.5;
                arr[2] = 0;
                arr[4] = 159.5;
                $('.lineRightYearMonth').attr('d', arr.join(' '));
                $('.lineRightYearMonth').attr('visibility', 'visible');
            }

            // move minmax line to right
            if( $('.highcharts-plot-band:first').length) {
                d = $('.highcharts-plot-band:first').attr('d');
                arr = d.split(' ');
                arr[4] = 185;
                arr[6] = 185;
                arr[1] = 9.5;
                arr[8] = 9.5;
                $('.highcharts-plot-band:first').attr('d', arr.join(' '));
            }

            // draw last minmax to full width
            if( $('.highcharts-plot-band:last').length) {
                d = $('.highcharts-plot-band:last').attr('d');
                arr = d.split(' ');
                if (arr[4] > 1000) {
                    arr[1] = arr[4] - 30;
                    arr[8] = arr[4] - 30;

                    $('.highcharts-plot-band:last').attr('d', arr.join(' '));
                }
                $('.highcharts-plot-band-label ').attr('x', '20');
            }

            // draw bottom of year month
           /* if( $('.highcharts-xaxis .highcharts-axis-line').length) {
                d = $('.highcharts-xaxis .highcharts-axis-line').attr('d');
                arr = d.split(' ');
                arr[1] = 9.5;
                $('.highcharts-xaxis .highcharts-axis-line').attr('d', arr.join(' '));
            }*/

            // draw naviation top
            if( $('.lineNavigationTopChart').length){
                $('.lineNavigationTopChart').attr('d', 'M 9.5 0 L ' + rightPoint + ' 0');
                $('.lineNavigationTopChart').attr('visibility', 'visible');
            }

            // draw naviation right
            if( $('.lineRightNavigation').length){
                $('.lineRightNavigation').attr('d', 'M ' + rightPoint + ' 0 L ' + rightPoint + ' 26.5');
                $('.lineRightNavigation').attr('visibility', 'visible');
            }
            var y = parseInt($('.highcharts-yaxis-labels text:first-child').attr('y'));
            $('.highcharts-yaxis-labels text:first-child').attr('y', y - 5);

            if( $('.highcharts-plot-band-label').length){
                y = parseInt($('text.highcharts-plot-band-label').attr('y'));
                if (bottomPoint && (y > bottomPoint)){
                    $('text.highcharts-plot-band-label').attr('y', bottomPoint - 5);
                }

            }
            // hide yMin, yMax
            if (!showYMinLabel ){
                $('.highcharts-axis-labels.highcharts-yaxis-labels text').each(function( index ) {
                    if ($( this ).text() == yMin){
                        $(this).attr("visibility", "hidden");
                    }
                });
            }
            if (!showYMaxLabel ){
                $('.highcharts-axis-labels.highcharts-yaxis-labels text').each(function( index ) {
                    if ($( this ).text() == yMax){
                        $(this).attr("visibility", "hidden");
                    }
                });
            }
        },
        drawChart = function (datas) {
            var supportedMeasurePoints = [];

            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_A}}' ] = {dashStyle: 'solid'};
            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_A_PLUS}}' ] = {dashStyle: 'solid'};
            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_B}}' ] = {dashStyle: 'shortdot'};
            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_B_PLUS}}' ] = {dashStyle: 'shortdot'};
            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_C}}' ] = {dashStyle: 'dash'};
            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_C_PLUS}}' ] = {dashStyle: 'dash'};
            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_D}}' ] = {dashStyle: 'dashdot'};
            supportedMeasurePoints[ '{{\App\Http\Controllers\Helper::WATER_POINT_D_PLUS}}' ] = {dashStyle: 'dashdot'};

            var hasDifferentMinmax = false;
            if (datas.minmax){
                for(var i = 0; i < datas.minmax.length; i++){
                    if (datas.minmax[i].length > 2){
                        if ((yMin != null && yMin != datas.minmax[i][1]) || (yMax != null && yMax != datas.minmax[i][2])){
                            hasDifferentMinmax = true;
                        }
                        if (yMin == null ||  yMin > datas.minmax[i][1]){
                            yMin = datas.minmax[i][1];
                        }
                        if (yMax== null || yMax < datas.minmax[i][2]){
                            yMax = datas.minmax[i][2];
                        }
                    }
                }
            }else{
                yMin = yRangeMin;
                yMax = yRangeMax;
            }
            yInterval = (Math.round((yMax - yMin)*100/3))/100;
            yMin = yMin - yInterval;
            yMax = yMax + yInterval;

            var chartSeries = [];
            chartSeries.push({
                name: '基準値',
                data: datas.minmax,
                type: 'arearange',
                lineWidth: 0,
                linkedTo: ':previous',
                color: '#F4F0DF',
                fillOpacity: 1,
                zIndex: 1,
                marker: {
                    enabled: false
                },
                states: { hover: { enabled: false } },
            });

            for(key in chartData.points){
                chartSeries.push({
                    name: '{{trans("ebi.地点")}}' + key,
                    data: datas.points[key],
                    zIndex: 1,
                    color: '#A4B3D2',
                    dashStyle: supportedMeasurePoints[key].dashStyle,
                    marker: {
                        fillColor: '#A4B3D2',
                        lineWidth: 1,
                        lineColor: '#A4B3D2',
                        symbol: 'round'
                    },
                    states: { hover: { enabled: false } },
                });
                for(var i = 0; i < datas.points[key].length; i++){
                    if (datas.points[key][i].length > 1 && datas.points[key][i][1] != null){
                        while (yMin > datas.points[key][i][1]){
                            if (yInterval == 0) {
                                yMin = datas.points[key][i][1];
                            }else {
                                yMin = yMin - yInterval*0.5;
                            }
                        }

                        while (yMax < datas.points[key][i][1]){
                            if (yInterval == 0) {
                                yMax = datas.points[key][i][1];
                            }else {
                                yMax = yMax + yInterval*0.5;
                            }
                        }
                    }
                }
                if (yInterval == 0) {
                    yInterval = (Math.round((yMax - yMin)*100/3))/100;
                    yMin = yMin - yInterval;
                    yMax = yMax + yInterval;
                }
            }
            if (yMin < yRangeMin){
                yMin = yRangeMin;
            }
            if (yMax > yRangeMax){
                yMax = yRangeMax;
            }
            yInterval = (yMax - yMin)*0.2;
            var positions = [yMin];
            if (yInterval != 0){
                var addedMin = (datas.minmaxLast.min <= yMin || datas.minmaxLast.min >= yMax);
                var addedMax = (datas.minmaxLast.max <= yMin || datas.minmaxLast.max >= yMax);
                var newTick = null, nextTick = null;
                for(var i = 0; i< 4; i++){
                    newTick = yMin + (i+1)*yInterval;
                    nextTick = yMin + (i+2)*yInterval;
                    if (!addedMin && newTick == datas.minmaxLast.min){
                        positions.push(newTick);
                        addedMin = true;
                    }else if (!addedMin && (newTick < datas.minmaxLast.min) && (nextTick > datas.minmaxLast.min) && Math.abs(newTick - datas.minmaxLast.min) <= Math.abs(nextTick - datas.minmaxLast.min)){
                        positions.push(datas.minmaxLast.min);
                        addedMin = true;
                    }else if (!addedMin && (newTick > datas.minmaxLast.min)){
                        positions.push(datas.minmaxLast.min);
                        addedMin = true;
                    }else if (!addedMax && newTick == datas.minmaxLast.max){
                        positions.push(newTick);
                        addedMax = true;
                    }else if (!addedMax && (newTick < datas.minmaxLast.max) && (nextTick > datas.minmaxLast.max) && Math.abs(newTick - datas.minmaxLast.max) <= Math.abs(nextTick - datas.minmaxLast.max)){
                        positions.push(datas.minmaxLast.max);
                        addedMax = true;
                    }else if (!addedMax && (newTick > datas.minmaxLast.max)){
                        positions.push(datas.minmaxLast.max);
                        addedMax = true;
                    }else{
                        positions.push(newTick);
                    }
                }
                if (!addedMax && !addedMin){
                    positions[4] = datas.minmaxLast.max;
                    positions[3] = datas.minmaxLast.min;
                }else if (!addedMax){
                    positions[4] = datas.minmaxLast.max;
                }else if (!addedMin){
                    positions[3] = datas.minmaxLast.min;
                }
                positions.push(yMax);
            }
            // check if min and max tick is overlapped with yMin or yMax
            if (yInterval != 0){
                if ((yMin != datas.minmaxLast.min && Math.abs(yMin - datas.minmaxLast.min)/ yInterval < 0.2) || (yMin != datas.minmaxLast.max && Math.abs(yMin - datas.minmaxLast.max)/ yInterval < 0.2)){
                    showYMinLabel = false;
                }
                if ((yMax != datas.minmaxLast.min && Math.abs(yMax - datas.minmaxLast.min)/ yInterval < 0.2) || (yMax != datas.minmaxLast.max && Math.abs(yMax - datas.minmaxLast.max)/ yInterval < 0.2)){
                    showYMaxLabel = false;
                }
            }
            var yPlotLines = [{
                    color: '#ccd6eb', // Color value
                    value: 10, // Value of where the line will appear
                    width: 1, // Width of the line
                    zIndex: 3,
                    className: "lineTopChart"
                },{
                    color: '#ccd6eb', // Color value
                    value: 10, // Value of where the line will appear
                    width: 1, // Width of the line
                    zIndex: 3,
                    className: "lineBottomChart"
                },{
                    color: '#ccd6eb', // Color value
                    value: 10, // Value of where the line will appear
                    width: 1, // Width of the line
                    zIndex: 3,
                    className: "lineNavigationTopChart"
                }]
            ;
            if (hasDifferentMinmax){
                yPlotLines.push({
                    color: 'red',
                    width: 1,
                    value: datas.minmaxLast.max,
                    zIndex: 5,
                    dashStyle: 'dash',
                    className: "lineLatestMax"
                });
                yPlotLines.push({
                    color: 'red',
                    width: 1,
                    value: datas.minmaxLast.min,
                    zIndex: 5,
                    dashStyle: 'dash',
                    className: "lineLatestMin"
                });

            }

            Highcharts.setOptions({
                lang: {
                    thousandsSep: ','
                }
            });
            chart = Highcharts.chart('chart-detail-criteria', {
                chart: {
                    height: "29%",
                    plotBorderWidth: 1,
                    events: {
                        load: drawChartLines,
                        redraw: drawChartLines,
                        render: drawChartLines,
                    }
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: '',
                },
                xAxis: {
                    categories: datas.days,
                    labels: {
                        style: {"color": "#231F20", "fontSize": "14px", "fontFamily": '"M PLUS 1p"', "border": "1px solid #000"},
                        y: -10
                    },
                    tickLength: 30,
                    gridLineWidth: 1,
                    opposite: true,
                    plotLines: [{
                        color: '#ccd6eb', // Color value
                        value: 10, // Value of where the line will appear
                        width: 1, // Width of the line
                        zIndex: 3,
                        className: "lineLeftChart"
                    },{
                        color: '#ccd6eb', // Color value
                        value: 10, // Value of where the line will appear
                        width: 1, // Width of the line
                        zIndex: 3,
                        className: "lineRightYearMonth"
                    },{
                        color: '#ccd6eb', // Color value
                        value: 10, // Value of where the line will appear
                        width: 1, // Width of the line
                        zIndex: 3,
                        className: "lineRightNavigation"
                    }],
                },

                yAxis: {
                    title: {
                        text: null
                    },
                    tickPositions: positions,
                    labels: {
                        style: {"color": "#231F20", "fontSize": "14px", "fontFamily": '"M PLUS 1p"'},
                        y: 5,
                        format: yFormat
                    },
                    tickLength: 150,
                    tickWidth: 1,
                    tickColor: 'rgba(255, 255, 255, 0)',
                    gridLineWidth: 0,
                    plotBands: [{ // Light air
                            from: datas.minmaxFirst.min,
                            to: datas.minmaxFirst.max,
                            id: 'minmaxFirst',
                            color: '#F4F0DF',
                            label: {
                                text: '{{trans("ebi.基準値")}}',
                                style: {
                                    color: '#333333'
                                }
                            },
                            zIndex: 0,

                        },{ // Light air
                            from: datas.minmaxLast.min,
                            to: datas.minmaxLast.max,
                            color: '#F4F0DF',
                            zIndex: 0,
                        }],
                    plotLines: yPlotLines
                },

                plotOptions: {
                    series: {
                        events: {
                            legendItemClick: function() {
                            return false;
                            }
                        }
                    }
                },

                tooltip: {
                    /*headerFormat: '<span style="font-size: 16px;font-weight: bold">日: {point.key}</span><br/>',
                    crosshairs: true,
                    shared: true,
                    valueSuffix: ' '+ datas.unit,
                    style: {"color": "#231F20", "fontSize": "14px", "fontFamily": '"M PLUS 1p"'},*/
                    enabled: false,
                },

                legend: {
                    align: 'left',
                    rtl: true,
                    symbolWidth: 30,
                    itemStyle: {
                        color: "#818285",
                        fontWeight: "Regular",
                        fontSize:'10px',
                        font: '"M PLUS 1p',
                    },
                },

                series: chartSeries
            });
        }
        function drawActiveDate(){
            if (currentDate !== null && chartData){
                // get minmax
                var dataIndex = currentDate-1;
                if (dataIndex < 0){
                    dataIndex = 0;
                }
                if (chartData.minmax && currentDate < chartData.minmax.length){
                    var minmax = chartData.minmax[dataIndex];
                    var min = null, max = null;
                    if (minmax.length > 1 && minmax[1] != null){
                        min = minmax[1];
                    }
                    if (minmax.length > 1 && minmax[2] != null){
                        max = minmax[2];
                    }
                }

                var supportedMeasurePoints = ['{{\App\Http\Controllers\Helper::WATER_POINT_A}}','{{\App\Http\Controllers\Helper::WATER_POINT_A_PLUS}}'
                    ,'{{\App\Http\Controllers\Helper::WATER_POINT_B}}','{{\App\Http\Controllers\Helper::WATER_POINT_B_PLUS}}'
                    ,'{{\App\Http\Controllers\Helper::WATER_POINT_C}}','{{\App\Http\Controllers\Helper::WATER_POINT_C_PLUS}}'
                    ,'{{\App\Http\Controllers\Helper::WATER_POINT_D}}','{{\App\Http\Controllers\Helper::WATER_POINT_D_PLUS}}'];
                for(key in chartData.points){
                    drawActivePoint(dataIndex, chartData.points[key], ('current_' + key.replace("+", "\\+")), min, max, 'point_' + key.replace("+", "_plus").toLowerCase() + '_part1', 'point_' + key.replace("+", "_plus").toLowerCase() + '_part2');
                    const index = supportedMeasurePoints.indexOf(key);

                    if (index !== -1) {
                        supportedMeasurePoints.splice(index, 1);
                    }
                }
                for(var i=0; i<supportedMeasurePoints.length; i++){
                    $('#block_current_' + supportedMeasurePoints[i].replace("+", "\\+")).remove();
                }

                var activeMonth = '{{$activeMonth}}';
                activeMonth = activeMonth.padStart(2, '0');
                $('#activeDate').html("[{{$activeYear}}/" + activeMonth + "/" + currentDate.toString().padStart(2, '0') + "]");
                $('[data-toggle="tooltip"]').tooltip();

                // show button confirm alert
                if(chartData.dayAlerts[dataIndex] == 1){
                    $(".btn-change-alert-confirm").show();
                }else {
                    $(".btn-change-alert-confirm").hide();
                }
            }
        }

        function drawActivePoint(dataIndex, pointDatas, pointId, min, max, pointMeasurePart1Id, pointMeasurePart2Id){
            var strValue = null;
            var value = null;
            for(var i = 0; i < pointDatas.length; i++){
                if (pointDatas[i][0] == dataIndex){
                    var pointData = pointDatas[i];
                    if (pointData.length > 1 && (pointData[1] != null)){
                        strValue = pointData[1];
                        value = strValue.replace(',','');
                        value = parseFloat(value);
                    }else{
                        value = null;
                    }
                    break;
                }
            }
            var splitted_values = splitDecimalStr(strValue, decimalPlace);
            var newContent = splitted_values[0] + "<small class='small-2'>" + (splitted_values[1]?("."+splitted_values[1]):"") + "</small>";
            $('#'+pointId).html(newContent);
            $('#'+pointId).attr('title', strValue);
            $('#'+pointId).attr('data-original-title', strValue);
            var isNormalValue = true;
            if (min!= null && max!= null && value!= null){
                isNormalValue = (value <= max) && (value >= min);
            }else if (min!= null && value!= null){
                isNormalValue = (value >= min);
            }else if (max!= null && value!= null){
                isNormalValue = (value <= max) ;
            }
            if (isNormalValue){
                $('#'+pointId).removeClass('text-quality-abnormal');
                $('#'+pointId).addClass('text-quality-normal');
            }else{
                $('#'+pointId).removeClass('text-quality-normal');
                $('#'+pointId).addClass('text-quality-abnormal');
            }
            $('#'+pointMeasurePart1Id).attr('class',isNormalValue?'criteria-normal':'criteria-abnormal');
            $('#'+pointMeasurePart2Id).attr('class',isNormalValue?'criteria-normal':'criteria-abnormal');
        }

        function fillChartTable(){
            if (chartData) {
                var supportedMeasurePoints = ['{{\App\Http\Controllers\Helper::WATER_POINT_A}}','{{\App\Http\Controllers\Helper::WATER_POINT_A_PLUS}}'
                                            ,'{{\App\Http\Controllers\Helper::WATER_POINT_B}}','{{\App\Http\Controllers\Helper::WATER_POINT_B_PLUS}}'
                                            ,'{{\App\Http\Controllers\Helper::WATER_POINT_C}}','{{\App\Http\Controllers\Helper::WATER_POINT_C_PLUS}}'
                                            ,'{{\App\Http\Controllers\Helper::WATER_POINT_D}}','{{\App\Http\Controllers\Helper::WATER_POINT_D_PLUS}}'];
                for(key in chartData.points){
                    fillChartRow("cell-"+ key.replace("+", "\\+") + "-data-", chartData.points[key]);

                    const index = supportedMeasurePoints.indexOf(key);

                    if (index !== -1) {
                        supportedMeasurePoints.splice(index, 1);
                    }
                }
                for(var i=0; i<supportedMeasurePoints.length; i++){
                    $('#row-' + supportedMeasurePoints[i].replace("+", "\\+")).remove();
                }
            }
        }

        function fillChartRow(rowPrefixId, pointDatas){
            var dataIndex = 0;
            for(var i = 0; i< {{$dayOfMonth}}; i++){
                while(dataIndex < pointDatas.length && pointDatas[dataIndex][0] <= i) {
                    if (pointDatas[dataIndex][0] == i){
                        var value = "";
                        if (pointDatas[dataIndex].length > 1 && parseFloat(pointDatas[dataIndex][1])){
                            value = pointDatas[dataIndex][1];
                        }
                        $('#'+rowPrefixId+(i+1)).text((value!=null && value!="")?value:'-');
                    }
                    dataIndex++;
                }
            }
        }

        function changeCriteria(criteria){
            location.href = "{!!  route('viewWaterQualityDetail').'?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$activeYear.'-'.$activeMonth.'-'!!}" + currentDate  + "&c=" + criteria;
        }

        var currentDate = {{(int)$activeDate}};
        var chartData = null;
        $(document).ready(function(){
            $.ajax({
                url: site_url + "/admin/water-criteria/values.json?pondId={{$pondId}}&aquaId={{$aquaId}}&month={{$activeMonth}}&year={{$activeYear}}&c={{$activeCriteria}}",
                dataType: 'json',
                success: function(datas){

                    var dataFLoats = jQuery.extend(true,{}, datas);
                    for(var point in dataFLoats.points){
                        dataFLoats.points[point].forEach(function(item, index){
                            var strValue = dataFLoats.points[point][index][1];
                            if (strValue != null) {
                                strValue = strValue.replace(',', '');
                            }
                            dataFLoats.points[point][index][1] = parseFloat(strValue);
                        });
                    }
                    chartData = datas;
                    drawChart(dataFLoats);
                    fillChartTable();
                    var startDate = '{{$current_aquaculture->start_date}}';
                    var endDate = '{{$current_aquaculture->completed_date?$current_aquaculture->completed_date:date_format(now(),'Y-m-d')}}';
                    var activeMonth = '{{$activeMonth}}';
                    activeMonth = activeMonth.padStart(2, '0');
                    drawActiveDate();
                    $('.highcharts-axis-labels.highcharts-xaxis-labels text').each(function( index ) {
                         if ($( this ).text() == currentDate){
                             $(this).attr("class", "active");
                         }
                         var date = $( this ).text();
                         date = '{{$activeYear}}-' + activeMonth + '-' + date.padStart(2, '0');
                         if (date < startDate || date > endDate){
                             $(this).attr("class", "active disabled");
                         }
                    });

                    $('.highcharts-axis-labels.highcharts-xaxis-labels text:not(.disabled)').click(function(){
                        $('.highcharts-axis-labels.highcharts-xaxis-labels text.active:not(.disabled)').attr("class", "");
                        $(this).attr("class", "active");
                        currentDate = $(this).text();
                        drawActiveDate();
                    });
                }
            });

            $('#switchChart').click(function(){
                if($('.chart-ph-2').hasClass('hidden')){
                    $('.chart-ph-1').addClass('hidden');
                    $('.chart-ph-2').removeClass('hidden');
                    $(this).find('span').text("{{trans('ebi.グラフ表示')}}");
                }else{
                    $('.chart-ph-2').addClass('hidden');
                    $('.chart-ph-1').removeClass('hidden');
                    $(this).find('span').text("{{trans('ebi.表表示')}}");
                }
            });
            var part = window.location.pathname.split("/").pop();
            //console.log(part);
            if (part == "viewQualityDetail") {
                $( "#rdWater" ).change(function() {
                    $('.f1 input').prop('checked', false);
                    $(this).prop('checked', true);
                }).change();
            }
            var params = (new URL(document.location)).searchParams;
            var tab = params.get("t");
            if (tab == '2'){
                $('#switchChart').trigger('click');
            }

            $("#modal-confirm-alert .control .btn-ok").click(function(){
                $("#modal-confirm-alert").modal('hide');
                $.ajax({
                    url: "{{ route('ajaxConfirmAlert') }}",
                    data: {pondId: {{$pondId}}, date:currentDate, month: {{ (int)$activeMonth}},
                        year: {{$activeYear}}, c: '{{$activeCriteria}}' },
                    type: "post", dataType: 'json',
                    success: function(data){
                        if(data == 1){
                            $(".btn-change-alert-confirm").hide();
                            chartData.dayAlerts[currentDate - 1] = 0;
                        }
                    }
                })
            });
        })
    </script>
    <script src="{{ asset('js/front_end.js') }}?v=3"></script>
@endpush
