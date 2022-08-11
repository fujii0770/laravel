<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
       {{-- <div class="user-panel">
            <div class="pull-{{ trans('crudbooster.left') }} image">
                <img src="{{ CRUDBooster::myPhoto() }}" class="img-circle" alt="{{ trans('crudbooster.user_image') }}"/>
            </div>
            <div class="pull-{{ trans('crudbooster.left') }} info">
                <p>{{ CRUDBooster::myName() }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('crudbooster.online') }}</a>
            </div>
        </div>--}}


        <div class='main-menu'>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">

                <!-- Build menu for worker -->
                    @if (Request::is('admin/users/profile') || Request::is('admin/users/add'))
                        <li class="header">
                            <h5>{{trans("ebi.プロファイル")}}</h5>
                        </li>
                        <li class='{{ (Request::is('admin/users/profile'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('users/profile') }}?return_url={{urlencode(CRUDBooster::adminPath('/'))}}' class='text-normal'>
                                <span>{{trans("ebi.ユーザー情報")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/users/add'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('users/add') }}?return_url={{urlencode(CRUDBooster::adminPath('users/profile'))}}' class='text-normal'>
                                <span>{{trans("ebi.新規ユーザー登録")}}</span>
                            </a>
                        </li>
                    
                    @elseif (Request::is('admin/ponds_aquacultures'))

                    @elseif (Request::is('admin/import_logs') || Request::is('admin/import-pond-states-completed'))
                        <li class="header">
                            <h5>{{trans('ebi.測定データ取込')}}</h5>
                        </li>
                        @foreach(CRUDBooster::sidebarMenu() as $menu)
                            @if ($menu->id == 13)
                                <li data-id='{{$menu->id}}' class="active">
                                    <a href='{{ ($menu->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$menu->url }}'
                                       class='{{($menu->color)?"text-".$menu->color:""}}'>
                                        <span>{{trans('ebi.'.$menu->name)}}</span>
                                    </a>
                                </li>
                                @break
                            @endif
                        @endforeach

                    @elseif (Request::is('admin/solution/edit*'))
                        <li class="header">
                            <h5>{{trans('ebi.解決')}}</h5>
                        </li>
                        <li class='{{ Request::is('admin/solution/edit*')?"active":""}}'>
                            <a href='' class='text-normal'>
                                <span>{{trans('ebi.解決の変更')}}</span>
                            </a>
                        </li>
                    @elseif (Request::is('admin/solution/add'))
                        <li class="header">
                            <h5>{{trans('ebi.解決')}}</h5>
                        </li>
                        <li class='{{ Request::is('admin/solution/add')?"active":""}}'>
                            <a href='' class='text-normal'>
                                <span>{{trans("ebi.解決の登録")}}</span>
                            </a>
                        </li>
                    @elseif (Request::is('admin/import_bait*') || Request::is('admin/import_drug*') )
                        <li class="header">
                            <h5>{{trans('ebi.餌、薬運用')}}</h5>
                        </li>
                        <li class='{{ Request::is('admin/import_bait*')?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('import_bait') }}' class='text-normal'>
                                <span>{{trans("ebi.餌運用")}}</span>
                            </a>
                        </li>
                        <li class='{{ Request::is('admin/import_drug*')?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('import_drug') }}' class='text-normal'>
                                <span>{{trans("ebi.薬運用")}}</span>
                            </a>
                        </li>
                    @elseif (Request::is('admin/all*'))
                        <style>
                            input[type="month"] {
                                text-align: left;
                                width: 140px;
                                font-size: 15px;
                            }
                            select{
                            font-size:15px;
                            }

                        </style>
                        <li class="header">
                            <h5>{{trans('収支管理')}}</h5>
                        </li>
                        <form action= "all" method="post">
                        @csrf
                        <li align="left">
                        <label style="width:62px;">{{trans('ebi.年度検索')}}</label>
                        <select name=year id=year style="color: #000000; width:71.5%;" value="" onchange="grade()">
                        <option style="color: #000000;" value=""><p>-</p></option>
                        @for($i=2018 ; $i <=2050; $i++)
                        <option style="color: #000000;" value=<?php echo $i ?>><p><?php echo $i."年" ?></p></option>
                        @endfor
                        </select>
                        </li>
                        <li align="left">
                        <label for="month" style="width:62px;" >{{trans('ebi.月別検索')}}</label>
                        <input style="color: #000000;" type="month" name="month" id="month" value="" onchange="tuki()">
                        </li>
                        <li>
                        <button type="post"><p style="color: #000000;" >{{trans('ebi.検索')}}</p></button>
                        </li>
                        </form>
                        <script>
                        function grade(){
                            var a = null;
                             document.getElementById("month").value=a;
                             console.log(document.getElementById("month").value);
                        }
                        function tuki(){
                            var a = null;
                            document.getElementById("year").value = a;
                            console.log(document.getElementById("year").value);
                        }
                    </script>

                @elseif(Request::is('admin/report_farm*'))
                    <li class="header">
                        <style>
                            input[type="month"] {
                                text-align: left;
                                width: 140px;
                                font-size: 15px;
                            }
                            select{
                            font-size:15px;
                            }


                        </style>
                        <?php
                      
                        $farms=DB::table('ebi_farms')->get();
                        $a=0;
                        foreach($farms as $farm){
                            $name[$a]=$farm->farm_name;
                            $id[$a]=$farm->id;
                            $a++;
                        }
                        ?>
                            <h5>{{trans('収支管理')}}</h5>
                        </li>
                        <form action= "report_farm" method="get">
                        @csrf
                        <li align="left">
                        <label style="width:62px;">{{trans('ebi.養殖場')}}</label>
                        <select name=farm_id id=farm_id style="color: #000000; width:71.5%;" value= <?php $farm_id ?>>
                        @for($i=0 ; $i <= $a-1 ; $i++)
                        <option style="color: #000000;" value="<?php echo $id[$i] ?>"><p><?php echo $name[$i] ?></p></option>
                        @endfor
                        </select>
                        </li>
                        <li align="left">
                        <label style="width:62px;">{{trans('ebi.年度検索')}}</label>
                        <select name=year id=year style="color: #000000; width:71.5%;" value=""  onchange="grade()">
                        <option style="color: #000000;" value=""><p>-</p></option>
                        @for($i=2018 ; $i <=2050; $i++)
                        <option style="color: #000000;" value=<?php echo $i ?>><p><?php echo $i."年" ?></p></option>
                        @endfor
                        </select>
                        </li>
                        <li align="left">
                        <label for="month" style="width:62px;">{{trans('ebi.月別検索')}}</label>
                        <input style="color: #000000;" type="month" name="month" id="month" value=""  onchange="tuki()">
                        </li>
                        <li>
                        <button type="post" onclick="test()"><p style="color: #000000;" >{{trans('ebi.検索')}}</p></button>
                        </li>
                        </form>
                        <script>
                        function grade(){
                            var a = null;
                             document.getElementById("month").value=a;
                             console.log(document.getElementById("month").value);
                        }
                        function tuki(){
                            var a = null;
                             document.getElementById("year").value=a;
                             console.log(document.getElementById("year").value);
                        }
                        function ima(){
                            var a = null;
                            document.getElementById("month").value=a;
                             document.getElementById("year").value=a;
                             console.log(document.getElementById("year").value);
                        }
                        function test(){
                             console.log(document.getElementById("farm_id").value);
                             console.log(document.getElementById("month").value);
                             console.log(document.getElementById("year").value);
                        }

                        </script>
                        

                    @elseif(Request::is('admin/report_pond*'))
                    <style>
                        input[type="month"] {
                            text-align: left;
                            width: 140px;
                            font-size: 15px;
                        }
                        select{
                        font-size:15px;
                        }


                        <?php
                        $ponds=DB::table('ebi_ponds')->get();
                        $a=0;
                        foreach($ponds as $pond){
                            $name[$a]=$pond->pond_name;
                            $pond_id[$a]=$pond->id;
                            $a++;
                        }
                        ?>



                    </style>
                        <li class="header">
                            <h5>{{trans('収支管理')}}</h5>
                        </li>
                        <form action="report_pond" method="post">
                        @csrf
                        <li align="left">
                        <label style="width:62px;">{{trans('ebi.養殖池')}}</label>
                        <select name=pond_id id=pond_id style="color: #000000; width:71.5%;" value= <?php $pond_id ?>>
                        @for($i=0 ; $i <= $a-1 ; $i++)
                        <option style="color: #000000;" value=<?php echo $pond_id[$i] ?>><p><?php echo $name[$i] ?></p></option>
                        @endfor
                        </select>
                        <li align="left">
                        <label style="width:62px;">{{trans('ebi.年度検索')}}</label>
                        <select name=year id=year style="color: #000000; width:71.5%;" value=""  onchange="grade()">
                        <option style="color: #000000;" value=""><p>-</p></option>
                        @for($i=2018 ; $i <=2050; $i++)
                        <option style="color: #000000;" value=<?php echo $i ?>><p><?php echo $i."年" ?></p></option>
                        @endfor
                        </select>

                        </li>
                        <li align="left">
                        <label for="month" style="width:62px;">{{trans('ebi.月別検索')}}</label>
                        <input style="color: #000000;" type="month" name="month" id="month" value=""  onchange="tuki()" >
                        </li>
                        <li align="left">
                        <label for="now" style="width:62px;">{{trans('ebi.現在')}}</label>
                        <input style="color: #000000;" type="checkbox" name="now" id="now" value=""  onchange="ima()">
                        </li>
                        <li>
                        <button type="post"  onclick="test()"><p style="color: #000000;" >{{trans('ebi.検索')}}</p></button>
                        </li>
                        </form>
                        <script>
                        function grade(){
                            var a = null;
                             document.getElementById("month").value=a;
                             var checkbox = document.getElementById( "now" );
                             checkbox.checked = false;
                             console.log(document.getElementById("month").value);
                        }
                        function tuki(){
                            var a = null;
                             document.getElementById("year").value=a;
                             var checkbox = document.getElementById( "now" );
                             checkbox.checked = false;
                             console.log(document.getElementById("year").value);
                        }
                        function ima(){
                            var a = null;
                            document.getElementById("month").value=a;
                             document.getElementById("year").value=a;
                             console.log(document.getElementById("year").value);
                        }
                        function test(){
                             console.log(document.getElementById("pond_id").value);
                             console.log(document.getElementById("month").value);
                             console.log(document.getElementById("year").value);
                        }



                        </script>

                    @elseif (Request::is('admin/aquaculture_method*') || Request::is('admin/ebi_bait_inventories*') || Request::is('admin/default_ponds*') || Request::is('admin/minmax*')
                            || Request::is('admin/tag*')|| Request::is('admin/pond*') || Request::is('admin/farm*') 
                            || Request::is('admin/default_farm*') || Request::is('admin/aquaculture_method*') || Request::is('admin/aquacultures/start*')
                            || Request::is('admin/ebi_price*') || Request::is('admin/ebi_kind*') || Request::is('admin/feed_price*'))
                        <!-- Menu Basic Setting-->
                        <li class="header">
                            <h5>{{trans('ebi.基本情報登録')}}</h5>
                        </li>
                        <li class='{{ (Request::is('admin/default_ponds*') || Request::is('admin/pond*') || Request::is('admin/farm*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('default_ponds') }}' class='text-normal'>
                                <span>{{trans("ebi.基本情報")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/aquaculture_method*') || Request::is('admin/ebi_price*') || Request::is('admin/ebi_kind*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('aquaculture_method') }}' class='text-normal'>
                                <span>{{trans("ebi.エビ設定")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/feed_price*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('feed_price') }}' class='text-normal'>
                                <span>{{trans("ebi.餌、薬単価設定")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/minmax*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('minmax/setting') }}'>
                                <span>{{trans("ebi.水質基準値設定")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/aquacultures/start*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('aquacultures/start') }}' class='text-normal'>
                                <span>{{trans("ebi.養殖開始設定")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/ebi_bait_inventories*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('ebi_bait_inventories') }}' class='text-normal'>
                                <span>{{trans("ebi.発注管理")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/tags*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('tags') }}' class='text-normal'>
                                <span>{{trans("ebi.Tag_registration")}}</span>
                            </a>
                        </li>
                        <li class='{{ (Request::is('admin/default_farm*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('default_farm') }}' class='text-normal'>
                                <span>{{trans("ebi.養殖サイクルデフォルト")}}</span>
                            </a>
                        </li>
                    @elseif (Request::is('admin/aquacultures*') && !Request::is('admin/aquacultures/start*'))
                        <li class="header">
                            <h5>{{trans('ebi.養殖履歴検索')}}</h5>
                        </li>
                        <li class='{{ (Request::is('admin/aquacultures*') && !Request::is('admin/aquacultures/start*'))?"active":""}}'>
                            <a href='{{ CRUDBooster::adminPath('aquacultures') }}' class='text-normal'>
                                <span>{{trans("ebi.養殖履歴検索")}}</span>
                            </a>
                        </li>
                    
                    @elseif (Request::is('admin/shrimp_states*'))
                        <li class="header">
                            <h5>{{trans('ebi.養殖状況登録')}}</h5>
                        </li>
                        <li class='{{ (Request::is('admin/shrimp_states*'))?"active":""}}'>
                            <span>{{trans("ebi.養殖状況登録")}}</span>
                        </li>

                    @elseif (Request::is('admin/sell_add'))

                        <li class="header">
                            <h5>{{trans('ebi.売上登録')}}</h5>
                        </li>
                        <li class='{{ (Request::is('admin/cold*'))?"active":""}}'>
                            <a href="cold?pond_id={{$pond_id}}&pond_name={{$pond_name}}" class='text-normal'>
                                <span>{{trans("ebi.冷凍在庫登録")}}</span>
                            </a>
                        </li>

                    @elseif (Request::is('admin/cold'))
                        <li class="header">
                            <h5>{{trans("ebi.冷凍在庫登録")}}</h5>
                        </li>
                        <li class='{{ (Request::is('admin/selladd*'))?"active":""}}'>
                            <a href="sell_add?pond_id={{$pond_id}}&pond_name={{$pond_name}}" class='text-normal'>
                                <span>{{trans("ebi.売上登録")}}</span>
                            </a>
                        </li>

                    @elseif(Request::is('admin/report*'))
                        <li class="header">
                            <label style="width:120px; font-size: 30px">収支管理</label>
                    </li>
                        <ul_ Class="b" >
                        @foreach($ponds as $pond)
                        @if($pond==$pond_name)
                        <li><input name="pond" value="{{$pond}}" class="d" type="button" onclick="pond(this.value)"></li>
                        @else
                        <li><input name="pond" value="{{$pond}}" class="a" type="button" onclick="pond(this.value)"></li>
                        @endif
                        @endforeach
                    </ul_>
                    <button class="c">TOPへ戻る</button>
                        <style>
                            .header_content{
                                display: flex;
                                color: white;
                                font-size: 20px;
                            }
                            ul_ {
                                display: flex;
                                justify-content: flex-end;
                                flex-wrap: wrap;
                                width: calc(100% - 200px);
                                list-style: none;
                            }
                            .c{
                                font-size: 110%;
                                font-weight: bold;
                                width: 75%;
                                height: 39px;
                                background: #5FC9EC;
                                color: snow;
                                border-radius: 3px;
                                border-bottom: solid 4px #3D9DB7;
                                text-decoration: none;
                                margin-top: 85%;
                                border-color: #5FC9EC;
                            }
                            .main-menu ul li {
                                padding-left: 5px;
                                padding-right: 15px;
                            }
                            .b{
                                width: 90%;
                                margin-top: 196px;
                                height: auto;
                            }
                            .a{
                                font-weight: bold;
                                padding-top: 3px;
                                margin-top: 5px;
                                margin-right: 3%;
                                font-size: 120%;
                                float: left;
                                width: 40px;
                                height: 30px;
                                background: black;
                                border-radius: 20%;
                                text-align: center;
                                color: snow;
                            }
                            .a:hover{
                                font-weight: bold;
                                background: green;
                                color: white;
                            }
                            .d {
                                font-weight: bold;
                                padding-top: 3px;
                                margin-top: 5px;
                                margin-right: 3%;
                                font-size: 120%;
                                float: left;
                                width: 40px;
                                height: 30px;
                                background: green;
                                border-radius: 20%;
                                text-align: center;
                                color: snow;
                            }
                        </style>
                  
                
                


                @elseif(Request::is('admin/feed_session*'))
                    <li class="header">
                        <label style="width:120px; font-size: 30px">収支管理</label>
                    </li>
                    <button class="c">TOPへ戻る</button>

                    <style>
                        
                        .c{
                            font-size: 110%;
                            font-weight: bold;
                            width: 75%;
                            height: 39px;
                            background: #5FC9EC;
                            color: snow;
                            margin-top: 215%;
                            border-radius: 3px;
                            border-bottom: solid 4px #3D9DB7;
                            text-decoration: none;
                            border-color: #5FC9EC;
                        }
                    </style>
                    
                @elseif(Request::is('admin/feed_total*'))
                    <li class="header">
                        <label style="width:120px; font-size: 30px">収支管理</label>
                    </li>
                    <button class="c">TOPへ戻る</button>

                    <style>
                        .main-sidebar{
                            height: 1000px !important;
                            min-height: auto !important;
                        }
                        .c{
                            font-size: 110%;
                            font-weight: bold;
                            width: 75%;
                            height: 39px;
                            background: #5FC9EC;
                            color: snow;
                            margin-top: 215%;
                            border-radius: 3px;
                            border-bottom: solid 4px #3D9DB7;
                            text-decoration: none;
                            border-color: #5FC9EC;
                        }
                    </style>
                @else

                @endif

            <!-- End build menu for worker -->

                {{-- @if(CRUDBooster::isSuperadmin())--}}
                @if(false)

                    <li class="header">{{ trans('crudbooster.SUPERADMIN') }}</li>
                    <!-- Optionally, you can add icons to the links -->
                    @foreach(CRUDBooster::sidebarMenu() as $menu)
                        <li data-id='{{$menu->id}}' class='{{(!empty($menu->children))?"treeview":""}} {{ (Request::is($menu->url_path."*"))?"active":""}}'>
                            <a href='{{ ($menu->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$menu->url }}'
                               class='{{($menu->color)?"text-".$menu->color:""}}'>
                                {{--<i class='{{$menu->icon}} {{($menu->color)?"text-".$menu->color:""}}'></i>--}} <span>{{trans('ebi.'.$menu->name)}}</span>
                                @if(!empty($menu->children))<i class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i>@endif
                            </a>
                            @if(!empty($menu->children))
                                <ul class="treeview-menu">
                                    @foreach($menu->children as $child)
                                        <li data-id='{{$child->id}}' class='{{(Request::is($child->url_path .= !ends_with(Request::decodedPath(), $child->url_path) ? "/*" : ""))?"active":""}}'>
                                            <a href='{{ ($child->is_broken)?"javascript:alert('".trans('crudbooster.controller_route_404')."')":$child->url}}'
                                               class='{{($child->color)?"text-".$child->color:""}}'>
                                                {{-- <i class='{{$child->icon}}'></i>--}} <span>{{$child->name}}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    <li class='treeview'>
                        <a href='#'><i class='fa fa-key'></i> <span>{{ trans('crudbooster.Privileges_Roles') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/privileges/add*')) ? 'active' : '' }}"><a
                                        href='{{Route("PrivilegesControllerGetAdd")}}'>{{ $current_path }}<i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Privilege') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/privileges')) ? 'active' : '' }}"><a
                                        href='{{Route("PrivilegesControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Privilege') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-users'></i> <span>{{ trans('crudbooster.Users_Management') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/users/add*')) ? 'active' : '' }}"><a
                                        href='{{Route("AdminCmsUsersControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.add_user') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/users')) ? 'active' : '' }}"><a
                                        href='{{Route("AdminCmsUsersControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_users') }}</span></a></li>
                        </ul>
                    </li>

                    <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/menu_management*')) ? 'active' : '' }}"><a
                                href='{{Route("MenusControllerGetIndex")}}'><i class='fa fa-bars'></i>
                            <span>{{ trans('crudbooster.Menu_Management') }}</span></a></li>
                    <li class="treeview">
                        <a href="#"><i class='fa fa-wrench'></i> <span>{{ trans('crudbooster.settings') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class="treeview-menu">
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/settings/add*')) ? 'active' : '' }}"><a
                                        href='{{route("SettingsControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Setting') }}</span></a></li>
                            <?php
                            $groupSetting = DB::table('cms_settings')->groupby('group_setting')->pluck('group_setting');
                            foreach($groupSetting as $gs):
                            ?>
                            <li class="<?=($gs == Request::get('group')) ? 'active' : ''?>"><a
                                        href='{{route("SettingsControllerGetShow")}}?group={{urlencode($gs)}}&m=0'><i class='fa fa-wrench'></i>
                                    <span>{{$gs}}</span></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li class='treeview'>
                        <a href='#'><i class='fa fa-th'></i> <span>{{ trans('crudbooster.Module_Generator') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/module_generator/step1')) ? 'active' : '' }}"><a
                                        href='{{Route("ModulsControllerGetStep1")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Module') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/module_generator')) ? 'active' : '' }}"><a
                                        href='{{Route("ModulsControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Module') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-dashboard'></i> <span>{{ trans('crudbooster.Statistic_Builder') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/statistic_builder/add')) ? 'active' : '' }}"><a
                                        href='{{Route("StatisticBuilderControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Statistic') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/statistic_builder')) ? 'active' : '' }}"><a
                                        href='{{Route("StatisticBuilderControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Statistic') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-fire'></i> <span>{{ trans('crudbooster.API_Generator') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator/generator*')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetGenerator")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_API') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.list_API') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/api_generator/screet-key*')) ? 'active' : '' }}"><a
                                        href='{{Route("ApiCustomControllerGetScreetKey")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.Generate_Screet_Key') }}</span></a></li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-envelope-o'></i> <span>{{ trans('crudbooster.Email_Templates') }}</span> <i
                                    class="fa fa-angle-{{ trans("crudbooster.right") }} pull-{{ trans("crudbooster.right") }}"></i></a>
                        <ul class='treeview-menu'>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/email_templates/add*')) ? 'active' : '' }}"><a
                                        href='{{Route("EmailTemplatesControllerGetAdd")}}'><i class='fa fa-plus'></i>
                                    <span>{{ trans('crudbooster.Add_New_Email') }}</span></a></li>
                            <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/email_templates')) ? 'active' : '' }}"><a
                                        href='{{Route("EmailTemplatesControllerGetIndex")}}'><i class='fa fa-bars'></i>
                                    <span>{{ trans('crudbooster.List_Email_Template') }}</span></a></li>
                        </ul>
                    </li>

                    <li class="{{ (Request::is(config('crudbooster.ADMIN_PATH').'/logs*')) ? 'active' : '' }}"><a href='{{Route("LogsControllerGetIndex")}}'><i
                        class='fa fa-flag'></i> <span>{{ trans('crudbooster.Log_User_Access') }}</span></a></li>
                @endif

            </ul><!-- /.sidebar-menu -->

        </div>

    </section>
    <!-- /.sidebar -->

</aside>
@push('bottom')
    <script>
        function onElementHeightChange(elm, callback) {
            var lastHeight = elm.clientHeight, newHeight;
            (function run() {
                newHeight = elm.clientHeight;
                if (lastHeight != newHeight)
                    callback();
                lastHeight = newHeight;

                if (elm.onElementHeightChangeTimer)
                    clearTimeout(elm.onElementHeightChangeTimer);

                elm.onElementHeightChangeTimer = setTimeout(run, 200);
            })();
        }

        onElementHeightChange(document.body, function () {
            //alert('Body height changed');
            $('.main-sidebar').height($(document).outerHeight() - 70);
        });

    </script>
@endpush
