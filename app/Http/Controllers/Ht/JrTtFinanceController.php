<?php

namespace App\Http\Controllers\Ht;

use App\Models\Ht\Finance;
use App\Models\Ht\JrTt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/***
 * 财务统计
 * Class JrTtFinanceController
 * @package App\Http\Controllers\Ht
 */
class JrTtFinanceController extends Controller{
    //

    private $finance ;
    private $jrtt ;

    public function __construct(Finance $finance,JrTt $jrTt){
        $this->finance = $finance;
        $this->jrtt = $jrTt;
    }

    // 获取小编提交的财务列表
    // 统计每个号，每天的收入，总收入，按时间筛选
    public function getFinanceList(Request $request){
        $par = $request->all();
        $par["keyword"] = checkEmpty__($request["keyword"],"");
        $par["keyType"] = checkEmpty__($request["keyType"],"默认搜索");
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $par["createTimeStart"] = checkEmpty__($request["createTimeStart"],Date("Y-m-d",strtotime("-7 day")));
        $par["createTimeEnd"] = checkEmpty__($request["createTimeEnd"],Date("Y-m-d",strtotime("-1 day")));
        $Data = $this->finance->getFinanceList($par);
        $Data = json_decode($Data);
        if(!empty($Data)){
            $articles = $Data->data->result;
        }else{
            $articles = [];
        }
        $keyword = $par["keyword"];
        $keyType = $par["keyType"];
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $createTimeStart = $par["createTimeStart"];
        $createTimeEnd = $par["createTimeEnd"];
//        $keyType = $par["keyType"];
        $last = empty($Data->data->last)?1:$Data->data->last;
//        dd(compact("articles","page","pageSize","last"));
        return view("ht.caiwu.financeList",compact("articles","page","pageSize","createTimeStart","createTimeEnd","keyword","keyType","last"));
    }

    // 小编结算
    public function getXiaoBianList(){
        $adminList = $this->jrtt->getHtAdminList();
        $adminOperate = $this->jrtt->getHtAdminOperate(); // 默认是管理员的

        $time = "2017-09-01"; // 系统开始计算
//        dd(getThisMonth($time));
        $thisTime = Date("Y-m-d"); // 当前月
        $dateTimes = [];
        do{
            array_push($dateTimes,[
                "val"=>date("Y-m-d",strtotime(getNextMonthDays($time)[0])),
                "text"=>date("Y年m月",strtotime(getNextMonthDays($time)[0])),
            ]);
            $time = getNextMonthDays($time)[0];
        }while(strtotime($time)<strtotime(getNextMonthDays($thisTime)[0]));
        return view("ht.caiwu.xb_balance",compact("articles","adminList","adminOperate","dateTimes"));
    }

    // 结算记录
    public function getOperateSettlement(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $Data = $this->jrtt->getBalanceList($par);
        $Data = json_decode($Data);
        if(!empty($Data)){
            $balanceList = $Data->data->result;
        }else{
            $balanceList = [];
        }
//        dd($balanceList);
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = empty($Data->data->last)?1:$Data->data->last;
        return view("ht.caiwu.balanceList",compact("balanceList","page","pageSize","last"));
    }

    // 获取提交记录，未结算金额总计，所有运营的账号
    public function getOperateList(Request $request){
        $par = $request->all();
        echo $this->finance->getOperateList($par);
    }

    // 费用结算
    public function operateCost(Request $request){
        $par = $request->all();
        $par["imgurl"] = "";
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()){
                // 上传成功
                // 随机名字 . 后缀
                $fileName = "zimeiti/".Date("YmdHis").substr(md5(time()),5,15).".".$request->file("file")->extension();// 需要 开启php_fileinfo 扩展 否则会报错
                // 获取临时上传的路径
                $fileUrl = $request->file('file')->path();
                $bucket = "laigaonew";
                $auth = new Auth(___accessKey, ___secretKey);
                // 上传七牛
                $uptoken = $auth->uploadToken($bucket);
                $uploadMgr = new UploadManager();
                list($ret, $err) = $uploadMgr->putFile($uptoken, $fileName, $fileUrl);
                if ($err !== null) {

                } else {
                    $par["imgurl"] = $fileName; // "http://odxq81dcn.bkt.clouddn.com/". 方便更改七牛域名
                }
            }
        }
        echo $this->finance->operateCost($par);
    }

    // 发奖金
    public function bonusForm(Request $request){
        echo $this->finance->bonusForm($request->all());
    }

}
