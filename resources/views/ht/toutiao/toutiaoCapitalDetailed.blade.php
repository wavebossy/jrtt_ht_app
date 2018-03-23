@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    [<span style="color: #F36A5A">{{$account->account}}</span>]未结算记录(请核对真实数据后打款)<br>
                </caption>
                <tr>
                    <td>时间</td>
                    <td>总计</td>
                </tr>
                @if(!empty($profits))
                    @foreach($profits as $profit)
                        <tr>
                            <td>{{Date("Y-m-d",strtotime($profit->times))}}</td>
                            <td>{{$profit->moneysums}}</td>
                        </tr>
                    @endforeach
                @endif
            </table>

        </div>
    </div>

@endsection

@section('script')
    <script>
        $(function () {
            {{--setPaging(parseInt({{$last}}),"toutiaoAccountCapitalDetailed","?id={{$account->id}}&");--}}
        });
    </script>
@endsection
