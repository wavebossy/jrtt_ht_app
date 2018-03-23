@extends('ht.layouts._ht_layout')
{{--style 在下面--}}
@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-striped">
                <caption>
                    {{--头条号账号<br>--}}
                    <div style="width: 60%">
                        <form action="/{{htname}}/toutiaoAccountList" id="searchForm">
                            <div class="input-group">
                                <input type="text" name="keyword" value="{{$keyword}}" placeholder="搜索内容" class="form-control">
                                <input type="hidden" name="keyType" value="{{$keyType}}" >
                                <input type="hidden" name="types" value="{{$types}}" >
                                <input type="hidden" name="isauth" value="{{$isauth}}" >
                                <span class="input-group-btn">
                                    <button type="submit" id="btn_keyType" class="btn btn-default">{{$keyType}}</button>
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">切换下拉菜单</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" id="search_ul">
                                        <li><a href="#" data-target="subject">默认搜索</a></li>
                                        <li><a href="#" data-target="subject">只搜主体</a></li>
                                        <li><a href="#" data-target="contacts">只搜联系人</a></li>
                                        <li><a href="#" data-target="operator">只搜小编</a></li>
                                        <li><a href="#" data-target="phone">只搜手机</a></li>
                                        <li><a href="#" data-target="field">只搜领域</a></li>
                                        <li><a href="#" data-target="field">只搜归属</a></li>
                                    </ul>
                                </span>
                                <span class="btn btn-success" onclick="offon(this)"><i class="fa fa-edit "></i> 开启编辑</span>
                            </div>
                        </form>
                    </div>
                </caption>
                <tr>
                    <td>选择</td>
                    <td>序列号</td>
                    @if(session("admin")->role == -1 or session("admin")->role == 1)
                        <td>头条ID</td>
                    @endif
                    <td>头条号名称</td>
                    <td>领域</td>
                    <td>手机号</td>
                    <td>指数</td>
                    <td>邮箱</td>
                    <td>主体</td>
                    <td>联系人</td>
                    <td>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                是否认证
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a style="color: gray;" href="/{{htname}}/toutiaoAccountList?types={{$types}}&isauth=1">未认证</a></li>
                                <li class="divider"></li>
                                <li><a style="color: green;" href="/{{htname}}/toutiaoAccountList?types={{$types}}&isauth=2">已认证</a></li>
                                <li class="divider"></li>
                                <li><a style="color: #F36A5A;" href="/{{htname}}/toutiaoAccountList?types={{$types}}&isauth=3">被封禁</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>小编</td>
                    <td>账号归属</td>
                    <td>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                账号状态
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a style="color: gray;" href="/{{htname}}/toutiaoAccountList?types=1&isauth={{$isauth}}">新手号</a></li>
                                <li class="divider"></li>
                                <li><a style="color: green;" href="/{{htname}}/toutiaoAccountList?types=2&isauth={{$isauth}}">正常号</a></li>
                                <li class="divider"></li>
                                <li><a style="color: #F36A5A;" href="/{{htname}}/toutiaoAccountList?types=3&isauth={{$isauth}}">商品号</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>操作</td>
                </tr>
                @if(!empty($articles))
                    @foreach($articles as $article)
                        <tr>
                            <td><input type="checkbox" /></td>
                            <td><a href="#" class="table_td_data" data-name="serial" data-id="{{$article->id}}" >{{$article->serial}}</a></td>
                            @if(session("admin")->role == -1 or session("admin")->role == 1)
                                <td><a title="{{$article->ttid}}" onclick="alert({{$article->ttid}})">{{mb_substr($article->ttid,0,3)}}...</a></td>
                            @endif
                            <td><a href="#" class="table_td_data" data-name="account" data-id="{{$article->id}}" >{{$article->account}}</a></td>
                            <td><a href="#" class="table_td_data" data-name="field" data-id="{{$article->id}}" >{{$article->field}}</a></td>
                            <td><a href="#" class="table_td_data" data-name="phone" data-id="{{$article->id}}" >{{$article->phone}}</a></td>
                            <td><a href="#" class="table_td_data" data-name="tindex" data-id="{{$article->id}}" >{{$article->tindex}}</a></td>
                            <td><a href="#" class="table_td_data" data-name="mailboxs" data-id="{{$article->id}}" >{{$article->mailboxs}}</a></td>
                            <td><a href="#" class="table_td_data" data-name="subject" data-id="{{$article->id}}" >{{$article->subject}}</a></td>
                            <td><a href="#" class="table_td_data" data-name="contacts" data-id="{{$article->id}}" >{{$article->contacts}}</a></td>
                            <td>
                                <a style="color: {{$article->isauth==1?"gray":($article->isauth==2?"green":"#F36A5A")}}" href="#" class="table_td_data_select_isauth" data-value="{{$article->isauth}}" data-name="isauth" data-id="{{$article->id}}" >{{$article->isauth==1?"未认证":($article->isauth==2?"已认证":($article->isauth==3?"被封禁":"异常"))}}</a>
                            </td>
                            <td>{{$article->operator}}</td>
                            <td>{{$article->power}}</td>
                            <td>
                                <a style="color: {{$article->status==1?"gray":($article->status==2?"green":"#F36A5A")}}" href="#" class="table_td_data_select_status" data-value="{{$article->status}}" data-name="status" data-id="{{$article->id}}" >{{$article->status==1?"新手号":($article->status==2?"正常号":($article->status==3?"商品号":"异常"))}}</a>
                            </td>
                            <td>
                                {{--功能有用，暂时隐藏--}}
                                {{--<button class="btn btn-success btn-md" onclick="toutiaoAccountCapitalDetailed({{$article->id}})" >明细</button>--}}
                                <button class="btn btn-primary btn-md" onclick="toutiaoAccountDetail({{$article->id}})" ><i class="fa fa-edit "></i> 操作</button>
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

@endsection
@section('style')
    {{-- 文档 ================ http://vitalets.github.io/x-editable/docs.html--}}
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <style>
        .___red{
            background: #f36a5a;
        }
    </style>
    {{--onclick="$(this).toggleClass('___red')"--}}
@endsection
@include("ht.layouts._pagination")
@section('script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script>
        $(function () {
            //$.fn.editable.defaults.mode = 'inline'; // 默认 popup
            $('.table_td_data').editable({
                disabled:true,
                name:$(this).attr("data-name"),
                type: "text",
                url: "/{{htname}}/toutiaoAccountUpdate",
                pk: 1,
                params: function(params) {
                    //originally params contain pk, name and value
                    params.types = 666; // 不等于1 就行
                    params.id = $(this).attr("data-id");
                    //params._token = "{{csrf_token()}}"; // 只能过滤
                    return params;
                },
                value:''
            });
            $('.table_td_data_select_isauth').editable({
                disabled:true,
                source: [{value: 1, text: '未认证'},{value: 2, text: '已认证'},{value: 3, text: '被封禁'}],
                name:$(this).attr("data-name"),
                type: "select",
                url: "/{{htname}}/toutiaoAccountUpdate",
                pk: 1,
                params: function(params) {
                    //originally params contain pk, name and value
                    params.types = 666; // 不等于1 就行
                    params.id = $(this).attr("data-id");
                    //params._token = "{{csrf_token()}}"; // 只能过滤
                    return params;
                },
                value:$(this).attr("data-value")
            });
            $('.table_td_data_select_status').editable({
                disabled:true,
                source: [{value: 1, text: '新手号'},{value: 2, text: '正常号'},{value: 3, text: '商品号'}],
                name:$(this).attr("data-name"),
                type: "select",
                url: "/{{htname}}/toutiaoAccountUpdate",
                pk: 1,
                params: function(params) {
                    //originally params contain pk, name and value
                    params.types = 666; // 不等于1 就行
                    params.id = $(this).attr("data-id");
                    //params._token = "{{csrf_token()}}"; // 只能过滤
                    return params;
                },
                value:$(this).attr("data-value")
            });
            $("#search_ul li a").on("click",function () {
                //$("#btn_keyType").html($(this).html());
                $("input[name='keyType']").val($(this).html());
                $("#searchForm").submit();
            });
            setPaging(parseInt({{$last}}),"toutiaoAccountList","?types={{$types}}&isauth={{$isauth}}&keyword={{$keyword}}&keyType={{$keyType}}&");
        });
        function offon(e) {
            $('.table_td_data,.table_td_data_select_isauth,.table_td_data_select_status').editable('toggleDisabled');
            $(e).toggleClass("btn-info");
            console.log($(e).attr("class"));
            if($(e).attr("class").length > 15){
                $(e).html("关闭编辑");
            }else{
                $(e).html("开启编辑");
            }
        }
    </script>
    <script>
        function search_ul() {
            
        }
        function toutiaoAccountCapitalDetailed(e) {
            progress(8);
            window.location.href="/{{htname}}/toutiaoAccountCapitalDetailed?id=" + e ;
        }
        function toutiaoAccountDetail(e) {
            window.location.href="/{{htname}}/toutiaoAccountDetail?page={{$page}}&pageSize={{$pageSize}}&id=" + e ;
            return;
            $.ajax({
                type: "post",
                url: "/{{htname}}/toutiaoAccountDetail",
                data:{
                    id:e,
                    _token:"{{csrf_token()}}"
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                    progress(3);
                },
                success:function(res){
                    progress(5);
                    var result = res.data.result;// .breadcrumb
                    console.log(result);
                    $("input[name='account']").val(result.account);
                    $("input[name='field']").val(result.field);
                    $("input[name='id']").val(result.id);
                    $("input[name='mailboxs']").val(result.mailboxs);
                    $("input[name='operator']").val(result.operator);
                    $("input[name='phone']").val(result.phone);
                    $("input[name='pwd']").val(result.pwd);
                    $("input[name='remark']").val(result.remark);
                    $("input[name='serial']").val(result.serial);
                    $("input[name='subject']").val(result.subject);
                    $("input[name='contacts']").val(result.contacts);
                    $("input[name='ttid']").val(result.ttid);
                    $("input[name='status']").val(result.status);
                    $("#myModal").modal("show");
                    progress(10);
                },
                error: function (XMLHttpRequest) {
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
    </script>
@endsection
