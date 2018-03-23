@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <form role="form" action="/{{htname}}/saveHtAscription" method="post" >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <input type="hidden" name="id" value="{{$htAscriptionDetail->id}}" />
                <div class="form-group">
                    <label for="name">姓名</label>
                    <input type="text" class="form-control" name="usname" value="{{$htAscriptionDetail->usname}}" placeholder="姓名">
                </div>
                <div class="form-group">
                    <label for="name">账户</label>
                    <input type="text" class="form-control" name="uaccount" value="{{$htAscriptionDetail->uaccount}}" placeholder="账户">
                </div>
                <div class="form-group">
                    <label for="name">重置密码</label>
                    <input type="text" class="form-control" name="uspwd" value="" placeholder="重置密码">
                </div>
                <div class="form-group">
                    <label for="name">所属加盟商</label>
                    <select name="admin_id" class="form-control" >
                        @if(empty($adminOperate))
                            @foreach($adminList as $admin)
                                @if($htAscriptionDetail->admin_id == $admin->id)
                                    <option selected value="{{$admin->id}}">{{$admin->usname}}</option>
                                @else
                                    <option value="{{$admin->id}}">{{$admin->usname}}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">在职状态</label>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio11" name="op_types" value="1" {{$htAscriptionDetail->op_types == 1?"checked":""}}>
                        <label for="radio11">全职</label>
                    </div>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio12" name="op_types" value="2" {{$htAscriptionDetail->op_types == 2?"checked":""}}>
                        <label for="radio12">兼职</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio13" name="op_types" value="3" {{$htAscriptionDetail->op_types == 3?"checked":""}}>
                        <label for="radio13">离职</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">账号状态</label>
                    {{--（封禁无法登入）--}}
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio1" name="status" value="1" {{$htAscriptionDetail->status == 1?"checked":""}}>
                        <label for="radio1">正常</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio2" name="status" value="2" {{$htAscriptionDetail->status == 2?"checked":""}}>
                        <label for="radio2">封禁</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">备注</label>
                    {{--<input type="text" class="form-control" name="remark" value="{{$htAscriptionDetail->remark}}" placeholder="状态">--}}
                    <textarea class="form-control" rows="3" placeholder="备注(200字以内)" name="remark">{{$htAscriptionDetail->remark}}</textarea>
                </div>
                <button type="submit" class="btn btn-success">保存</button>
            </form>
        </div>
    </div>

@endsection
