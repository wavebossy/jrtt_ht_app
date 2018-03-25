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
            <p><a target="_blank" href="http://protal.tuike520.com/%E7%AC%AC%E4%B8%80%E8%AF%BE%EF%BC%9A%E4%BA%86%E8%A7%A3%E5%A4%B4%E6%9D%A1%E6%A6%82%E5%86%B5.mp4">第一课：了解头条概况</a></p>
            <p><a target="_blank" href="http://protal.tuike520.com/%E7%AC%AC%E4%BA%8C%E8%AF%BE%EF%BC%9A%E5%A4%B4%E6%9D%A1%E7%9A%84%E6%94%B6%E7%9B%8A%E6%9D%A5%E8%87%AA%E5%93%AA%E9%87%8C.mp4">第二课：头条的收益来自哪里</a></p>
            <p><a target="_blank" href="http://protal.tuike520.com/%E7%AC%AC%E4%B8%89%E8%AF%BE%EF%BC%9A%E6%B3%A8%E5%86%8C%E5%A4%B4%E6%9D%A1%E5%8F%B7%E7%9A%84%E6%B5%81%E7%A8%8B%E5%8F%8A%E6%B3%A8%E6%84%8F%E4%BA%8B%E9%A1%B9.mp4">第三课：注册头条号的流程及注意事项</a></p>
            <p><a target="_blank" href="http://protal.tuike520.com/%E7%AC%AC%E5%9B%9B%E8%AF%BE%EF%BC%9A%E5%AE%9E%E5%90%8D%E8%AE%A4%E8%AF%81%E7%9A%84%E6%B5%81%E7%A8%8B%E5%92%8C%E6%B3%A8%E6%84%8F%E4%BA%8B%E9%A1%B9.mp4">第四课：实名认证的流程和注意事项</a></p>
            <p><a target="_blank" href="http://protal.tuike520.com/%E7%AC%AC%E4%BA%94%E8%AF%BE%EF%BC%9A%E5%A6%82%E4%BD%95%E4%BD%BF%E7%94%A8%E9%87%87%E9%9B%86%E5%B9%B3%E5%8F%B0%E5%BF%AB%E9%80%9F%E7%9A%84%E7%AD%9B%E9%80%89%E6%96%87%E7%AB%A0.mp4">第五课：如何使用采集平台快速的筛选文章</a></p>
            <p><a target="_blank" href="http://protal.tuike520.com/%E7%AC%AC%E5%85%AD%E8%AF%BE%EF%BC%9A%E5%A6%82%E4%BD%95%E5%BF%AB%E9%80%9F%E7%9A%84%E5%AE%9E%E7%8E%B0%E9%AB%98%E8%B4%A8%E9%87%8F%E7%9A%84%E4%BC%AA%E5%8E%9F%E5%88%9B.mp4">第六课：如何快速的实现高质量的伪原创</a></p>
            <p><a target="_blank" href="http://protal.tuike520.com/%E7%AC%AC%E4%B8%83%E8%AF%BE%EF%BC%9A%E5%8F%91%E6%96%87%E6%97%B6%E7%BB%8F%E5%B8%B8%E7%8A%AF%E7%9A%84%E5%87%A0%E4%B8%AA%E9%94%99%E8%AF%AF.mp4">第七课：发文时经常犯的几个错误</a></p>
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