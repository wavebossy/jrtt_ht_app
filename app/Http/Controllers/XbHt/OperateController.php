<?php

namespace App\Http\Controllers\XbHt;

use App\Models\XbHt\Operate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OperateController extends Controller
{

    private $xb = "";

    public function __construct(Operate $htIndex){
        $this->xb = $htIndex;
    }

    // 获取or提交账户资料
    public function operateInfo(Request $request){
        $par = $request->all();
        $par["types"] = checkEmpty__($request["types"],0); // 0 获取数据 1 提交数据
        $rs = $this->xb->operateInfo($par);
        $rsjson = json_decode($rs);
        if(intval(strval($par["types"])) == 1 and $rsjson->code== 200){
            return redirect("/".htname."/homepage_xb")->with('success_info', '登入成功');
        }else if(intval(strval($par["types"])) == 1 and $rsjson->code!= 200){
            return redirect("/".htname."/homepage_xb")->with('error_info', '登入失败');
        }else{
            // ajax
            echo $rs;
        }
    }

    // 结算记录
    public function getBalance(Request $request){
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $balances = $this->xb->getBalance($par);
        $Data = json_decode($balances);
        if(!empty($Data)){
            $balances = $Data->data->result;
        }else{
            $balances = [];
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = empty($Data->data->last)?1:$Data->data->last;
        return view("xbht.balances",compact("balances","page","pageSize","last"));
    }

}
