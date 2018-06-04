@extends('xbht.layouts._ht_layout')
@section('page_header')

    <h1 class="page-header">
        收入报表
        <small>
            于每日下班时间上报昨日收益
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
    <div class="row" style="margin: 0">
        <button class="btn btn-success" onclick="offon(this)">开启编辑</button>
        <form action="/{{htname}}/profitlist" id="formSearch">
            <input type="hidden" name="page" value="{{$page}}" >
            <input type="hidden" name="pageSize" value="{{$pageSize}}" >
            <div class="input-group col-md-3 col-sm-3 col-xs-3" style="float: left;margin: 0;padding: 0;">
                <span class="input-group-addon">从</span>
                <input type="date" name="createTimeStart" value="{{$createTimeStart}}" class="form-control" placeholder="twitterhandle">
                <span class="input-group-addon">到</span>
                <input type="date" name="createTimeEnd" value="{{$createTimeEnd}}" class="form-control" placeholder="twitterhandle">
            </div>
            <div class="input-group col-md-3 col-sm-3 col-xs-3" style="float: left;margin: 0;padding: 0;">
                <input type="text" name="keyword" value="{{$keyword}}" placeholder="头条号名称(支持关键字搜索)" class="form-control">
                <span class="input-group-addon btn" onclick="$('#formSearch').submit()">搜索</span>
            </div>
        </form>
    </div>
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td>账户/日期</td>
                    <td>手机号</td>
                    <td>头条号指数</td>
                    {{--<td>总计</td>--}}
                    {{--@foreach($articles->searchDate as $time)--}}
                        {{--<td>{{$time}}</td>--}}
                    {{--@endforeach--}}
                </tr>
                @if(!empty($articles->sumDataTable))
                    @foreach($articles->sumDataTable as $sumDataTable)
                        <tr>
                            <td><lable style="color: #F36A5A">[{{$sumDataTable->field}}]</lable> {{$sumDataTable->account}}</td>
                            <td>{{$sumDataTable->phone}}</td>
                            <td>
                                <a href="#" class="table_td_data_index" data-pk="{{$sumDataTable->admin_id}}" >{{$sumDataTable->tindex}}</a>
                            </td>
                            {{--@foreach($sumDataTable->dataTable as $item)--}}
                                {{--<td style="color: {{$item->counts>1?"red":""}}">--}}
                                    {{--<a href="#" class="table_td_data" data-select_time="{{$item->select_time}}" data-admin_id="{{$item->admin_id}}" >{{$item->moneysums}}</a>--}}
                                {{--</td>--}}
                            {{--@endforeach--}}
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

@include("xbht.layouts._pagination")
@section('style')
    {{-- 文档 ================ http://vitalets.github.io/x-editable/docs.html--}}
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
@endsection
@section('script')
    <!-- Latest compiled and minified JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <!-- Latest compiled and minified Locales -->
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/locale/bootstrap-table-zh-CN.min.js"></script>--}}
    <script>
        $(function () {
//            切换布局
            $.fn.editable.defaults.mode = 'inline'; // popup
            var pathname = window.location.pathname;
            $("a").removeClass("active-menu");
            $.each($("a"),function () {
                if($(this).attr("href") == pathname){
                    $(this).addClass("active-menu")
                }
            });
            $('.table_td_data').editable({
                disabled:true,
                name:"datas",
                type: "text",
                url: "/{{htname}}/postProfit",
                pk: 1,
                params: function(params) {
                    //originally params contain pk, name and value
                    params.admin_id = $(this).attr("data-admin_id");
                    params.select_time = $(this).attr("data-select_time");
                    return params;
                },
                value: ''
            });
            $('.table_td_data_index').editable({
                disabled:true,
                name:"tindex",
                type: "text",
                url: "/{{htname}}/postAdminIndex",
                value: ''
            });
            setPaging(parseInt({{$last}}),"profitlist","?");
        });
        function offon(e) {
            $('.table_td_data,.table_td_data_index').editable('toggleDisabled');
            $(e).toggleClass("btn-info");
            console.log($(e).attr("class"));
            if($(e).attr("class").length > 15){
                $(e).html("关闭编辑");
            }else{
                $(e).html("开启编辑");
            }
        }
        function b(e) {
            progress(8);
            $.ajax({
                type: "post",
                url: "/{{htname}}/profitDetail",
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
                    $("#id").val(result.id);
                    $("#account").val(result.account);
                    $("#admin_id").val(result.admin_id);
                    $("#moneysum").val(result.moneysum);
                    $("#select_time").val(result.select_time);
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