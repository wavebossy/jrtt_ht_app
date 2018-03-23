@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    <button class="btn btn-primary" onclick="$('#myModal').modal('show')"><i class="fa fa-plus "></i> 添加小编</button>
                </caption>
                <tr>
                    {{--<td>ID</td>--}}
                    <td>名称</td>
                    @if(session("admin")->role == -1)
                        <td>加盟商名称</td>
                    @endif
                    <td>账户</td>
                    <td>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                在职状况
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a style="color: green;" href="/{{htname}}/accountAscription?op_types=1&admin_id={{$admin_id}}">全职</a></li>
                                <li class="divider"></li>
                                <li><a style="color: #ffaa26;" href="/{{htname}}/accountAscription?op_types=2&admin_id={{$admin_id}}">兼职</a></li>
                                <li class="divider"></li>
                                <li><a style="color: #F36A5A;" href="/{{htname}}/accountAscription?op_types=3&admin_id={{$admin_id}}">离职</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>运营数量</td>
                    <td>已运营领域</td>
                    {{--<td>密码</td>--}}
                    <td>备注</td>
                    <td>操作</td>
                </tr>
                @if(!empty($accountAscriptions))
                    @foreach($accountAscriptions as $accountAscription)
                        <tr>
                            {{--<td>{{$accountAscription->id}}</td>--}}
                            <td>{{$accountAscription->usname}}</td>
                            @if(session("admin")->role == -1)
                                <td>{{$accountAscription->admin_usname}}</td>
                            @endif
                            <td>{{$accountAscription->uaccount}}</td>
                            <td style="color: {{$accountAscription->op_types==1?"green":($accountAscription->op_types==2?"#ffaa26":($accountAscription->op_types==3?"#F36A5A":"red"))}}">{{$accountAscription->op_types==1?"全职":($accountAscription->op_types==2?"兼职":($accountAscription->op_types==3?"离职":"异常"))}}</td>
                            <td>{{$accountAscription->account_number}}</td>
                            <td>
                                @if(!empty($accountAscription->fields))
                                    @foreach($accountAscription->fields as $field)
                                        <label>{{$field->field}}</label>
                                        @if($loop->iteration%4 == 0)<br/>@endif
                                    @endforeach
                                @endif
                            </td>
                            {{--<td>{{$accountAscription->uspwd}}</td>--}}
                            <td>{{$accountAscription->remark}}</td>
                            <td>
                                <button class="btn btn-success btn-md" onclick="a('{{$accountAscription->usname}}')"><i class="fa fa-search "></i> 管辖账户</button>
                                <button class="btn btn-primary btn-md" onclick="updateAscriptions({{$accountAscription->id}})" ><i class="fa fa-edit "></i> 修改</button>
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
        <form action="/{{htname}}/saveHtAscription" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            新增小编
                        </h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="page" value="{{$page}}" class="form-control" >
                        <input type="hidden" name="pageSize" value="{{$pageSize}}" class="form-control" >
                        <div class="form-group">
                            <label for="name">小编名称</label>
                            <input type="text" name="usname" class="form-control" placeholder="小编名称" />
                        </div>
                        <div class="form-group">
                            <label for="name">小编账户</label>
                            <input type="text" name="uaccount" class="form-control" placeholder="小编账户" />
                        </div>
                        <div class="form-group">
                            <label for="name">密码</label>
                            <input type="text" name="uspwd" class="form-control" placeholder="密码" />
                        </div>
                        <div class="form-group">
                            <label for="name">在职状态</label>
                            <div class="radio3 radio-check radio-success radio-inline">
                                <input type="radio" id="radio11" name="op_types" value="1" checked>
                                <label for="radio11">全职</label>
                            </div>
                            <div class="radio3 radio-check radio-success radio-inline">
                                <input type="radio" id="radio12" name="op_types" value="2" >
                                <label for="radio12">兼职</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">账户状态</label>
                            {{--（封禁无法登入）--}}
                            <div class="radio3 radio-check radio-success radio-inline">
                                <input type="radio" id="radio1" name="status" value="1" checked>
                                <label for="radio1">正常</label>
                            </div>
                            <div class="radio3 radio-check radio-warning radio-inline">
                                <input type="radio" id="radio2" name="status" value="2" >
                                <label for="radio2">封禁</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">备注</label>
                            <input type="text" name="remark" class="form-control" placeholder="备注" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                        </button>
                        <input type="submit" class="btn btn-success" value="添加"/>
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
            setPaging(parseInt({{$last}}),"accountAscription","?admin_id={{$admin_id}}&op_types={{$op_types}}&");
        });
        function a(e) {
            window.location.href="/{{htname}}/toutiaoAccountList?keyword="+e+"&keyType=只搜小编";
        }
        function updateAscriptions(e) {
            window.location.href="/{{htname}}/htAscriptionDetail?id=" + e ;
        }
    </script>
@endsection
