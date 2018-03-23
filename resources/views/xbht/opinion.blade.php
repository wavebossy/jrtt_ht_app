@extends('xbht.layouts._ht_layout')

@section('page_header')

    <h1 class="page-header">
        意见反馈
        <small>
            在这里，可以提交你的问题，包括，但不限于使用方式，数据错误，以及程序的Bug ！
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">系统主页</a></li>
        {{--<li><a href="#"></a></li>--}}
        <li class="active">意见反馈</li>
    </ol>

@endsection

@section('content')

    @include('xbht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <form role="form" action="/{{htname}}/saveOpinion" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <div class="form-group">
                    <label for="name">选择类别</label>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio1" name="status" value="1" checked>
                        <label for="radio1">投诉</label>
                    </div>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio2" name="status" value="2">
                        <label for="radio2">建议</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="moneysum">附件截图(可选)</label>
                    <input name="file" type="file" />
                </div>
                <div class="form-group">
                    <label for="moneysum">输入描述的问题</label>
                    <textarea name="context" class="form-control" rows="5" required='required' oninvalid="setCustomValidity('请简单描述你的问题，或者上传截图')" oninput="setCustomValidity('')"></textarea>
                </div>
                <button type="submit" class="btn btn-success">提交</button>
            </form>
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