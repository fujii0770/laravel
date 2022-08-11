<div class="row f5_page_nav">
    <div class="col-md-2">
        <div class="name-number text-center">
            <p style="white-space: nowrap;  overflow: hidden; text-overflow: ellipsis;"  title="{{(app()->getLocale() == 'en')?$pond_farm->farm_name_en .'-'. $pond_farm->pond_name :$pond_farm->farm_name .'-'. $pond_farm->pond_name}}">
                @if(app()->getLocale() == 'en')
                    {{ $pond_farm->farm_name_en }} 
                @else
                    {{ $pond_farm->farm_name }} 
                @endif
                    {{ $pond_farm->pond_name }}
            </p>
        </div>        
    </div>
    <div class="col-md-10 menu-page-hozi-radio">
        <div class="f5_tab_top">
            <div class="f1 f1_page_nav">
                <label class="f5_radio">{{trans('ebi.養殖池状況')}}
                    <input id="rdWater" type="radio" name="radio">
                    <span class="checkmark"></span>
                </label>
                @if($pond_farm->farm_id)
                    <label class="f5_radio">{{trans('ebi.エビ管理')}}
                        <input id="rdShrimp" type="radio" name="radio">
                        <span class="checkmark"></span>
                    </label>

                    <label class="f5_radio">{{trans('ebi.餌状況')}}
                        <input id="rdFeeding" type="radio" name="radio" >
                        <span class="checkmark"></span>
                    </label>
                @endif

                <label class="f5_radio">{{trans('ebi.収支')}}
                    <input id="rdBalance" type="radio" name="radio" >
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
</div>
