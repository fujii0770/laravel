<div class="content-detail content_right_block">
    <div class="status_title">
        <p class="title"><img src="{{ asset('img/svg/icon_tom.svg') }}" style="top:0;height: 20px;width: 20px"><span class="icon-text">{{trans('ebi.エビ状況')}}</span></p>
    </div>
    <div class="Shrimp_logo" style="width: 110px; height: 110px;">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 94 50.2" style="enable-background:new 0 0 94 50.2;" xml:space="preserve">
                                <style type="text/css">
                                    .st0{display:none;fill:#E6E7E8;}
                                    .st2{fill:#FFFFFF;}
                                    .st1{fill:#FCBC86;}
                                </style>
            <g>
                <circle class="st0" cx="49.5" cy="20.4" r="60"/>
                <g class="g_class">
                    <polygon max="7" class="st st2" points="37.9,31.8 35.7,41.7 60.7,42.7 		"/>
                    <polygon max="14" class="st st2" points="60.8,43.5 49.4,48.9 36.7,42.6 		"/>
                    <polygon max="21" class="st st2" points="61.9,43.7 50.8,49.1 62.5,49.8 		"/>
                    <polygon max="28" class="st st2" points="62.5,41.6 63.3,49.8 72.1,50.7 		"/>
                    <polygon max="35" class="st st2" points="63,40.9 72.4,49.9 69.5,37.1 		"/>
                    <polygon max="42" class="st st2" points="70.4,37.4 73.2,50.4 83.7,46.2 		"/>
                    <polygon max="48" class="st st2" points="71,37 84.3,45.7 90.4,36.8 		"/>
                    <polygon max="55" class="st st2" points="70.3,36.2 89.4,36.1 73.4,28.2 		"/>
                    <polygon max="62" class="st st2" points="74.1,27.7 90.8,35.9 93.1,25 		"/>
                    <polygon max="69" class="st st2" points="73.9,26.9 93.1,24.3 88.4,11.6 		"/>
                    <polygon max="73" class="st st2" points="73.4,26.3 88,10.9 77.6,4.4 		"/>
                    <polygon max="80" class="st st2" points="76.6,5.3 72.5,26.9 60.5,24.7 		"/>
                    <polygon max="87" class="st st2" points="76.6,4 60.3,23.7 61.8,0.3 		"/>
                    <polygon max="90" class="st st2" points="61.1,0.3 59.5,24.3 12.7,2.9 		"/>
                    <path max="100" class="st st2" d="M16,5.3L-0.2,6.8l13.3,8.8l41.7,7.5L16,5.3z M15.4,10.5c-1,0-1.9-0.8-1.9-1.9s0.8-1.9,1.9-1.9c1,0,1.9,0.8,1.9,1.9S16.4,10.5,15.4,10.5z"/>
                    <polygon max="100" class="st st2" points="24.7,18.5 18.9,28.1 27.2,19 		"/>
                    <polygon max="100" class="st st2" points="30.9,19.7 25.1,29.3 33.4,20.2 		"/>
                    <polygon max="100" class="st st2" points="37.1,20.9 31.3,30.5 39.6,21.4 		"/>
                </g>
            </g>
                            </svg>

    </div>
    <div class="text-center">
        <p style="color: #818285;">{{trans('ebi.エビ成長度')}}</p>
    </div>

    <ul class="ul-detail ul_shrimp_status">
        <li>
            @php
                if ($current_aquaculture && $current_aquaculture->start_date){
                    $dateStart = $current_aquaculture -> start_date;
                    if ($current_aquaculture->completed_date){
                        $dateEnd = $current_aquaculture->completed_date;
                    }else{
                        $dateEnd = date("Y-m-d");
                    }

                    $days = (strtotime($dateEnd) - strtotime($dateStart)) / (60 * 60 * 24) + 1;
                    if ($days > 0){
                        $days = $days;
                    } else {
                        $days = "~";
                    }
                }else{
                    $days = '';
                }

                if ($shrimp){
                    $date_target = $shrimp->date_target;
                    $date_target  = date('m/d',strtotime($date_target));
                }else{
                    $date_target = '';
                }
            @endphp
            <small class="small-1">{{trans('ebi.養殖日数（日）')}} </small><span class="text-blue-chart">{{ $days }}</span>
        </li>
        <li>
            <small class="small-1">{{trans('ebi.最終測定日')}} </small><span class="text-blue-chart">{{ $date_target }}</span>
        </li>
        <li>
            <small class="small-1">{{trans('ebi.サイズ')}} </small><span class="text-blue-chart">{{ $shrimp?$shrimp->size:'' }}</span>
        </li>
        <li>
            <small class="small-1">{{trans('ebi.重量')}}</small><span class="text-blue-chart">{{ $shrimp?$shrimp->weight:'' }}</span>
        </li>
        @if($current_aquaculture->completed_date)
            <li>
                <small class="small-1">{{trans('ebi.エビ総量')}}</small><span class="text-blue-chart">{{ number_format($current_aquaculture->shipment_count,0) }}</span>
            </li>
            @php
                $shrimp_num = $current_aquaculture->shrimp_num;
                $shrimp_shipping = $current_aquaculture->shipment_count;
                if ($shrimp_num){
                    $shrimp_percent = ($shrimp_shipping / $shrimp_num)*100;
                }else{
                    $shrimp_percent = 0;
                }
                $shrimp_percent = number_format($shrimp_percent,2);
            @endphp
            <li>
                <small class="small-1">{{trans('ebi.生存率')}} （%）</small><span class="text-blue-chart">{{ $shrimp_percent }}</span>
            </li>
        @endif
        <li>
            <small class="small-1">{{trans('ebi.天気')}}</small><span class="weather text-blue-chart">-</span>
        </li>
        <li>
            <small class="small-1" style="width: 40%">{{trans('ebi.気温')}}</small><span class="temperature-over text-blue-chart" style="width: 60%">-</span>
        </li>
        <li>
            <small class="small-1" style="width: 40%">{{trans('ebi.水温')}}</small><span class="water-temperature text-blue-chart" style="width: 60%">-</span>
        </li>
        <li><!--
            // TODO DATEDIFF for completed -->
            <small class="small-1">{{trans('ebi.理想との比較')}}</small><span class="text-blue-chart">{{ $latest_shrimp_state && $threshold_grow?number_format($latest_shrimp_state->weight*100/$threshold_grow->weight).'%':'-' }}</span>
        </li>
    </ul>
</div>

@push('bottom')
    <script>

        $('document').ready(function(){
            getWeatherInfo();

            $('#datePicker').change(function(e){
                getWeatherInfo(e);
            });
        });

        function getWeatherInfo(e) {
            var date = null;
            if (e) {
                date = e.target.value;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{ CrudBooster::adminPath('getWeatherInfo') }}',
                data: {date: date, pondId : '{{$pondId}}'},
                dataType: 'json',
                success: function (item) {
                    if (item && item.weather){
                        var assetUrl = "{{asset('svg/0.svg')}}";
                        var img = "<img src=\"" + assetUrl.replace('0.svg', item.weather.weather + '.svg') + "\"  style=\"width: 25px;\">";
                        $('.weather').html(img);
                        $('.temperature-over').html(Math.round(item.weather.temperature_over) + "°C/" + Math.round(item.weather.temperature_under) + "°C");
                    }else{
                        $('.weather').html("-");
                        $('.temperature-over').html("-");
                    }
                    if (item && item.water_temp){
                        $('.water-temperature').html(Math.round(item.water_temp) + "°C/");
                    }else{
                        $('.water-temperature').html("-");
                    }
                },
                error: function (e) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@endpush