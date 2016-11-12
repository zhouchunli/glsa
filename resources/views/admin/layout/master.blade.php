<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>
	<meta charset="utf-8" />

	<title>后台管理系统</title>

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="" name="author" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />

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

	<link href="{{ URL::asset('/')}}admin/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>

	<link href="{{ URL::asset('/')}}admin/css/daterangepicker.css" rel="stylesheet" type="text/css" />

	<link href="{{ URL::asset('/')}}admin/css/fullcalendar.css" rel="stylesheet" type="text/css"/>

	<link href="{{ URL::asset('/')}}admin/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>

	<link href="{{ URL::asset('/')}}admin/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>

	<!-- END PAGE LEVEL STYLES -->

	<link rel="shortcut icon" href="{{ URL::asset('/')}}admin/image/favicon.ico" />


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

	<script src="{{ URL::asset('/')}}admin/js/jquery.uniform.min.js" type="text/javascript" ></script>
	<script src="{{ URL::asset('/')}}admin/js/session.js" type="text/javascript" ></script>


	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="{{ URL::asset('/')}}admin/js/app.js" type="text/javascript"></script>

	<script src="{{ URL::asset('/')}}admin/js/index.js" type="text/javascript"></script>




	<style>
		.table th, .table td {
			text-align: center !important;
		}
		.chat-form {
		    background-color: #4B8DF8 !important;
		    border-top: 1px solid !important;
		    padding-left: 0px !important;
	</style>
	<style>
		*{ margin:0; padding:0; list-style:none;}
		a{ text-decoration:none;}
		a:hover{ text-decoration:none;}
		.tcdPageCode{padding: 15px 0px;text-align: left;color: #ccc;}
		.tcdPageCode a{display: inline-block;color: #428bca;display: inline-block;height: 25px;	line-height: 25px;	padding: 0 10px;border: 1px solid #ddd;	margin: 0 2px;border-radius: 4px;vertical-align: middle;}
		.tcdPageCode a:hover{text-decoration: none;border: 1px solid #428bca;}
		.tcdPageCode span.current{display: inline-block;height: 25px;line-height: 25px;padding: 0 10px;margin: 0 2px;color: #fff;background-color: #428bca;	border: 1px solid #428bca;border-radius: 4px;vertical-align: middle;}
		.tcdPageCode span.disabled{	display: inline-block;height: 25px;line-height: 25px;padding: 0 10px;margin: 0 2px;	color: #bfbfbf;background: #f2f2f2;border: 1px solid #bfbfbf;border-radius: 4px;vertical-align: middle;}
	</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->

<body class="page-header-fixed">
    <input type="hidden" id="louding_img" value="{{ URL::asset('/')}}watermark/load.jpg"/>
	<!-- BEGIN HEADER -->
     @include('admin.static.header')
	<!-- END HEADER -->

	<!-- BEGIN CONTAINER -->

	<div class="page-container">

		<!-- BEGIN SIDEBAR -->
         @include('admin.static.leftbar')
		<!-- END SIDEBAR -->

		<!-- BEGIN PAGE -->

		<div class="page-content">
            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->

            <div id="portlet-config" class="modal hide">

                <div class="modal-header">

                    <button data-dismiss="modal" class="close" type="button"></button>

                    <h3>Widget Settings</h3>

                </div>

                <div class="modal-body">

                    Widget settings form goes here

                </div>

            </div>

            <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid liickkk" style="min-height:875px;">
                 @yield('content')
			</div>

			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->

	</div>

	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->
     @include('admin.static.footer')


	<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>

	<script>

		jQuery(document).ready(function() {    

		   App.init(); // initlayout and core plugins
		   
		   Index.init();


		   //Index.initJQVMAP(); // init index page's custom scripts

		   //Index.initCalendar(); // init index page's custom scripts

		   //Index.initCharts(); // init index page's custom scripts

		   //Index.initChat();

		   //Index.initMiniCharts();

		   //Index.initDashboardDaterange();

		   //Index.initIntro();

		   //TableEditable.init();

		});

	</script>

	<!-- END JAVASCRIPTS -->
{{--所有请求头加入CSRF--}}
<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	function get_allbox(){
		var id_array=new Array();
		$("INPUT[type='checkbox']").each(function(){
			if($(this).attr('checked')){
				id_array.push($(this).val());//向数组中添加元素
			}
		});
		var idstr=id_array.join(',');//将数组元素连接起来以构建一个字符串
		return idstr;
	}
</script>