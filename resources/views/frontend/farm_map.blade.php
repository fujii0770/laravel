@extends('crudbooster::admin_template')

@push('head')
    <style>
        #f5_nav_farm li a {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .abnormal_pond_alerts{
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .solution{
            margin-top: 3px;
        }
    </style>
@endpush

@section('content')
    <div class="f5_container">
        <div class="f5_content">
            <ul id="f5_nav_farm" class="nav nav-tabs">
                @php
                    $i=0;
                @endphp
                @foreach($myFarms as $farm)
                    @php
                        $i++;
                    @endphp
                    @if($i <= App\Http\Controllers\AppConst::MAX_FARM_NUMBER )
                        @php
                            $farmName = (app()->getLocale() == 'en')?$farm->farm_name_en:$farm->farm_name;
                        @endphp
                        <li class="{{ $i==1?'active':'' }}">
                            <a data-id="{{ $farm->id }}" title="{{$farmName}}" alt="{{$farmName}}" data-toggle="tab" href="#menu_{{ $i }}">
                                {{$farmName}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="tab-content">
                <div class="f5_map_tab_top f5_tab_top">
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                        <p class="f2 pull-left"><a href="#" id="link_farm_status" class="text-white">{{trans('ebi.池状況(養殖場全体)')}}</a>&nbsp;<a href="#" id="link_farm_balance" class="text-white">{{trans('ebi.養殖場収支')}}</a></p>
                        </div>
                    </div>
                </div>
                <div class="mapView">
                    <div>
                        @include('frontend.farm_svg')
                    </div>
                </div>
                <div class="f5_tab_command">
                    <ul class="f5_status">
                        <li><label class="command_radio f5_radio">{{trans('ebi.養殖池状況')}}
                                <input id="rdWater" type="radio" name="radio" checked="checked">
                                <span class="checkmark"></span>
                            </label>
                        </li>
                        <li><label class="command_radio f5_radio">{{trans('ebi.エビ管理')}}
                                <input id="rdShrimp" type="radio" name="radio">
                                <span class="checkmark"></span>
                            </label>
                        </li>
                        <li>
                            <label class="command_radio f5_radio ">{{trans('ebi.餌状況')}}
                                <input id="rdFeeding" type="radio" name="radio" >
                                <span class="checkmark"></span>
                            </label>
                        </li>
                        <li>
                            <label class="command_radio f5_radio ">{{trans('ebi.収支')}}
                                <input id="rdBalance" type="radio" name="radio" >
                                <span class="checkmark"></span>
                            </label>
                        </li>
                       {{-- <li>
                            <label class="command_radio f5_radio ">{{trans('ebi.水質管理')}}
                                <input id="rdWater" type="radio" name="func" >
                                <span class="checkmark"></span>
                            </label>
                        </li>--}}
                    </ul>
                </div>
                <div class="f5_tab_status">
                    <ul class="f5_status">
                     
                    </ul>
                  
                </div>
                <div class="f5_weather">
                    @if($weather_daily)
                        <div class="by_date fs_10">
                            <div class="current_day">
                                <div>{{date("Y.m.d", strtotime($weather_daily[0]->day))}}</div>
                                <div><img src="{{asset('./svg/'.$weather_daily[0]->weather.'.svg')}}"  style="width: 56px;"></div>
                                <div><span class="f5_temperature_highest">{{ROUND($weather_daily[0]->temperature_over)}}°C</span>/<span class="f5_temperature_lowest">{{ROUND($weather_daily[0]->temperature_under)}}°C<span></div>
                            </div>
                        </div>
                        <div class="by_date fs_7 following_days">
                            @foreach ($weather_daily as $index => $weather)
                                @if($index > 0)
                                    <div class="next_day {{ $index == 6 ? 'border-right-none' : ''  }}">
                                        <div>{{date("m.d", strtotime($weather->day))}}</div>
                                        <div><img src="{{asset('./svg/'.$weather->weather.'.svg')}}"  style="width: 25px; height:35px;"></div>
                                        <div><span class="f5_temperature_highest">{{ROUND($weather->temperature_over)}}°C</span>/<span class="f5_temperature_lowest">{{ROUND($weather->temperature_under)}}°C<span></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="f5_tab_bottom">
                    <a class="btn btn-default f5_radius_30" href="{{CRUDBooster::adminPath('import_bait')}}"><i class="fa fa-shopping-cart"></i><span style="width: 150px; display: inline-block">{{trans('ebi.餌運用')}}</span></a><br><br>
                    <a class="btn btn-default f5_radius_30" href="{{CRUDBooster::adminPath('import_logs')}}"><i class="fa fa-cog"></i><span style="width: 150px; display: inline-block">{{trans('ebi.測定データ')}}</span></a>
                  {{--  <button class="btn btn-default f5_radius_30"><i class="fa fa-bar-chart"></i>{{trans('ebi.養殖池比較')}} </button>--}}
                </div>
            </div>
        </div>
        <div class="f5_sidebar">
            <ol class="timeline" style="height: 60%; overflow: auto;" >
                @foreach($abnormalPondAlerts as $alert)
                    <?php
                        $abnormalPond = null;
                        $countErrorCriteria = $alert->criterion_total - 1;
                        if(array_key_exists($alert->pond_id,$abnormalPonds)){
                            $abnormalPond = $abnormalPonds[$alert->pond_id];
                        }
                    ?>
                    <li>
                        <a href="{{ route('viewWaterQualityDetail').'?pondId='.$alert->pond_id.'&date='.$alert->alert_date.'&c=' .$alert->first_criterion}}">
                            <div class="f5_datetime">
                                <p class="f5_date">
                                    {{str_replace('-', '/', substr($alert->alert_date,5))}}
                                </p>
                                <p class="f5_time">{{substr($alert->alert_time, 0, 5)}}</p>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="title abnormal_pond_alerts" title="{{$abnormalPond->farm_name . '-' . $abnormalPond->pond_name}}">{{ $abnormalPond?$abnormalPond->farm_name.' '.$abnormalPond->pond_name:''}}</p>
                                    <p class="desc abnormal_pond_alerts">
                                        {{\App\Http\Controllers\Helper::getWaterCriteriaLabel($alert->first_criterion) . ($countErrorCriteria ? trans('ebi.その他') . $countErrorCriteria : '') . trans('ebi.水質異常値検出')}}
                                    </p>
                                </div>
                                <div class="col-md-4 solution">
                                    @if(isset($states[$alert->id]))
                                        @if(!$states[$alert->id]->solution_id)
                                            <a href="{{CRUDBooster::adminPath('solution/add').'?pondStatesId='.$states[$alert->id]->id}}" class="btn btn-primary">status</a>
                                        @else
                                            <a href="{{CRUDBooster::adminPath('solution/edit/'.$states[$alert->id]->solution_id)}}" class="btn btn-primary">{{trans('ebi.編集')}}</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ol>
            @if(count($arrExpectedEarningsByFarm))
                <ol class="timeline expected_revenue" style="height: 30%; overflow: scroll ;" >
                    @foreach ($arrExpectedEarningsByFarm as $farm => $expectedEarningsByAquacultures)
                        @foreach ($expectedEarningsByAquacultures as $day => $value)
                            <li class="row expected_revenue_by_farm">
                                <span class="col-md-5 abnormal_pond_alerts" title="{{$farm}}" >{{$farm}}</span>
                                <span class="col-md-3">{{$day}} 日目</span>
                                <span class="col-md-4" style="text-align: end">{{$value}}</span>
                            </li>
                        @endforeach
                    @endforeach  
                </ol>
            @endif
        </div>
    </div>
@endsection

@push('bottom')
    <script type="text/javascript">
        var special_ponds = [];
        @foreach(\App\Http\Controllers\AppConst::SPECIAL_POND as $p)
            special_ponds.push('{{$p}}');
        @endforeach
        function applyClickEvent(element){
            var pondId = $(element).attr('data-id');
            if (pondId){
                if ($(element).hasClass('ponds-normal')){
                    if ($("#rdWater").is(":checked")) {
                        $.ajax({
                            url: "{{CRUDBooster::adminPath('getCurrentEbiAquacultureOfPond?pondId=')}}" + pondId,
                            type: "get",
                            dataType: "json",
                            success: function (data) {
                                if (data) {
                                    location.href = "{{ route('viewPond') }}?aquaId=" + data + "&farmId=&pondId=" + pondId;
                                } else {
                                    location.href = "{{ route('viewPond') }}?pondId=" + pondId;
                                }
                            },
                            error: function () {
                                location.href = "{{ route('viewPond') }}?pondId=" + pondId;
                            }
                        });
                    }
                    if ($("#rdShrimp").is(":checked")) {
                        location.href = "{{ route('getShrimpManager') }}?pondId=" + pondId;
                    }
                    if ($("#rdFeeding").is(":checked")) {
                        location.href = "{{ route('viewShrimpFeed') }}?pondId=" + pondId;
                    }
                    if ($("#rdBalance").is(":checked")) {
                        location.href = '{{CRUDBooster::adminPath("report_pond")}}?&now=1&pond_id='+ pondId;
                    }
                }else{
                    $.ajax({
                        url: "{{CRUDBooster::adminPath('getCurrentEbiAquacultureOfPond?pondId=')}}" + pondId,
                        type: "get",
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                location.href = "{{ route('viewPond') }}?aquaId=" + data + "&farmId=&pondId=" + pondId;
                            } else {
                                location.href = "{{ route('viewPond') }}?pondId=" + pondId;
                            }
                        },
                        error: function () {
                            location.href = "{{ route('viewPond') }}?pondId=" + pondId;
                        }
                    });
                }
            }
        }

        $('document').ready(function(){
            $('#f5_nav_farm li a').click(function() {
                var farmId = $(this).attr("data-id");
                if (farmId){
                    $.ajax({
                        url: "{{CRUDBooster::adminPath('listPondByFarmMap?farm=')}}"+farmId,
                        type: "get",
                        dataType: "json",
                        success: function (data) {
                            $('.f5_tab_status .f5_status li').remove();
                            $(".f5_tab_status .f5_status").append("<li><span class='pond-current-farm'></span>{{trans('ebi.養殖停止')}} </li>");

                            if(data.aquacultures.length){
                                $(".f5_tab_status .f5_status").append("<li><span class='pond_map_color_3'></span>{{trans('ebi.養殖中')}}</li>");
                            }
                            $('.mapView .pond').removeClass("ponds-normal pond_map_color_3 pond-current-farm");
                            $('.mapView .pond').removeClass("clickable");
                            $('.mapView .pond').unbind("click");
                            if (data.list_ponds) {
                                for (var i = 0; i < data.list_ponds.length; i++) {
                                    if (data.list_ponds[i].aquacultures_id) {
                                        let aquaculture = data.aquacultures.find((item) => item.id == data.list_ponds[i].aquacultures_id)
                                        if (data.list_ponds[i].status == 0 && data.aquacultures[0] && aquaculture) {
                                            var startDate = new Date(aquaculture.start_date);
                                        } else {
                                            startDate = new Date();
                                        }
                                    }
                                    var today = new Date();
                                    let dayCount = 0;
                                    while (today > startDate) {
                                        dayCount++
                                        startDate.setDate(startDate.getDate() + 1)
                                    }
                                    // TODO pond color
                                    if (data.list_ponds[i].pond_image_area && data.list_ponds[i].status == 0) {
                                        $('.mapView #' + data.list_ponds[i].pond_image_area).html("<title> " + data.list_ponds[i].name + "&#xA;<br>養殖日数：" + dayCount + "日</title>");

                                        if (data.list_ponds[i].status == 0 && data.list_ponds[i].aquacultures_id && data.aquacultures[0]) {
                                            $('.mapView #' + data.list_ponds[i].pond_image_area).addClass('pond_map_color_3  ponds-normal clickable');
                                        }
                                        if(special_ponds.includes(data.list_ponds[i].pond_image_area)){
                                            $('.mapView .pond').removeClass("ponds-normal pond_map_color_3 pond-current-farm");
                                        }
                                    } else {
                                        $('.mapView #' + data.list_ponds[i].pond_image_area).html("<title> " + data.list_ponds[i].name + "</title>");
                                        $('.mapView #' + data.list_ponds[i].pond_image_area).addClass('pond-current-farm clickable');
                                    }
                                    $('.mapView #' + data.list_ponds[i].pond_image_area).attr("data-id", data.list_ponds[i].id);
                                    $('.mapView #' + data.list_ponds[i].pond_image_area).click(function () {
                                        applyClickEvent(this);
                                    })
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            });
            $('#f5_nav_farm li:first-child a').trigger('click');
            $( ".f1 input" ).click(function() {
                $('.f1 input').prop('checked', false);
                $(this).prop('checked', true);
            });

            $('#link_farm_status').click(function (){
                var activeFarmTab =$('#f5_nav_farm li.active a').first();
                if (activeFarmTab){
                    var farmId = activeFarmTab.attr("data-id");
                    //location.href = '{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('ponds_aquacultures')}}?farmId=' + farmId;
                    location.href = '{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('month_report')}}';

                }
            });

            $('#link_farm_balance').click(function (){
                var activeFarmTab =$('#f5_nav_farm li.active a').first();
                if (activeFarmTab){
                    var farmId = activeFarmTab.attr("data-id");
                 //   location.href = '{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('ponds_aquacultures')}}?farmId=' + farmId;
                }
            });

        });
    </script>
@endpush
