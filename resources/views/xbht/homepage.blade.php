@extends('xbht.layouts._ht_layout')

@section('page_header')

    <h1 class="page-header">
        系统主页
        {{--<small>--}}
            {{--基本面板，个人信息，收益状况--}}
        {{--</small>--}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">系统主页</a></li>
        {{--<li><a href="#"></a></li>--}}
        <li class="active">桌面菜单</li>
    </ol>

@endsection

@section('content')

    @include('xbht.layouts._myalert')
    <div class="row">
        {{--<div class="col-xs-6 col-md-3">--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-body easypiechart-panel">--}}
                    {{--<h4>未结算收益</h4>--}}
                    {{--<div class="easypiechart" id="easypiechart-blue">--}}
                        {{--@if($formReport->bonus1 != 0)--}}
                            {{--<span style="color: red;">含奖金 {{$formReport->bonus1}} </span>--}}
                        {{--@endif--}}
                        {{--<span class="percent">{{doubleval($formReport->profits1+$formReport->bonus1)}}</span>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-xs-6 col-md-3">--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-body easypiechart-panel">--}}
                    {{--<h4>历史收益</h4>--}}
                    {{--<div class="easypiechart" id="easypiechart-orange">--}}
                        {{--@if($formReport->bonus2 != 0)--}}
                            {{--<span style="color: red;">含奖金 {{$formReport->bonus2}} </span>--}}
                        {{--@endif--}}
                        {{--<span class="percent">{{doubleval($formReport->profits2+$formReport->bonus2)}}</span>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="row">--}}
        <div class="col-md-12 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    系统公告
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tr>
                            {{--<th>公告ID</th>--}}
                            <th>公告标题</th>
                            <th>发布时间</th>
                        </tr>
                        @foreach($notices as $notice)
                            <tr>
                                {{--<td>{{$loop->iteration}}</td>--}}
                                <td>
                                    <label style="color: #F36A5A">{{$notice->admin_id==1?"[系统]":""}}</label>
                                    <label style="color: #F36A5A">{{$notice->is_top==1?"[置顶]":""}}</label>
                                    <a target="_blank" href="/{{htname}}/notice/{{$notice->id}}">{{$notice->title}}</a></td>
                                <td>{{$notice->times}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                {{--<div class="panel-footer">--}}
                    {{--查看更多--}}
                {{--</div>--}}
            </div>
        </div>
        {{--<div class="col-md-4 col-sm-4">--}}
            {{--<div class="panel panel-primary">--}}
                {{--<div class="panel-heading">--}}
                    {{--Primary Panel--}}
                {{--</div>--}}
                {{--<div class="panel-body">--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>--}}
                {{--</div>--}}
                {{--<div class="panel-footer">--}}
                    {{--Panel Footer--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-md-4 col-sm-4">--}}
            {{--<div class="panel panel-success">--}}
                {{--<div class="panel-heading">--}}
                    {{--Success Panel--}}
                {{--</div>--}}
                {{--<div class="panel-body">--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>--}}
                {{--</div>--}}
                {{--<div class="panel-footer">--}}
                    {{--Panel Footer--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-md-4 col-sm-4">--}}
            {{--<div class="panel panel-info">--}}
                {{--<div class="panel-heading">--}}
                    {{--Success Panel--}}
                {{--</div>--}}
                {{--<div class="panel-body">--}}
                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>--}}
                {{--</div>--}}
                {{--<div class="panel-footer">--}}
                    {{--Panel Footer--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>

@endsection

@section('script')
    <script>
        $(function () {
            var pathname = window.location.pathname;
            $("a").removeClass("active-menu");
            $.each($("a"),function () {
                if($(this).attr("href") == pathname){
                    $(this).addClass("active-menu")
                }
            });
        });
    </script>
@endsection