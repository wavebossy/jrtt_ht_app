@extends('xbht.layouts._ht_layout')

@section('page_header')

    <h1 class="page-header">
        视频教程
        <small>
            在这里，你将学习来自公司内部统一的教学视频！
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">系统主页</a></li>
        {{--<li><a href="#"></a></li>--}}
        <li class="active">视频教程</li>
    </ol>

@endsection

@section('content')

    @include('xbht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <a>点击播放第一课</a>
        </div>
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