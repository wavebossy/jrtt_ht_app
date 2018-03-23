@extends('ht.layouts._ht_layout')

@section('content')
    @include('ht.layouts._myalert')
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-6">
            <form action="/{{htname}}/toutiaoAccountUpdate" method="post">
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <input type="hidden" name="id" value="{{$toutiaoAccount->id}}" class="form-control" >
                <input type="hidden" name="page" value="{{$page}}" class="form-control" >
                <input type="hidden" name="pageSize" value="{{$pageSize}}" class="form-control" >
                <div class="form-group">
                    <label for="name">序列号</label>
                    <input type="text" name="serial" value="{{$toutiaoAccount->serial}}" class="form-control" placeholder="序列号" />
                </div>
                @if(session("admin")->role == -1 or session("admin")->role == 1)
                    <div class="form-group">
                        <label for="name">头条ID</label>
                        <input type="text" name="ttid" value="{{$toutiaoAccount->ttid}}" class="form-control" placeholder="头条ID" />
                    </div>
                @endif
                <div class="form-group">
                    <label for="name">头条号名称</label>
                    <input type="text" name="account" value="{{$toutiaoAccount->account}}" class="form-control" placeholder="头条号名称" />
                </div>
                <div class="form-group">
                    <label for="name">领域</label>
                    <input type="text" name="field" value="{{$toutiaoAccount->field}}" class="form-control" placeholder="领域" />
                </div>
                <div class="form-group">
                    <label for="name">手机号</label>
                    <input type="text" name="phone" value="{{$toutiaoAccount->phone}}" class="form-control" placeholder="手机号" />
                </div>
                <div class="form-group">
                    <label for="name">头条指数</label>
                    <input type="text" name="tindex" value="{{$toutiaoAccount->tindex}}" class="form-control" placeholder="手机号" />
                </div>
                <div class="form-group">
                    <label for="name">邮箱</label>
                    <input type="text" name="mailboxs" value="{{$toutiaoAccount->mailboxs}}" class="form-control" placeholder="邮箱" />
                </div>
                <div class="form-group">
                    <label for="name">主体</label>
                    <input type="text" name="subject" value="{{$toutiaoAccount->subject}}" class="form-control" placeholder="主体" />
                </div>
                <div class="form-group">
                    <label for="name">联系人</label>
                    <input type="text" name="contacts" value="{{$toutiaoAccount->contacts}}" class="form-control" placeholder="联系人" />
                </div>
                @if(session("admin")->role == -1 or session("admin")->role == 1)
                    <div class="form-group">
                        <label for="name">账号归属(加盟商)</label>
                        <select name="power" class="form-control" onchange="operatorChange()">
                            @foreach($adminList as $admin)
                                <option {{$toutiaoAccount->power == $admin->id?"selected":""}} value="{{$admin->id}}">{{$admin->usname}}</option>
                            @endforeach
                        </select>
                        {{--<input type="text" name="power" value="{{$toutiaoAccount->power}}" class="form-control" placeholder="运营" />--}}
                    </div>
                @endif
                <div class="form-group">
                    <label for="name">小编</label>
                    <select name="operator" class="form-control" >
                        @foreach($adminOperate as $admin)
                            @if($toutiaoAccount->operator == $admin->id)
                                <option selected value="{{$admin->id}}">{{$admin->usname}}</option>
                            @else
                                <option value="{{$admin->id}}">{{$admin->usname}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">是否认证</label>
                    {{--（1 未认证 2 已认证）--}}
                    <div class="radio3 radio-check radio-inline">
                        <input type="radio" id="radio1" name="isauth" value="1" {{$toutiaoAccount->isauth == 1?"checked":""}}>
                        <label for="radio1">未认证</label>
                    </div>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio2" name="isauth" value="2" {{$toutiaoAccount->isauth == 2?"checked":""}}>
                        <label for="radio2">已认证</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio3" name="isauth" value="3" {{$toutiaoAccount->isauth == 3?"checked":""}}>
                        <label for="radio3">被封禁</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">账户状态</label>
                    {{--（1 新手 2 正常 3商品 ）--}}
                    <div class="radio3 radio-check radio-inline">
                        <input type="radio" id="radio4" name="status" value="1" {{$toutiaoAccount->status==1?"checked":""}}>
                        <label for="radio4">新手号</label>
                    </div>
                    <div class="radio3 radio-check radio-success radio-inline">
                        <input type="radio" id="radio5" name="status" value="2" {{$toutiaoAccount->status==2?"checked":""}}>
                        <label for="radio5">正常号</label>
                    </div>
                    <div class="radio3 radio-check radio-warning radio-inline">
                        <input type="radio" id="radio6" name="status" value="3" {{$toutiaoAccount->status==3?"checked":""}}>
                        <label for="radio6">商品号</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">备注</label>
                    {{--<input type="text" name="remark" value="{{$toutiaoAccount->remark}}" class="form-control" placeholder="备注" />--}}
                    <textarea class="form-control" rows="3" placeholder="备注(200字以内)" name="remark">{{$toutiaoAccount->remark}}</textarea>
                </div>

                <input type="submit" class="btn btn-success" value="更改"/>
            </form>
        </div>
    </div>

@endsection
@include("ht.layouts._pagination")

@section('script')
    <script>
        function operatorChange(){
            $.ajax({
                type: "post",
                url: "/{{htname}}/getDetailAccountAscription",
                data:{
                    admin_id:$("select[name='power']").val(),
                    _token:"{{csrf_token()}}"
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                    progress(3);
                },
                success:function(res){
                    progress(5);
                    var result = res.data.result;// .breadcrumb
                    console.log(result);
                    var option = "";
                    $.each(result,function (i,item) {
                        option += "<option value='"+item.id+"'>" +
                            "" + item.usname +
                            "</option>";
                    });
                    // operator
                    $("select[name='operator']").html(option);
                    progress(10);
                },
                error: function (XMLHttpRequest) {
                    progress(8);
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
    </script>
@endsection
