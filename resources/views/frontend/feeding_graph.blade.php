@extends('crudbooster::admin_template')

@push('head')
<style>
    #showData {
        padding: 0 20px 0 25px;
    }
    .feed-graph-detail-item > span:not( :first-child) {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
@section('content')
    @include('partials/page_nav')
    <div class="row">
        @include('partials/frontend_sidebar')

        <div class="col-md-10 content-radio">
            <div class="pond_wrap icon_title">
                <div class="content_center_block">
                    <div class="content-chart">
                        <div class="title" style="padding-left: 10px">
                            <div class="col-md-6 no-padding text-left"><p><span class="icon-text">{{trans('ebi.餌状況')}}</span></p></div>
                            <div class="col-md-6 no-padding p-button-change-chart_ text-right"><a href="#" id="switchChart" class="btn btn-link change-chart-ph p-0 button-icon" style="padding-right: 10px;"><img src="{{asset('./img/icon/icon_refresh.svg')}}" style="right:60px; left:auto; width: 20px;"><span class="icon-text">{{trans('ebi.表表示')}}</span></a></div>
                        </div>
                        <div id="chart-line" class="chart-ph-1" style="margin-bottom: 15px;border: 1px solid #7c8490;"></div>
                        <div class="hidden chart-ph-2" style="padding: 0 10px 40px 10px">
                            <table class="table-navigation table" style="margin-bottom: 0">
                                <tr>
                                    <td  class="fixed-side" style="min-width: 141px !important;"></td>
                                    <td class="text-left text-blue-chart" width="33%">
                                        @if ($current_aquaculture && (substr($current_aquaculture->start_date, 0, 7) <= ($previousYear.'-'.str_pad($previousMonth, 2, '0', STR_PAD_LEFT))))
                                            <a href="{{CRUDBooster::adminPath('viewShrimpFeed?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$previousYear.'-'.$previousMonth.'-01&t=2&c='.$activeCriteria)}}"><<&nbsp;&nbsp;{{ $previousYear }}.{{ $previousMonth }}</a>
                                        @endif
                                    </td>
                                    <td class="text-center no-border"><span class="text-bold" style="font-size: 16px">{{ $activeYear }}.{{ $activeMonth }}</span></td>
                                    <td class="text-right text-blue-chart" width="33%">
                                        @if ($current_aquaculture && (($current_aquaculture->completed_date?substr($current_aquaculture->completed_date, 0, 7):date_format(now(), 'Y-m')) >= ($nextYear.'-'.str_pad($nextMonth, 2, '0', STR_PAD_LEFT))))
                                            <a href="{{CRUDBooster::adminPath('viewShrimpFeed?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$nextYear.'-'.$nextMonth.'-01&t=2&c='.$activeCriteria)}}">{{ $nextYear }}.{{ $nextMonth }}&nbsp;&nbsp;>></a>
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
                                        <tr id="row-{{\App\Http\Controllers\Helper::FEED_IDEAL_AMOUNT}}">
                                            <td class="fixed-side zui-sticky-col">{{trans('ebi.理想餌量')}}</td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && (!$current_aquaculture->completed_date || $fullDate <= $current_aquaculture->completed_date))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::FEED_IDEAL_AMOUNT}}-data-{{$i}}">{{isset($idealWeightByDate[$fullDate])?$idealWeightByDate[$fullDate]:''}}&nbsp;</td>
                                                @else
                                                    <td style="background-color: lightgrey"></td>
                                                @endif
                                            @endfor
                                        </tr>
                                        <tr id="row-{{\App\Http\Controllers\Helper::FEED_ACTUAL_AMOUNT}}">
                                            <td class="fixed-side zui-sticky-col ">{{trans('ebi.実績餌量')}}</td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $fullDayPrefix.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && (!$current_aquaculture->completed_date || $fullDate <= $current_aquaculture->completed_date))
                                                    <td id="cell-{{\App\Http\Controllers\Helper::FEED_ACTUAL_AMOUNT}}-data-{{$i}}">{{isset($amountByDate[$fullDate])?$amountByDate[$fullDate]:''}}&nbsp;</td>
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
                        <div class="title" style="padding-left: 10px">
                            <p><img src="{{ asset('img/svg/icon_search.svg') }}"><span class="icon-text">{{trans('ebi.計測項目')}}</span></p>
                            <form action="">
                            {{trans('ebi.日付')}} <input id="datePicker" type="date" value="" onchange="showFeedGraph(event);" min="{{$current_aquaculture?$current_aquaculture->start_date:date_format(now(),'Y-m-d')}}" max="{{$current_aquaculture&&$current_aquaculture->completed_date?$current_aquaculture->completed_date:date_format(now(),'Y-m-d')}}">
                            </form>
                        </div>
                        <style>
                            .icon_title .title {
                                display: inline-flex;
                                width: 100%;
                            }
                            .icon_title .title p {
                                padding-top: 3px;
                            }
                            .icon_title .title form {
                                margin-left: 10%;
                            }
                        </style>
                        <div class="row">
                            <div class="col-md-12  chart-detail" id="showData"></div>
                        </div>
                    </div>
                </div>
                <div style="float:left; margin-left: 30px; width: 197px">
                    @include('partials/shrimp_state_sidebar')
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
        var chart = null;
        var orgX = null;
        const idealWeight = {{ json_encode($idealWeight) }};
        const actualAmount = {{ json_encode($actualAmount) }};
        const training_period = {{$training_period}};

        drawChart = function () {
            chart = Highcharts.chart('chart-line', {
                credits: { enabled: false },
                chart: {
                    type: 'line',
                },
                title: {
                    enabled: false,
                    text: ''
                },
                subtitle: {
                    enabled: false,
                    text: ''
                },
                xAxis: {
                    allowDecimals: false,
                    floor: 0,
                    ceiling: training_period,
                    tickWidth: 0,
                    tickLength: 0
                },
                yAxis: {
                    title: {
                        enabled: false,
                        text: ''
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: false
                        },
                    }
                },
                series: [
                    {
                        name: '{{App\Http\Controllers\Helper::getFeedGraphLabel(App\Http\Controllers\Helper::FEED_IDEAL_AMOUNT)}}',
                        data: idealWeight,
                        color: '#6C9BD2',
                        lineWidth: 2,
                       /* marker: {
                            enabled: false
                        },*/
                    },
                    {
                        name: '{{App\Http\Controllers\Helper::getFeedGraphLabel(App\Http\Controllers\Helper::FEED_ACTUAL_AMOUNT)}}',
                        data: actualAmount,
                        color: '#C61E57',
                        lineWidth: 2,
                        /*marker: {
                            enabled: false
                        },*/
                    }
                ]
            });
        }

        $('document').ready(function(){
            var part = window.location.pathname.split("/").pop();
            //console.log(part);
            if (part == "viewShrimpFeed") {
                $( "#rdFeeding" ).change(function() {
                    $('.f1 input').prop('checked', false);
                    $(this).prop('checked', true);
                }).change();
            }

            showFeedGraph();
            drawChart();

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
        });

        function showFeedGraph(e) {
            var date = null;
            if (e){
                date = e.target.value;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type : "POST",
                url : '{{ CrudBooster::adminPath('feeding-cumulatives') }}',
                data : {date : date, pondId : '{{$pondId}}', aquaId : '{{$aquaId}}'},
                dataType : 'json',
                success:function (item) {
                    if (!$.isEmptyObject(item)) {
                        date = item.date;
                        $('#datePicker').val(date);
                    }else{
                        var today = new Date();
                        var today_date = ("0" + today.getDate()).slice(-2);
                        var today_month = ("0" + (today.getMonth() + 1)).slice(-2)
                        var now = today.getFullYear()+"-"+today_month+"-"+today_date;
                        $('#datePicker').val(now);
                    }
                    var feed_cumulatives = item.feed_cumulatives;
                    var medicine_cumulatives = item.medicine_cumulatives;
                    var html = "";
                    var i = 0;
                    var totalFeedCumulatives = 0;
                    var totalFeedCost = 0;
                    var totalMedicineCumulatives = 0;
                    var totalMedicineCost = 0;
                    var rateShrimp = item.rateShrimpPerM3.toFixed(2);
                    for(; i < feed_cumulatives.length && i < medicine_cumulatives.length; i++){
                        let feed_i = parseFloat(feed_cumulatives[i].cumulative);
                        let medicine_i = parseFloat(medicine_cumulatives[i].cumulative);
                        totalFeedCumulatives += parseFloat(feed_cumulatives[i].cumulative);
                        totalFeedCost += feed_cumulatives[i].cost;
                        totalMedicineCumulatives += parseFloat(medicine_cumulatives[i].cumulative);
                        totalMedicineCost += medicine_cumulatives[i].cost;
                        html += `<div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                        <span>{{trans("ebi.餌種類")}}</span><span title="${feed_cumulatives[i].bait_name}">${feed_cumulatives[i].bait_name}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                        <span>{{trans("ebi.累計")}} kg</span><span title="${feed_i}">${feed_i}kg</span><span title="${feed_cumulatives[i].cost}">${feed_cumulatives[i].cost}{{trans("ebi.円")}}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                        <span>{{trans("ebi.薬種類")}}</span><span title="${medicine_cumulatives[i].bait_name}">${medicine_cumulatives[i].bait_name}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                        <span>{{trans("ebi.累計")}}</span><span title="${medicine_i}">${medicine_i}kg</span><span title="${medicine_cumulatives[i].cost}">${medicine_cumulatives[i].cost}{{trans("ebi.円")}}</span>
                                    </div>
                                </div>`;
                    }
                    if (i < feed_cumulatives.length){
                        for(; i < feed_cumulatives.length; i++){
                            let feed_i = parseFloat(feed_cumulatives[i].cumulative);
                            totalFeedCumulatives += parseFloat(feed_cumulatives[i].cumulative);
                            totalFeedCost += feed_cumulatives[i].cost;
                            html += `<div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                        <span>{{trans("ebi.餌種類")}}</span><span title="${feed_cumulatives[i].bait_name}">${feed_cumulatives[i].bait_name}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                        <span>{{trans("ebi.累計")}} kg</span><span title="${feed_i}">${feed_i}kg</span><span title="${feed_cumulatives[i].cost}">${feed_cumulatives[i].cost}{{trans("ebi.円")}}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="feed-graph-detail-item">
                                    </div>
                                </div>`;
                        }
                    }else if (i < medicine_cumulatives.length){
                        for(; i < medicine_cumulatives.length; i++){
                            let medicine_i = parseFloat(medicine_cumulatives[i].cumulative);
                            totalMedicineCumulatives += parseFloat(medicine_cumulatives[i].cumulative);
                            totalMedicineCost += medicine_cumulatives[i].cost;
                            html += `<div class="col-md-3">
                                        <div class="feed-graph-detail-item">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="feed-graph-detail-item">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="feed-graph-detail-item">
                                            <span>{{trans("ebi.薬種類")}}</span><span title="${medicine_cumulatives[i].bait_name}">${medicine_cumulatives[i].bait_name}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="feed-graph-detail-item">
                                            <span>{{trans("ebi.累計")}}</span><span title="${medicine_i}">${medicine_i}kg</span><span title="${medicine_cumulatives[i].cost}">${medicine_cumulatives[i].cost}{{trans("ebi.円")}}</span>
                                        </div>
                                    </div>`;
                        }
                    }
                    html += `<div class="col-md-3">
                            <div class="feed-graph-detail-item">
                                <span>{{trans("ebi.餌合計")}}</span><span title="${totalFeedCumulatives}">${totalFeedCumulatives}kg</span><span title="${totalFeedCost}">${totalFeedCost}{{trans("ebi.円")}}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feed-graph-detail-item">
                                <span>{{trans("ebi.薬合計")}}</span><span title="${totalMedicineCumulatives}">${totalMedicineCumulatives}kg</span><span title="${totalMedicineCost}">${totalMedicineCost}{{trans("ebi.円")}}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="feed-graph-detail-item">
                                <span>{{trans("ebi.エビ(1m3)")}}</span><span title="${rateShrimp}">${rateShrimp}{{trans("ebi.匹")}}</span>
                            </div>
                        </div>`;

                    $('#showData').html(html);

                },
                error:function (e) {
                    console.log('Error:', data);
                }
            });
            $('[data-toggle="tooltip"]').tooltip();
        }

    </script>
    <script src="{{ asset('js/front_end.js') }}?v=3"></script>
@endpush