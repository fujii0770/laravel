@extends('crudbooster::admin_template_with_left_sidebar')
@push('head')
<style>
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
    .farm-name{
        padding: 30px 0px 15px 0px;
    }
    #add-farm{
        display: flex;
    }

    .f5_tab_status {
        top: 410px;
        right: 100px;
    }
    .f5_content{
        width: 100%;
    }

</style>
@endpush
@section('content')
    <div class="f5_content">
        <div class="mapView">
            <div class="panel-heading">
                <div id="message">
                </div>
                <strong> {{trans('ebi.基本情報設定')}} </strong>
                <hr class="bottom-hr w-100">
                <div class="form-group farm-name row">
                    <label class="control-label col-sm-2"> {{trans('ebi.養殖場名')}} <span class="text-danger" title="This field is required">*</span> </label>
                    <div class="col-sm-3">
                        <select id="fram-choose" class="column-title form-control" name="farm-select">
                            @foreach($farmList as $list)
                                <option value='{{ $list->id }}'  >{{ $list->farm_name }}</option>
                            @endforeach
                            <option value="0" class="option-add" id="action-add" style="display: none"> {{trans('ebi.add_Farm')}}</option>
                        </select>
                        <div id='add-farm' style="display: none">
                            <input id='new-farm' class='form-control' required>
                            <button class='btn btn-primary btn-submit' id='addFarm'type="submit">{{ trans('ebi.btn_add')}}</button>
                            <button class='btn btn-primary btn-submit' id='saveFarm'type="submit">{{ trans('crudbooster.button_save')}}</button>
                            <button class='btn btn-danger btn-submit' id='deleteFarm'type="submit">{{ trans('crudbooster.action_delete_data')}}</button>
                            
                        </div>
                        <span style="display: none" class="text-danger required-name">{{ trans('ebi.validate_required')}}</span>
                    </div>
                </div>
            </div>          
            <!-- modal confirm -->
            <div id="modal-confirm-alert" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-body" style="text-align: center;">
                            <p style="font-size: 40px; border: 1px solid #6eb0ce; width: 60px; height: 60px; text-align: center; line-height: 60px; border-radius: 50%; color: #6eb0ce; margin: 0 auto;">
                                <i class="fa fa-info"></i>
                            </p>
                            <p style="margin: 15px 0; font-size: 16px; font-weight: bold; display: none;" class="confirm-add-pond">{{trans('ebi.confirm_add_pond')}}</p>
                            <p style="margin: 15px 0; font-size: 16px; font-weight: bold; display: none;" class="confirm-delete-pond">{{trans('ebi.confirm_delete_pond')}}</p>
                            <div class="control" style="color: #fff;">
                                <div class="btn btn-update" style="background: #9bbb59; margin-right: 10px;">{{ trans('crudbooster.confirmation_yes') }}</div>
                                <div class="btn btn-cancel" style="background: #a6a6a6; margin-left: 10px;" data-dismiss="modal">{{ trans('crudbooster.confirmation_no') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div>
                @include('frontend.farm_svg')
            </div>
            <div class="f5_tab_status">
                <ul class="f5_status">
                    <li><span class="unregistered-pond"></span>{{trans('ebi.登録されない池')}}</li>
                    <li><span class="st7"></span>{{trans('ebi.特別池（この池が選択不可）')}} </li>
                    <li><span class="pond-other-farm"></span>{{trans('ebi.他の養殖場に紐づく池')}}</li>
                    <li><span class="pond-current-farm"></span>{{trans('ebi.この養殖場に紐づく池')}}</li>
                    <li><span class="pond-has-been-created"></span>{{trans('ebi.池を選択可能')}}</li>
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
            $(".btn-update").unbind( "click" );
            $("#modal-confirm-alert").modal('show');
            var farmCurrent = $(element).attr('data-farmCurrent');
            var pondId = $(element).attr('data-id');
            var farmId = null;

            if($(element).attr('data-farmId')){
                var farmId = $(element).attr('data-farmId');
            }
            $(".btn-update").click(function(){
                $(element).attr("data-farmId", null);
                $.ajax({
                    url: "{{ route('addPondsByFarms') }}",
                    data: {pondId: pondId, farmId: farmId},
                    type: "post", dataType: 'json',
                    success: function(data){
                        if(data == 1){
                            $("#modal-confirm-alert").modal('hide');
                            listPondByFarms(farmCurrent);
                        }
                    }
                })
            });

        }
        function listPondByFarms(element){
            $.ajax({
                url: "{{CRUDBooster::adminPath('listAllPonds')}}",
                type: "get",
                dataType: "json",
                success: function (data) {
                    $( ".mapView .pond").unbind( "click" );
                    $('.mapView .pond').attr("class", 'pond unregistered-pond clickable');
                    $('.mapView .pond').attr("data-farmCurrent", element);
                    for(var i = 0; i < data.length; i++){
                        if (data[i].pond_image_area){
                            $('.mapView #' + data[i].pond_image_area).html("<title>" + data[i].name + "</title>");
                            if(data[i].farm_id == element){
                                $('.mapView #' + data[i].pond_image_area).attr("class", 'pond pond-current-farm delete-pond clickable');
                                $('.mapView #' + data[i].pond_image_area).attr("data-id", data[i].id);
                            }else{
                                if(data[i].farm_id == null){
                                    if(element == 0){
                                        $('.mapView #' + data[i].pond_image_area).attr("class", 'pond clickable pond-has-been-created');
                                    }else{
                                        $('.mapView #' + data[i].pond_image_area).attr("class", 'pond clickable pond-has-been-created pond-selected');
                                    }

                                    if(special_ponds.includes(data[i].pond_image_area)){
                                        $('.mapView #' + data[i].pond_image_area).addClass('st7');
                                        $('.mapView #' + data[i].pond_image_area).removeClass('pond-has-been-created pond-selected');
                                    }
                                    $('.mapView #' + data[i].pond_image_area).attr("data-id", data[i].id);
                                    $('.mapView #' + data[i].pond_image_area).attr("data-farmId", element);
                                }else{
                                    $('.mapView #' + data[i].pond_image_area).attr("class", 'pond pond-other-farm clickable');
                                }
                            }
                        }
                    }

                   
                    $('.delete-pond').click(function () {
                        $('.confirm-add-pond').hide();
                        $('.confirm-delete-pond').show();
                        applyClickEvent(this);
                    });

                    $('.pond-selected').click(function () {
                        $('.confirm-delete-pond').hide();
                        $('.confirm-add-pond').show();
                        applyClickEvent(this);
                    });
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });

        }

        $('document').ready(function(){
            var farmNameSelected = $('#fram-choose').val();
            var countFarm = '{!! count($farmList) !!}';
            if(countFarm < 6){
                $('#action-add').show();
            }else{
                $('#action-add').hide();
            }   
            listPondByFarms(farmNameSelected);
            $('#fram-choose').on('click',function(ev){
                if(ev.offsetY < 0){
                    $("#message").removeClass("alert alert-warning");
                    $("#message span").remove()
                    $('#add-farm').show();
                    var text = $('#fram-choose :selected').text();
                    if($(this).val() == 0){
                        $('#addFarm').show();
                        $('#deleteFarm').hide();
                        $('#saveFarm').hide();
                        $('#new-farm').val('');
                        listPondByFarms($(this).val());
                    }else{
                        $('#saveFarm').show();
                        $('#deleteFarm').show();
                        $('#addFarm').hide();
                        $('#new-farm').val(text);
                        listPondByFarms($(this).val());
                    }
                }
            });
          
            $("#addFarm").click(function(){
                $("#message").removeClass("alert alert-warning");
                $("#message").removeClass("alert alert-success");
                $("#message span").remove();
                var farmName = $('#new-farm').val();
                checkRequired(farmName);
                $.ajax({
                    url: "{{ route('ajaxCreateFarm') }}",
                    data: {farm_name: farmName},
                    type: "post", dataType: 'json',
                    success: function(data){
                        if(data == 1){
                            $('#add-farm').hide();
                            $.ajax({
                                url: "{{CRUDBooster::adminPath('getFarmLastAdd')}}",
                                type: "get",
                                dataType: "json",
                                success: function (data) {
                                    listPondByFarms(data.farmList.id);
                                    $('.option-add').before("<option selected value= '"+data.farmList.id+"' > "+data.farmList.farm_name +"</option>")
                                    if(data.countFarm < 6){
                                        $('#action-add').show();
                                    }else{
                                        $('#action-add').hide();
                                    }  
                                }
                            })
                        }else{
                            if(data.message){
                                $("#message").append("<span class='label label-important'>"+data.message+'</span>');
                                $("#message").addClass("alert alert-warning");
                                if(data.message == '{!! trans("ebi.養殖場の最大数は6つです、もっと追加できません。") !!}'){
                                    $('#add-farm').hide();
                                    $('#action-add').hide();
                                    var valueSelect = $('#fram-choose').find("option:first-child").val();
                                    $('#fram-choose').val(valueSelect);
                                    listPondByFarms(valueSelect);
                                }
                            }
                        }
                    }
                })

            });  

            $("#saveFarm").click(function(){
                $("#message").removeClass("alert alert-warning");
                $("#message").removeClass("alert alert-success");
                $("#message span").remove();
                var farmName = $('#new-farm').val();
                farmId = $('#fram-choose').val();
                checkRequired(farmName);
                $.ajax({
                    url: "{{ route('ajaxUpdateFarm') }}",
                    data: {farm_name: farmName,id:farmId},
                    type: "post", dataType: 'json',
                    success: function(data){
                        if(data == 1){
                            $('#add-farm').hide();
                            $('#fram-choose :selected').text(farmName);
                            $("#fram-choose").val(farmId)
                            .find("option[value=" + farmId +"]").attr('selected', true);
                        }else{
                            if(data.message){
                                $("#message").append("<span class='label label-important'>"+data.message.farm_name+'</span>');
                                $("#message").addClass("alert alert-warning");
                            }
                        }
                    }
                })
            });            
            
            $("#deleteFarm").click(function(){
                $("#message").removeClass("alert alert-warning");
                $("#message").removeClass("alert alert-success");
                farmId = $('#fram-choose').val();
                $.ajax({
                    url: "{{ route('ajaxDeleteFarm') }}",
                    data: {id:farmId},
                    type: "post", dataType: 'json',
                    success: function(data){
                        if(data == 1){
                            $('#add-farm').hide();
                             $('#fram-choose').find('[value="' + farmId + '"]').remove();
                             $('#action-add').show();
                             $("#message").append("<span class='label label-important'>{!!trans('ebi.養殖場が削除しました')!!}</span>");
                             $("#message").addClass("alert alert-success");
                             var valueSelect = $('#fram-choose').find("option:first-child").val();
                             listPondByFarms(valueSelect);
                        }else{
                            $("#message").addClass("alert alert-warning");
                            $('#add-farm').hide();
                            if(data.message){
                                $("#message").append("<span class='label label-important'>"+data.message+'</span>');
                            }else{
                                $("#message").append("<span class='label label-important'>{!!trans('ebi.養殖場が削除失敗しました') !!}</span>");
                            }
                        }
                    }
                })
            });

            function checkRequired(value){
                if(value == ''){
                    $('.required-name').show();
                }else{
                    $('.required-name').hide();
                }
            }
        });
    </script>
@endpush
