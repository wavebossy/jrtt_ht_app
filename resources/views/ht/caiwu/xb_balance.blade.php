@extends('ht.layouts._ht_layout')

@section('style')
    <style>
        .td{
            width:60%;
        }
    </style>
@endsection

@section('content')
    @include('ht.layouts._myalert')

    <div class="row" style="margin-left: 0;">
        <div class="col-md-3 col-sm-3 col-xs-3" style="background: #f9f9f9;">
            @if(session("admin")->role == -1 or session("admin")->role == 2)
                {{--或者有财务权限（后续设置）--}}
                <div class="form-group" style="margin-top: 1rem">
                    <label for="name">账号归属</label>
                    <select name="power" class="form-control" onchange="operatorChange()">
                        @foreach($adminList as $admin)
                            <option value="{{$admin->id}}">{{$admin->usname}}</option>
                        @endforeach
                    </select>
                    {{--<input type="text" name="power" value="{{$toutiaoAccount->power}}" class="form-control" placeholder="运营" />--}}
                </div>
                <div class="form-group">
                    <label for="name">小编</label>
                    <select name="operator" class="form-control" onchange="operatorChangeInfo()">
                        @foreach($adminOperate as $admin)
                            <option data-op_types="{{$admin->op_types}}" data-alipay="{{$admin->alipay}}" data-bankcard="{{$admin->bankcard}}" value="{{$admin->id}}">{{$admin->usname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">月份</label>
                    <select name="dateTime" class="form-control" onchange="operatorChangeInfo()">
                        @foreach($dateTimes as $dateTime)
                            <option value="{{$dateTime["val"]}}">{{$dateTime["text"]}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">小编支付宝</label>
                    <input type="text" id="alipay" class="form-control" />
                </div>
                <div class="form-group">
                    <label for="name">小编银行卡</label>
                    <input type="text" id="bankcard" class="form-control" />
                </div>
                <hr/>
                <div class="form-group">
                    <label for="name">小编状态</label>
                    <span id="op_types">请选择</span><br/>
                    <label for="name">结算总计</label>
                    <span id="money">0</span><br/>
                    <label for="name">小编应得</label>
                    <span id="deserved">0</span>
                </div>
                <div class="form-group">
                    {{--结算完成，新增记录ht_admin_operate_balance--}}
                    <button class="btn btn-success" onclick="balance()">一键结算</button>
                    <button class="btn btn-info" onclick="bonus()">奖金发放</button>
                    {{--结算完毕后，跳转上传截图，方便做账！--}}
                    {{--财务对账，表格导出，图片链接导出 or 图片贴上！--}}
                    <button class="btn btn-warning">数据有误？</button>
                </div>
            @endif
        </div>
        <div class="col-md-9 col-sm-9 col-xs-9">
            @if(session("admin")->role == -1 or session("admin")->role == 2)
                {{--或者有财务权限（后续设置）--}}
                <table class="table table-bordered table-striped">
                    <tr><td>运营账号</td><td>未结算金额</td></tr>
                    <tbody id="table_body"></tbody>
                </table>
                <hr/>
                {{--<div style="display: none" id="bonus_div">--}}
                    <table class="table table-bordered table-striped">
                        <tr><td>奖金金额/缘由</td><td>奖金时间</td></tr>
                        <tbody id="bonus_table_body"></tbody>
                    </table>
                {{--</div>--}}
            @endif
        </div>
    </div>

    <div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-labelledby="balanceModalLabel" aria-hidden="true">
        <form id="operateCostForm" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="balanceModalLabel">
                            提现核对
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">小编</label>
                            <input type="text" id="operatorName" disabled class="form-control" placeholder="小编名称" />
                            <input type="hidden" id="operate_id" name="operate_id" />
                        </div>
                        <div class="form-group">
                            <label for="name">总金额</label>
                            <input type="text" id="moneysum" name="moneysum" class="form-control" placeholder="总金额" />
                        </div>
                        <div class="form-group">
                            <label for="name">状态</label>
                            <div class="radio3 radio-check radio-success radio-inline">
                                <input type="radio" id="radio5" name="status" value="1" checked>
                                <label for="radio5">支付宝</label>
                            </div>
                            <div class="radio3 radio-check radio-warning radio-inline">
                                <input type="radio" id="radio6" name="status" value="2">
                                <label for="radio6">银行卡</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">凭证截图</label>
                            <input type="file" name="file" placeholder="凭证截图" />
                        </div>
                        <div class="form-group">
                            <label for="name">备注</label>
                            <textarea rows="3" name="remark" class="form-control" placeholder="备注,200字以内" ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <input type="button" onclick="operateCost()" class="btn btn-success" value="确认无误"/>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </form>
    </div>

    {{--发奖金--}}
    <div class="modal fade" id="bonusModal" tabindex="-1" role="dialog" aria-labelledby="bonusModalLabel" aria-hidden="true">
        <form id="bonusForm" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title" id="bonusModalLabel">
                            奖金发放
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">小编</label>
                            <input type="text" id="operatorName_bonus" disabled class="form-control" placeholder="小编名称" />
                            <input type="hidden" id="operate_id_bonus" name="operate_id" />
                        </div>
                        <div class="form-group">
                            <label for="name">发放金额</label>
                            <input type="text" name="moneysum" class="form-control" placeholder="总金额" />
                        </div>
                        <div class="form-group">
                            <label for="name">发放原因（必填）</label>
                            <textarea rows="3" name="remark" class="form-control" placeholder="" ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <input type="button" onclick="bonusForm()" class="btn btn-success" value="确认无误"/>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->
        </form>
    </div>

@endsection

@include("ht.layouts._progress")
@section('script')
    <script>
        $(function () {
            operatorChangeInfo();
        });
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
                        option += "<option data-op_types='"+item.op_types+"' data-alipay='"+item.alipay+"' data-bankcard='"+item.bankcard+"' value='"+item.id+"'>" +
                            "" + item.usname +
                            "</option>";
                    });
                    // operator
                    $("select[name='operator']").html(option);
                    operatorChangeInfo();
                    progress(10);
                },
                error: function (XMLHttpRequest) {
                    progress(8);
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
        function operatorChangeInfo(){
            $.ajax({
                type: "post",
                url: "/{{htname}}/operateList",
                data:{
                    operate_id:$("select[name='operator']").val(),
                    dateTime:$("select[name='dateTime']").val(),
                    _token:"{{csrf_token()}}"
                },
                cache:false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                    progress(3);
                    // 1 全职 2 兼职 3 离职
                    $("#money").html("计算中..."); // 统计
                    $("#deserved").html("计算中..."); // 应得
                    var op_types = $("select[name='operator'] option:selected").attr("data-op_types");
                    switch (op_types){
                        case "1" : $("#op_types").html("全职");break;
                        case "2" : $("#op_types").html("兼职");break;
                        case "3" : $("#op_types").html("离职");break;
                        default : $("#op_types").html("异常");return ;
                    }

                    var alipay = $("select[name='operator'] option:selected").attr("data-alipay");
                    if(alipay != "" && alipay != "null"){
                        $("#alipay").val(alipay);
                    }else{
                        $("#alipay").val("无");
                    }
                    var bankcard = $("select[name='operator'] option:selected").attr("data-bankcard");
                    if(bankcard != "" && bankcard != "null"){
                        $("#bankcard").val(bankcard);
                    }else{
                        $("#bankcard").val("无");
                    }
                },
                success:function(res){
                    progress(5);
                    var result = res.data.result;
                    console.log(result);
                    var option = "";
                    var bonus = "";
                    $.each(result.profit,function (i,item) {
                        option += "<tr>" +
                            "<td class='td'><a href='/{{htname}}/toutiaoAccountCapitalDetailed?id="+item.admin_id+"&types=2&operate_id="+item.operate_id+"' target='_blank'>"+item.account+"</a></td>" +
                            "<td>"+item.moneysum+"</td>" +
                            "</tr>";
                    });
                    $("#table_body").html(option);
                    $.each(result.bonus,function (i,item) {
                        bonus += "<tr>" +
                            "<td class='td'>奖励金额："+item.moneys+"<br/>奖励缘由："+item.remark+"</td>" +
                            "<td>"+item.times+"</td>" +
                            "</tr>";
                    });
                    $("#bonus_table_body").html(bonus);
                    $("#money").html(result.money);
                    $("#deserved").html(result.deserved);
                    $("#op_types").html(result.op_types);
                    progress(10);
                },
                error: function (XMLHttpRequest) {
                    progress(8);
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
        // 提交转账记录，或者截图
        function balance() {
            // $("select[name='operator']  ==  $("select[name='operator'] option:selected")
            $("#operate_id").val($("select[name='operator'] ").val());
            $("#operatorName").val($("select[name='operator'] option:selected").html());
            $("#moneysum").val($("#deserved").html());
            $('#balanceModal').modal('show');
        }

        // 奖金发放
        function bonus() {
            // $("select[name='operator']  ==  $("select[name='operator'] option:selected")
            $("#operate_id_bonus").val($("select[name='operator'] ").val());
            $("#operatorName_bonus").val($("select[name='operator'] option:selected").html());
            $('#bonusModal').modal('show');
        }


        function operateCost() {
            var form = new FormData($("#operateCostForm")[0]);
            form.append("dateTime",$("select[name='dateTime']").val());
            $.ajax({
                type: "post",
                url: "/{{htname}}/operateCost",
                data:form,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                    progress(3);
                },
                success:function(res){
                    progress(5);
                    console.log(res);
                    var code = res.code;
                    if(code!=200){
                        return alert("结算失败");
                    }else{
                        var result = res.data.result;// .breadcrumb
                        console.log(result);
                        progress(10);
                        alert("结算成功");
                        $('#balanceModal').modal('hide');
                        operatorChangeInfo();
                    }
                },
                error: function (XMLHttpRequest) {
                    progress(8);
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }

        function bonusForm() {
            var form = new FormData($("#bonusForm")[0]);
            $.ajax({
                type: "post",
                url: "/{{htname}}/bonusForm",
                data:form,
                cache:false,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend:function(XMLHttpRequest){
                    progress(3);
                },
                success:function(res){
                    progress(5);
                    console.log(res);
                    var code = res.code;
                    if(code!=200){
                        return alert("发放失败");
                    }else{
                        progress(10);
                        alert("发放成功");
                        operatorChangeInfo();
                        $('#bonusModal').modal('hide');
                    }
                },
                error: function (XMLHttpRequest) {
                    progress(8);
                    console.log("ajax error: \n" + XMLHttpRequest.responseText);
                }
            });
        }
    </script>
@endsection
