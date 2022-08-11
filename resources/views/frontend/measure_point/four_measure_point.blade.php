<div class="row map-ph">
    <div class="col-md-4">
        <p class="content-p button-icon"><img src="{{asset('./img/icon/icon_map.svg')}}" > <span>{{trans('ebi.計測地点')}}</span></p>
        
        @if(app()->getLocale() == 'en')
            <svg width="378.8" height="145" xmlns="http://www.w3.org/2000/svg">
                <title>1</title>

                <g>
                <title>background</title>
                <rect fill="none" id="canvas_background" height="402" width="582" y="-1" x="-1"/>
                </g>
                <g>
                <title>Layer 1</title>
                <path id="svg_1" fill="#adcdec" d="m319.24008,45.01001l-260,0a50,50 0 0 0 0,100l260,0a50,50 0 1 0 0,-100z"/>
                <path id="svg_2" fill="none" stroke="#fff" stroke-linejoin="round" stroke-width="2px" d="m190.24008,45.01001c0,4.54 -2,4.54 -2,9.09s2,4.55 2,9.09s-2,4.54 -2,9.09s2,4.54 2,9.09s-2,4.54 -2,9.09s2,4.54 2,9.09s-2,4.55 -2,9.09s2,4.55 2,9.09s-2,4.55 -2,9.09s2,4.55 2,9.1s-2,4.55 -2,9.1"/>
                <line id="svg_3" fill="none" stroke="#fff" stroke-linejoin="round" y2="95" x2="369.24" y1="95" x1="9.24"/>
                <path fill="#a4b2d1" d="m28.08008,56.28001a15,15 0 1 0 -26.16,0l13.08,28.22l13.08,-28.22z" id="point_a_part1"/>
                <circle id="svg_4" fill="#fff" r="10" cy="48.95" cx="15"/>
                <path fill="#a4b2d1" d="m376.88008,56.28001a15,15 0 1 0 -26.16,0l13.11,28.23l13.05,-28.23z" id="point_d_part1"/>
                <circle id="svg_5" fill="#fff" r="10" cy="48.95" cx="363.8"/>
                <path fill="#a4b2d1" d="m201.41008,22.33001a15,15 0 1 0 -26.16,0l13.08,28.22l13.08,-28.22z" id="point_b_part1"/>
                <circle id="svg_6" fill="#fff" r="10" cy="15" cx="188.33"/>
                <path fill="#a4b2d1" d="m201.41008,114.01001a15,15 0 1 0 -26.16,0l13.08,28.22l13.08,-28.22z" id="point_c_part1"/>
                <circle id="svg_7" fill="#fff" r="10" cy="106.68" cx="188.33"/>
                <text id="svg_8" x="11.21" y="53.26" font-size="12px" fill="#231f20" font-family="Roboto">A</text>
                <text id="svg_9" x="359.73999" y="53.26" font-size="12px" fill="#231f20" font-family="Roboto">D</text>
                <text id="svg_10" x="184.50999" y="19.31" font-size="12px" fill="#231f20" font-family="Roboto">B</text>
                <text id="svg_11" x="184.53" y="111" font-size="12px" fill="#231f20" font-family="Roboto">C</text>
                <text stroke="#000" transform="matrix(0.6972143521799268,0,0,0.6836590277876304,12.029772194217955,32.67157390394908) " xml:space="preserve" text-anchor="start" font-family="Helvetica, Arial, sans-serif" font-size="24" id="svg_12" y="133.27856" x="109.97203" stroke-width="0" fill="#ffffff">Pond</text>
                </g>
            </svg>
        @else
            <svg id="contents" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 378.8 145">
                <title>1</title>
                <path d="M3480.41,2274h-260a50,50,0,0,0,0,100h260A50,50,0,1,0,3480.41,2274Z" transform="translate(-3161.17 -2228.99)" style="fill:#adcdec" />
                <path d="M3351.41,2274c0,4.54-2,4.54-2,9.09s2,4.55,2,9.09-2,4.54-2,9.09,2,4.54,2,9.09-2,4.54-2,9.09,2,4.54,2,9.09-2,4.55-2,9.09,2,4.55,2,9.09-2,4.55-2,9.09,2,4.55,2,9.1-2,4.55-2,9.1" transform="translate(-3161.17 -2228.99)" style="fill:none;stroke:#fff;stroke-linejoin:round;stroke-width:2px" />
                <line x1="9.24" y1="95" x2="369.24" y2="95" style="fill:none;stroke:#fff;stroke-linejoin:round" />
                <path d="M3241.7,2355.27a31.31,31.31,0,0,0,4.14.88l-0.47,1.12a22.31,22.31,0,0,1-6.48-1.85l0.07,0.85q-4.61.7-8.77,1l-0.09-1.15,2.34-.16v-5.56a16.29,16.29,0,0,1-2.54,1.76l-0.49-1.31a17,17,0,0,0,4.32-3.08h-3.91v-1.06h7.29v-1h-5.89v-1h5.89v-1h-6.77v-1.08h4.1q-0.58-.86-0.86-1.24l1.26-.34q0.45,0.58,1.1,1.58h3.82a16.18,16.18,0,0,0,1-1.57l1.31,0.27q-0.43.76-.81,1.3h4v1.08h-6.77v1h5.89v1h-5.89v1h7.29v1.06h-3.91a17,17,0,0,0,4.32,3.08l-0.49,1.31a16.29,16.29,0,0,1-2.54-1.76v2.92l1-.59,0.72,0.85A25.74,25.74,0,0,1,3241.7,2355.27Zm-7.87-4.41h8V2350h-8v0.9Zm0,0.92v0.92h8v-0.92h-8Zm4.7,3.46a10.18,10.18,0,0,1-2.3-1.57h-2.39v2.14Q3236,2355.61,3238.53,2355.23Zm2.92-6.25a14.67,14.67,0,0,1-1.12-1.24h-5a14.67,14.67,0,0,1-1.12,1.24h2.92v-0.92h1.4V2349h2.92Zm-1.3,5.76q1.37-.56,2.38-1.08H3238A9.59,9.59,0,0,0,3240.15,2354.75Z" transform="translate(-3161.17 -2228.99)" style="fill:#fff" />
                <path d="M3249.6,2343.42v-1.17h5.88v1.17h-2.7q-0.16,1.33-.36,2.21h2.93v1.08a15.13,15.13,0,0,1-1.51,6.24,10,10,0,0,1-3.56,4.17l-0.76-1a9.72,9.72,0,0,0,3.62-4.77q-0.92-.72-2.09-1.49a13.66,13.66,0,0,1-1,1.84l-0.86-.88a18.61,18.61,0,0,0,2.34-7.42h-1.94Zm3.94,6.79a16.13,16.13,0,0,0,.56-3.49h-1.95q-0.36,1.31-.65,2.05Q3252.44,2349.38,3253.54,2350.21Zm2.32-6.32v-1.13h4.72c0-.17.06-0.41,0.1-0.73s0.08-.54.1-0.67l1.35,0.05c0,0.35-.1.8-0.2,1.35h3.87v1.13h-4q-0.13.77-.27,1.51H3265v8.73h-6.59v-8.73h1.67q0.13-.54.29-1.51h-4.52Zm1.4,1.91v9.54h8.53v1.13h-8.53v0.86h-1.35V2345.8h1.35Zm2.48,2.18h3.91v-1.46h-3.91V2348Zm0,1v1.51h3.91V2349h-3.91Zm3.91,4.07v-1.57h-3.91V2353h3.91Z" transform="translate(-3161.17 -2228.99)" style="fill:#fff" />
                <path d="M3272.47,2349.27a35.28,35.28,0,0,0-3.17-2.66l0.79-1a36.56,36.56,0,0,1,3.19,2.66Zm1.33,2.12a21,21,0,0,1-2.84,5.71l-1.1-.72a22.44,22.44,0,0,0,2.79-5.44Zm-3.67-8.68,0.79-1q1.53,1.17,2.88,2.43l-0.81,1A36.91,36.91,0,0,0,3270.13,2342.72Zm5.4-.32h1.37v3.26l2.47-.59v-3.6h1.3v3.29l4.18-1v0.7q0,3.38-.15,5a5.53,5.53,0,0,1-.42,2,0.94,0.94,0,0,1-.85.41q-0.38,0-1.67-.07l-0.2-1.21q1.08,0.07,1.46.07a0.19,0.19,0,0,0,.14-0.05,0.67,0.67,0,0,0,.12-0.23,2.39,2.39,0,0,0,.1-0.54c0-.24,0-0.55.07-0.94s0-.88.05-1.46,0-1.28,0-2.1l-2.84.7v7.22h-1.3v-6.89l-2.47.61v7.87a0.75,0.75,0,0,0,.46.84,18.14,18.14,0,0,0,3.09.14h1.16a6,6,0,0,0,.85-0.07l0.65-.11a1.05,1.05,0,0,0,.41-0.29,1.07,1.07,0,0,0,.31-0.41c0-.11.08-0.33,0.15-0.67a6.56,6.56,0,0,0,.14-0.88q0-.38.08-1.21l1.3,0.25q-0.07,1-.12,1.48a7.79,7.79,0,0,1-.18,1.1,5.55,5.55,0,0,1-.24.83,1.6,1.6,0,0,1-.44.54,1.67,1.67,0,0,1-.62.37,8,8,0,0,1-.93.17,9.91,9.91,0,0,1-1.22.1h-1.62q-1.39,0-2.12,0a12.79,12.79,0,0,1-1.3-.13,1.28,1.28,0,0,1-.78-0.33,1.5,1.5,0,0,1-.3-0.55,3.37,3.37,0,0,1-.09-0.9v-7.78l-1.82.45-0.23-1.26,2.05-.49v-3.6Z" transform="translate(-3161.17 -2228.99)" style="fill:#fff" />
                <path id="point_a_part1" d="M3189.25,2285.27a15,15,0,1,0-26.16,0l13.08,28.22Z" transform="translate(-3161.17 -2228.99)" style="fill:#a4b2d1" />
                <circle cx="15" cy="48.95" r="10" style="fill:#fff" />
                <path id="point_d_part1" d="M3538.05,2285.27a15,15,0,1,0-26.16,0L3525,2313.5Z" transform="translate(-3161.17 -2228.99)" style="fill:#a4b2d1" />
                <circle cx="363.8" cy="48.95" r="10" style="fill:#fff" />
                <path id="point_b_part1" d="M3362.58,2251.32a15,15,0,1,0-26.16,0l13.08,28.22Z" transform="translate(-3161.17 -2228.99)" style="fill:#a4b2d1" />
                <circle cx="188.33" cy="15" r="10" style="fill:#fff" />
                <path id="point_c_part1" d="M3362.58,2343a15,15,0,1,0-26.16,0l13.08,28.22Z" transform="translate(-3161.17 -2228.99)" style="fill:#a4b2d1" />
                <circle cx="188.33" cy="106.68" r="10" style="fill:#fff" />
                <text transform="translate(11.21 53.26)" style="font-size:12px;fill:#231f20;font-family:Roboto">A</text>
                <text transform="translate(359.74 53.26)" style="font-size:12px;fill:#231f20;font-family:Roboto">D</text>
                <text transform="translate(184.51 19.31)" style="font-size:12px;fill:#231f20;font-family:Roboto">B</text>
                <text transform="translate(184.53 111)" style="font-size:12px;fill:#231f20;font-family:Roboto">C</text>
            </svg>
        @endif
    </div>
    <div class="col-md-8 chart-detail detail-ph water-detail">
        <p class="content-p"><img src="{{asset('./img/icon/icon3.png')}}" >{{trans('ebi.計測項目')}} <text id="activeDate">[{{ $activeYear }}/{{ $activeMonth }}/{{$activeDate}}]</text></p>

        @foreach(\App\Http\Controllers\Helper::WATER_MEASURE_POINTS as $measurePoints)
            <div class="col-md-3" id="block_current_{{$measurePoints}}">
                <div class="chart-detail-item text-center" style="height: 160px;">
                    <small class="small-1" style="position: relative; font-size: 16px">{{$measurePoints}}</small><br/><span id="current_{{$measurePoints}}" data-toggle="tooltip" style="position: relative; margin-top: 40px; font-size: 36px"></span>
                </div>
            </div>
        @endforeach
    </div>
</div>
<style>
    .detail-ph .small-2{
        font-size: 20px ;
    }
</style>