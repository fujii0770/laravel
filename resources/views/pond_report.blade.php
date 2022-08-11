@extends('crudbooster::admin_template_with_left_sidebar')

@section('content')
<style type="text/css">
    thead, tbody {
    display: block;
    }
    tbody {
    overflow-x: hidden;
    overflow-y: scroll;
    height: 150px;
    }
    th, td {
    text-align: center;
    }
   
</style>


    <div id='box-statistic' class='row'>
        
        <div class="box-body no-padding">
                <div class="col-md-8">
                        <div style="width: 300px; margin:0 auto;">
                        <canvas id="chart" width="150" height="150" ></canvas>
                        </div>
                </div>
                 <div class="col-md-4">
                    <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr class="active">
                        
                                            <th width='200'>{{trans('ebi.売上')}}&nbsp; </th>
                                            <th width='200'>{{trans('ebi.出荷量(kg)')}}&nbsp; </th>
                                            <th width='200'>{{trans('ebi.エビ重量(g)')}}&nbsp; </th>   
                        </tr>
                        <tbody>
                    
                        @for($c=0;$c<=$f;$c++)
                        <tr> 
                                <td width='200'><?php echo $shipment_sell[$c]?></td>
                                <td width='200'><?php echo $shipment_amount[$c]?></td>  
                                <td width='200'><?php echo $shipment_ebi_weight[$c]?></td> 
                        </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            <div class="col-md-8">
                        <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr class="active">
                                            <th width='200'>{{trans('ebi.池名')}}&nbsp; </th>
                                            <th width='200'>{{trans('ebi.餌費用')}} &nbsp; </th>
                                            <th width='200'>{{trans('ebi.薬費用')}} &nbsp; </th>
                                            <th width='200'>{{trans('ebi.売上実績')}} &nbsp; </th>
                                            <th width='200'>{{trans('ebi.収支実績')}} &nbsp; </th>
                                            <th width='200'>{{trans('ebi.アクション')}} &nbsp; </th>
                                        
                    </tr>
                    
                    　 @for($c =0; $c <=27 ; $c++)
                    
                                        
                            @if($pond_name[$c])
                                <?php   $totalsell +=$sell[$c];
                                    $totalfeed +=$feed[$c];
                                    $totalmedicine +=$medicine[$c];
                                    $totalfeed +=$feed[$c];
                                    $total_ebi_price +=$ebi_price[$c]                
                                ?>
                                <tr>
                                <td><?php echo $pond_name[$c]?></td>
                                <td><?php echo $feed[$c]?></td>
                                <td><?php echo $medicine[$c]?></td>
                                <td><?php echo $sell[$c]?></td> 
                                <td><?php echo $syusi[$c]?></td>       
                                <td><a href="sell_add?pond_id={{$pond_id[$c]}}&pond_name={{$pond_name[$c]}}">{{trans('ebi.売上登録')}}<a></td>
                                
                                </tr>
                            @endif
                        
                        @endfor
                    </table>                 
            </div>
        </div>  
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
    <script>
        var ctx = $('#chart');
        var mychart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [
                    "{{trans('ebi.売上')}}",
                    "{{trans('ebi.餌費用')}}",
                    "{{trans('ebi.薬費用')}}",
                ],
                datasets: [{
                    label: '収支',
                    data: [
                       <?php echo $totalsell ?>,
                       <?php echo $totalfeed ?>,
                       <?php echo $totalmedicine ?>,
                       <?php echo $total_ebi_price ?>
                    ],
                    backgroundColor: [
                        'rgba(241, 107, 141, 1)',
                        'rgba( 31, 167, 165, 1)',
                        'rgba(249, 199,  83, 1)',
                    ]
                }]
            },
            options: {
                legend: {
                    position: 'bottom',
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            display: false,
                            beginAtZero: true,
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        }); 
    </script>
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

@endsection