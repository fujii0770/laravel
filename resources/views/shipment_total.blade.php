@extends('crudbooster::admin_template')

@push('head')
    <link href="{{ asset("css/backend.css")}}?v=6" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("css/ebi_feed.css")}}" rel="stylesheet" type="text/css"/>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
@endpush
@section('sidebar')
    @php
        $contentWrapperClass = 'content-wrapper';
        $ignoreFrontendCss = true;
    @endphp
    <!-- Sidebar -->
    @include('crudbooster::sidebar')
@endsection

@section('content')
    <div style="height: 68px;margin-top: -29px;margin-left: -5%;margin-right: -5%;" class="content-header">
        <div class="breadcrumb f5_page_nav">
            <div class="f1 f1_page_nav">
               
            </div>
        </div>
    </div>
    <body>
    <div class="container_">
        <div class="top_">
            <a href="{{CRUDBooster::adminPath('report')}}" class="logo_">池一覧へ戻る</a>
        </div>
        <div class="section-middle">
            <div class="row">
                <div class="col-4">
                    <div class="ike">
                    <img src="{{ asset('img/icon2/'.$ebi_ponds_id.'.png')}}">
                    </div>
                </div>
                <div class="col-8">
                    <div class="midashi2">育成期間</div>
                    <div class="line"></div>
                    <div class="waku2">
                        <div class="contentA">
                        </div>
                        <div class="contentB">
                            期間<span></span>({{$start_date}}~{{$completed_date}})
                        </div>
                    </div>
                    <div class="waku3">
                        <div class="content1">
                            <div class="red_line2">売上</div>
                        </div>
                        <div class="content2">
                            <span>{{$sell}}</span>PHP
                        </div>
                        
                    </div>
                    <div class="waku3">
                        <div class="content1">
                            <div class="red_line2" style="width: 69%">費用/利益</div>
                        </div>
                        <div class="content2">
                            <span>{{$cost}}</span>PHP<span>/{{$profit}}</span>PHP
                        </div>
                        <div class="content3">
                        </div>
                    </div>

                    <div class="waku4">
                        <div class="contentSp">
                            <div class="orange_line2" style="width: 29%; background: linear-gradient(transparent 50%, #ff7f7f 0%); margin-right: 4%">FCR</div>
                            <img style="margin-left: -39%;" src="{{ asset('img/icon2/ebi_fcr.png')}}" width="20%"><span>{{$fcr}}<div class="gcm">g/cm</div></span>
                            <div style="margin-top: -16%;">
                            <a href="" class="button-b-m" style="margin-left: -32%; padding-bottom: 1%;padding-top:2.5%;padding-bottom: 2.5%;">餌詳細</a>
                            </div>
                        </div>
                        <div class="contentSu">
                            <div class="orange_line2">ナノバブル</div>
                            @if($pond_buble==1)
                            <span style="margin-left: -46%;">有</span>
                            @else
                            <span style="margin-left: -46%;">無</span>
                            @endif
                        </div>
                        <div class="contentAu">
                            <div class="orange_line2" style="background: linear-gradient(transparent 50%, yellow 0%);">生存率</div>
                            <span style="margin-left: -39%">{{(int)$suv}}</span>%
                        </div>
                        <div class="contentWi2" style="width: 25%">
                            <div class="orange_line2" style="background: linear-gradient(transparent 50%, yellow 0%);">1/m3</div>
                            <div style="margin-left: -25%">初期値<span>{{$cubic_meter_num}}</span>匹</div>
                        </div>
                    </div>
                    <div class="ebinarabe">
                        @for($i=0;$i<=10;$i++)
                        @if($i<=$suv/10)
                        <img src="{{ asset('img/icon2/ebi.png')}}">
                        @else
                        <img src="{{ asset('img/icon2/ebi_touka.png')}}">
                        @endif
                        @endfor
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <?php $times=0; ?>
            @for($i=0;$i<$a;$i++)
            <?php $times++; ?>
            <div class="col-2">
                <div class="section_last__item_m">
                    <div class="midashi3">ハーベスト</div>
                    <div class="waku_chukan">
                        @if($cold_flg[$i]==0)
                        <div class="symbol_chukan_j">通常</div>
                        @else
                        <div class="symbol_chukan_j">冷凍</div>
                        @endif
                        <div class="kaisu"><span>{{$times}}</span><span style="font-size: 146%">回目</span></div>
                    </div>
                    <div class="red_line3">売上</div>
                    <div class="c_uriage"><span>{{$shipment_sell[$i]}}</span>PHP</div>
                    <div class="gyokan"><a href="shipment_session?shipment_id={{$shipment_id[$i]}}" class="button-b-m">詳細画面</a></div>
                    
                </div>
            </div>
            @endfor
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    </body>
    <script>
        var input = document.getElementById('test');
        input.onclick = function(){
            location.href = '{{CRUDBooster::adminPath('test_1')}}' ;
        };
        var input = document.getElementById('test2');
        input.onclick = function(){
            location.href = '{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('test_2')}}' ;
        };
    </script>

@endsection
