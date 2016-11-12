@extends('admin.layout.master')
@section('content')
    <style>
        .top-inputall input{
            color: white;
            margin-left: 10px;
            margin-top: 10px;;
        }
    </style>
    <form id="submitexcel" action="excelpush" method="post">
        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="hidden" value="" id="user_id_all" name="id">

    </form>
    <div style="background:#F0F0F0;padding-left: 10px;"><h3>{{$title}}</h3></div>
    <div>
        {{--顶部按钮组--}}
        <div class="top-inputall">
            <input class="btn" style="background: #FF3300;margin-left: 0;" type="button" value="全选" onclick="selectAll()">
            <input class="btn" style="background: #FF3300;" type="button" value="反选" onclick="invertSelect()">
           <!--  <input class="btn" style="background: #FFCC33;" type="button" value="模板消息推送" onclick="modimgpush()" > -->
            <input class="btn" style="background: #909090;" type="button" value="停用选中用户" onclick="userstateoff()">
            <input class="btn blue" type="button" value="恢复选中用户" onclick="userstateon()">
            <input  class="btn" style="background: #00CC00;" type="button" value="导出为EXCEL" onclick="excelpush()" >
            <form action="/admin/users" style="float: right;">
                <input class="form-control" name="search" type="text" style="float: right;margin-top: 11px;color: #000000;" placeholder="输入昵称或手机号搜索">
            </form>
            <div style="clear: both"></div>
        </div>

        <div style="margin-top: 10px;">
            <table class="table table-striped">
                    <tr>
                        <th>\</th>
                        <th>用户编号</th>
                        <th>状态标识</th>
                        <th>昵称</th>
                        <th>性别</th>
                        <th>年龄</th>
                        <th>手机号</th>
                        <th>注册日期</th>
                        <th>提问次数</th>
                        <th>升级成为达人</th>
                        <th>被举报次数</th>
                        <th>详情</th>

                    </tr>
                @foreach($userdata as $item)
                <tr>
                    <td>
                        <div class="checkbox">
                            <label><input class="checkbox-all" name="checkbox[]" type="checkbox" value="{{$item->user_id}}" autocomplete="off"></label>
                        </div>
                    </td>
                    <td>{{$item->user_id}}</td>
                    <td>{{$item->state ==  0 ? "正常":"禁用"}}</td>
                    <td>{{$item->nickname}}</td>
                    <td>{{$item->gender ==1 ? "男":"女"}}</td>
                    <td>{{$item->age}}</td>
                    <td>{{$item->tel}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>{{countquestion($item->user_id)}}</td>
                    <td>
                        <?php if($item->idcard != 1){
                            echo '<input data-uid="'.$item->user_id.'" onclick="upmaster(this)" class="btn blue upmaster" style="height: 20px;padding: 0 6px;" type="button" value="升级">';
                        }else{
                            echo '<input data-uid="'.$item->user_id.'" onclick="unmaster(this)" class="btn red unmaster" style="height: 20px;padding: 0 6px;" type="button" value="取消">';
                        }
                        ?>
                    </td>
                    <td>{{countAccusation($item->user_id)}}</td>
                    <td class="userdetails" val="{{$item->user_id}}" style="cursor: pointer;">
                    <input class="btn" style="background: #00CC00;color:white;" type="button" value='进入'>
                    </td>
                </tr>
                @endforeach
            </table>

            <form action="{{URL::asset('admin/users')}}" method="get" id="cinemaforselect">
                <input type="hidden" id="page" name="page"  value='1'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
            <table style="margin-left: auto; margin-right: 2px;">
                <tfoot>
                <tr>
                    <td>
                        <div id="show_page" class="dataTables_paginate paging_bootstrap pagination">
                            <ul class="">
                                @if($userdata->currentPage()>1)
                                    <li><a data-original-title="首页" href="{{$userdata->url(1)}}">&lt;&lt;</a></li>
                                    <li><a data-original-title="上一页" href="{{$userdata->url($userdata->currentPage()-1)}}">&lt;</a></li>
                                @else
                                    <li><a data-original-title="首页" href="{{$userdata->url(1)}}" style="pointer-events: none; color: rgb(175, 175, 175); cursor: default;">&lt;&lt;</a></li>
                                    <li><a data-original-title="上一页" href="{{$userdata->url($userdata->currentPage()-1)}}" style="pointer-events: none; color: rgb(175, 175, 175); cursor: default;">&lt;</a></li>
                                @endif
                                <li class="active"><a id="_a_page">{{$userdata->currentPage()}}/{{$userdata->lastPage()}}</a></li>
                                @if($userdata->nextPageUrl()!="")
                                    <li><a data-original-title="下一页" href="{{$userdata->nextPageUrl()}}" >&gt;</a></li>
                                    <li><a data-original-title="尾页" href="{{$userdata->url($userdata->lastPage())}}" >&gt;&gt;</a></li>
                                @else
                                    <li><a data-original-title="下一页" href="{{$userdata->nextPageUrl()}}"  style="pointer-events: none; color: rgb(175, 175, 175); cursor: default;" >&gt;</a></li>
                                    <li><a data-original-title="尾页" href="{{$userdata->url($userdata->lastPage())}}"  style="pointer-events: none; color: rgb(175, 175, 175); cursor: default;">&gt;&gt;</a></li>
                                @endif
                                <li>
                                    <div class="filter-search btn-group pull-right">
                                        <select style="width: 120px;" id="_page_currentPage" class="_page_currentPage" onchange="page_acv()">
                                            @for($i=1;$i<=$userdata->lastPage();$i++)
                                                @if($userdata->currentPage()==$i)
                                                    <option value="{{$userdata->url($i)}}" selected="selected">{{$i}}</option>
                                                @else
                                                    <option value="{{$userdata->url($i)}}">{{$i}}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>



        </div>
    </div>

    <script>
        $(function(){
            $('.users').addClass('active');
        })
    //去详情页
    $('.userdetails').click(function(){
        var user_id = $(this).attr('val');
        window.location.href = "userdetails?user_id="+user_id;
    })
        //分页url加载
        $("#_page_currentPage").change(function(){
            var url =$(this);
            if(url.val()!=""){
                $('#page').attr('value',url.val().split('?')[1].split('=')[1]);
                $("#cinemaforselect").submit();
            }
        });
        $('#show_page a').click(function(event){
            event.preventDefault();
            $('#page').attr('value', this.href.split('?')[1].split('=')[1]);
            $("#cinemaforselect").submit();
        });

//全选/反选JS
        function selectAll(){
            $("INPUT[type='checkbox']").each( function() {
                $(this).attr('checked', true);
                $(this).parents('.checkbox').find('span').addClass('checked');
            });
        }

        function invertSelect(){
            $("INPUT[type='checkbox']").each( function() {
                if($(this).attr('checked')) {
                    $(this).attr('checked', false);
                    $(this).parents('.checkbox').find('span').removeClass('checked');
                } else {
                    $(this).attr('checked', true);
                    $(this).parents('.checkbox').find('span').addClass('checked');
                }
            });
        }
        //模板消息推送
        function modimgpush(){
            alert("模板消息申请后制作！！");
        }
        //导出表格
        function excelpush(){
            var  idarr =  get_allbox();
            if(idarr == ""){alert("请选择用户！");return false;}
            $("#user_id_all").val(idarr);

            $("#submitexcel").submit();

        }
        //停用用户，启用用户
        function userstateon(){
           var  idarr =  get_allbox();
            if(idarr == ""){alert("请选择用户！");return false;}
            if(confirm("确定启用选中的用户么？选中ID:"+idarr)){
                $.post('userstate',{id:idarr,state:0},function(res){
                    if(res == "ok"){
                        location.reload();
                    }else {
                        alert("网络或系统错误，请检查");
                    }
                });
            }
        }
        function userstateoff(){
            var  idarr =  get_allbox();
            if(idarr == ""){alert("请选择用户！");return false;}
            if(confirm("确定停用选中的用户么？ 选中ID:"+idarr)){
                $.post('userstate',{id:idarr,state:1},function(res){
                    if(res == "ok"){
                        location.reload();
                    }else {
                        alert("网络或系统错误，请检查");
                    }
                });
            }
        }
    </script>

    <script type="text/javascript">
        function unmaster(obj){
            var id=obj.getAttribute("data-uid");
            if(confirm("确定取消达人身份？，成为普通用户！")){
                $.post('upmaster',{uid:id,state:0},function(res){
                    if(res == "ok"){
                        location.reload();
                    }else {
                        alert("网络或系统错误，请检查");
                    }
                });
            }

        }
        function upmaster(obj){
            var id=obj.getAttribute("data-uid");
            if(confirm("确定将此用户升级成为达人？")){
                $.post('upmaster',{uid:id,state:1},function(res){
                    if(res == "ok"){
                        window.location.href = "/admin/masterdetails?user_id="+id;
                    }else {
                        alert("网络或系统错误，请检查");
                    }
                });
            }

        }
    </script>
@stop
