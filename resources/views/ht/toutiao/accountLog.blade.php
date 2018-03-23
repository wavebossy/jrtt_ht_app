@extends('ht.layouts._ht_layout')
{{--style 在下面--}}
@section('content')
    @include('ht.layouts._myalert')
    @if(session("admin")->role == -1 or session("admin")->role == 1)
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <table class="table table-bordered table-striped">
                    <caption>
                        {{--头条号账号<br>--}}
                        <div style="width: 60%">
                            <form action="/{{htname}}/toutiaoAccountList" id="searchForm">
                                <div class="input-group">
                                    <input type="text" name="keyword" value="{{$keyword}}" placeholder="搜索内容" class="form-control">
                                    <span class="input-group-btn">
                                        <button type="submit" id="btn_keyType" class="btn btn-default">搜索</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </caption>
                    <tr>
                        <td>操作账号</td>
                        <td>ID</td>
                        <td>序列号</td>
                        <td>头条ID</td>
                        <td>头条号名称</td>
                        <td>领域</td>
                        <td>手机号</td>
                        <td>指数</td>
                        <td>邮箱</td>
                        <td>主体</td>
                        <td>联系人</td>
                        <td>认证</td>
                        <td>小编</td>
                        <td>账号归属</td>
                        <td>账号状态</td>
                        <td>备注</td>
                        <td>操作时间</td>
                        <td>操作</td>
                    </tr>
                    @if(!empty($articles))
                        @foreach($articles as $article)
                            <tr>
                                <td>{{$article->ht_admin}}</td>
                                <td>{{$article->tt_admin->id}}</td>
                                <td>{{$article->tt_admin->serial}}</td>
                                <td><a title="{{$article->tt_admin->ttid}}" onclick="alert({{$article->tt_admin->ttid}})">{{mb_substr($article->tt_admin->ttid,0,3)}}...</a></td>
                                <td>{{$article->tt_admin->account}}</td>
                                <td>{{$article->tt_admin->field}}</td>
                                <td>{{$article->tt_admin->phone}}</td>
                                <td>{{$article->tt_admin->tindex}}</td>
                                <td>{{$article->tt_admin->mailboxs}}</td>
                                <td>{{$article->tt_admin->subject}}</td>
                                <td>{{$article->tt_admin->contacts}}</td>
                                <td><a style="color: {{$article->tt_admin->isauth==1?"gray":($article->tt_admin->isauth==2?"green":"#F36A5A")}}" href="#">{{$article->tt_admin->isauth==1?"未认证":($article->tt_admin->isauth==2?"已认证":($article->tt_admin->isauth==3?"被封禁":"异常"))}}</a></td>
                                <td>{{$article->tt_admin->operator}}</td>
                                <td>{{$article->tt_admin->power}}</td>
                                <td><a style="color: {{$article->tt_admin->status==1?"gray":($article->tt_admin->status==2?"green":"#F36A5A")}}" href="#">{{$article->tt_admin->status==1?"新手号":($article->tt_admin->status==2?"正常号":($article->tt_admin->status==3?"商品号":"异常"))}}</a></td>
                                <td>{{$article->tt_admin->remark}}</td>
                                <td>{{$article->times}}</td>
                                <td><button class="btn btn-warning  btn-md" onclick="toutiaoAccountDetail({{$article->id}})" ><i class="fa fa-edit "></i> 删除</button></td>
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
    @endif
@endsection
@include("ht.layouts._pagination")
@section('script')
    <script>
        $(function () {
            setPaging(parseInt({{$last}}),"toutiaoAccountLog","?keyword={{$keyword}}&");
        });
    </script>
@endsection
