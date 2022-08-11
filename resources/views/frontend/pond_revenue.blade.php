
@push('head')
    <link href="{{ asset('css/ebi_revenue.css')}}?v=7" rel="stylesheet" type="text/css"/>
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
@endpush
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-7" style="flex: 0 0 auto; width: 58.3333333333%;">
                <div class="flex-container">
                    <div class='column--l'>
                        <h1 class="midashi">
                            <span style="font-size:300%; font-weight: 700; margin-left: 20px;" id="Month" ></span>
                            {{$month}}月の収支
                            <i class="fas fa-calendar-alt date-picker"  id="startDate" name="startDate" ></i>
                        </h1>
                    </div>
                    <div class='column--r'>
                        <div class="image2">
                        <p>今月のMBW：<?php echo (int)$mbw ?> <img src="{{ asset('img/ebi.png')}}" style="width:15%" ></p>
                        <img src="{{ asset('img/hukidashi.png')}}" style="width:70%">
                        </div>
                    </div>
                </div>
                <div class="image">
                 <img src="{{asset('img/pool.png')}}" style="width:85%">
                 <div class="p2">今月の売上</div>
                 <p><?php echo $total_sell ?><span style="font-size:40%; font-weight: 700;"></span></p>
                </div>
                <div class="tate">内訳</div>
                <div class="wrapper_">
                    @for($a = 0; $a < $i; $a++)
                    <div class="waku">
                        <div class="contentA"><div class="ebi_midashi">＼<?php echo $ebi_weight[$a] ?>g／</div><img src="{{asset('img/ebi.png')}}" style="width:70%"></div>
                        <div class="contentB">
                            <div class="title1"><span>出荷量<?php echo $amount[$ebi_weight[$a]] ?>kg</span></div>
                            <div class="title3"><br>売上<?php echo $sell[$ebi_weight[$a]] ?></div>
                        </div>
                    </div>
                    @endfor
                    @for($a=0; $a<$b; $a++)
                    <div class="waku">
                        <div class="contentA"><div class="ebi_reito">\<?php echo $cold_ebi_weight[$a] ?>g／</div><img src="{{ asset('img/ebi_reto.png')}}" style="width:70%"></div>
                        <div class="contentB">
                            <div class="title1"><span>出荷量<?php echo $cold_amount[$cold_ebi_weight[$a]] ?>kg</span></div>
                            <div class="title3"><br>売上<?php echo $cold_sell[$cold_ebi_weight[$a]] ?></div>
                        </div>
                    </div>
                    @endfor
                  </div>
            </div>
            <div class="col-md-5">
                <div class="image">
                    <div class="position2"><img src="{{asset('img/kanban.png')}}" style="width:100%"></div>
                    <div class="p3">利益</div>
                    <div class="p4"><?php  echo $total_sell-$total_drug-$total_feed-$ebi_price-$cost ?><span style="font-size:40%; font-weight: 700;"></span></div>
                    <div class="col- ml-3">
                        <table class="p5 border-primary table_ table-bordered border table-sm" style="width:65%;">
                            <thead style="">
                                <tr style="text-align: center;">
                                    <td style="width:40%;">売上</td>
                                    <td style="width:60%;"><?php  echo $total_sell ?></td>
                                </tr>
                            </thead>
                            <tbody >
                                <tr style="text-align: center;">
                                    <td>薬費用</td>
                                    <td><?php  echo $total_drug ?></td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td>餌費用</td>
                                    <td><?php  echo $total_feed ?></td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td>稚エビ費用</td>
                                    <td><?php  echo $ebi_price ?></td>
                                </tr>
                                <tr style="text-align: center;">
                                    <td>その他費用</td>
                                    <td><?php  echo $cost ?></td>
                                </tr>
                            </tbody>
                        </table>
                        {{--<div class="p6"><button type="button" class="btn btn-success">費用の詳細を見る</button></div>--}}
                    <div class="p7"><span style="font-size:60%; font-weight: 600;">目標MBW:</span>25g</div>
                    <div class="position"><img src="{{asset('img/ebihako.png')}}" style="width:90%" ></div>                
                </div>
            </div>
        </div>
    </div>
    </div>
<script type="text/javascript">
    var Month = new Date();
    
    //document.getElementById('Month1').innerHTML = '<h4>' + (Month.getFullYear())+'年'+(Month.getMonth() + 1)+'月</h4>';
    
    $(function() {
        $('.date-picker').datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            format: "mm-yyyy",
            startView: "months",
            minViewMode: "months",
            onClose: function(dateText, inst) {
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        }).on('changeDate', function(){
            var time = new Date($(this).datepicker('getDate'));
            var month= time.getMonth() + 1;
            var year= time.getFullYear();
            if(1<=month&&month<=12){
               // document.getElementById('Month').innerHTML = '<span>' + month + '</span>';
                //document.getElementById('Month1').innerHTML = '<h4>' +year+'年'+month+'月</h4>'; 
            }
            location.href = "{{ route('month_report') }}?year="+year+"&month="+month;
        });
        
    });
    // dp.on('changeMonth', function (e) {
    //     console.log('hello')
    //     e.preventDefault();
    // });
</script>
</body>
   

