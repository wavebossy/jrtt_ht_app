@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    {{--财务统计<br>--}}
                    <div>
                        <form action="/{{htname}}/financeList" id="formSearch">
                            <input type="hidden" name="page" value="{{$page}}" >
                            <input type="hidden" name="pageSize" value="{{$pageSize}}" >
                            <div class="input-group col-md-3 col-sm-3 col-xs-3" style="float: left;padding: 0;">
                                <span class="input-group-addon">从</span>
                                <input type="date" name="createTimeStart" value="{{$createTimeStart}}" class="form-control" placeholder="twitterhandle">
                                {{--</div>--}}
                                {{--<div class="input-group col-md-3 col-sm-3 col-xs-3">--}}
                                <span class="input-group-addon">到</span>
                                <input type="date" name="createTimeEnd" value="{{$createTimeEnd}}" class="form-control" placeholder="twitterhandle">
                            </div>
                            <div class="input-group col-md-3 col-sm-3 col-xs-3" style="float: left;padding: 0;">
                                <input type="text" name="keyword" value="{{$keyword}}" placeholder="头条号名称(支持关键字搜索)" class="form-control">
                                <input type="hidden" name="keyType" value="{{$keyType}}" >
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
                                {{--<span class="input-group-addon btn" onclick="$('#formSearch').submit()">搜索</span>--}}
                            </div>
                        </form>
                    </div>
                </caption>
                <tr>
                    <td>账户/日期</td>
                    <td>总计</td>
                    @foreach($articles->searchDate as $time)
                        <td>{{$time}}</td>
                    @endforeach
                </tr>
                @if(!empty($articles->sumDataTable))
                    @foreach($articles->sumDataTable as $sumDataTable)
                        <tr>
                            <td><label style="color: #F36A5A">[{{$sumDataTable->field}}]</label> {{$sumDataTable->account}}</td>
                            <td>{{$sumDataTable->moneys}}</td>
                            @foreach($sumDataTable->dataTable as $item)
                                {{--<td>{{$item->times}}</td>--}}
                                <td style="color: {{$item->counts>1?"red":""}}">{{$item->moneysums}}</td>
                                {{--<td>{{$item->counts}}</td>--}}
                            @endforeach
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
        $(function () {
            setPaging(parseInt({{$last}}),"financeList","?createTimeStart={{$createTimeStart}}&createTimeEnd={{$createTimeEnd}}&");

            $("#search_ul li a").on("click",function () {
                //$("#btn_keyType").html($(this).html());
                $("input[name='keyType']").val($(this).html());
                $("#formSearch").submit();
            });
        });
    </script>
@endsection
