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
            <ul_>
                @for($i=0;$i<$a;$i++)
                @if($shipment_id[$i]==$trueship)
                <li class="li_"><a href="shipment_session?shipment_id={{$shipment_id[$i]}}" id="test" style="background: #39B556" class="m-maru"><p style="margin-top: 2px;margin-right: 1.5px">{{$i+1}}</p></a></li>
                <?php $times=$i+1 ?>
                @else
                <li class="li_"><a href="shipment_session?shipment_id={{$shipment_id[$i]}}" class="m-maru"><p style="margin-top: 2px;margin-right: 1.5px">{{$i+1}}</p></a></li>
                @endif
                @endfor
                @if($a==0)
                <?php  $times=0 ?>
                @endif
                <li class="li_"><a href="total?id={{$pond_id}}"  class="m-btn-square-shadow">TOTAL</a></li>
                
            </ul_>
        </div>
        <div class="section-middle">
            <div class="row">
                <div class="col-4">
                    
                    <div class="ike">
                        <img src="{{ asset('img/icon2/'.$ebi_ponds_id.'.png')}}">
                    </div>
                </div>
                <div class="col-8">
                    <div class="midashi2">ハーベスト日時
                    <div class="line" style="width: 78%"></div>
                    </div>
                    <div class="waku2">
                        <div class="contentA">
                            中間ハーベスト<span>{{$times}}</span>回目
                        </div>
                        <div class="contentB">
                        <span>{{$harvest_date}}</span>
                        </div>
                    </div>
                    <div class="waku3">
                        <div class="content1">
                            <div class="red_line2" style="width: 50%">売上</div>
                        </div>
                        <div class="content2">
                        <span>{{$sell}}</span>PHP
                        </div>
                        <div class="content3">
                            <div class="symbol_j">国内向</div>
                        </div>
                    </div>
                    
                    <div class="waku4">
                        <div class="contentSp">
                            <div class="orange_line2">エビ出荷数</div>
                            <span style="margin-left: -47%;">{{$num}}匹</span>
                        </div>
                        <div class="contentSu">
                            <div class="orange_line2" style="width: 39%">重量</div>
                            <span style="margin-left: -52%;">{{$amount}}kg</span>
                        </div>
                        <div class="contentAu">
                            <div class="orange_line2" style="width: 30%">ABW</div>
                           <span><a style="font-weight: lighter; font-size: 60%;margin-right: 11%;color: black;margin-left: -70%;"></a>{{$ebi_weight}}g</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    </body>
<script>
    var input = document.getElementById('test');
    input.onclick = function(){
        location.href = '{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('test_1')}}' ;
    };
    var input = document.getElementById('test2');
    input.onclick = function(){
        location.href = '{{\crocodicstudio\crudbooster\helpers\CRUDBooster::adminPath('test_2')}}' ;
    };
</script>

@endsection