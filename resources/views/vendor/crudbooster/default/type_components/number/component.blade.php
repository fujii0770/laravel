<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label class='control-label {{$label_width}}'>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}}">
        <input type='number' step="{{($form['step'])?:'1'}}" title="{{$form['label']}}" {{$min !== null ? 'min='.$min:''}} {{$max !== null ? 'max='.$max:''}}
               {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} class='form-control'
               name="{{$name}}" id="{{$name}}" value='{{($value!==null && $value!=='')?(float)$value:''}}'/>
        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>
    </div>
</div>