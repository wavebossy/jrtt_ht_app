<?php

namespace App\Http\Controllers\Ht;

use App\Models\Ht\HtIndex;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class IndexController extends Controller{

    private $ht = "";

    public function __construct(HtIndex $htIndex){
        $this->ht = $htIndex;
    }

    public function index(){
        $webht = $this->ht->getConfig();
        session(["webht"=>$webht]);
        return view("ht.index");
    }

    /***
     * 登入
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request){
        $par = $request->all();
        $data = $this->ht->login($par);
        $d = json_decode($data);
        if(empty($d->errorcode)){
            session(["admin"=>$d->data->result]);
            return redirect("/".htname."/homepage")->with('success_info', '登入成功');
        }else if($d->errorcode == 3001){
            return redirect("/".htname."/index")->with('errorinfo', '账号或密码错误');
        }else if($d->errorcode == 3002){
            return redirect("/".htname."/index")->with('errorinfo', '您的账户被封禁，无法登入');
        }
    }

    public function uploadFileImg(Request $request){
        $par = $request->all();
        $image="";
        if ($request->hasFile('upfile')) {
            if ($request->file('upfile')->isValid()){
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
                }
            }
        }
        return $image;
    }

}
