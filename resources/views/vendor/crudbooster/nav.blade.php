<div class="collapse navbar-collapse pull-right" id="navbar-collapse">
    <ul class="nav navbar-nav">
        <li class="{{ (Request::is('admin/default_ponds*') || Request::is('admin/minmax*') || Request::is('admin/ebi_bait_inventories*')
                || Request::is('admin/tags*') || Request::is('admin/pond*') || Request::is('admin/default_farm*')  || Request::is('admin/aquacultures/start*')
                || Request::is('admin/aquaculture_method*') || Request::is('admin/ebi_price*') || Request::is('admin/ebi_kind*')
                || Request::is('admin/farm*') || Request::is('admin/feed_price*')) && !Request::is('admin/ponds_aquacultures*') ? 'active' : '' }}">
            <a href='{{CRUDBooster::adminPath('default_ponds/setting')}}'>
                    <span>{{trans("ebi.基本情報登録")}}</span> </a>
        </li>
        <!-- Dummy menu -->
            {{-- @foreach(CRUDBooster::sidebarMenu() as $menu)
                 @if ($menu->id == 14)
                     <li ><a href='{{CRUDBooster::adminPath('ebi_bait_inventories')}}' class="{{ (Request::is('admin/ebi_bait_inventories') || Request::is('admin/ebi_bait_inventories/add') || Request::is('admin/ebi_bait_inventories/edit/*')) ? 'active' : '' }}">
                             <span>{{trans('ebi.基本情報登録')}}</span> </a></li>
                 @endif
        @endforeach
        --}}

        <li ><a href='{{CRUDBooster::adminPath('cold_stock_list')}}'>
                <span>{{trans("ebi.冷凍在庫")}}</span></a>
        </li>

        <li class="{{ Request::is('admin/aquacultures*') && !Request::is('admin/aquacultures/start*') ? 'active' : '' }}">
            <a href='{{CRUDBooster::adminPath('report')}}'>
                <span>{{trans("ebi.養殖履歴検索")}}</span> </a>
        </li>

        <li ><a href='{{CRUDBooster::adminPath('all')}}'>{{--<i class='fa fa-dashboard'></i>--}}
                <span>{{trans('ebi.収支管理')}}</span> </a></li>

        <li ><a href='{{CRUDBooster::adminPath('simulation')}}'>{{--<i class='fa fa-dashboard'></i>--}}
                <span>{{trans('ebi.シミュレーション')}}</span> </a></li>

        {{--@foreach(CRUDBooster::sidebarMenu() as $menu)
            <li data-id='{{$menu->id}}' class='{{(!empty($menu->children))?"treeview":""}} {{ (Request::is($menu->url_path."*"))?"active":""}}'>
                <a href='{{ ($menu->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$menu->url }}'
                   class='{{($menu->color)?"text-".$menu->color:""}}'>
                    <i class='{{$menu->icon}} {{($menu->color)?"text-".$menu->color:""}}'></i> <span>{{$menu->name}}</span>
                    @if(!empty($menu->children))<i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>@endif
                </a>
                @if(!empty($menu->children))
                    <ul class="treeview-menu">
                        @foreach($menu->children as $child)
                            <li data-id='{{$child->id}}' class='{{(Request::is($child->url_path .= !ends_with(Request::decodedPath(), $child->url_path) ? "/*" : ""))?"active":""}}'>
                                <a href='{{ ($child->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$child->url}}'
                                   class='{{($child->color)?"text-".$child->color:""}}'>
                                    <i class='{{$child->icon}}'></i> <span>{{$child->name}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach--}}

        <li class="li-circle"><i class='fa fa-circle'></i></li>
    </ul>
    <style>
        header .nav a {
            color: #333;
        }
        .nav li.active a span {
            border-bottom: 2px solid #99c4c7;
        }
        .nav li a span {
            font-size: 16px;
        }
        .fa-circle {
            color: #659ad2;
            font-size: 8px;
        }
        .li-circle{
            padding-top: 13px;
            padding-bottom: 15px;
        }
    </style>
</div>