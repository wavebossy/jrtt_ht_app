@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <form role="form" action="/{{htname}}/toutiaoAccountSave" method="post" >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <div class="form-group">
                    <label for="name">序列号（数字，排序使用）</label>
                    <input type="text" class="form-control" name="serial" placeholder="序列号" value="{{session("serial")}}" required='required' oninvalid="setCustomValidity('请输入序列号')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">头条ID</label>
                    <input type="text" class="form-control" name="ttid" placeholder="头条ID" required='required' oninvalid="setCustomValidity('请输入头条ID')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">头条号名称</label>
                    <input type="text" class="form-control" name="account" placeholder="头条号名称" required='required' oninvalid="setCustomValidity('请输入头条号名称')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">领域</label>
                    <input type="text" class="form-control" name="field" value="{{session("field")}}" placeholder="领域">
                </div>
                <div class="form-group">
                    <label for="name">手机号</label>
                    <input type="text" class="form-control" name="phone" placeholder="手机号" required='required' oninvalid="setCustomValidity('请输入手机号')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">邮箱</label>
                    <input type="text" class="form-control" name="mailboxs" placeholder="邮箱" required='required' oninvalid="setCustomValidity('请输入邮箱')" oninput="setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="name">主体（公司 or 个人）</label>
                    <input type="text" class="form-control" name="subject" value="{{session("subject")}}" placeholder="主体" >
                </div>
                <div class="form-group">
                    <label for="name">联系人（谁认证的号）</label>
                    <input type="text" class="form-control" name="contacts" placeholder="联系人" >
                </div>
                {{--只有管理员能分配账户归属，如果将添加的权限分配出去了，则由保存的时候只能添加到他的管理列表内--}}
                @if(session("admin")->role == -1 or session("admin")->role == 1)
                    <div class="form-group">
                        <label for="name">账号归属</label>
                        <select name="power" class="form-control" >
                            @foreach($adminList as $admin)
                                <option value="{{$admin->id}}">{{$admin->usname}}</option>
                            @endforeach
                        </select>
                        {{--<input type="text" name="power" value="{{$toutiaoAccount->power}}" class="form-control" placeholder="运营" />--}}
                    </div>
                @endif
                <div class="form-group">
                    <label for="name">是否认证</label>
                    <div class="radio3 radio-check radio-inline">
                        <input type="radio" id="radio1" name="isauth" value="1">
                        <label for="radio1">未认证</label>
                    </div>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio2" name="isauth" value="2" checked>
                        <label for="radio2">已认证</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio3" name="isauth" value="3">
                        <label for="radio3">被封禁</label>
                    </div>
                </div>
                {{--业务需求，添加的时候不指定小编--}}
                {{--<div class="form-group">--}}
                    {{--<label for="name">小编（谁在写文章）</label>--}}
                    {{--<select name="operator" class="form-control" >--}}
                        {{--@foreach($adminOperate as $admin)--}}
                            {{--<option value="{{$admin->id}}">{{$admin->usname}}</option>--}}
                        {{--@endforeach--}}
                    {{--</select>--}}
                {{--</div>--}}
                <div class="form-group">
                    <label for="name">账户状态</label>
                    {{--（1 新手 2 正常 3 商品）--}}
                    {{--<input type="text" class="form-control" name="status" value="1" placeholder="运营" required='required' oninvalid="setCustomValidity('请输入运营')" oninput="setCustomValidity('')">--}}
                    <div class="radio3 radio-check radio-inline">
                        <input type="radio" id="radio4" name="status" value="1">
                        <label for="radio4">新手号</label>
                    </div>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio5" name="status" value="2" checked>
                        <label for="radio5">正常号</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio6" name="status" value="3">
                        <label for="radio6">商品号</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">备注</label>
                    {{--<input type="text" class="form-control" name="remark" placeholder="备注" >--}}
                    <textarea class="form-control" rows="3" placeholder="备注(200字以内)" name="remark"></textarea>
                </div>
                <button type="submit" class="btn btn-success"><i class="fa fa-check-circle "></i> 添加</button>
            </form>
        </div>
    </div>

@endsection
