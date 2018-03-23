<?php

namespace App\Models\XbHt;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class JrTt extends Model{

    // 获取文章，图集分类
    public function getToutiaoType(){
        try{

            $articles = DB::table("tt_article")
                ->select("chinese_tag")
                ->where("chinese_tag","!=","''")
                ->groupBy('chinese_tag')
                ->get();

            return jsonEncodeData(getCodes()["CODE_200"],$articles);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getToutiaoArticle($params){
        try{

            $articles = DB::table("tt_article") ;

            if($params["types"] != "全部"){
                $articles = $articles->where('chinese_tag',$params["types"]);
            }
            $articles = $articles->orderBy('comments_count', $params["comments_count_orderby"]);
            $articles = $articles->orderBy('go_detail_count', $params["go_detail_count_orderby"]);
            $articles = $articles
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();

            // 分页
            $count = DB::table("tt_article");
            if($params["types"] != "全部"){
                $count = $count->where('chinese_tag',$params["types"]);
            }
            $count = $count->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$articles,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


}
