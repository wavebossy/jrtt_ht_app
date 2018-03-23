<?php

namespace App\Models\Ht;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class JrTt extends Model{

    // 获取账户资金明细
    public function getCapitalDetailed($params){
        try{
            if($params["types"] == 1){
// 查询天数
                $daysum = diffBetweenTwoDays($params["createTimeStart"],$params["createTimeEnd"]);
                $searchDate = array(); // 时间轴【2017-12-8，2017-12-9】
//            $beginTime = strtotime($params["createTimeStart"]); //
                $endTime = strtotime($params["createTimeEnd"]); //
                for($s=0;$s<=$daysum;$s++){
//                array_push($searchDate,Date("Y-m-d",$beginTime));
                    array_push($searchDate,Date("Y-m-d",$endTime));
//                $beginTime+=86400;
                    $endTime-=86400;
                }
                $sumDataTable = array(); // 表格
                for($k=0;$k<=$daysum;$k++){
                    $profit = DB::select("select count(id) counts,sum(moneysum) moneysums from tt_admin_profit where admin_id = ? and select_time >= ? and select_time <= ? ",[$params["id"],$searchDate[$k],$searchDate[$k]]);
                    array_push($sumDataTable,[
                        "times"=>$searchDate[$k],
                        "counts"=>empty($profit[0]->counts)?0:$profit[0]->counts,
                        "moneysums"=>empty($profit[0]->moneysums)?0:$profit[0]->moneysums
                    ]);
                }
                // select moneysum from tt_admin_profit where select_time > 2017-12-01 and select_time < 2017-12-02 and admin_id = and operate_id =
                return jsonEncodeData(getCodes()["CODE_200"],$sumDataTable);
            }else{
                $sumDataTable = DB::table('tt_admin_profit')
                    ->select(DB::raw('sum(moneysum) as moneysums, admin_id, operate_id,select_time as times'))
                    ->where('status',$params["status"])
                    ->where('admin_id',$params["id"])
                    ->where("operate_id",$params["operate_id"])
                    ->groupBy('select_time','admin_id','operate_id')
                    ->orderBy('select_time','desc')
                    ->get();
                return jsonEncodeData(getCodes()["CODE_200"],$sumDataTable);
            }

        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function updateToutiaoAccount($params){
        DB::beginTransaction();
        try{
            unset($params["page"]);
            unset($params["pageSize"]);
            unset($params["_token"]); // 添加了 VerifyCsrfToKen

            $first = DB::table("tt_admin")->where("id",$params["id"])->first();
            DB::table("tt_admin_logs")->insert([
                "logs_"=>json_encode(array(
                    session("admin")->usname,$first
                )),
                "times"=>Date("Y-m-d H:i:s")
            ]);

            DB::table("tt_admin")->where("id",$params["id"])->update($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function saveToutiaoAccount($params){
        DB::beginTransaction();
        try{
            unset($params["_token"]);

            $par = [
                "times" =>Date("Y-m-d H:i:s"),
            ];
            // 归属谁(如果是管理员将此权限分配给其他账户，则只能添加到他的账户下)
            $____power = session("admin")->id; // true
            $____role = session("admin")->role;
            if($____role!=-1 && $____role!=1){
                $params = array_merge($params,["power"=>$____power]);
            }
            $params = array_merge($params,$par);
            DB::table("tt_admin")->insert($params);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 获取账号归属(加盟商)
    public function getHtAdminList(){
        return  DB::table("ht_admin")->where("role","<=",0)->get()->toArray(); // 炒鸡管理员是 -1 权限， 特殊
    }

    // 获取账号归属下的所有小编
    public function getHtAdminOperate($par=1){
        return  DB::table("ht_admin_operate")->where("op_types","<>",3)->where("admin_id",$par)->get()->toArray();
    }

    // 获取单个小编
    public function getHtAscriptionDetail($params){
        return DB::table("ht_admin_operate")->where("id",$params["id"])->first();
    }

    //
    public function getBalanceList($params){
        try{
            $balance = DB::table("ht_admin_operate_balance")
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->orderBy("id","desc")
                ->get()->toArray();
            foreach ($balance as &$item){
                $usname = DB::table("ht_admin_operate")->select("usname","admin_id")->where("id",$item->operate_id)->first();
                $item->usname = $usname->usname;
                $admin_usname = DB::table("ht_admin")->select("usname")->where("id",$usname->admin_id)->first();
                $item->admin_usname = $admin_usname->usname;
                unset($item->operate_id);
            }
            // 分页
            $count = DB::table("ht_admin_operate_balance");
            $count = $count->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$balance,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getDetailToutiaoAccount($params){
        try{
            $results = DB::table("tt_admin")->where("id",$params["id"])->first();
            return jsonEncodeData(getCodes()["CODE_200"],$results);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 头条号列表
    public function getToutiaoAccountList($params){
        try{
            $articles = DB::table("tt_admin");
            if($params["types"]!=0){
                $articles = $articles->where("status",$params["types"]);
            }
            if($params["isauth"]!=0){
                $articles = $articles->where("isauth",$params["isauth"]);
            }
            // 当前账户所管辖的账户
            $____role = session("admin")->role; // 是否是员工权限
            // 炒鸡管理员 或者 员工权限可以过滤不用这个条件
            if($____role !=-1 && $____role !=1){
                $____power = session("admin")->id; // true
                $articles = $articles->where("power",$____power);
            }
            if($params["keyType"]!="默认搜索"){
                switch ($params["keyType"]){
                    case "只搜主体" : $articles = $articles->where("subject",$params["keyword"]);break;
                    case "只搜联系人" : $articles = $articles->where("contacts",$params["keyword"]);break;
                    case "只搜小编" :
                        $ht_admin_operate = DB::table("ht_admin_operate")->select("id")->where("usname",$params["keyword"])->first();
                        $articles = $articles->where("operator",empty($ht_admin_operate)?-1:$ht_admin_operate->id);break;
                    case "只搜手机" : $articles = $articles->where("phone",$params["keyword"]);break;
                    case "只搜领域" : $articles = $articles->where("field",$params["keyword"]);break;
                    case "只搜归属" :
                        $ht_admin = DB::table("ht_admin")->select("id")->where("usname",$params["keyword"])->first();
                        $articles = $articles->where("power",empty($ht_admin)?-1:$ht_admin->id);break;
                    default : break;
                }
            }else{
                if(!empty($params["keyword"]) and isset($params["keyword"])){

                    $articles = $articles
                        ->where(function ($query) use ($params) {
                            $query
                                ->orWhere("ttid","like",'%'.$params["keyword"].'%')
                                ->orWhere("account","like",'%'.$params["keyword"].'%')
                                ->orWhere("field","like",'%'.$params["keyword"].'%')
                                ->orWhere("phone","like",'%'.$params["keyword"].'%')
                                ->orWhere("mailboxs","like",'%'.$params["keyword"].'%')
                                ->orWhere("subject","like",'%'.$params["keyword"].'%')
                                ->orWhere("contacts","like",'%'.$params["keyword"].'%')
                                ->orWhere("operator","like",'%'.$params["keyword"].'%')
                                ->orWhere("remark","like",'%'.$params["keyword"].'%');
                        });
//                    $articles = $articles
//                        ->orWhere("ttid","like",'%'.$params["keyword"].'%')
//                        ->orWhere("account","like",'%'.$params["keyword"].'%')
//                        ->orWhere("field","like",'%'.$params["keyword"].'%')
//                        ->orWhere("phone","like",'%'.$params["keyword"].'%')
//                        ->orWhere("mailboxs","like",'%'.$params["keyword"].'%')
//                        ->orWhere("subject","like",'%'.$params["keyword"].'%')
//                        ->orWhere("contacts","like",'%'.$params["keyword"].'%')
//                        ->orWhere("operator","like",'%'.$params["keyword"].'%')
//                        ->orWhere("remark","like",'%'.$params["keyword"].'%')
//                    ;
                }
            }


            $articles = $articles->orderBy('serial', 'asc')
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();

//            dd($articles);
            foreach ($articles as &$article){
                // 加盟商
                $ht_power = DB::table("ht_admin")->select("usname")->where("id",$article->power)->first();
                $article->power = empty($ht_power)?"未分配":$ht_power->usname;
                // 小编
                $ht_admin_operate = DB::table("ht_admin_operate")->select("usname")->where("id",$article->operator)->first();
                $article->operator = empty($ht_admin_operate)?"未分配":$ht_admin_operate->usname;
            }

//            dd($articles);
//            dd(DB::getQueryLog());

            // count
            $count = DB::table("tt_admin");
            if($params["types"]!=0){
                $count = $count->where("status",$params["types"]);
            }
            if($params["isauth"]!=0){
                $count = $count->where("isauth",$params["isauth"]);
            }
            // 当前账户所管辖的账户
            $____role = session("admin")->role; // 是否是员工权限
            // 炒鸡管理员 或者 员工权限可以过滤不用这个条件
            if($____role !=-1 && $____role !=1){
                $____power = session("admin")->id; // true
                $count = $count->where("power",$____power);
            }
            if($params["keyType"]!="默认搜索"){
                switch ($params["keyType"]){
                    case "只搜主体" : $count = $count->where("subject",$params["keyword"]);break;
                    case "只搜联系人" : $count = $count->where("contacts",$params["keyword"]);break;
                    case "只搜小编" :
                        $ht_admin_operate = DB::table("ht_admin_operate")->select("id")->where("usname",$params["keyword"])->first();
                        $count = $count->where("operator",empty($ht_admin_operate)?-1:$ht_admin_operate->id);break;
                    case "只搜手机" : $count = $count->where("phone",$params["keyword"]);break;
                    case "只搜领域" : $count = $count->where("field",$params["keyword"]);break;
                    case "只搜归属" :
                        $ht_admin = DB::table("ht_admin")->select("id")->where("usname",$params["keyword"])->first();
                        $count = $count->where("power",empty($ht_admin)?-1:$ht_admin->id);break;
                    default : break;
                }
            }else{
                if(!empty($params["keyword"]) and isset($params["keyword"])){
                    $count = $count
                        ->where(function ($query) use ($params) {
                            $query
                                ->orWhere("ttid","like",'%'.$params["keyword"].'%')
                                ->orWhere("account","like",'%'.$params["keyword"].'%')
                                ->orWhere("field","like",'%'.$params["keyword"].'%')
                                ->orWhere("phone","like",'%'.$params["keyword"].'%')
                                ->orWhere("mailboxs","like",'%'.$params["keyword"].'%')
                                ->orWhere("subject","like",'%'.$params["keyword"].'%')
                                ->orWhere("contacts","like",'%'.$params["keyword"].'%')
                                ->orWhere("operator","like",'%'.$params["keyword"].'%')
                                ->orWhere("remark","like",'%'.$params["keyword"].'%');
                        });
//                    $count = $count
//                        ->orWhere("ttid","like",'%'.$params["keyword"].'%')
//                        ->orWhere("account","like",'%'.$params["keyword"].'%')
//                        ->orWhere("field","like",'%'.$params["keyword"].'%')
//                        ->orWhere("phone","like",'%'.$params["keyword"].'%')
//                        ->orWhere("mailboxs","like",'%'.$params["keyword"].'%')
//                        ->orWhere("subject","like",'%'.$params["keyword"].'%')
//                        ->orWhere("contacts","like",'%'.$params["keyword"].'%')
//                        ->orWhere("operator","like",'%'.$params["keyword"].'%')
//                        ->orWhere("remark","like",'%'.$params["keyword"].'%')
//                    ;
                }
            }

            $count = $count->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$articles,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }



    public function getToutiaoAccountLog($params){
        try{
            $tt_admins = DB::table("tt_admin_logs")
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->orderBy("id","desc")
                ->get()->toArray();
            foreach ($tt_admins as &$admin){
                $admin->ht_admin = json_decode($admin->logs_)[0];
                $admin->tt_admin = json_decode($admin->logs_)[1];
                unset($admin->logs_);
                $ht_power = DB::table("ht_admin")->select("usname")->where("id",$admin->tt_admin->power)->first();
                $admin->tt_admin->power = empty($ht_power)?"未分配":$ht_power->usname;
                $ht_admin_operate = DB::table("ht_admin_operate")->select("usname")->where("id",$admin->tt_admin->operator)->first();
                $admin->tt_admin->operator = empty($ht_admin_operate)?"未分配":$ht_admin_operate->usname;
            }
            // 分页
            $count = DB::table("tt_admin_logs");
            $count = $count->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$tt_admins,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


    public function getAccountAdminList($params){
        try{

            $tt_admins = DB::table("ht_admin") ;
            $tt_admins = $tt_admins
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
//                ->orderBy("role","asc")
                ->get()->toArray();
            // 分页
            $count = DB::table("ht_admin");
            $count = $count->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$tt_admins,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getHtAccountDetail($params){
        return DB::table("ht_admin")->where("id",$params["id"])->first();
    }

    public function saveHtAccount($params){
        DB::beginTransaction();
        try{
            unset($params["_token"]);
            unset($params["page"]);
            unset($params["pageSize"]);
            // 输入新密码的时候，会重置
            if(!empty($params["uspwd"])){
                $params["uspwd"] = md5($params["uspwd"]);
            }else{
                unset($params["uspwd"]);
            }
            $params["times"] = Date("Y-m-d H:i:s");
            !isset($params["power"]) or $params["power"] = serialize($params["power"]);
            if(isset($params["id"]) && !empty($params["id"])){
                // 修改账户
                DB::table("ht_admin")->where(
                    "id",$params["id"]
                )->update($params);
            }else{
                DB::table("ht_admin")->insert($params);
            }
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 更改小编
    public function saveHtAscription($params){
        DB::beginTransaction();
        try{
            unset($params["_token"]);
            unset($params["page"]);
            unset($params["pageSize"]);
            // 输入新密码的时候，会重置
            if(!empty($params["uspwd"])){
                $params["uspwd"] = md5($params["uspwd"]);
            }else{
                unset($params["uspwd"]);
            }
            // 如果不是总账户，只能管理他自己的
            $____power = session("admin")->id; // true
            // 修改 or 添加
            if(isset($params["id"]) && !empty($params["id"])){
                if($____power!=1){
                    $params["admin_id"] = $____power;
                }
                // 修改账户
                DB::table("ht_admin_operate")->where(
                    "id",$params["id"]
                )->update($params);
            }else{
                $params["admin_id"] = $____power;
                DB::table("ht_admin_operate")->insert($params);
            }
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getAccountAscription($params){
        try{

            $ht_admin_operates = DB::table("ht_admin_operate") ;
            $____power = session("admin")->id; // true
            if($____power!=1){
                $ht_admin_operates = $ht_admin_operates->where("admin_id",$____power);
            }
            // admin 使用 快速通过加盟商查看小编
            if($params["admin_id"]!=-1 && session("admin")-> role == -1){
                $ht_admin_operates = $ht_admin_operates->where("admin_id",$params["admin_id"]);
            }
            if($params["op_types"]!=-1){
                $ht_admin_operates = $ht_admin_operates->where("op_types",$params["op_types"]);
            }
            $ht_admin_operates = $ht_admin_operates
//                ->where("status",$params["status"])
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();
            foreach ($ht_admin_operates as $admin_operate){
                $ht_admin = DB::table("ht_admin")->select("usname")->where("id",$admin_operate->admin_id)->first();
                $admin_operate->admin_usname = $ht_admin->usname;
                $tt_admin_count = DB::table("tt_admin")->where("operator",$admin_operate->id)->count();
                $admin_operate->account_number = $tt_admin_count;
                $tt_admin_field = DB::table("tt_admin")->select("field")->where("operator",$admin_operate->id)->groupBy("field")->get()->toArray();
                $admin_operate->fields = $tt_admin_field;
            }
            // 分页
            $count = DB::table("ht_admin_operate");
            $____power = session("admin")->id; // true
            if($____power!=1){
                $count = $count
                    ->where("admin_id",$____power);
            }
            // admin 使用 快速通过加盟商查看小编
            if($params["admin_id"]!=-1 && session("admin")-> role == -1){
                $count = $count->where("admin_id",$params["admin_id"]);
            }
            if($params["op_types"]!=-1){
                $count = $count->where("op_types",$params["op_types"]);
            }
            $count = $count
//                ->where("status",$params["status"])
                ->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$ht_admin_operates,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


    public function getAccountNotice($params){
        try{
            if($params['id'] == -1){
                $notice = DB::table("ht_admin_notice")
                    ->where("status",1)
                    ->skip($params["pageSize"]*($params['page']-1))
                    ->limit($params["pageSize"]);
                $count = DB::table("ht_admin_notice");
                $____power = session("admin")->id; // true
                $____role = session("admin")->role;
                if($____role!=-1){ // 不是炒鸡管理员 只能看到自己发的
                    $notice = $notice->where("admin_id",$____power);
                    $count = $count->where("admin_id",$____power);
                }
                $count = $count->count();
                $notice = $notice->get()->toArray();

                foreach ($notice as $admin_operate){
                    $ht_admin = DB::table("ht_admin")->select("usname")->where("id",$admin_operate->admin_id)->first();
                    $admin_operate->admin_usname = $ht_admin->usname;
                }
                $last = ceil($count/$params["pageSize"]);
                // 超极管理员能看到所有加盟商发的公告
                return jsonEncodeData(getCodes()["CODE_200"],$notice,$last);
            }else{
                // 获取单个详情
                $notice = DB::table("ht_admin_notice")->where("status",1);
                $____power = session("admin")->id; // true
                $____role = session("admin")->role;
                if($____role!=-1){ // 不是炒鸡管理员 只能看到自己发的
                    $notice = $notice->where("admin_id",$____power);
                }
                $notice = $notice->where("id",$params['id'])->first();
                $ht_admin = DB::table("ht_admin")->select("usname")->where("id",$notice->admin_id)->first();
                $notice->admin_usname = $ht_admin->usname;
                return $notice;
            }
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


    public function getAccountNoticeTop($params){
        DB::beginTransaction();
        try{
            $notice = DB::table("ht_admin_notice")
                ->where("status",1)
                ->where("id",$params["id"]);
            $____power = session("admin")->id; // true
            $____role = session("admin")->role;
            if($____role!=-1){ // 不是炒鸡管理员 只能看到自己发的
                $notice = $notice->where("admin_id",$____power);
            }
            if($params["type"] == 1){
                $notice = $notice->update(["is_top"=>1]);
            }else{
                $notice = $notice->update(["is_top"=>0]);
            }
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"],$notice);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function subNotice($params){
        DB::beginTransaction();
        try{
            unset($params["_token"]);
            if($params["id"] == -1){
                unset($params["id"]);
                // 归属谁(如果是管理员将此权限分配给其他账户，则只能添加到他的账户下)
                $____power = session("admin")->id; // true
                $par = [
                    "admin_id"=>$____power,
                    "status"=>1,
                    "times" =>Date("Y-m-d H:i:s"),
                ];
                $params = array_merge($params,$par);
                DB::table("ht_admin_notice")->insert($params);
                DB::commit();
                return jsonEncodeData(getCodes()["CODE_200"]);
            }else{
                // 修改
                $____power = session("admin")->id; // true
                $par = [
                    "admin_id"=>$____power,
                    "status"=>1,
                    "times" =>Date("Y-m-d H:i:s"),
                ];
                $params = array_merge($params,$par);
                DB::table("ht_admin_notice")->where(
                    "id",$params["id"]
                )->update($params);
                DB::commit();
                return jsonEncodeData(getCodes()["CODE_200"]);
            }

        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getDetailAccountAscription($params){
        try{
            $ht_admin_operates = DB::table("ht_admin_operate")
                ->where("op_types","<>",3)
                ->where("admin_id",$params["admin_id"])
                ->get()->toArray();
            foreach ($ht_admin_operates as $admin_operate){
                $ht_admin = DB::table("ht_admin")->select("usname")->where("id",$admin_operate->admin_id)->first();
                $admin_operate->admin_usname = $ht_admin->usname;
            }
            return jsonEncodeData(getCodes()["CODE_200"],$ht_admin_operates);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


}
