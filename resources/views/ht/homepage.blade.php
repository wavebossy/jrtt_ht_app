@extends('ht.layouts._ht_layout')

@section('content')

    @include('ht.layouts._myalert')
    {{--{{json_encode(session("admin"))}}--}}
    @if(session("admin")->role == -1 or session("admin")->role == 2)
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body easypiechart-panel">
                        <h4>昨日收益</h4>
                        <div class="easypiechart" id="easypiechart-blue">
                            <span class="percent">{{$formReport->profits1}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body easypiechart-panel">
                        <h4>本周收益</h4>
                        <div class="easypiechart" id="easypiechart-orange">
                            <span class="percent">{{$formReport->profits2}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body easypiechart-panel">
                        <h4>本月收益</h4>
                        <div class="easypiechart" id="easypiechart-teal">
                            <span class="percent">{{$formReport->profits3}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body easypiechart-panel">
                        <h4>历史收益</h4>
                        <div class="easypiechart" id="easypiechart-red">
                            <span class="percent">{{$formReport->profits4}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
