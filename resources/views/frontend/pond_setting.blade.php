@extends('crudbooster::admin_template_with_left_sidebar')
@push('head')
<style>
    .pond-abnormal{
        fill: #de773f !important;;
    }
    .clickable{
        cursor: pointer;
    }
    .panel-heading {
        padding-left: 0;
        background-color: white;
        color: #9bbce0;
        border-color: white;
        padding-left: 15px;
    }
    .f5_content{
        width: 100%;
    }
    .f5_tab_status {
        top: 270px;
        right: 150px;
    }
</style>
@endpush
@section('content')
    <div class="f5_content">
        <div class="mapView">
            <div class="panel-heading">
                <strong> {{trans('ebi.基本情報設定')}} </strong>
                <hr class="bottom-hr w-100">
            </div>
            <div>
                @include('frontend.farm_svg')
            </div>
            <div class="f5_tab_status">
                <ul class="f5_status">
                    <li><span class="unregistered-pond"></span>{{trans('ebi.登録されない池')}}</li>
                    <li><span class="st7"></span>{{trans('ebi.特別池')}} </li>
                    <li><span class="pond-has-been-created"></span>{{trans('ebi.池が登録されました')}}</li>
                </ul>
            </div>  
        </div>
    </div>
@endsection

@push('bottom')
    <script type="text/javascript">
        var special_ponds = [];
        @foreach(\App\Http\Controllers\AppConst::SPECIAL_POND as $p)
            special_ponds.push('{{$p}}');
        @endforeach
        function applyClickEvent(element){
            var pondId = $(element).attr('data-id');
            if (pondId){
                location.href = "{{ CRUDBooster::adminPath('ponds/edit') }}/" + pondId; //+ '?return_url=' + encodeURIComponent(this.location.href)
            }else{
                location.href = "{{ CRUDBooster::adminPath('ponds/add') }}?elId=" + $(element).attr('id');//+ '&return_url=' + encodeURIComponent(this.location.href)
            }
        }

        $('document').ready(function(){
            $.ajax({
                url: "{{CRUDBooster::adminPath('listAllPonds')}}",
                type: "get",
                dataType: "json",
                success: function (data) {
                    $('.mapView .pond').attr("class", 'pond unregistered-pond clickable');
                    $('.mapView .storage').attr("class", 'storage st7 clickable');
                    for(var i = 0; i < data.length; i++){
                        if (data[i].pond_image_area){
                            $('.mapView #' + data[i].pond_image_area).html("<title>" + data[i].name + "</title>");

                            $('.mapView #' + data[i].pond_image_area).attr("class", 'pond pond-has-been-created clickable');
                            $('.mapView #' + data[i].pond_image_area).attr("data-id", data[i].id);
                            if(special_ponds.includes(data[i].pond_image_area)){
                                $('.mapView #' + data[i].pond_image_area).addClass('st7');
                                $('.mapView #' + data[i].pond_image_area).removeClass('pond-has-been-created');
                            }
                        }
                    }
                    $('.mapView .pond').click(function () {
                        applyClickEvent(this);
                    });
                    $('.mapView .storage').click(function () {
                        applyClickEvent(this);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

        });
    </script>
@endpush
