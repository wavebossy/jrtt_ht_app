<?php

namespace App\Http\Controllers\Ht;

use App\Models\Ht\JrTt;
use App\Models\Ht\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class JrTtController extends Controller{
    // ht.user
    private $jrtt ;

    public function __construct(JrTt $jrtt){
        $this->jrtt = $jrtt;
    }

    // 更变记录
    public function getToutiaoAccountLog(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],15);
        $par["keyword"] = checkEmpty__($request["keyword"],"");
        $Data = $this->jrtt->getToutiaoAccountLog($par);
        $Data = json_decode($Data);
        if(!empty($Data)){
            $articles = $Data->data->result;
        }else{
            $articles = [];
        }
//        dd($articles);
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $keyword = $par["keyword"];
        $last = empty($Data->data->last)?1:$Data->data->last;
        return view("ht.toutiao.accountLog",compact("articles","keyword","page","pageSize","last"));
    }

    // 跳转页面
    public function getToutiaoAccountList(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $par["types"] = checkEmpty__($request["types"],0);
        $par["isauth"] = checkEmpty__($request["isauth"],0); // 已认证
        $par["keyType"] = checkEmpty__($request["keyType"],"默认搜索");
        $par["keyword"] = checkEmpty__($request["keyword"],"");
        $Data = $this->jrtt->getToutiaoAccountList($par);
//        dd($Data);
        $Data = json_decode($Data);
        if(!empty($Data)){
            $articles = $Data->data->result;
        }else{
            $articles = [];
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $types = $par["types"];
        $isauth = $par["isauth"];
        $keyword = $par["keyword"];
        $keyType = $par["keyType"];
        $last = empty($Data->data->last)?1:$Data->data->last;
        return view("ht.toutiao.account",compact("articles","types","isauth","keyword","keyType","page","pageSize","last"));
    }

    // 新增头条号页面
    public function getToutiaoAccountAdd(){
        $adminList = $this->jrtt->getHtAdminList();
        // 获取当前权限下的小编（运营）
        // $adminOperate = $this->jrtt->getHtAdminOperate(session("admin")->id);
        // return view("ht.toutiao.toutiaoAccountAdd",compact("adminOperate","adminList"));
        return view("ht.toutiao.toutiaoAccountAdd",compact("adminList"));
    }

    // 新增头条号保存数据
    public function saveToutiaoAccount(Request $request){
        $params = $request->all();
        $rs = $this->jrtt->saveToutiaoAccount($params);
        $code = json_decode($rs);
        if($code->code==200){
            session(["serial"=>intval(strval($params["serial"]))+1]);
            session(["field"=>$params["field"]]);
            session(["subject"=>$params["subject"]]);
            return redirect("/".htname."/toutiaoAccountAdd")->with("success_info","添加成功");
        }else{
            return redirect("/".htname."/toutiaoAccountAdd")->with("error_info","添加失败");
        }
    }

    // 头条详情，
    public function detailToutiaoAccount(Request $request){
        $toutiaoAccount = $this->jrtt->getDetailToutiaoAccount($request->all());// 头条ID
        $toutiaoAccount = json_decode($toutiaoAccount);
        $toutiaoAccount = $toutiaoAccount->data->result;
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $adminList = $this->jrtt->getHtAdminList();
        $adminOperate = $this->jrtt->getHtAdminOperate($toutiaoAccount->power); // 谁在管理的权限，如果是炒鸡管理员修改，则也应该是操作他的人
        return view("ht.toutiao.detailToutiaoAccount",compact("toutiaoAccount","adminList","adminOperate","page","pageSize"));
    }

    // 修改头条账户
    public function updateToutiaoAccount(Request $request){
        $params = $request->all();
        $types = checkEmpty__($request["types"],1);// 只针对下面的
        $page = checkEmpty__($request["page"],1);
        $pageSize = checkEmpty__($request["pageSize"],10);
        if($types == 1){
            $rs = $this->jrtt->updateToutiaoAccount($params);
            $code = json_decode($rs);
            if($code->code==200){
                return redirect("/".htname."/toutiaoAccountList?page=$page&pageSize=$pageSize")->with("success_info","修改成功");
            }else{
                return redirect("/".htname."/toutiaoAccountList?page=$page&pageSize=$pageSize")->with("error_info","修改失败");
            }
        }else{
//            Log::info($params);
            $params[$params["name"]] = $params["value"];
            unset($params['name']);
            unset($params['value']);
            unset($params['types']);
            unset($params['pk']);
//            Log::info($params);
            return $this->jrtt->updateToutiaoAccount($params);
        }
    }

    // 获取单个账户的收益明细
    public function getCapitalDetailed(Request $request){
        $par = $request->all();
        $par["id"] = checkEmpty__($request["id"],1);
        $par["types"] = checkEmpty__($request["types"],1); // types ： 1 默认按时间查询 2 数据库已存在的数据
        $par["status"] = checkEmpty__($request["status"],1); // status ： 1 未结算 0 所有（提交的记录）
        $par["createTimeStart"] = checkEmpty__($request["createTimeStart"],Date("Y-m-d",strtotime("-7 day")));
        $par["createTimeEnd"] = checkEmpty__($request["createTimeEnd"],Date("Y-m-d",strtotime("-1 day")));
        $account = $this->jrtt->getDetailToutiaoAccount($par); // 账户信息
        $account = json_decode($account);
        $account = $account->data->result;
        $Data = $this->jrtt->getCapitalDetailed($par);
        $Data = json_decode($Data);
        if(!empty($Data)){
            $profits = $Data->data->result;
//            $moneysum = $Data->data->result->moneysum;
        }else{
            $profits = [];
//            $moneysum = 0;
        }
//        dd($profits);
        return view("ht.toutiao.toutiaoCapitalDetailed",compact("profits","account"));
    }

    // 获取所有头条账户
    public function getAccountAdminList(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],15);
        $accountAdminListData = json_decode($this->jrtt->getAccountAdminList($par));
        if(!empty($accountAdminListData->data->result)){
            $accountAdminLists = $accountAdminListData->data->result;
        }else{
            $accountAdminLists = "";
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = empty($accountAdminListData->data->last)?1:$accountAdminListData->data->last;
        return view("ht.toutiao.accountAdminLists",compact("accountAdminLists","page","pageSize","last"));
    }

    // 加盟商账户详情
    public function getHtAccountDetail(Request $request){
        $htAccountDetail = $this->jrtt->getHtAccountDetail($request->all());
//        $power = (unserialize($htAccountDetail->power));
//        dd($power);
//        $s = explode(",",$power); // 已经是数组了
////
//        dd(in_array("1",$s));
        return view("ht.jms.htAccountDetail",compact("htAccountDetail"));
    }

    // 小编账户详情
    public function getHtAscriptionDetail(Request $request){
        $htAscriptionDetail = $this->jrtt->getHtAscriptionDetail($request->all());
        $adminList = $this->jrtt->getHtAdminList();
        return view("ht.jms.htAscriptionDetail",compact("htAscriptionDetail","adminList"));
    }

    // 更改 or 保存 子账户
    public function saveHtAccount(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],15);
        $htAccountDetail = json_decode($this->jrtt->saveHtAccount($par));
        if($htAccountDetail->code == 200){
            return redirect("/".htname."/accountList?page={$par["page"]}&pageSize={$par["pageSize"]}")->with("success_info","更改成功");
        }else{
            return redirect("/".htname."/accountList?page={$par["page"]}&pageSize={$par["pageSize"]}")->with("error_info","更改失败");
        }
    }

    // 小编账户 列表
    public function getAccountAscription(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],15);
        $par["op_types"] = checkEmpty__($request["op_types"],-1); // 1 全职 2 兼职 3 离职
//        $par["status"] = checkEmpty__($request["status"],1); // 1 正常 2封禁
        $par["admin_id"] = checkEmpty__($request["admin_id"],-1); // 管理员查看（筛选）
        $accountAscriptionData = json_decode($this->jrtt->getAccountAscription($par));
        if(!empty($accountAscriptionData->data->result)){
            $accountAscriptions = $accountAscriptionData->data->result;
        }else{
            $accountAscriptions = "";
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $op_types = $par["op_types"];
//        $status = $par["status"];
        $admin_id = $par["admin_id"];
        $last = empty($accountAscriptionData->data->last)?1:$accountAscriptionData->data->last;
        return view("ht.toutiao.accountAscriptions",compact("accountAscriptions","page","pageSize","last","admin_id","op_types"));
    }

    // 小编公告
    public function getAccountNotice(Request $request){
        $par = $request->all();
        $par["id"] = checkEmpty__($request["id"],-1);
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],15);
        $noticesData = json_decode($this->jrtt->getAccountNotice($par));
        if(!empty($noticesData->data->result)){
            $notices = $noticesData->data->result;
        }else{
            $notices = "";
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $last = empty($noticesData->data->last)?1:$noticesData->data->last;
        return view("ht.notice",compact("notices","page","pageSize","last"));
    }

    // 小编公告详情
    public function getAccountNoticeDetail(Request $request){
        $notices = $this->jrtt->getAccountNotice($request->all());
        return view("ht.noticeUpdate",compact("notices"));
    }

    // 公告Top
    public function getAccountNoticeTop(Request $request){
        $par = $request->all();
        $par["id"] = checkEmpty__($request["id"],-1);
        $par["type"] = checkEmpty__($request["type"],1); // 1 置顶 2 取消置顶
        $notices = json_decode($this->jrtt->getAccountNoticeTop($par));
        if($notices->data->result == 1){
            return redirect("/".htname."/accountNotice")->with("success_info","操作成功");
        }else{
            return redirect("/".htname."/accountNotice")->with("error_info","操作失败");
        }
    }

    // 获取单独的加盟商所有的小编( 1 全职 2 兼职  )
    public function getDetailAccountAscription(Request $request){
        $par = $request->all();
        echo $this->jrtt->getDetailAccountAscription($par);
    }

    // 发布公告
    public function subNotice(Request $request){
        $par = $request->all();
        $par["id"] = checkEmpty__($request["id"],-1);
        $htAccountDetail = json_decode($this->jrtt->subNotice($par));
        if($htAccountDetail->code == 200){
            return redirect("/".htname."/accountNotice")->with("success_info","发布成功");
        }else{
            return redirect("/".htname."/accountNotice")->with("error_info","发布失败");
        }
    }

    // 加盟商 - > 添加 or 修改 小编
    public function saveHtAscription(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],15);
        $htAccountDetail = json_decode($this->jrtt->saveHtAscription($par));
        if($htAccountDetail->code == 200){
            return redirect("/".htname."/accountAscription?page={$par["page"]}&pageSize={$par["pageSize"]}")->with("success_info","操作成功");
        }else{
            return redirect("/".htname."/accountAscription?page={$par["page"]}&pageSize={$par["pageSize"]}")->with("error_info","操作失败");
        }
    }


}
