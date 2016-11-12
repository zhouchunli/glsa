<!DOCTYPE html>

<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

    <meta charset="utf-8"/>

    <title>后台管理系统</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <meta content="" name="description"/>

    <meta content="" name="author"/>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->

    <link href="{{ URL::asset('/')}}admin/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <link href="{{ URL::asset('/')}}admin/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

    <link href="{{ URL::asset('/')}}admin/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

    <link href="{{ URL::asset('/')}}admin/css/style-metro.css" rel="stylesheet" type="text/css"/>

    <link href="{{ URL::asset('/')}}admin/css/style.css" rel="stylesheet" type="text/css"/>

    <link href="{{ URL::asset('/')}}admin/css/style-responsive.css" rel="stylesheet" type="text/css"/>

    <link href="{{ URL::asset('/')}}admin/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>

    <link href="{{ URL::asset('/')}}admin/css/uniform.default.css" rel="stylesheet" type="text/css"/>

    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->

    <link href="{{ URL::asset('/')}}admin/css/login-soft.css" rel="stylesheet" type="text/css"/>

    <!-- END PAGE LEVEL STYLES -->

    <link rel="shortcut icon" href="{{ URL::asset('/')}}admin/image/favicon.ico"/>

</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="login">

<!-- BEGIN LOGO -->

<div class="logo">

    {{--<img src="{{ URL::asset('/')}}admin/image/logo-big.png" alt=""/>--}}
    <span style="font-size: 25px; font-weight: bold; color: white;">"旋嘉1.0"</span>
    <span style="font-size: 25px; font-weight: bold; color: #ff4343;">&nbsp;管理系统</span>
</div>

<!-- END LOGO -->

<!-- BEGIN LOGIN -->

<div class="content">

    <!-- BEGIN LOGIN FORM -->

    <form class="form-vertical login-form" action="{{URL::asset("admin/loginp")}}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h3 class="form-title">登录你的帐号</h3>
        <div style="color: #F80000;text-align: center;font-size: 15px;">
            {{Session::get('msg')}}
        </div>
        <div class="alert alert-error hide">

            <button class="close" data-dismiss="alert"></button>

            <span>Enter any username and password.</span>

        </div>

        <div class="control-group">

            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->

            <label class="control-label visible-ie8 visible-ie9">Username</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-user"></i>

                    <input class="m-wrap placeholder-no-fix" type="text" placeholder="帐号" name="username"/>

                </div>

            </div>

        </div>

        <div class="control-group">

            <label class="control-label visible-ie8 visible-ie9">Password</label>

            <div class="controls">

                <div class="input-icon left">

                    <i class="icon-lock"></i>

                    <input class="m-wrap placeholder-no-fix" type="password" placeholder="密码" name="password"/>

                </div>

            </div>

        </div>

        <div class="form-actions">



            <button id="submit_forlgo" type="submit" class="btn blue pull-right">

                登录 <i class="m-icon-swapright m-icon-white"></i>

            </button>

        </div>



    </form>



    <!-- END REGISTRATION FORM -->

</div>

<!-- END LOGIN -->

<!-- BEGIN COPYRIGHT -->

<div class="copyright">

    2016 &copy; shareg.cn - Admin login.

</div>

<!-- END COPYRIGHT -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

<!-- BEGIN CORE PLUGINS -->

<script src="{{ URL::asset('/')}}admin/js/jquery-1.10.1.min.js" type="text/javascript"></script>

<script src="{{ URL::asset('/')}}admin/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

<script src="{{ URL::asset('/')}}admin/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

<script src="{{ URL::asset('/')}}admin/js/bootstrap.min.js" type="text/javascript"></script>

<!--[if lt IE 9]>

<script src="{{ URL::asset('/')}}admin/js/excanvas.min.js"></script>

<script src="{{ URL::asset('/')}}admin/js/respond.min.js"></script>

<![endif]-->

<script src="{{ URL::asset('/')}}admin/js/jquery.slimscroll.min.js" type="text/javascript"></script>

<script src="{{ URL::asset('/')}}admin/js/jquery.blockui.min.js" type="text/javascript"></script>

<script src="{{ URL::asset('/')}}admin/js/jquery.cookie.min.js" type="text/javascript"></script>

<script src="{{ URL::asset('/')}}admin/js/jquery.uniform.min.js" type="text/javascript"></script>

<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->

<script src="{{ URL::asset('/')}}admin/js/jquery.validate.min.js" type="text/javascript"></script>

<script src="{{ URL::asset('/')}}admin/js/jquery.backstretch.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script src="{{ URL::asset('/')}}admin/js/app.js" type="text/javascript"></script>

<script src="{{ URL::asset('/')}}admin/js/login-soft.js" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->

<script>

    jQuery(document).ready(function () {

        App.init();

        Login.init();

    });

</script>

<!-- END JAVASCRIPTS -->
<script type="text/javascript">
    $(document).keydown(function(e){
        if(!e){
            e=window.event;
        }
        if((e.keyCode||e.which)===13){
            $("#submit_forlgo").click();//回车键按下执行的方法
        }
    });
</script>
</body>

<!-- END BODY -->

</html>