<?php

namespace App\Http\Controllers\XbHt;

use App\Models\XbHt\HtIndex;
use App\Models\XbHt\Operate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class IndexController extends Controller{

    private $xbht = "";
    private $operate = "";

    public function __construct(HtIndex $htIndex,Operate $operate){
        $this->xbht = $htIndex;
        $this->operate = $operate;
    }

    public function index(){
        $webht = $this->xbht->getConfig();
        session(["webht"=>$webht]);
        return view("xbht.index");
    }

    public function homePage(){
        $notices = $this->xbht->getAdminNotice(); // 公告
        $formReport = webData($this->operate->formReport());
        return view("xbht.homepage",compact('notices','formReport'));
    }

    public function notice($id){
        if(intval($id) <= 0 ) $id = 1;
        $noticeDetail = $this->xbht->getAdminNotice($id);
        return view("xbht.notice",compact('noticeDetail'));
    }

    /***
     * 登入
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request){
        $par = $request->all();
        $data = $this->xbht->login($par);
        $d = json_decode($data);
        if(empty($d->errorcode)){
            session(["admin_xb"=>$d->data->result]);
            return redirect("/".htname."/homepage_xb")->with('success_info', '登入成功');
        }else if($d->errorcode == 3001){
            return redirect("/".htname."/index_xb")->with('errorinfo', '账号或密码错误');
        }else if($d->errorcode == 3002){
            return redirect("/".htname."/index_xb")->with('errorinfo', '您的账户被封禁，无法登入');
        }
    }


    // 提交每日数据
    public function saveProfit(Request $request){
        $par = $request->all();
        $saveProfit = $this->xbht->saveProfit($par);
        $saveProfit = json_decode($saveProfit);
        if($saveProfit->code == 200){
            return redirect("/".htname."/subdata_xb")->with('success_info', '提交成功');
        }else{
            return redirect("/".htname."/subdata_xb")->with('errorinfo', '提交失败');
        }
    }

    // 更新每日数据
    public function updateProfit(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $saveProfit = $this->xbht->updateProfit($par);
        $saveProfit = json_decode($saveProfit);
        if($saveProfit->code == 200){
            return redirect("/".htname."/profitlist?page={$par["page"]}&pageSize={$par["pageSize"]}")->with('success_info', '提交成功');
        }else{
            return redirect("/".htname."/profitlist?page={$par["page"]}&pageSize={$par["pageSize"]}")->with('errorinfo', '提交失败');
        }
    }


    // 历史提交记录
    public function getProfitList(Request $request){
        $par = $request->all();
        $par["keyword"] = checkEmpty__($request["keyword"],"");
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],10);
        $par["createTimeStart"] = checkEmpty__($request["createTimeStart"],Date("Y-m-d",strtotime("-7 day")));
        $par["createTimeEnd"] = checkEmpty__($request["createTimeEnd"],Date("Y-m-d",strtotime("-1 day")));
        $Data = $this->xbht->getProfitList($par);
        $Data = json_decode($Data);
//        dd($Data);
        if(!empty($Data)){
            $articles = $Data->data->result;
        }else{
            $articles = [];
        }
        $keyword = $par["keyword"];
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $createTimeStart = $par["createTimeStart"];
        $createTimeEnd = $par["createTimeEnd"];
        $last = empty($Data->data->last)?1:$Data->data->last;
        return view("xbht.profitlist",compact("articles","page","pageSize","createTimeStart","createTimeEnd","keyword","last"));
    }

    // 保存select_time，moneysum，账户即可
    public function postProfit(Request $request){
//        Log::info($request->all());
        // value = 输入的值
        // name = 无所谓，可以不要
        // select_time = 自定义属性
        // !!!!!!!!二手提交失败的时候，做一个提示
        $this->xbht->postProfit($request->all());
//        return ;
    }

    public function postAdminIndex(Request $request){
        Log::info($request->all());
        $rs = $this->xbht->postAdminIndex($request->all());
        Log::info($rs);
    }

    // 历史提交记录
    public function profitDetail(Request $request){
        echo $this->xbht->profitDetail($request->all());
    }


    // 反馈
    public function saveOpinion(Request $request){
        $par = $request->all();
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
                    $image="";
                } else {
                    //$image="http://static.tuike520.com/".$fileName;
                    $image="http://odxq81dcn.bkt.clouddn.com/".$fileName;
                    $par["file"] = $image;
                }
            }
        }
        $saveProfit = $this->xbht->saveOpinion($par);
        $saveProfit = json_decode($saveProfit);
        if($saveProfit->code == 200){
            return redirect("/".htname."/opinion")->with('success_info', '提交成功');
        }else{
            return redirect("/".htname."/opinion")->with('errorinfo', '提交失败');
        }
    }
}
