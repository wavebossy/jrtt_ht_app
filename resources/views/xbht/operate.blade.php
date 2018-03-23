<script>
    function getOperate() {
        $.ajax({
            type: "post",
            url: "/{{htname}}/operateInfo",
            data:{
                _token:"{{csrf_token()}}"
            },
            cache:false,
            dataType: "json",
            beforeSend:function(XMLHttpRequest){},
            success:function(res){
                var result = res.data.result;// .breadcrumb
                console.log(result);
                $("#usname").val(result.usname);
                $("#uaccount").val(result.uaccount);
                $("#alipay").val(result.alipay);
                $("#bankcard").val(result.bankcard);
                $('#getOperateModal').modal('show');
            },
            error: function (XMLHttpRequest) {
                progress(8);
                console.log("ajax error: \n" + XMLHttpRequest.responseText);
            }
        });

    }
</script>

<div class="modal fade" id="getOperateModal" tabindex="-1" role="dialog" aria-labelledby="getOperateModalLabel" aria-hidden="true">
    <form action="/{{htname}}/operateInfo" method="post">
        <input type="hidden" name="types" value="1" />
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="getOperateModalLabel">
                        修改账户资料
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">姓名</label>
                        <input type="text" id="usname" class="form-control" placeholder="姓名" disabled />
                    </div>
                    <div class="form-group">
                        <label for="name">手机号</label>
                        <input type="text" id="uaccount" class="form-control" placeholder="手机号" disabled />
                    </div>
                    <div class="form-group">
                        <label for="name">密码</label>
                        <input type="text" id="uspwd" name="uspwd" class="form-control" placeholder="不输入则不修改密码" />
                    </div>
                    <div class="form-group">
                        <label for="name">支付宝账户</label>
                        <input type="text" id="alipay" name="alipay" class="form-control" placeholder="支付宝账户" />
                    </div>
                    <div class="form-group">
                        <label for="name">银行卡账户</label>
                        <input type="text" id="bankcard" name="bankcard" class="form-control" placeholder="银行卡账户" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                    </button>
                    <input type="submit" class="btn btn-success" value="更改"/>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </form>
</div>

