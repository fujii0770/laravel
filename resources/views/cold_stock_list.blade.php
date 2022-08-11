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
    <style type="text/css">
    th, td {
    text-align: center;
    width: 30%;
    border: 1px solid black;
    }
    table{
    width: 70%;
    border: 3px solid black;
    }
   
    </style>
                        <div class="panel-heading">
                            <strong></i>{{trans('ebi.冷凍在庫')}}</strong>
                            <hr class="bottom-hr w-100">
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <table id='table_dashboard' class="table table-hover table-striped" >
                            <thead>
                            <tr class="active">
                                    <th width=100>{{trans('ebi.エビ重量')}}(g)</th>
                                    <th width=100>{{trans('ebi.ストック')}}(kg)</th>
                                    <th width=100></th>             
                            </tr>
                            </thead>
                            <tbody>
                            @for($c=0;$c<=$i;$c++) 
                            <tr>
                                    <td><p><?php echo $ebi_weight[$c] ?></p></td>
                                    <td><?php echo $amount[$c] ?></td>
                                    <td><a href="cold_job?ebi_weight={{$ebi_weight[$c]}}&amount={{$amount[$c]}}">売上登録</a></td>
                            </tr>
                            @endfor
                            </tbody>
                        </table>
@endsection