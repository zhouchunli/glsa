@extends('admin.layout.master')

@section('content')
    <h3 style="background: #00CCFF">权限管理</h3>
    <table class="table table-striped" style="border: 1px solid #00CCFF">

        <tr>
            <th>管理员ID</th>
            <th>管理员名称</th>
            <th>登陆名</th>
            <th>权限分配</th>
        </tr>
        @foreach($data as $value )
            <?php $powerid = explode(",",$value->power);?>
            <tr>
                <td>{{$value->id}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->user_id}}</td>
                <td><input type="button" class="btn btn-default" value="权限"  data-toggle="modal" data-target="#qx_{{$value->id}}"></td>
            </tr>
            <div style="display: none;" class="modal fade bs-example-modal-lg" id="qx_{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{URL::asset("admin/powerpost")}}" method="post" autocomplete = 'off'>
                        @foreach( $menu as $item)
                            @if($item->lv == "1" && $item->url !== "#")
                                <div class="">
                                    <label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        {{$item->name}}
                                        <select class="form-control" name="power_{{$item->id}}" style="width: 80px;">
                                            <option value="1">禁止</option>
                                            <option value="2" <?php if(in_array($item->id,$powerid)){echo"selected='selected'";}?>>允许</option>
                                        </select>
                                    </label>
                                </div>
                            @elseif($item->lv == "1" && $item->url == "#")
                                <div>
                                    <div>&nbsp;&nbsp;&nbsp;&nbsp;⊙{{$item->name}}</div>
                                    @foreach($menu as $subitem)
                                        @if($subitem->group == $item->id)
                                            <div>
                                                <label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;├
                                                    {{$subitem->name}}
                                                    <select class="form-control" name="power_{{$subitem->id}}" style="width: 80px;">
                                                        <option value="1">禁止</option>
                                                        <option value="2" <?php if(in_array($subitem->id,$powerid)){echo"selected='selected'";}?>>允许</option>
                                                    </select>
                                                </label>
                                            </div>

                                        @endif
                                    @endforeach

                                </div>
                            @endif

                        @endforeach
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <input type="hidden" name="admin_id" value="{{$value->id}}">
                            <input type="submit" value="保存" class=" btn btn-block">
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </table>
@stop
