<div class="col-md-2 menu-sidebar-pong-water f5_page_sidebar">
    <div style="position: relative; height: calc(100vh - 130px)">
        <ul>
            @if(!Request::is('admin/ponds_aquacultures'))
                <li class="li-only-text">{{trans('ebi.養殖環境')}}</li>
            @endif
            <li class="li-down" style="padding: 0px">
                <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#drop-li">
                    <small class="text-left" style="width: 40%">{{trans('ebi.養殖開始日')}} </small>
                    <span style="width: 50%; margin-right: 10px;">
                        @if($current_aquaculture->start_date)
                            {{app()->getLocale() == "en" ?date("m.d.Y", strtotime($current_aquaculture->start_date)):str_replace('-','.', $current_aquaculture->start_date)}}
                        @endif
                    </span>
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
                <div id="drop-li" class="ul-vertical collapse text-center ftDate">
                    <form action="" name="ftDate">
                        <input id="aquaId" type="hidden" value="" name="aquaId">
                        <input id="farmId" type="hidden" value="{{$farmId}}" name="farmId">
                        <input id="pondId" type="hidden" value="{{$pondId}}" name="pondId">
                    </form>
                    @if($aquacultures)
                        @foreach($aquacultures as $key => $item)
                            @if(app()->getLocale() == "en")
                            <p data-id="{{$item->ponds_aquacultures_id?:$item->id}}">{{ date("m.d.yy", strtotime($item->start_date)) }} 〜 {{ $item->completed_date?date("m.d.yy", strtotime($item->completed_date)) :date("m.d.yy", strtotime($item->estimate_shipping_date))}} </p>
                            @else
                                <p data-id="{{$item->ponds_aquacultures_id?:$item->id}}">{{ str_replace('-','.', $item->start_date) }} 〜 {{ $item->completed_date?str_replace('-','.', $item->completed_date):str_replace('-','.', $item->estimate_shipping_date )}} </p>
                            @endif
                        @endforeach
                    @endif
                </div>
            </li>

            <li>
                @if(app()->getLocale() == "en")
                    @if( $current_aquaculture->completed_date)
                        <small style="width: 40%">{{ trans('ebi.出荷日') }} </small>
                        <span style="width: 60%">{{ date("m.d.yy", strtotime( $current_aquaculture->completed_date)) }}</span>
                    @elseif ($current_aquaculture->estimate_shipping_date)
                        <small style="width: 40%">{{ trans('ebi.出荷予定日') }} </small>
                        <span style="width: 60%">{{ date("m.d.yy", strtotime($current_aquaculture->estimate_shipping_date)) }}</span>
                    @else
                        <small style="width: 40%">{{ trans('ebi.出荷予定日') }} </small>
                    @endif
                @else
                    <small style="width: 40%">{{ $current_aquaculture->completed_date?trans('ebi.出荷日'):trans('ebi.出荷予定日')}} </small><span style="width: 60%">{{ $current_aquaculture->completed_date?str_replace('-','.', $current_aquaculture->completed_date):str_replace('-','.', $current_aquaculture->estimate_shipping_date ) }}</span>
                @endif
            </li>

            @if(Request::is('admin/ponds_aquacultures'))
                @php
                    $ebiSales = 0;
                    $grossWeight = 0;
                    $dayNo = 0;
                    if ($current_aquaculture){
                        if ($current_aquaculture->completed_date){
                            $dayNo = \Carbon\Carbon::createFromFormat('Y-m-d', $current_aquaculture->completed_date)->diffInDays(\Carbon\Carbon::createFromFormat('Y-m-d', $current_aquaculture->start_date)) + 1;
                        }else{
                            $dayNo = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::createFromFormat('Y-m-d', $current_aquaculture->start_date)) + 1;
                        }
                    }
                @endphp
                @foreach($result as $pondAquaculture)
                    @php
                        $ebiSales += $pondAquaculture->sell;
                        $grossWeight += ($pondAquaculture->last_shrimp_weight*$pondAquaculture->ebi_remaining)/1000;
                    @endphp
                @endforeach
                {{--<li><small>{{trans('ebi.売上').' ('.trans('ebi.円').')'}}</small><span>{{ number_format($ebiSales)}} </span></li>--}}
                {{--<li><small>{{trans('ebi.総重量').' (kg)'}}</small><span>{{ number_format($grossWeight)}} </span></li>--}}
                {{--<li><small>{{trans('ebi.平均単価').' ('.trans('ebi.円').')'}}</small><span>{{ number_format($current_aquaculture->average_price)}} </span></li>--}}
                {{--<li><small>{{trans('ebi.平均成長度').' (%)'}}</small><span></span></li>--}}
                {{--<li><small>{{trans('ebi.日数')}}</small><span>{{$dayNo}} </span></li>--}}
            @else
                <li class="li-only-text"><small>{{trans('ebi.養殖方法')}} </small><span style="font-size: 14px">{{ \App\Http\Controllers\Helper::toPondMethodLabel($pond_farm->pond_method) }}</span></li>
                <li><small>{{trans('ebi.稚エビ数(匹)')}}</small><span>{{ $current_aquaculture->shrimp_num }}</span></li>

                <li class="li-only-text">{{trans('ebi.現在値')}}</li>
                <li><small>{{trans('ebi.消費電力総量(Wh)')}}</small><span>{{ $current_aquaculture->power_consumption }} </span></li>
                <li><small>{{trans('ebi.予想売上')}}</small><span>{{ $current_aquaculture->ponds_aquacultures_sell + ($unitPrice * $latest_shrimp_state->weight * $current_aquaculture->ponds_aquacultures_ebi_remaining)/1000}} </span></li>
                <li><small>{{trans('ebi.総重量')}}</small><span>{{ $latest_shrimp_state->weight * $current_aquaculture->ponds_aquacultures_ebi_remaining/1000 }} </span></li>
                <li><small>{{trans('ebi.単価')}}</small><span>{{ $unitPrice }} </span></li>
                <li><small>{{trans('ebi.売上')}}</small><span>{{ $current_aquaculture->sell}} </span></li>
            @endif
        </ul>
    </div>

</div>
