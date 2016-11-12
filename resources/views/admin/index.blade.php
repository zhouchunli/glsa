@extends('admin.layout.master')

@section('content')
    <style>
        body {
            background-color: #000;
            margin: 0 0 ;
            /*overflow: hidden;*/
            font-family:arial;
            color:#fff;
            padding:  0 0 ;;
        }
        h1{
            margin:0;
        }

        a {
            color:#0078ff;
        }
        #canvas{
            width:100%;
            height:900px;
            overflow: hidden;
            position:absolute;
            top:0;
            left:0;
            background-color: #1a1724;
        }
        .canvas-wrap{
            position:relative;

        }
        div.canvas-content{
            position:relative;
            z-index:2000;
            color:#fff;
            text-align:center;
            padding-top:30px;
        }
        .liickkk{
            padding: 0 0 ;
            margin: 0 0 ;
        }
    </style>
    <!--[if IE]>
    <script src="http://libs.baidu.com/html5shiv/3.7/html5shiv.min.js"></script>
    <![endif]-->
    <section class="canvas-wrap">
        <div class="canvas-content">
            <h1>欢迎光临，{{Session::get("username_i")}}</h1>
        </div>
        <div id="canvas" class="gradient"></div>
    </section>
    <!-- Main library -->
    <script src="http://cdn.bootcss.com/three.js/r68/three.min.js"></script>
    <!-- Helpers -->
    <script src="{{ URL::asset('/')}}admin/3Dlines/js/projector.js"></script>
    <script src="{{ URL::asset('/')}}admin/3Dlines/js/canvas-renderer.js"></script>
    <!-- Visualitzation adjustments -->
    <script src="{{ URL::asset('/')}}admin/3Dlines/js/3d-lines-animation.js"></script>
    <script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ URL::asset('/')}}admin/3Dlines/js/color.js"></script>

<!-- The main CSS file -->
<link href="{{ URL::asset('/')}}admin/assets/css/style.css" rel="stylesheet" />

<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<div style="background:#F0F0F0;padding-left: 30px;"><h3>{{$title}}</h3></div>
{{--<h2 style="text-align: center;margin-top: 30px;">欢迎光临，{{Session::get("username_i")}}</h2>--}}
    <div id="clock" class="light" style="margin: 60px auto !important;">
        <div class="display">
            <div class="weekdays"></div>
            <div class="ampm"></div>
            <div class="alarm"></div>
            <div class="digits"></div>
        </div>
    </div>
    <!-- JavaScript Includes -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.0.0/moment.min.js"></script>
    <script src="{{ URL::asset('/')}}admin/assets/js/script.js"></script>
@stop
