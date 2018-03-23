@extends('xbht.layouts._ht_layout')

@section('style')
    <style>
        .articles_ul{
            margin: 0;
            padding: 0;
            list-style:none;
        }
        .articles_ul li{
            float: left;
            padding: 1rem;
        }
        /*li{*/
            /*height: 1rem;*/
        /*}*/
    </style>
@endsection
@section('page_header')

    <h1 class="page-header">
        账号管理
        <small>
            头条账号信息，存储
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">系统主页</a></li>
        {{--<li><a href="#"></a></li>--}}
        <li class="active">历史提交</li>
    </ol>

@endsection


@section('content')
    @include('xbht.layouts._myalert')

    <div>
        <ul class="articles_ul">
            <li><a href="/{{htname}}/toutiaoArticle?types=全部"><button class="btn btn-link ul_li_a_but">全部</button></a></li>
                @foreach($toutiaoTypes as $toutiaoType)
                    <li>
                        <a href="/{{htname}}/toutiaoArticle?types={{$toutiaoType->chinese_tag}}"><button class="btn btn-link ul_li_a_but">{{$toutiaoType->chinese_tag}}</button></a>
                    </li>
                @endforeach
            <li><a href="/{{htname}}/toutiaoArticle?types=其他"><button class="btn btn-link ul_li_a_but">其他</button></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    <div class="row" style=";padding: 0;margin: 0;">
                        <form action="/{{htname}}/toutiaoAccountList">
                            <div class="input-group col-md-3 col-sm-3 col-xs-3" style="float: left;padding: 0;margin: 0;">
                                <span class="input-group-addon">从</span>
                                <input type="date" class="form-control" placeholder="twitterhandle">
                            {{--</div>--}}
                            {{--<div class="input-group col-md-3 col-sm-3 col-xs-3">--}}
                                <span class="input-group-addon">到</span>
                                <input type="date" class="form-control" placeholder="twitterhandle">
                            </div>
                            <div class="input-group col-md-3 col-sm-3 col-xs-3" style="float: left;padding: 0;margin: 0;">
                                <input type="text" name="keyword" placeholder="标题搜索" class="form-control">
                                <span class="input-group-addon">搜索</span>
                            </div>
                            <div class="btn-group" style="float: left;padding: 0;margin: 0;">
                                <button type="button" class="btn btn-info">今日爆文抓取</button>
                            </div>
                        </form>
                    </div>
                </caption>
                <tr>
                    {{--<td>是否原创</td>--}}
                    <td>标题</td>
                    <td>评论数</td>
                    <td>阅读数</td>
                    {{--<td>作者</td>--}}
                    <td>标签</td>
                    <td>发文时间</td>
                </tr>
                @if(!empty($articles))
                    @foreach($articles as $article)
                        <tr>
                            <td><a target="_blank" href="https://www.toutiao.com/{{$article->source_url}}">{{$article->titles}}</a></td>
                            <td>{{$article->comments_count}}</td>
                            <td>{{$article->go_detail_count}}</td>
                            <td>{{$article->chinese_tag}}</td>
                            <td>{{date("Y-m-d H:i:s",$article->behot_time)}}</td>
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

@include("ht.layouts._pagination")
@section('script')
    <script>
        var types = "{{$types}}";
        setPaging(parseInt({{$last}}),"toutiaoArticle","?types="+types+"&");
        $(function () {
            var pathname = window.location.pathname;
            $("a").removeClass("active-menu");
            $.each($("a"),function () {
                if($(this).attr("href") == pathname){
                    $(this).addClass("active-menu")
                }
            });
            $(".ul_li_a_but").on("click",function () {
                $(".ul_li_a_but").removeClass("btn-success").removeClass("btn-link").addClass("btn-link");
                $(this).addClass("btn-success").removeClass("btn-link");
                types = $(this).html();
            }).each(function () {
                var htmls = $(this).html();
                if( htmls == "{{$types}}"){
                    $(this).addClass("btn-success").removeClass("btn-link");
                }
            });
        });
    </script>
@endsection
