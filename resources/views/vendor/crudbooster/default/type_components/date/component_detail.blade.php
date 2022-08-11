@php
setlocale(LC_ALL, "ja_JP");
@endphp
@if ($form['only_month'])
    {{ date("Y年 m月", strtotime($value)) }}
@else
    {{ date("Y年 m月 d日", strtotime($value)) }}
@endif