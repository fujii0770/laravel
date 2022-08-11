@extends('crudbooster::admin_template')

@push('head')
    <style>
        .dis-non {
            display: none;
        }
        #chart-line {
            height: 300px;
        }
        #main-table tr>td {
            font-size: 12px;
        }
        #main-table tbody>tr>td {
            padding: 4px;
        }
        #btn-add-measure a, #btnShrimpMigration a {
            font-size: 10px;
            display: block;
            width: 75px;
            white-space: inherit;
        }
        #btnShrimpMigration {
            margin-top: 10px;
        }
    </style>
@endpush

@section('content')
    @include('partials/page_nav')
    <div class="row">
        @include('partials/frontend_sidebar')
        <div class="col-md-10 content-radio" style="padding: 15px 30px 15px 30px;">
            <div class="content-condition shrimp icon_title" style="min-height: calc(100vh - 250px)">
                <div class="row">
                    <div class="col-sm-9">
                        <p id="chart-left-top-title" class="title button-icon"><span class="icon-text">ABW</span></p>
                    </div>
                    <div class="col-sm-3 text-right">
                        <a href="#" id="abw" class="btn btn-default change-chart-ph button-icon active" style="padding: 3px 12px 3px 12px">
                            <span class="icon-text">ABW</span>
                        </a>
                        <a href="#" id="adg" class="btn btn-default change-chart-ph button-icon" style="padding: 3px 12px 3px 12px">
                            <span class="icon-text">ADG</span>
                        </a>
                        <a href="#" id="fcr" class="btn btn-default change-chart-ph button-icon" style="padding: 3px 12px 3px 12px">
                            <span class="icon-text">FCR</span>
                        </a>
                    </div>
                </div>
                <div class="box shrimp_completed">
                    <div class="box-footer" style="padding:0;">
                        <div class="row row-flex">
                            <div class="col-md-12">
                                <div id="chart-line"></div>
                            </div>
                        {{--<div class="col-sm-2" style="margin: auto">
                            <div class="description-block">
                                <span class="description-text">{{trans('ebi.サイズ別出荷数')}}</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-7 border-right">
                            <table class="shipment_standard table" style="margin-bottom: 0">
                                @if($current_aquaculture && $current_aquaculture->completed_date)
                                    @php
                                        $colNum = 5;
                                        $shipment_standards = array('20' => $current_aquaculture->shipment_standard_20, '30' => $current_aquaculture->shipment_standard_30, '40' => $current_aquaculture->shipment_standard_40,
                                                                '50' => $current_aquaculture->shipment_standard_50, '60' => $current_aquaculture->shipment_standard_60, '70' => $current_aquaculture->shipment_standard_70,
                                                                '80' => $current_aquaculture->shipment_standard_80, '90' => $current_aquaculture->shipment_standard_90, '100' => $current_aquaculture->shipment_standard_100,
                                                                '110' => $current_aquaculture->shipment_standard_110, '120' => $current_aquaculture->shipment_standard_120, '130' => $current_aquaculture->shipment_standard_130,
                                                                '140' => $current_aquaculture->shipment_standard_140, '150' => $current_aquaculture->shipment_standard_150, '160' => $current_aquaculture->shipment_standard_160,
                                                                '170' => $current_aquaculture->shipment_standard_170, '180' => $current_aquaculture->shipment_standard_180, '190' => $current_aquaculture->shipment_standard_190,
                                                                '200' => $current_aquaculture->shipment_standard_200
                                                                );
                                        $rowNum = ceil(count($shipment_standards)/$colNum);
                                        $shipment_standards = array_filter($shipment_standards);
                                        $chunks = array_chunk(array_keys($shipment_standards), $rowNum);
                                        $chunkNum = count($chunks);
                                    @endphp
                                    @for($row = 0; $row < $rowNum; $row++)
                                        <tr>
                                            @for($col = 0; $col < $colNum; $col++)
                                                @if(key_exists($col, $chunks) && key_exists($row, $chunks[$col]))
                                                    <td class="shipment_standard">{{$chunks[$col][$row]}} - <span class="text-blue-chart">{{$shipment_standards[$chunks[$col][$row]]}} <small>{{trans('ebi.匹')}} </small></span></td>
                                                @else
                                                    <td class="shipment_standard">&nbsp;</td>
                                                @endif
                                            @endfor
                                        </tr>
                                    @endfor
                                @else
                                    <tr>
                                        <td class="shipment_standard">20 - </td>
                                        <td class="shipment_standard">60 - </td>
                                        <td class="shipment_standard">100 - </td>
                                        <td class="shipment_standard">140 - </td>
                                        <td class="shipment_standard">180 - </td>
                                    </tr>
                                    <tr>
                                        <td class="shipment_standard">30 - </td>
                                        <td class="shipment_standard">70 - </td>
                                        <td class="shipment_standard">110 - </td>
                                        <td class="shipment_standard">150 - </td>
                                        <td class="shipment_standard">190 - </td>
                                    </tr>
                                    <tr>
                                        <td class="shipment_standard">40 - </td>
                                        <td class="shipment_standard">80 - </td>
                                        <td class="shipment_standard">120 - </td>
                                        <td class="shipment_standard">160 - </td>
                                        <td class="shipment_standard">200 - </td>
                                    </tr>
                                    <tr>
                                        <td class="shipment_standard">50 - </td>
                                        <td class="shipment_standard">90 - </td>
                                        <td class="shipment_standard">130 - </td>
                                        <td class="shipment_standard">170 - </td>
                                        <td class="shipment_standard"></td>
                                    </tr>
                                @endif
                            </table>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-3" style="margin: auto">
                            <div class="row row-flex" style="padding: 20px">
                                <div class="col-sm-7" style="margin: auto">{{trans('ebi.エビ総数')}} </div>
                                <div class="col-sm-5 text-blue-chart text-right no-padding"><span class="fa-2x">{{$current_aquaculture->shipment_count}}</span> {{$current_aquaculture->shipment_count?'匹':''}}</div>
                                <div class="col-sm-7" style="margin: auto">{{trans('ebi.生存率')}}</div>
                                <div class="col-sm-5 text-blue-chart text-right"><span class="fa-2x">{{(($current_aquaculture->shipment_count && $current_aquaculture->shrimp_num)?(int)($current_aquaculture->shipment_count*100/$current_aquaculture->shrimp_num):'')}}</span>{{($current_aquaculture->shipment_count && $current_aquaculture->shrimp_num)?'%':''}}</div>
                            </div>
                            <!-- /.description-block -->
                        </div>--}}
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>

                <div class="row" style="display: flex; align-items: flex-end">
                    <div class="col-sm-11">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="title button-icon"><img src="{{ asset('img/svg/icon_tom.svg') }}" style="top: 0px;width: 20px"><span class="icon-text">{{trans('ebi.エビ状況')}}</span></p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p class="title button-icon"><span class="icon-text">{{trans('ebi.エビ価格表')}}</span></p>
                            </div>
                        </div>
                        @php
                            $countShrimpState = count($shrimpStates) + 1;
                        @endphp
                        <div class="table-scroll">
                            <table class="table table-hover table-striped table-bordered text-center" id="main-table">
                                <thead>
                                <tr>
                                    <td class="fixed-side" style="border-top: 1px solid #a7a9ac">&nbsp;</td>
                                    @if($countShrimpState <= 9)
                                        @for ($i = 1; $i <= 9; $i++)
                                            <td style="border-top: 1px solid #a7a9ac">{{$i}}</td>
                                        @endfor
                                    @else
                                        @for ($i = 1; $i <= ($countShrimpState - 1); $i++)
                                            <td style="border-top: 1px solid #a7a9ac">{{$i}}</td>
                                        @endfor
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="fixed-side">{{trans('ebi.測定日')}} </td>
                                    @foreach($shrimpStates as $shrimpState)
                                        <td scope="col">{{App\Http\Controllers\Helper::formatDate($shrimpState->date_target)}}</td>
                                    @endforeach
                                    @for ($i = $countShrimpState; $i <= 9; $i++)
                                        <td scope="col"></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="fixed-side">{{trans('ebi.サイズ')}}</td>
                                    @foreach($shrimpStates as $shrimpState)
                                        <td scope="col">{{$shrimpState->size}}</td>
                                    @endforeach
                                    @for ($i = $countShrimpState; $i <= 9; $i++)
                                        <td scope="col"></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="fixed-side">{{trans('ebi.重量')}}</td>
                                    @foreach($shrimpStates as $shrimpState)
                                        <td scope="col">{{$shrimpState->weight}}</td>
                                    @endforeach
                                    @for ($i = $countShrimpState; $i <= 9; $i++)
                                        <td scope="col"></td>
                                    @endfor
                                </tr>
                                {{--<tr>
                                    <td class="fixed-side">{{trans('ebi.餌総量')}}</td>
                                    @for ($i = 1; $i <= 9; $i++)
                                        <td scope="col"></td>
                                    @endfor
                                </tr>--}}
                                <tr>
                                    <td class="fixed-side">{{trans('ebi.現在の単価')}}</td>
                                    @foreach($prices as $price)
                                        <td scope="col">{{$price}}</td>
                                    @endforeach
                                    @for ($i = $countShrimpState; $i <= 9; $i++)
                                        <td scope="col"></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="fixed-side" style="vertical-align: middle">{{trans('ebi.エビ写真')}}</td>
                                    @foreach($shrimpStates as $shrimpState)
                                        <td scope="col">
                                            @if($shrimpState->photo)
                                                <a href="{{ url($shrimpState->photo) }}" target="_blank"><img width="200px" style="padding: 4px" src="{{ url($shrimpState->photo) }}" /></a>
                                            @endif
                                        </td>
                                    @endforeach
                                    @for ($i = $countShrimpState; $i <= 9; $i++)
                                        <td scope="col"></td>
                                    @endfor
                                </tr>
                                <tr>
                                    <td class="fixed-side" style="vertical-align: middle">{{trans('ebi.アクション')}}</td>
                                    @foreach($shrimpStates as $shrimpState)
                                        <td scope="col">
                                            <a class="btn btn-flat" href="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('shrimp_states/edit').'/'.$shrimpState->id}}"><span class="fa fa-edit"></span></a>
                                            <a class="btn btn-flat" href="javascript:void(0);" onclick="swal({
                                                    title: &quot;削除確認&quot;,
                                                    text: &quot;{{App\Http\Controllers\Helper::formatDate($shrimpState->date_target)}}のデータを削除してよろしいでしょうか？&quot;,
                                                    type: &quot;warning&quot;,
                                                    showCancelButton: true,
                                                    confirmButtonColor: &quot;#ff0000&quot;,
                                                    confirmButtonText: &quot;削除&quot;,
                                                    cancelButtonText: &quot;キャンセル&quot;,
                                                    closeOnConfirm: false },
                                                    function(){  location.href=&quot;{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('shrimp_states/delete').'/'.$shrimpState->id}}&quot; });"><span class="fa fa-trash"></span></a>
                                        </td>
                                    @endforeach
                                    @for ($i = $countShrimpState; $i <= 9; $i++)
                                        <td scope="col"></td>
                                    @endfor
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        @if(!$current_aquaculture->ponds_aquacultures_status)
                            <div id="btn-add-measure">
                                <a href="{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('shrimp_states/add')}}" class="btn btn-default">
                                    {{trans('ebi.エビ状況登録')}}
                                </a>
                            </div>
                            <div id="btnShrimpMigration">
                                <a href="#" class="btn btn-default">
                                    {{trans('ebi.エビ移行登録')}}
                                </a>
                            </div>
                        @endif
                    </div>
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
    <script src="{{ asset('js/front_end.js') }}?v=3"></script>
    <script>
        $('document').ready(function(){
            var part = window.location.pathname.split("/").pop();
            //console.log(part);
            if (part == "viewShrimpMeasure") {
                $( "#rdShrimp" ).change(function() {
                    $('.f1 input').prop('checked', false);
                    $(this).prop('checked', true);
                }).change();
            }

            let idealWeight = {{ json_encode($idealWeight) }};
            let actualWeight = {{ json_encode($actualWeight) }};
            let training_period = {{$training_period}};
            drawChart(training_period, idealWeight, actualWeight);

            const aqua_status = '{{$current_aquaculture->ponds_aquacultures_status}}'
            const ponds_aqua = '{{ $current_aquaculture->ponds_aquacultures_id }}';
            if ( ponds_aqua === "" || aqua_status != 0) {
                $('#btn-add-measure').addClass('dis-non');
            }
        });

        var chart = null;
        drawChart = function (training_period, idealWeight, actualWeight) {
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
                    },

                },
                plotOptions: {
                },
                legend: {
                },
                series: [
                    {
                        name: '理想',
                        data: idealWeight,
                        color: '#6C9BD2',
                        lineWidth: 2,
                        marker: {
                            enabled: true
                        },
                    },{
                        name: '実績',
                        data: actualWeight,
                        color: '#E71F1A',
                        lineWidth: 2,
                        marker: {
                            enabled: true
                        },
                    },
                ]
            });
        }

        const threshold_fcr = {{ json_encode($threshold_fcr) }};
        const fcr = {{ json_encode($fcr) }};
        const fcr_date = {{$fcr_date}};

        const threshold_adg = {{ json_encode($threshold_adg) }};
        const adg = {{ json_encode($adg) }};
        const adg_date = {{$adg_date}};

        const idealWeight = {{ json_encode($idealWeight) }};
        const actualWeight = {{ json_encode($actualWeight) }};
        const training_period = {{$training_period}};

        $("#adg").click(function () {
            $("#adg").addClass('active');
            $("#fcr").removeClass('active');
            $("#abw").removeClass('active');
            drawChart(adg_date, threshold_adg, adg);
            $('#chart-left-top-title').text("ADG");
        });

        $("#fcr").click(function () {
            $("#fcr").addClass('active');
            $("#adg").removeClass('active');
            $("#abw").removeClass('active');
            drawChart(fcr_date, threshold_fcr, fcr);
            $('#chart-left-top-title').text("FCR");
        });

        $("#abw").click(function () {
            $("#abw").addClass('active');
            $("#adg").removeClass('active');
            $("#fcr").removeClass('active');
            drawChart(training_period, idealWeight, actualWeight);
            $('#chart-left-top-title').text("ABW");
        });

    </script>
@endpush