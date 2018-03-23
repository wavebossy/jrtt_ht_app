@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <form action="/{{htname}}/subNotice" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="row">
            <label>公告标题</label>
            <input type="text" placeholder="公告标题" name="title" class="form-control" id="titles" style="margin: 0 0 2rem 0" />
        </div>
        <div class="row">
            <label>公告内容</label>
            <script id="container" name="bodys" type="text/plain"></script>
        </div>
        <div class="row"><button class="btn btn-success" style="margin: 2rem 0 2rem 0" type="submit">发表</button></div>
    </form>
@endsection

@section('script')
    @include('UEditor::head');
    <script>
        $(function () {
            $("#titles").css("width","80%");
            $("#container").css("width",$("#titles").css("width")).css("height","200px");
        });
    </script>

    <script type="text/javascript">
        //实例化编辑器
        var ue = UE.getEditor('container');
        ue.ready(function(){
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
        });
    </script>
@endsection
