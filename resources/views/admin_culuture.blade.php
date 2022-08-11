@extends('crudbooster::admin_template')

@push('head')
    <link href="{{ asset("css/backend.css")}}?v=6" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("css/frontend.css")}}?v=1" rel="stylesheet" type="text/css"/>
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
    <div style="height: 68px" class="content-header">
        <div class="breadcrumb f5_page_nav">
            <div class="f1 f1_page_nav">
                <button style="margin-top: 10px;margin-left: 3.5% " onclick=""></button>
                
            </div>
        </div>
    </div>
    <body>
        <div class="top" style="padding: 1%;">
            <input type="checkbox" onchange="status(this.value)" name="status1" id="status" value="3"><div class="top-menu1">全</div>
            <input type="checkbox" onchange="status(this.value)" name="status1" id="status" value="0"><div class="top-menu2">未完了</div>
            <input type="checkbox" onchange="status(this.value)" name="status2" id="status" value="1"><div class="top-menu1">完了</div>
        </div>
        <div class="section">
            @for($i=0;$i<$a;$i++)
            <?php $pond_id=$id[$i] ?>
            <div class="section__item">
                <div class="row">
                    <div class="col-2">
                        @if($status[$pond_id]==0)
                        <div class="pink_line">
                        @else
                        <div class="green_line">
                        @endif
                        <?php echo $pond_name[$pond_id] ?></div>
                    </div>
                    <div class="col-4"><div class="kikan">期間<span style="font-size:150%; font-weight: 700;"></span><?php echo "($start_date[$pond_id]~$completed_date[$pond_id])" ?></div></div>
                    <div class="col-2">
                        <div class="wrap">
                            <div class="content_ red_line">現在の売上</div>
                            <div class="content_"><span><?php echo $sell[$pond_id] ?></span>PHP</div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="grid-container">
                            <?php $b=0; ?>
                            @for($c=0;$c<$shipment_times[$pond_id];$c++)
                                <?php $b++;  $ship=$shipment_id[$pond_id][$c]?>
                                @if($b==0)
                                    <a id="test" href="shipment_session?shipment_id={{$ship}}"class="maru"><p style="margin-top: 2px;margin-right: 1.5px"> <?php echo $b; ?></p></a>
                                @else
                                    <a href="shipment_session?shipment_id={{$ship}}" class="maru"><p style="margin-top: 2px;margin-right: 1.5px"><?php echo $b; ?></p></a>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="col-2">
                        <a href="total?id={{$pond_id}}" id="test2" class="btn-square-shadow">TOTAL</a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
    </body>
    <script>
        function status(value){
                if(value==3){
                    location.href = "{{ route('report') }}";
                }else{
                    location.href = "{{ route('report') }}?status=" +value;
                }

        }
        function pond(value){
            
            location.href = "{{ route('report') }}?pond_name="+value;

        }
    </script>
@endsection