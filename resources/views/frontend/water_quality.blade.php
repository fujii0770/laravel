@extends('crudbooster::admin_template')

@section('content')
    @include('partials/page_nav')
    <div class="row">
        @include('partials/frontend_sidebar')
        <div class="col-md-10 content-radio">
            <div class="water_wrap icon_title" style="min-height: calc(100vh - 190px)">
                <div class="title pl-0" style="padding-top: 5px;padding-left: 0">
                    <div class="row">
                        <div class="col-md-6 text-left"><p><img src="{{ asset('img/svg/icon_line.svg') }}"><span class="icon-text">{{trans('ebi.水質状況')}}</span></p></div>
                        <div class="col-md-6 p-button-change-chart_ text-right"><a href="{{CRUDBooster::adminPath("viewPond")."?pondId=".$pondId."&aquaId=".($current_aquaculture?$current_aquaculture->ponds_aquacultures_id:'')}}" class="btn-link change-chart-ph p-0"><img src="{{asset('./img/icon/icon_refresh.svg')}}" style=" width: 20px;"><span class="icon-text">{{trans('ebi.グラフ表示')}}</span></a></div>
                    </div>
                </div>

                {{--<div class="box-header">
                    <table width="100%">
                        <tr>
                            <td class="text-left text-blue-chart" width="33%">
                                @if ($current_aquaculture && (substr($current_aquaculture->start_date, 0, 7) <= ($previousYear.'-'. ($nextYear.'-'.str_pad($previousMonth, 2, '0', STR_PAD_LEFT)))))
                                    <a href="{{CRUDBooster::adminPath('viewWaterQuality?pondId='.$pondId.'&aquaId='.$aquaId.'&month='.$previousYear.$previousMonth)}}"><<&nbsp;&nbsp;{{ $previousYear }}.{{ $previousMonth }}</a>
                                @else
                                  --}}{{--  <<&nbsp;&nbsp;{{ $previousYear }}.{{ $previousMonth }}--}}{{--
                                @endif
                            </td>
                            <td class="text-center" width="34%"><span class="text-bold">{{ $selectedYear }}.{{ $selectedMonth }}</span></td>
                            <td class="text-right text-blue-chart" width="33%">
                                @if ($current_aquaculture && (($current_aquaculture->completed_date?substr($current_aquaculture->completed_date, 0, 7):date_format(now(), 'Y-m')) >= ($nextYear.'-'.str_pad($nextMonth, 2, '0', STR_PAD_LEFT))))
                                    <a href="{{CRUDBooster::adminPath('viewWaterQuality?pondId='.$pondId.'&aquaId='.$aquaId.'&month='.$nextYear.$nextMonth)}}">{{ $nextYear }}.{{ $nextMonth }}&nbsp;&nbsp;>></a>
                                @else
                                --}}{{--    {{ $nextYear }}.{{ $nextMonth }}&nbsp;&nbsp;>>--}}{{--
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>--}}
                <div class="box-body">
                    <table class="table-navigation table">
                        <tr>
                            <td  class="fixed-side"></td>
                            <td class="text-left text-blue-chart" width="33%">
                                @if ($current_aquaculture && (substr($current_aquaculture->start_date, 0, 7) <= ($previousYear.'-'. str_pad($previousMonth, 2, '0', STR_PAD_LEFT))))
                                    <a href="{{CRUDBooster::adminPath('viewWaterQuality?pondId='.$pondId.'&aquaId='.$aquaId.'&month='.$previousYear.$previousMonth)}}"><<&nbsp;&nbsp;{{ $previousYear }}.{{ $previousMonth }}</a>
                                @else
                                    {{--  <<&nbsp;&nbsp;{{ $previousYear }}.{{ $previousMonth }}--}}
                                @endif
                            </td>
                            <td class="text-center no-border"><span class="text-bold" style="font-size: 16px">{{ $selectedYear }}.{{ $selectedMonth }}</span></td>
                            <td class="text-right text-blue-chart" width="33%">
                                @if ($current_aquaculture && (($current_aquaculture->completed_date?substr($current_aquaculture->completed_date, 0, 7):date_format(now(), 'Y-m')) >= ($nextYear.'-'.str_pad($nextMonth, 2, '0', STR_PAD_LEFT))))
                                    <a href="{{CRUDBooster::adminPath('viewWaterQuality?pondId='.$pondId.'&aquaId='.$aquaId.'&month='.$nextYear.$nextMonth)}}">{{ $nextYear }}.{{ $nextMonth }}&nbsp;&nbsp;>></a>
                                @else
                                    {{--    {{ $nextYear }}.{{ $nextMonth }}&nbsp;&nbsp;>>--}}
                                @endif
                            </td>
                        </tr>
                    </table>
                    <div class="table-scroll">
                        <div class="zui-scroll">
                            <table class="zui-table chart-detail-table table table-hover table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <td class="fixed-side zui-sticky-col">&nbsp;</td>
                                        @for($i = 1; $i <= $dayOfMonth; $i++)
                                            <td style="border-top: 1px solid #a7a9ac">{{$i}}</td>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(App\Http\Controllers\Helper::getAllWaterCriteriaLabel() as $criteria => $label)
                                        <tr>
                                            <td class="fixed-side zui-sticky-col"><a href="{{ route('viewWaterQualityDetail').'?pondId='.$pondId.'&aquaId='.$aquaId.'&date='.$selectedYear.'-'.$selectedMonth.'-01'.'&c=' .$criteria}}">{{$label}}</a></td>
                                            @for($i = 1; $i <= $dayOfMonth; $i++)
                                                @php
                                                    $fullDate = $selectedYear.'-'.str_pad($selectedMonth, 2, '0', STR_PAD_LEFT).'-'.str_pad($i, 2, '0', STR_PAD_LEFT);
                                                @endphp
                                                @if ($current_aquaculture->start_date <= $fullDate && $fullDate <= ($current_aquaculture->completed_date?:date_format(now(), 'Y-m-d')))
                                                    <td>{{($pondStates[$criteria][$i]!==null && $pondStates[$criteria][$i]!=="")?$pondStates[$criteria][$i]:'-'}}</td>
                                                @else
                                                    <td style="background-color: lightgrey">&nbsp;</td>
                                                @endif
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br/>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('bottom')
    <script>
        $('document').ready(function(){
            var part = window.location.pathname.split("/").pop();
            console.log(part);
            if (part == "viewWaterQuality") {
                $( "#rdWater" ).change(function() {
                    $('.f1 input').prop('checked', false);
                    $(this).prop('checked', true);
                }).change();
            }
        });
    </script>
    <script src="{{ asset('js/front_end.js') }}?v=3"></script>
@endpush