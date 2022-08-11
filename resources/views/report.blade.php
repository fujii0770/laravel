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
     @for($c =0; $c <=27 ; $c++)
                
                                    
                
                    <?php   $totalsell +=$sell[$c];
                            $totalmedicine +=$medicine[$c];
                            $totalfeed +=$feed[$c];
                            $total_ebi_price +=$ebi_price[$c];
                            $total_syusi +=$syusi[$c];
                            
                            if($target_syusi[$c]<0 and $syusi[$c]<0){

                                if($target_syusi[$c]<$syusi[$c]){
                                    $taseiritu = (-1)*$syusi[$c]/$target_syusi[$c]*100;
                                }else{
                                    $sabun =   $target_syusi[$c]-$syusi[$c];
                                    $taseiritu=$sabun/$target_syusi[$c];
                                }

                            }elseif($target_syusi[$c]>0 and $syusi[$c]<0){
                                $mokuhyou= $target_syusi[$c];
                                $jixtuseki=$syusi[$c]-$target_syusi[$c];
                                $taseiritu=$jixtuseki/$mokuhyou*100;
                            }elseif($target_syusi[$c]<0 and $syusi[$c]>0){
                                $jixtuseki=$syusi[$c] - $target_syusi[$c];
                                $mokuhyou=$target_syusi[$c]*(-1);
                                $taseiritu=$jixtuseki/$mokuhyou*100;
                            }elseif($target_syusi[$c]>0 and $syusi[$c]>0){
                                $taseiritu=$syusi[$c]/$target_syusi[$c]*100;
                            }
                    
                    ?>
              
    @endfor


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
                    
                                        <th width='200'>{{trans('ebi.費用名')}}&nbsp; </th>
                                        <th width='200'>{{trans('ebi.金額')}}&nbsp; </th>   
                    </tr>
                    <tbody>
                
                    @for($c=0;$c<=$a;$c++)
                    <?php 
                    $total_cost +=$hiyou[$c];
                    $total_syusi -=$hiyou[$c];

                    ?>
                    <tr> 
                            <td width='200'><?php echo $cost_name[$c]?></td>
                            <td width='200'><?php echo $hiyou[$c]?></td>   
                    </tr>
                    @endfor
                    </tbody>
                </table> 
                <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr class="active">
                    
                                        <th width='200'>{{trans('ebi.総収支')}}&nbsp; </th>
                                        <th width='200'>{{trans('ebi.総売上')}} &nbsp; </th>   
                    </tr>
                    <tr> 
                            <td width='200'><?php echo  $total_syusi ?></td>
                            <td width='200'><?php echo  $totalsell ?></td>   
                    </tr>
                </table>
                                
            </div>
            
           
            <div class="col-md-8">
                <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                    <thead>
                    <tr class="active">
                                        <th width='150'>{{trans('ebi.餌費用')}}&nbsp;</th>
                                        <th width='150'>{{trans('ebi.薬費用')}}&nbsp;</th>
                                        <th width='200'>{{trans('ebi.売上実績')}} &nbsp;</th>
                                        <th width='200'>{{trans('ebi.目標収支')}}&nbsp;</th>
                                        <th width='200'>{{trans('ebi.収支実績')}}&nbsp;</th>
                                    
                </tr>
                
                @for($c =0; $c <=27 ; $c++)
                
                                    
                        
                       
                    <tr> 
                            <td><?php echo $feed[$c]?></td>
                            <td><?php echo $medicine[$c]?></td>
                            <td><?php echo $sell[$c]?></td>
                            <td><?php echo $target_syusi[$c]?></td>
                            <td><?php echo $syusi[$c]?></td>    
                    </tr>
                        
                 @endfor
                </table>                 
            </div>
           
            
        </div>  
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/chartjs-plugin-colorschemes"></script>
    <script>
        var ctx = $('#chart');
        var mychart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [
                    "{{trans('ebi.売上')}}",
                    "{{trans('ebi.餌費用')}}",
                    "{{trans('ebi.薬費用')}}",
                    "{{trans('ebi.稚エビ費用')}}",
                    "{{trans('ebi.その他費用')}}",
                ],
                datasets: [{
                    label: '収支',
                    data: [
                       <?php echo $totalsell ?>,
                       <?php echo $totalfeed ?>,
                       <?php echo $totalmedicine ?>,
                       <?php echo $total_ebi_price ?>,
                       <?php echo $total_cost ?>,
                    ],
                    backgroundColor: [
                        'rgba(241, 107, 141, 1)',
                        'rgba( 31, 167, 165, 1)',
                        'rgba(249, 199,  83, 1)',
                        'rgba(176, 110,  30, 1)',
                        'rgba(90, 10,  30, 1)',
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

@endsection