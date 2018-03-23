@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    结算记录<br>
                </caption>
                <tr>
                    <td>ID</td>
                    <td>加盟商/小编</td>
                    <td>转出金额</td>
                    <td>转出类型</td>
                    <td>凭证截图</td>
                    <td>备注</td>
                    <td>结算日期</td>
                </tr>
                @if(!empty($balanceList))
                    @foreach($balanceList as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td><label>[{{$item->admin_usname}}]</label> {{$item->usname}}</td>
                            <td>{{$item->moneys}}</td>
                            <td>{{$item->paytype==1?"支付宝":($item->paytype==2?"银行卡":"异常")}}</td>
                            <td><img src="{{!empty($item->imgurl)?___qiniu.$item->imgurl:""}}" style="width: 40px;"/></td>
                            <td>{{$item->remark}}</td>
                            <td>{{$item->datetime}}</td>
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
            setPaging(parseInt({{$last}}),"operateSettlement","?");
        });
    </script>
@endsection
