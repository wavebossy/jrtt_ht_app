@extends('ht.layouts._ht_layout')

@section('style')
    <style>
        ul {
            list-style-type: none;
        }
        .ul_1{
            margin: 0;padding: 0;
        }
    </style>
@endsection

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <form role="form" action="/{{htname}}/saveHtAccount" method="post" >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <input type="hidden" name="id" value="{{$htAccountDetail->id}}" />
                <div class="form-group">
                    <label for="name">姓名</label>
                    <input type="text" class="form-control" name="usname" value="{{$htAccountDetail->usname}}" placeholder="姓名">
                </div>
                <div class="form-group">
                    <label for="name">账户</label>
                    <input type="text" class="form-control" name="uaccount" value="{{$htAccountDetail->uaccount}}" placeholder="账户">
                </div>
                <div class="form-group">
                    <label for="name">重置密码</label>
                    <input type="text" class="form-control" name="uspwd" value="" placeholder="重置密码(为空不修改)">
                </div>
                <div class="form-group">
                    <label for="name">账号状态</label>
                    {{--（1正常 2 封禁无法登入）--}}
                    {{--<input type="text" class="form-control" name="status" value="{{$htAccountDetail->status}}" placeholder="状态">--}}
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio1" name="status" value="1" {{$htAccountDetail->status == 1?"checked":""}}>
                        <label for="radio1">正常</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio2" name="status" value="2" {{$htAccountDetail->status == 2?"checked":""}}>
                        <label for="radio2">封禁</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">角色权限</label>
                    {{--1 员工 2 财务 3 待定--}}
                    <div class="radio3 radio-check radio-inline">
                        <input type="radio" id="radio0" name="role" value="0" {{$htAccountDetail->role == 0?"checked":""}}>
                        <label for="radio0">默认</label>
                    </div>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio3" name="role" value="1" {{$htAccountDetail->role == 1?"checked":""}}>
                        <label for="radio3">员工</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio4" name="role" value="2" {{$htAccountDetail->role == 2?"checked":""}}>
                        <label for="radio4">财务</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">菜单权限配置</label> <br/>
                    {{-- 勾选权限配置 --}}

                    <ul class="ul_1">
                        @foreach(session("menu_controller") as $menu_c)
                            @if($menu_c->parentid == 0)
                                <li>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="{{$menu_c->id}}" data-pid="{{$menu_c->parentid}}" name="power[]" {{ unserialize($htAccountDetail->power)?in_array($menu_c->id,unserialize($htAccountDetail->power))?"checked":"":""}} value="{{$menu_c->id}}">{{$menu_c->menuname}}
                                    </label>
                                    <span style="position: absolute;right: 0">{{$menu_c->smalltext}}</span>
                                </li>
                            @else
                                <li>
                                    <ul class="ul_2">
                                        <li>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="{{$menu_c->id}}" data-pid="{{$menu_c->parentid}}" name="power[]" {{ unserialize($htAccountDetail->power)?in_array($menu_c->id,unserialize($htAccountDetail->power))?"checked":"":""}} value="{{$menu_c->id}}">{{$menu_c->menuname}}
                                            </label>
                                            <span style="position: absolute;right: 0">{{$menu_c->smalltext}}</span>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    {{--<input type="text" class="form-control" name="power" value="{{$htAccountDetail->power}}" placeholder="权限配置">--}}
                </div>
                @if($htAccountDetail->id == 1)
                    <button type="button" class="btn btn-info">超级管理员无法更改</button>
                @else
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle "></i> 保存</button>
                @endif
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(function () {
            $(".ul_1 > li,.ul_2 > li").on("click",function () {
//                console.log($(this).children(":eq(0)").children(":eq(0)").val()); // input

//                console.info($(this).children(":eq(0)").children(":eq(0)").is(':checked'));
                var pid = $(this).children(":eq(0)").children(":eq(0)").attr('data-pid');
                var id = $(this).children(":eq(0)").children(":eq(0)").attr('id');
                if(pid != 0){
                    $.each($("input[type='checkbox']"),function () {
                        if($(this).attr("id") == pid){
                            $(this).attr("checked","checked"); // 把爸爸选上
                        }
                    });
                }else{
                    // 考虑是否有下级
                    $.each($("input[type='checkbox']"),function () {
                        if($(this).attr("data-pid") == id){
                            if($(this).attr("checked")) {
                                $(this).removeAttr("checked");
                            }else{
                                $(this).attr("checked","checked");
                            }
                        }
                    });
                }

                // 防止多层li 点击事件
                event.stopPropagation();
            });
        });
    </script>
@endsection