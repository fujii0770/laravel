@extends('crudbooster::admin_template')

@push('head')
<style>
    #showData {
        padding: 0 20px 0 25px;
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
                            <div class="col-md-6 no-padding text-left"><p><img src="{{ asset('img/svg/icon_line.svg') }}"><span class="icon-text">{{trans('ebi.水質状況')}}</span></p></div>
                            <div class="col-md-6 no-padding p-button-change-chart_ text-right"><a href="{{CRUDBooster::adminPath("viewWaterQuality")."?pondId=".$pondId."&aquaId=".($current_aquaculture?$current_aquaculture->ponds_aquacultures_id:'')}}" class="btn btn-link change-chart-ph p-0 button-icon" style="padding-right: 10px;"><img src="{{asset('./img/icon/icon_refresh.svg')}}" style="right:60px; left:auto; width: 20px;"><span class="icon-text">{{trans('ebi.表表示')}}</span></a></div>
                        </div>
                        <div id="chart-line"></div>

                        <div class="title" style="padding-left: 10px">
                            <p><img src="{{ asset('img/svg/icon_search.svg') }}"><span class="icon-text">{{trans('ebi.計測項目')}}</span></p>
                            <form action="">
                            {{trans('ebi.日付')}} <input id="datePicker" type="date" value="" onchange="showWaterQuality(event);" min="{{$current_aquaculture?$current_aquaculture->start_date:date_format(now(),'Y-m-d')}}" max="{{$current_aquaculture&&$current_aquaculture->completed_date?$current_aquaculture->completed_date:date_format(now(),'Y-m-d')}}">
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
                            <div class="col-md-12 chart-detail" id="showData"></div>
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
        drawChartLines = function () {

            var d = null, arr = null, leftPoint = null, rightPoint = null;
            // draw top of chart
            if( $('.highcharts-axis-line:not(.lineTopChart)').length){
                d = $('.highcharts-axis-line:not(.lineTopChart)').attr('d');
                arr = d.split(' ');
                arr[2] = 0;
                arr[5] = 0;
                arr[1] = 9.5;
                leftPoint = arr[1];
                if (rightPoint == null){
                    rightPoint = parseFloat(arr[4]) + 0.5;
                }
                arr[4] = rightPoint;
                $('.lineTopChart').attr('d',arr.join(' '));
                $('.lineTopChart').attr('visibility', 'visible');
            }

            // draw left of chart
            if( $('.lineLeftChart').length && leftPoint) {
                $('.lineLeftChart').attr('d', 'M ' + leftPoint + ' 0 L ' + leftPoint + ' 25 ');
                $('.lineLeftChart').attr('visibility', 'visible');
            }

            // draw right of chart
            if( $('.lineRightChart').length && rightPoint) {
                $('.lineRightChart').attr('d', 'M ' + rightPoint + ' 0 L ' + rightPoint + ' 25 ');
                $('.lineRightChart').attr('visibility', 'visible');
            }

            // draw main grid line
            var mainXs = [];
            var endX = null;
            var endIsStartMonth = false;
            $('.highcharts-axis-labels.highcharts-xaxis-labels text').each(function( index ) {
                var xLabel = $( this ).text();
                if (xLabel){
                    if (xLabel.indexOf('#') > -1) {
                        endX = $( this ).attr('x');
                        if (xLabel.length > 1){
                            endIsStartMonth = true;
                            $( this ).text(xLabel.substring(1));
                        }else{
                            $( this ).text('');
                        }
                        $( this ).attr('data-end',true);
                    }else if ($( this ).attr('data-end')){
                        endX = $( this ).attr('x');
                    }else{
                        mainXs.push($( this ).attr('x'));
                    }
                }else if ($( this ).attr('data-end')){
                    endX = $( this ).attr('x');
                }
            });
            $('.highcharts-xaxis-grid .highcharts-grid-line').each(function( index ) {
                d = $( this ).attr('d');
                arr = d.split(' ');
                var lineX = arr[1];
                $( this ).attr('stroke', '#eeeeee');
                for(var i =0 ; i< mainXs.length; i++){
                    if (Math.abs(lineX - mainXs[i]) < 2){
                        if (orgX == null){
                            orgX = arr[2] - 5;
                        }
                        arr[2] = orgX;
                        $( this ).attr('d',arr.join(' '));
                        $( this ).attr('stroke', '#ccd6eb');
                        break;
                    }
                }
                if (endX !== null && Math.abs(lineX - endX) < 2){
                    if (endIsStartMonth){
                        if (orgX == null){
                            orgX = arr[2] - 5;
                        }
                        arr[2] = orgX;
                        $( this ).attr('d',arr.join(' '));
                    }
                    $( this ).attr('stroke', '#fcbc86');
                }
            });
        },
        drawChart = function (datas) {
            chart = Highcharts.chart('chart-line', {
                chart: {
                    height: '34%',
                    marginTop: 25,
                    marginBottom: 67,
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
                    text: ''
                },
                xAxis: {
                    categories: datas.days,
                    tickmarkPlacement:'on',
                    labels: {
                        style: {"color": "#231F20", "fontSize": "12px", "fontFamily": '"M PLUS 1p"', "border": "1px solid #000"},
                        autoRotation: false,
                        y: -10
                    },
                    tickLength: 0,
                    gridLineWidth: 1,
                    gridLineColor: '#eeeeee',
                    opposite: true,
                    plotLines: [{
                        color: '#cccccc', // Color value
                        value: 10, // Value of where the line will appear
                        width: 1, // Width of the line
                        zIndex: 3,
                        className: "lineLeftChart"
                    },
                        {
                        color: '#cccccc', // Color value
                        value: 10, // Value of where the line will appear
                        width: 1, // Width of the line
                        zIndex: 3,
                        className: "lineRightChart"
                    }],
                    min: 0,
                    max: datas.days.length > 50?50:datas.days.length-1,
                    scrollbar: {
                        enabled: true
                    },
                },

                yAxis: [{
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 5.75,
                        max: 7.25,
                        tickInterval: 0.25,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                        plotLines: [{
                            color: '#cccccc', // Color value
                            value: 10, // Value of where the line will appear
                            width: 1, // Width of the line
                            zIndex: 3,
                            className: "lineTopChart"
                        }],
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 45,
                        max: 70,
                        tickInterval: 5,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 240,
                        max: 248,
                        tickInterval: 1,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                      /*  min: -5,
                        max: 5,
                        tickInterval: 2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 0,
                        max: 5,
                        tickInterval: 1,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: -5,
                        max: 5,
                        tickInterval: 2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 999890,
                        max: 1000010,
                        tickInterval: 30,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                      /*  min: 0,
                        max: 5,
                        tickInterval: 1,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: -5,
                        max: 5,
                        tickInterval: 2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 93,
                        max: 99,
                        tickInterval: 0.5,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 7.5,
                        max: 8.5,
                        tickInterval: 0.2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                      /*  min: 0,
                        max: 1,
                        tickInterval: 0.2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                       /* min: 22,
                        max: 26,
                        tickInterval: 0.5,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                      /*  min: 14,
                        max: 15,
                        tickInterval: 0.2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                        /*  min: 14,
                          max: 15,
                          tickInterval: 0.2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                        /*  min: 14,
                          max: 15,
                          tickInterval: 0.2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }, {
                        title: {
                            text: null
                        },
                        labels: {
                            enabled: false
                        },
                        /*  min: 14,
                          max: 15,
                          tickInterval: 0.2,*/
                        tickColor: 'transparent',
                        showLastLabel: false,
                        gridLineWidth: 0,
                    }
                ],

                tooltip: {
                    enabled: false,
                },

                legend: {
                    align: 'right',
                    rtl: true,
                    symbolWidth: 20,
                    itemStyle: {
                        color: "#818285",
                        fontWeight: "Regular",
                        fontSize:'10px',
                        font: '"M PLUS 1p',
                    },
                },

                series: [{
                        name: '17',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_ION}},
                        zIndex: 1,
                        color: '#ffebcd',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 16,
                    },{
                        name: '16',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_ION}},
                        zIndex: 1,
                        color: '#ee4000',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 15,
                    },{
                        name: '15',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_AMMONIA}},
                        zIndex: 1,
                        color: '#ee4000',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 14,
                    },{
                        name: '14',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_PRESS}},
                        zIndex: 1,
                        color: '#0f0f0f',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 13,
                    },{
                        name: '13',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_TMP}},
                        zIndex: 1,
                        color: '#0f0f0f',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 12,
                    },{
                        name: '12',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_TURB}},
                        zIndex: 1,
                        color: '#96277E',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 11,
                    },{
                        name: '11',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_DO_PPM}},
                        zIndex: 1,
                        color: '#96277E',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 10,
                    },{
                        name: '10',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_DO}},
                        zIndex: 1,
                        color: '#1C75BC',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 9,
                    },{
                        name: '9',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_SIGMA}},
                        zIndex: 1,
                        color: '#1C75BC',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 8,
                    },{
                        name: '8',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_SAL}},
                        zIndex: 1,
                        color: '#27AAE1',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 7,
                    },{
                        name: '7',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_TDS}},
                        zIndex: 1,
                        color: '#27AAE1',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 6,
                    },{
                        name: '6',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_RES}},
                        zIndex: 1,
                        color: '#3B9648',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 5,
                    },{
                        name: '5',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_EC_ABS}},
                        zIndex: 1,
                        color: '#3B9648',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 4,
                    },{
                        name: '4',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_EC}},
                        zIndex: 1,
                        color: '#FFCC32',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 3,
                    },{
                        name: '3',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_ORP}},
                        zIndex: 1,
                        color: '#FFCC32',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 2,
                    },{
                        name: '2',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_MV}},
                        zIndex: 1,
                        color: '#E32826',
                        dashStyle: 'shortdot',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 1,
                    },
                    {
                        name: '1',
                        data: datas.{{App\Http\Controllers\Helper::WATER_CRITERIA_PH}},
                        zIndex: 1,
                        color: '#E32826',
                        dashStyle: 'line',
                        states: { hover: { enabled: false } },
                        marker: {
                            symbol: 'round'
                        },
                        yAxis: 0,
                    }
                ]
            });
        }

        $('document').ready(function(){
            var part = window.location.pathname.split("/").pop();
            //console.log(part);
            if (part == "viewPond") {
                $( "#rdWater" ).change(function() {
                    $('.f1 input').prop('checked', false);
                    $(this).prop('checked', true);
                }).change();
            }

            drawShrimpStatus();
            showWaterQuality();
        });

        function drawShrimpStatus() {
            var shrimp_size_now = '{{ $shrimp->size }}';
            var shrimp_weight_now = '{{ $shrimp ->weight }}';
            var shrimp_size = '{{ $current_aquaculture ->target_size  }}';
            var shrimp_weight = '{{ $current_aquaculture ->target_weight  }}';
            var percent_size = shrimp_size_now / shrimp_size * 100;
            var percent_weight = shrimp_weight_now / shrimp_weight * 100;
            var percent_size_weight = (percent_size + percent_weight)/2;
            var percent_size_weight = percent_size_weight.toFixed(2);

            $(".g_class").children().each(function(index,item) {
                var maxPoligon = parseFloat($(item).attr('max'));
                if (maxPoligon <= percent_size_weight) {
                    $(item).attr('class','st st1');
                }
            });
        }

        function gotoQualityDetail(criteria){
            var date = ($('#datePicker').val());
            if (criteria && date){
                location.href = "{{CRUDBooster::adminPath('viewQualityDetail')}}?pondId={{$pondId}}&aquaId={{$aquaId}}&date=" + date + "&c=" + criteria;
            }
        }

        function showWaterQuality(e) {
            $.ajax({
                url: site_url + "/admin/water-criterias/values.json?pondId={{$pondId}}&aquaId={{$aquaId}}",
                dataType: 'json',
                success: function(datas){
                    chartData = datas;
                    drawChart(datas);
                }
            });

            //console.log(pondId);
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
                url : '{{ CrudBooster::adminPath('water-criterias') }}',
                data : {date : date, pondId : '{{$pondId}}', aquaId : '{{$aquaId}}'},
                dataType : 'json',
                success:function (item) {
                    var ph_values = null;
                    var mv_values = null;
                    var orp_values = null;
                    var ec_values = null;
                    var ec_abs_values = null;
                    var res_values = null;
                    var tds_values = null;
                    var sal_values = null;
                    var sigma_t_values = null;
                    var do_values = null;
                    var do_ppm_values = null;
                    var turb_fnu_values = null;
                    var tmp_values = null;
                    var press_values = null;
                    var ammonia_values = null;
                    var ion_values = null;
                    var out_temp_values = null;

                    var ph_values_state = true;
                    var mv_values_state = true;
                    var orp_values_state = true;
                    var ec_values_state = true;
                    var ec_abs_values_state = true;
                    var res_values_state = true;
                    var tds_values_state = true;
                    var sal_values_state = true;
                    var sigma_t_values_state = true;
                    var do_values_state = true;
                    var do_ppm_values_state = true;
                    var turb_fnu_values_state = true;
                    var tmp_values_state = true;
                    var press_values_state = true;
                    var ammonia_values_state = true;
                    var ion_values_state = true;
                    var out_temp_values_state = true;

                    var splitted_ph_values = null;
                    var splitted_mv_values = null;
                    var splitted_orp_values = null;
                    var splitted_ec_values = null;
                    var splitted_ec_abs_values = null;
                    var splitted_res_values = null;
                    var splitted_tds_values = null;
                    var splitted_sal_values = null;
                    var splitted_sigma_t_values = null;
                    var splitted_do_values = null;
                    var splitted_do_ppm_values = null;
                    var splitted_turb_fnu_values = null;
                    var splitted_tmp_values = null;
                    var splitted_press_values = null;
                    var splitted_ammonia_values = null;
                    var splitted_ion_values = null;
                    var splitted_out_temp_values = null;

                    if (!$.isEmptyObject(item)){
                        date = item.date;
                        $('#datePicker').val(date);

                        ph_values = item.ph_values;
                        mv_values = item.mv_values;
                        orp_values = item.orp_values;
                        ec_values = item.ec_values;
                        ec_abs_values = item.ec_abs_values;
                        res_values = item.res_values;
                        tds_values = item.tds_values;
                        sal_values = item.sal_values;
                        sigma_t_values = item.sigma_t_values;
                        do_values = item.do_values;
                        do_ppm_values = item.do_ppm_values;
                        turb_fnu_values = item.turb_fnu_values;
                        tmp_values = item.tmp_values;
                        press_values = item.press_values;
                        ammonia_values = item.ammonia_values;
                        ion_values = item.ion_values;
                        out_temp_values = item.out_temp_values;

                        ph_values_state = item.ph_values_state;
                        mv_values_state = item.mv_values_state;
                        orp_values_state = item.orp_values_state;
                        ec_values_state = item.ec_values_state;
                        ec_abs_values_state = item.ec_abs_values_state;
                        res_values_state = item.res_values_state;
                        tds_values_state = item.tds_values_state;
                        sal_values_state = item.sal_values_state;
                        sigma_t_values_state = item.sigma_t_values_state;
                        do_values_state = item.do_values_state;
                        do_ppm_values_state = item.do_ppm_values_state;
                        turb_fnu_values_state = item.turb_fnu_values_state;
                        tmp_values_state = item.tmp_values_state;
                        press_values_state = item.press_values_state;
                        ammonia_values_state = item.ammonia_values_state;
                        ion_values_state = item.ion_values_state;
                        out_temp_values_state = item.out_temp_values_state;

                        splitted_ph_values = splitDecimalStr(ph_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_PH)}});
                        splitted_mv_values = splitDecimalStr(mv_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_MV)}});
                        splitted_orp_values = splitDecimalStr(orp_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_ORP)}});
                        splitted_ec_values = splitDecimalStr(ec_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_EC)}});
                        splitted_ec_abs_values = splitDecimalStr(ec_abs_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_EC_ABS)}});
                        splitted_res_values = splitDecimalStr(res_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_RES)}});
                        splitted_tds_values = splitDecimalStr(tds_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_TDS)}});
                        splitted_sal_values = splitDecimalStr(sal_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_SAL)}});
                        splitted_sigma_t_values = splitDecimalStr(sigma_t_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_SIGMA)}});
                        splitted_do_values = splitDecimalStr(do_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_DO)}});
                        splitted_do_ppm_values = splitDecimalStr(do_ppm_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_DO_PPM)}});
                        splitted_turb_fnu_values = splitDecimalStr(turb_fnu_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_TURB)}});
                        splitted_tmp_values = splitDecimalStr(tmp_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_TMP)}});
                        splitted_press_values = splitDecimalStr(press_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_PRESS)}});
                        splitted_ammonia_values = splitDecimalStr(ammonia_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_AMMONIA)}});
                        splitted_ion_values = splitDecimalStr(ion_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_ION)}});
                        splitted_out_temp_values = splitDecimalStr(out_temp_values, {{\App\Http\Controllers\Helper::getDecimalPlaceForCriteria(\App\Http\Controllers\Helper::WATER_CRITERIA_OUT_TEMP)}});
                    }else{
                        if (date == null){
                            var today = new Date();
                            var today_date = ("0" + today.getDate()).slice(-2);
                            var today_month = ("0" + (today.getMonth() + 1)).slice(-2)
                            var now = today.getFullYear()+"-"+today_month+"-"+today_date;
                            $('#datePicker').val(now);
                        }

                        ph_values = '';
                        mv_values = '';
                        orp_values = '';
                        ec_values = '';
                        ec_abs_values = '';
                        res_values = '';
                        tds_values = '';
                        sal_values = '';
                        sigma_t_values = '';
                        do_values = '';
                        do_ppm_values = '';
                        turb_fnu_values = '';
                        tmp_values = '';
                        press_values = '';
                        ammonia_values = '';
                        ion_values = '';
                        out_temp_values = '';

                        splitted_ph_values = ['-',''];
                        splitted_mv_values = ['-',''];
                        splitted_orp_values = ['-',''];
                        splitted_ec_values = ['-',''];
                        splitted_ec_abs_values = ['-',''];
                        splitted_res_values = ['-',''];
                        splitted_tds_values = ['-',''];
                        splitted_sal_values = ['-',''];
                        splitted_sigma_t_values = ['-',''];
                        splitted_do_values = ['-',''];
                        splitted_do_ppm_values = ['-',''];
                        splitted_turb_fnu_values = ['-',''];
                        splitted_tmp_values = ['-',''];
                        splitted_press_values = ['-',''];
                        splitted_ammonia_values = ['-',''];
                        splitted_ion_values = ['-',''];
                        splitted_out_temp_values = ['-',''];

                    }

                    $('#showData').html('<div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">1. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_PH}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_PH)}}</a> </small><span class="' + (ph_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+ph_values+'">'+splitted_ph_values[0]+'<small class="small-2">.'+ splitted_ph_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">2. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_MV}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_MV)}}（pH）</a> </small><span class="' + (mv_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+mv_values+'">'+splitted_mv_values[0]+'<small class="small-2">.'+ splitted_mv_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">3. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_ORP}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_ORP)}}</a> </small><span class="' + (orp_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+orp_values+'">'+splitted_orp_values[0]+'<small class="small-2">.'+ splitted_orp_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">4. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_EC}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_EC)}}</a> </small><span class="' + (ec_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+ec_values+'">'+splitted_ec_values[0]+'<small class="small-2"></small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">5. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_EC_ABS}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_EC_ABS)}}</a> </small><span class="' + (ec_abs_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+ec_abs_values+'">'+splitted_ec_abs_values[0]+'<small class="small-2"></small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">6. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_RES}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_RES)}}</a> </small><span class="' + (res_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+res_values+'">'+splitted_res_values[0]+'<small class="small-2"></small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">7. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_TDS}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_TDS)}}</a> </small><span class="' + (tds_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+tds_values+'">'+splitted_tds_values[0]+'<small class="small-2"></small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">8. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_SAL}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_SAL)}}</a> </small><span class="' + (sal_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+sal_values+'">'+splitted_sal_values[0]+'<small class="small-2">.'+splitted_sal_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">9. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_SIGMA}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_SIGMA)}}</a> </small><span class="' + (sigma_t_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+sigma_t_values +'">'+splitted_sigma_t_values[0]+'<small class="small-2">.'+splitted_sigma_t_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">10. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_DO}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_DO)}}</a> </small><span class="' + (do_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+do_values+'">'+splitted_do_values[0]+'<small class="small-2">.'+splitted_do_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">11. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_DO_PPM}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_DO_PPM)}}</a> </small><span class="' + (do_ppm_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+do_ppm_values+'">'+splitted_do_ppm_values[0]+'<small class="small-2">.'+splitted_do_ppm_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">12. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_TURB}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_TURB)}}</a> </small><span class="' + (turb_fnu_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+turb_fnu_values+'">'+splitted_turb_fnu_values[0]+'<small class="small-2">.'+splitted_turb_fnu_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">13. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_TMP}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_TMP)}}（℃）</a> </small><span class="' + (tmp_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+tmp_values+'">'+splitted_tmp_values[0]+'<small class="small-2">.'+splitted_tmp_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">14. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_PRESS}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_PRESS)}}（psi）</a> </small><span class="' + (press_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+press_values+'">'+splitted_press_values[0]+'<small class="small-2">.'+splitted_press_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">15. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_AMMONIA}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_AMMONIA)}}</a> </small><span class="' + (ammonia_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+ammonia_values+'">'+splitted_ammonia_values[0]+'<small class="small-2">.'+splitted_ammonia_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">16. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_ION}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_ION)}}</a> </small><span class="' + (ion_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+ion_values+'">'+splitted_ion_values[0]+'<small class="small-2">.'+splitted_ion_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-md-3">\n' +
                        '                                <div class="chart-detail-item">\n' +
                        '                                    <small class="small-1">17. <a href="javascript:void(0);" onclick="gotoQualityDetail(\'{{App\Http\Controllers\Helper::WATER_CRITERIA_OUT_TEMP}}\')">{{App\Http\Controllers\Helper::getWaterCriteriaLabel(App\Http\Controllers\Helper::WATER_CRITERIA_OUT_TEMP)}}</a> </small><span class="' + (out_temp_values_state?"text-quality-normal":"text-quality-abnormal") + '" data-toggle="tooltip" title="'+out_temp_values+'">'+splitted_out_temp_values[0]+'<small class="small-2">.'+splitted_out_temp_values[1]+'</small></span>\n' +
                        '                                </div>\n' +
                        '                            </div>');
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