@extends('crudbooster::admin_template_with_left_sidebar')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>{{trans("ebi.インポートした結果")}}</h2>
            <hr class="bottom-hr w-100">
        </div>        
        <div class="panel-body" style="padding:20px 10px 0px 10px">
            <table class="table" >
                <thead>
                <tr>
                    <th scope="col">{{trans("ebi.行目")}}</th>
                    <th scope="col">{{trans("ebi.エラー内容")}}</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $totalSuccess = 0;
                    $totalFailure = 0;
                    $message = $result!==null?'':($error?$error:trans("ebi.エラーが発生しました。"));
                @endphp

                @if ($result)
                    @foreach($result as $item => $key)
                        @php
                            $data = $key['stt'];
                            if ($data == 0){
                                $stt = trans('ebi.エラーが発生しました。');
                                $classStt = "error";
                                $totalFailure++;
                            }else{
                                $stt = trans('ebi.インポートが完了しました。');
                                $classStt = "success";
                                $totalSuccess++;
                            }
                        @endphp
                        @if($data == 0)
                            <tr class="{{ $classStt }}">
                                <td>{{ $key['row'] }}</td>
                                <td>{{ $key['kq'] }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">{!! $message?$message.'<br/>':'' !!}
                        @if($unmapping) {{ trans('ebi.msg_unmapping_pond_state',['count_unmapping'=>$unmapping]) }}  <br/> @endif
                        @if($totalFailure)
                            {{ trans('ebi.エラーが発生しました。') }} <br/>
                        @elseif($totalSuccess || isset($total['totalInsertSuccess']))
                            {{ trans('ebi.インポートが完了しました。') }} <br/>
                        @endif
                        {{trans("ebi.合計")}}: {{(isset($total) && isset($total['totalInsert']) )?$total['totalInsert']:number_format($totalSuccess + $totalFailure)}}<br/>
                        {{trans("ebi.成功件数")}}: {{(isset($total) && isset($total['totalInsertSuccess']) )?$total['totalInsertSuccess']:number_format($totalSuccess - ($unmapping?$unmapping:0))}}<br/>
                        {{trans("ebi.失敗件数")}}: {{(isset($total) && isset($total['totalInsertFail']) )?$total['totalInsertFail']:number_format($totalFailure + ($unmapping?$unmapping:0))}}<br/>
                    </td>
                </tr>
                @if ($unmapping)
                    <tr>
                        <td colspan="2"><div class="col-md-1" style="padding-right: 0; padding-left: 0">{{trans("ebi.エラー発生行：")}}</div><div class="col-md-11">
                            @foreach($unmappingItems as $item)
                                @if ($item->gps_lat_values && $item->gps_lat_values)
                                    {{$item->line_no}}{{trans("ebi.行目_GPS設定範囲外")}}<br/>
                                @else
                                    {{$item->line_no}}{{trans("ebi.行目_GPS不正")}}<br/>
                                @endif
                            @endforeach
                            </div>
                        </td>
                    </tr>
                @endif
                </tfoot>
            </table>
        </div>
        <div class="box-footer" style="background: #F5F5F5">
            <div class='pull-left'>
                <a href="{{$import_type == 'import_bait' ? CRUDBooster::adminPath('import_bait') :($import_type == 'import_drug' ? CRUDBooster::adminPath('import_drug') :CRUDBooster::adminPath('import_logs'))}}" class="btn btn-success">{{trans("ebi.戻る")}} </a>
            </div>
        </div><!-- /.box-footer-->
    </div>
@endsection
@push('head')
    <style>
        .error {
            background: #ffc177;
        }

        .success {
            background: #21800a;
        }

        thead {
            background: #3c8dbc;
            color: #fff;
        }
    </style>
@endpush