@extends('xbht.layouts._ht_layout')
@section('page_header')

    <h1 class="page-header">
        结算记录
        <small>
            系统给您结算的历史凭证
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">系统主页</a></li>
        {{--<li><a href="#"></a></li>--}}
        <li class="active">结算记录</li>
    </ol>

@endsection
@section('content')
    @include('xbht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    {{--<td>转账凭证</td>--}}
                    <td>结算日期</td>
                    <td>结算金额</td>
                    <td>转账类型</td>
                </tr>
                @if(!empty($balances))
                    @foreach($balances as $balance)
                        <tr>
                            <td>{{$balance->datetime}}</td>
                            <td>{{$balance->moneys}}</td>
                            <td>{{$balance->paytype == 1?"支付宝":($balance->paytype == 2?"银行卡":"其他")}}</td>
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
            setPaging(parseInt({{$last}}),"balance_xb","?");
        });
    </script>
@endsection