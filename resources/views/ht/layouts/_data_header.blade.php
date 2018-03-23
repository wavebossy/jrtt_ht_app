<h1 class="page-header">
@if(session("menuName"))
    {{session("menuName")}}
@endif
    <small>
        @if(session("smallText"))
            {{session("smallText")}}
        @endif
    </small>
</h1>
{{--<ol class="breadcrumb">--}}
{{--<li><a href="#">Home</a></li>--}}
{{--<li><a href="#">Dashboard</a></li>--}}
{{--<li class="active">Data</li>--}}
{{--{{dd(session("breadcrumb"))}}--}}
    @if(session("breadcrumb"))
        <ol class="breadcrumb">
            @foreach(session("breadcrumb") as $k=>$v)
                @if($k!=(sizeof(session("breadcrumb"))-1))
                    <li><a href="{{$v->href}}">{{$v->text}}</a></li>
                @else
                    <li class="active">{{$v->text}}</li>
                @endif
            @endforeach
        </ol>
    @endif
{{--</ol>--}}