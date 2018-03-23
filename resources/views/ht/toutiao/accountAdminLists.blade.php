@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            注：最高权限为“超级权限”，默认是一般权限(加盟用户)，加盟商菜单权限可在修改的地方控制；新增员工权限和财务权限，这2种权限依然需要分配菜单权限，但会比一般菜单权限多出一部分超极权限才有的功能！<br/>
            举个例子：一般的账号，即便分配财务的菜单显示功能，也无法正常使用！
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    <button class="btn btn-primary" onclick="$('#myModal').modal('show')"><i class="fa fa-plus "></i> 添加账户</button>
                </caption>
                <tr>
                    <td>账号ID</td>
                    <td>名称</td>
                    <td>账户</td>
                    <td>角色权限</td>
                    <td>状态</td>
                    <td>操作</td>
                </tr>
                @if(!empty($accountAdminLists))
                    @foreach($accountAdminLists as $accountAdminList)
                        <tr>
                            <td>{{$accountAdminList->id}}</td>
                            <td>{{$accountAdminList->usname}}</td>
                            <td>{{$accountAdminList->uaccount}}</td>
                            <td>{{$accountAdminList->role==-1?"超级权限":($accountAdminList->role==0?"加盟用户":($accountAdminList->role==1?"员工权限":($accountAdminList->role==2?"财务权限":"异常")))}}</td>
                            <td style="color: {{$accountAdminList->status==1?"green":($accountAdminList->status==2?"red":"#F36A5A")}}">
                                {{$accountAdminList->status==1?"正常":($accountAdminList->status==2?"封禁":"未知")}}</td>
                            <td>
                                <button class="btn btn-success btn-md" onclick="a('{{$accountAdminList->id}}')"><i class="fa fa-search "></i> 查看小编</button>
                                <button class="btn btn-primary btn-md" onclick="b({{$accountAdminList->id}})" ><i class="fa fa-gears "></i> 权限操作</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <ul id="foot_ul" class="pagination" style="margin-bottom: 0;"></ul>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action="/{{htname}}/saveHtAccount" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            新增加盟商账户
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="page" value="{{$page}}" class="form-control" >
                        <input type="hidden" name="pageSize" value="{{$pageSize}}" class="form-control" >
                        <div class="form-group">
                            <label for="name">加盟商名称</label>
                            <input type="text" name="usname" class="form-control" placeholder="加盟商名称" />
                        </div>
                        <div class="form-group">
                            <label for="name">加盟商账户</label>
                            <input type="text" name="uaccount" class="form-control" placeholder="加盟商账户" />
                        </div>
                        <div class="form-group">
                            <label for="name">密码</label>
                            <input type="text" name="uspwd" class="form-control" placeholder="密码" />
                        </div>
                        <div class="form-group">
                            <label for="name">账号状态</label>
                            <div class="radio3 radio-check radio-success radio-inline">
                                <input type="radio" id="radio5" name="status" value="1" checked>
                                <label for="radio5">正常</label>
                            </div>
                            <div class="radio3 radio-check radio-warning radio-inline">
                                <input type="radio" id="radio6" name="status" value="2">
                                <label for="radio6">封禁</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">角色权限</label>
                            {{--1 员工 2 财务 3 待定--}}
                            <div class="radio3 radio-check radio-inline">
                                <input type="radio" id="radio0" name="role" value="0" checked>
                                <label for="radio0">默认</label>
                            </div>
                            <div class="radio3 radio-check radio-success radio-inline">
                                <input type="radio" id="radio3" name="role" value="1">
                                <label for="radio3">员工</label>
                            </div>
                            <div class="radio3 radio-check radio-warning radio-inline">
                                <input type="radio" id="radio4" name="role" value="2">
                                <label for="radio4">财务</label>
                            </div>
                        </div>
                        <div class="form-group">
                            注：分配角色权限后，记得修改对应的菜单权限
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                        </button>
                        <input type="submit" class="btn btn-success" value="更改"/>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </form>
    </div>
@endsection

@include("ht.layouts._pagination")
@section('script')
    <script>
        $(function () {
            setPaging(parseInt({{$last}}),"accountList","?");
        });
        function a(e) {
            // 小编筛选
            window.location.href="/{{htname}}/accountAscription?admin_id="+e;
            {{--window.location.href="/{{htname}}/toutiaoAccountList?keyword="+e+"&keyType=只搜归属";--}}
        }
        function b(e) {
            progress(8);
            window.location.href="/{{htname}}/htAccountDetail?id=" + e ;
        }
    </script>
@endsection
