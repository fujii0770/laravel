 <!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<title>養殖情報登録</title>
</head>
    <body >
    <div class="form-group">
        <form action="#" method="post">
        
            <div class='form-group header-group-0 ' id='form-group-farm_id' style="">
                <label class='control-label col-sm-2'>養殖場
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                <div class="col-sm-10">
                    <select style='width:100%' class='form-control' id="farm_id"
                            required   disabled name="farm_id"  >
                        
                                </select>
                    <div class="text-danger">
                        
                    </div><!--end-text-danger-->
                    <p class='help-block'></p>

                </div>
            </div>
                            <div class='form-group header-group-0 ' id='form-group-pond_id' style="">
                <label class='control-label col-sm-2'>初期投入養殖池
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                <div class="col-sm-10">
                    <select style='width:100%' class='form-control' id="pond_id"
                            required   disabled name="pond_id"  >
                        
                                </select>
                    <div class="text-danger">
                        
                    </div><!--end-text-danger-->
                    <p class='help-block'></p>

                </div>
            </div>
                            <div class='form-group form-datepicker header-group-0 ' id='form-group-start_date'
                style="">
                <label class='control-label col-sm-2'>養殖開始日
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon open-datetimepicker"><a><i class='fa fa-calendar '></i></a></span>
                        <input type='text' title="養殖開始日" readonly
                            required    class='form-control notfocus input_date' name="start_date" id="start_date"
                            value='2020-07-05'/>
                    </div>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
                            <div class='form-group header-group-0 ' id='form-group-target_size' style="">
                <label class='control-label col-sm-2'>目標サイズ(cm)
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="目標サイズ(cm)" min=0 
                        required    class='form-control'
                        name="target_size" id="target_size" value='46.5'/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                <div class='form-group header-group-0 ' id='form-group-target_weight' style="">
                <label class='control-label col-sm-2'>目標重量(g)
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="目標重量(g)" min=0 
                        required    class='form-control'
                        name="target_weight" id="target_weight" value='21.5'/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                <div class='form-group header-group-0 ' id='form-group-food_amount' style="">
                <label class='control-label col-sm-2'>餌量(ペソ)
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="餌量(ペソ)" min=0 
                            class='form-control'
                        name="food_amount" id="food_amount" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                <div class='form-group header-group-0 ' id='form-group-power_consumption' style="">
                <label class='control-label col-sm-2'>消費電力(ペソ)
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="消費電力(ペソ)" min=0 
                            class='form-control'
                        name="power_consumption" id="power_consumption" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
                <div class='form-group header-group-0 ' id='form-group-power_medicine' style="">
                <label class='control-label col-sm-2'>薬費用(ペソ)
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="any" title="薬費用(ペソ)" min=0 
                            class='form-control'
                        name="power_consumption" id="medicine" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>                               <div class='form-group header-group-0 ' id='form-group-shrimp_num' style="">
                <label class='control-label col-sm-2'>稚エビ量(匹)
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="1" title="稚エビ量(匹)" min=0 
                        required    class='form-control'
                        name="shrimp_num" id="shrimp_num" value='120000'/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
            <div class='form-group header-group-0 ' id='form-group-shrimp_kind' style="">
                <label class='control-label col-sm-2'>稚エビ種類
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                        <div class="col-sm-10">
                    <select style='width:100%' class='form-control' id="shrimp_kind"
                            required   disabled name="shrimp_kind"  >
                </div>
            </div>
            <div class='form-group header-group-0 ' id='form-group-shrimp_method' style="">
                <label class='control-label col-sm-2'>養殖方法
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                        <div class="col-sm-10">
                    <select style='width:100%' class='form-control' id="shrimp_method"
                            required   disabled name="shrimp_method"  >
              
                </div>
            </div>
            <div class='form-group header-group-0 ' id='form-group-shrimp_servive' style="">
                <label class='control-label col-sm-2'>生存率(％)
                                <span class='text-danger' title='This field is required'>*</span>
                        </label>

                <div class="col-sm-10">
                    <input type='number' step="1" title="生存率" 
                        required    class='form-control'
                        name="shrimp_kind" id="servive" value=''/>
                    <div class="text-danger"></div>
                    <p class='help-block'></p>
                </div>
            </div>
            <input type="submit" value="登録" >
        </form>
        <script>
          ('body').on('click','#start_date',function(){
            $(this).datepicker(
            {
            format: "yyyy/mm/dd"
            });
            $(this).datepicker("show");
            })
        </script>
    </body>
</html>