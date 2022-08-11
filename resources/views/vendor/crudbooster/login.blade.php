<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{trans("crudbooster.page_title_login")}} : {{__(Session::get('appname'))}}</title>
    <meta name='robots' content='noindex,nofollow'/>
    <link rel="shortcut icon"
          href="{{ CRUDBooster::getSetting('favicon')?asset(CRUDBooster::getSetting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png') }}">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{asset('vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="{{asset('vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css')}}" rel="stylesheet" type="text/css"/>

    <!-- support rtl-->
    @if (in_array(App::getLocale(), ['ar', 'fa']))
        <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
        <link href="{{ asset("vendor/crudbooster/assets/rtl.css")}}" rel="stylesheet" type="text/css"/>
@endif

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link rel='stylesheet' href='{{asset("vendor/crudbooster/assets/css/main.css")}}'/>
    <style type="text/css">
        .login-page, .register-page {
        /*    background: {{ CRUDBooster::getSetting("login_background_color")?:'#dddddd'}} url('{{ CRUDBooster::getSetting("login_background_image")?asset(CRUDBooster::getSetting("login_background_image")):asset('vendor/crudbooster/assets/bg_blur3.jpg') }}'); */
            background: {{ CRUDBooster::getSetting("login_background_color")?:'#659ad2'}};
            color: {{ CRUDBooster::getSetting("login_font_color")?:'#ffffff' }}  !important;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .login-box, .register-box {
            margin: 2% auto;
        }

        .login-box-body {
            box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.8);
            background: #f3f3f4;
            color: {{ CRUDBooster::getSetting("login_font_color")?:'#666666' }}  !important;
            padding:0;
        }
        .login-box {
            margin: 8% auto;
            background: #f3f3f4;
            width: 40%;
            padding: 0 8%;
            padding-top: 40px;
            padding-bottom: 30px;
        }
        .login-logo {
            font-size: 35px;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 300;
            margin-left: auto;
            margin-right: auto;
        }
        .login-logo a {
            color: #659ad2;
            font-weight: bold;
            font-size: 26px;
        }
        .logo_img {
            background: #fcbc85;
            height: 130px;
            width: 130px;
            display: block;
            border-radius: 50%;
            margin-left: auto;
            margin-right: auto;
        }
        .login-logo img {
            width: 80%;
            text-align: center;
            padding: 40px 0;
        }
        form label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-size: 15px;
             font-weight: 400;
            color: #818285;
        }
        form button {
            background: #99c4c7;
            color: #fff;
        }
        form button {
            font-size: 16px;
        }
        html, body {
            overflow: hidden;
        }
    </style>
</head>

<body class="login-page">

<div class="login-box">
    <div class="login-logo">
       <a href="javascript:void(0);" style="cursor: default">
           <p class="logo_img">
               <img src='{{ asset('img/logo.png') }}'
                    style='max-width: 100%;max-height:170px'/>
           </p>
           <p>{{__(CRUDBooster::getSetting('appname'))}}</p>
        </a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">

        @if ( Session::get('message') != '' )
            <div class='alert alert-warning'>
                {{ Session::get('message') }}
            </div>
        @endif

        {{--<p class='login-box-msg'>{{trans("crudbooster.login_message")}}</p>--}}
        <form autocomplete='off' action="{{ route('postLogin') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="form-group has-feedback">
                <label for="">LOGIN</label>
                <input autocomplete='off' type="text" class="form-control" name='email' required placeholder="{{trans('ebi.メールアドレス')}}"/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <label for="">PASSWORD</label>
                <input autocomplete='off' type="password" class="form-control" name='password' required placeholder="{{trans('ebi.パスワード')}}"/>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div style="margin-bottom:10px" class='row'>
                <div class='col-xs-12'>
                    <button type="submit" class="btn btn-block btn-flat"><i class='fa fa-lock'></i> {{trans("crudbooster.button_sign_in")}}</button>
                </div>
            </div>

            {{--<div class='row'>
                <div class='col-xs-12' align="center"><p style="padding:10px 0px 10px 0px">{{trans("crudbooster.text_forgot_password")}} <a
                                href='{{route("getForgot")}}'>{{trans("crudbooster.click_here")}}</a></p></div>
            </div>--}}
        </form>


        <br/>
        <!--a href="#">I forgot my password</a-->

    </div><!-- /.login-box-body -->

</div><!-- /.login-box -->


<!-- jQuery 2.1.3 -->
<script src="{{asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{asset('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
</body>
</html>