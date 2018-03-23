@extends('xbht.layouts._ht_layout')

@section('page_header')

    <h1 class="page-header">
        提交数据
        <small>
            在这里，你要提交每日的收益（在今日头条收益内有显示）
        </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">系统主页</a></li>
        {{--<li><a href="#"></a></li>--}}
        <li class="active">提交数据</li>
    </ol>

@endsection

@section('content')

    @include('xbht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            @if(!empty($accounts))
            <form role="form" action="/{{htname}}/saveProfit" method="post" >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />

                <div class="form-group">
                    <label for="admin_id">选择提交账号</label>
                    <select class="form-control" name="admin_id">
                        @foreach($accounts as $account)
                            <option value="{{$account->id}}">{{$account->account}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="select_time">日期</label>
                    <input type="date" class="form-control" value="{{Date("Y-m-d")}}" name="select_time" placeholder="选择提交日期" required='required' oninvalid="setCustomValidity('请选择日期')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="moneysum">金额</label>
                    <input type="number" class="form-control" name="moneysum" placeholder="收入金额" required='required' oninvalid="setCustomValidity('请收入金额')" oninput="setCustomValidity('')">
                </div>
                <button type="submit" class="btn btn-success">保存</button>
            </form>
                @else
                <div>请联系管理员，分配头条账户</div>
            @endif
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