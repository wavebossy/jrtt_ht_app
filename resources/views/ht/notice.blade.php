@extends('ht.layouts._ht_layout')
@section('content')
    @include('ht.layouts._myalert')

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover table-striped">
                <caption>
                    {{--历史公告<br/>--}}
                    <button class="btn btn-info" onclick="noticeAdd()"><i class="fa fa-plus "></i> 新增发布</button>
                </caption>
                <tr>
                    <td>公告ID</td>
                    <td>发布账号</td>
                    <td>发布标题</td>
                    <td>发布时间</td>
                    <td>操作</td>
                </tr>
                @if(!empty($notices))
                    @foreach($notices as $notice)
                        <tr>
                            <td>{{$notice->id}}</td>
                            <td style="color: {{$notice->admin_id == 1?"red":""}}">{{$notice->admin_usname}}</td>
                            <td>{{$notice->title}}</td>
                            <td>{{$notice->times}}</td>
                            <td>
                                @if($notice->is_top == 0)
                                    <button class="btn btn-danger btn-md" onclick="noticeTop1({{$notice->id}})">置顶</button>
                                @else
                                    <button class="btn btn-info btn-md" onclick="noticeTop0({{$notice->id}})">取置</button>
                                @endif
                                <button class="btn btn-success btn-md" onclick="noticeDetail({{$notice->id}})">修改</button>
                                {{--<button class="btn btn-danger btn-md">删除</button>--}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>


@endsection

@include("ht.layouts._pagination")
@section('script')
    <script>
        $(function () {
            setPaging(parseInt({{$last}}),"accountNotice","?");
        });
        function noticeAdd() {
            window.location.href="/{{htname}}/accountNoticeAdd";
        }
        function noticeDetail(e) {
            window.location.href="/{{htname}}/accountNoticeDetail?id="+e;
        }
        function noticeTop1(e) {
            window.location.href="/{{htname}}/accountNoticeTop?id="+e+"&type=1";
        }
        function noticeTop0(e) {
            window.location.href="/{{htname}}/accountNoticeTop?id="+e+"&type=2";
        }
    </script>
@endsection
