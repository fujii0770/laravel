@extends('crudbooster::admin_template')

@push('head')
    <link href="{{ asset("css/backend.css")}}?v=6" rel="stylesheet" type="text/css"/>
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



    <div id='box-statistic' class='row'>
        
        <div class="box-body no-padding">
            <div class="col-md-9">
                    <div style="width: 300px; margin:0 auto;">
                    <canvas id="chart" width="150" height="150" ></canvas>
                    </div>
                <table id='table_dashboard' class="table table-hover table-striped table-bordered">
                <thead>
                <tr class="active">
                <th width='200'>{{trans('ebi.池名')}}&nbsp; </th>
                <th width='200'>{{trans('ebi.餌費用')}} &nbsp; </th>
                <th width='200'>{{trans('ebi.薬費用')}} &nbsp; </th>
                <th width='200'>{{trans('ebi.売上実績')}} &nbsp; </th>
                <th width='200'>{{trans('ebi.収支実績')}} &nbsp; </th>
                                   
             </tr>
             
             　 @for($c =0; $c <=27 ; $c++)
             
                                
                    @if($pond_name[$c])
                    <?php   $totalsell +=$sell[$c];
                            $totalfeed +=$feed[$c];
                            $totalmedicine +=$medicine[$c];
                            $totalfeed +=$feed[$c];               
                    ?>
                <tr>
                        <td><a href="report_pond?pond_id={{$pond_id[$c]}}&year={{$year}}&month={{$month}}"><?php echo $pond_name[$c]?><a></td>
                        <td><?php echo $feed[$c]?></td>
                        <td><?php echo $medicine[$c]?></td>
                        <td><?php echo $sell[$c]?></td>
                        <td><?php echo $syusi[$c]?></td> 
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
                    "{{trans('ebi.稚エビ費用')}}",
                ],
                datasets: [{
                    label: '収支',
                    data: [
                       <?php echo $totalsell ?>,
                       <?php echo $totalfeed ?>,
                       <?php echo $totalmedicine ?>,
                       <?php echo $ebi_price ?>,
                    ],
                    backgroundColor: [
                        'rgba(241, 107, 141, 1)',
                        'rgba( 31, 167, 165, 1)',
                        'rgba(249, 199,  83, 1)',
                        'rgba(176, 110,  30, 1)',
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