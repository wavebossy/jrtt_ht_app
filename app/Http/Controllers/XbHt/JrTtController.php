<?php

namespace App\Http\Controllers\XbHt;

use App\Models\XbHt\JrTt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JrTtController extends Controller{
    // ht.user
    private $jrtt ;

    public function __construct(JrTt $jrtt){
        $this->jrtt = $jrtt;
    }

    // 数据采集页面
    public function getToutiaoArticle(Request $request){
        $par = $request->all();
        $par["page"] = checkEmpty__($request["page"],1);
        $par["pageSize"] = checkEmpty__($request["pageSize"],15);
        $par["types"] = checkEmpty__($request["types"],"全部");
        $par["comments_count_orderby"] = checkEmpty__($request["comments_count"],"desc"); // 排序
        $par["go_detail_count_orderby"] = checkEmpty__($request["go_detail_count"],"desc");
        $toutiaoTypes = json_decode($this->jrtt->getToutiaoType());
        if(!empty($toutiaoTypes)){
            $toutiaoTypes = $toutiaoTypes->data->result;
        }else{
            $toutiaoTypes = "";
        }
        $Data = $this->jrtt->getToutiaoArticle($par);
        $Data = json_decode($Data);
        if(!empty($Data)){
            $articles = $Data->data->result;
        }else{
            $articles = "";
        }
        $page = $par["page"];
        $pageSize = $par["pageSize"];
        $types = $par["types"];
        $last = empty($Data->data->last)?1:$Data->data->last;
        return view("xbht.toutiaoArticles",compact("toutiaoTypes","types","articles","page","pageSize","last"));
    }


}
