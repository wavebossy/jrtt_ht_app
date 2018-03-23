<?php

namespace App\Models\XbHt;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

/***
 * 后台登入，以及其他配置
 * Class Index
 * @package App\Models\Ht
 */
class HtIndex extends Model{
    //

    public function login($par){
        try{
            $adminData = DB::table("ht_admin_operate")
                ->where("uaccount",$par["uaccount"])
                ->where("uspwd",md5($par["uspwd"]))->first();
            if(!empty($adminData)){
                // 登入IP
                // 登入时间
                if($adminData->status == 2){
                    return jsonEncodeData(getCodes()["CODE_200"],"","",3002,"");
                }
                return jsonEncodeData(getCodes()["CODE_200"],$adminData);
            }else{
                // 密码错误
                return jsonEncodeData(getCodes()["CODE_200"],"","",3001,"");
            }
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getConfig(){
        try{
            return DB::table("ht_config")->get()->toArray();
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getAdminNotice($id=0){
        try{
            if($id == 0){
                return DB::table("ht_admin_notice")
                    ->where("status",1)
                    ->where("admin_id",session("admin_xb")->admin_id)
                    ->orWhere("admin_id",1)
                    ->orderBy('is_top','desc')
                    ->orderBy('times','desc')
                    ->get()->toArray();
            }else{
                DB::table("ht_admin_notice")->where("status",1)->where("id",$id)->increment("indexs");
                return DB::table("ht_admin_notice")->where("status",1)->where("id",$id)->first();
            }
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 小编管理的账户
    public function getAccounts(){
        try{
            return DB::table("tt_admin")
            ->where("operator",session("admin_xb")->id)
            ->get()->toArray();

        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


    // 提交每日数据
    public function saveProfit($par){
        DB::beginTransaction();
        try{
            unset($par["_token"]);
            $par["operate_id"] = session("admin_xb")->id;
            $par["select_time"] = Date("Y-m-d",strtotime($par["select_time"]));
            $par["times"] = Date("Y-m-d H:i:s");
            DB::table("tt_admin_profit")
                ->insert($par);//$all
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


    // 保存&更新数据
    public function postProfit($par){
        DB::beginTransaction();
        try{

            $operate_id = session("admin_xb")->id;
            $select_time = Date("Y-m-d",strtotime($par["select_time"])); // 自定义属性
            $times = Date("Y-m-d H:i:s"); // 操作时间
            $moneysum = floatval(strval($par["value"])); // 输入框的值

            $isempty = DB::table("tt_admin_profit")
                ->where("admin_id",$par["admin_id"]) // 头条账户ID
                ->where("operate_id",$operate_id) // 小编ID
                ->where("select_time",$select_time)  // 提交时间
                ->first();
            if(empty($isempty)){
                // 是否是2手转过来的号（必须是没有人提交过收益的）
                $ershou = DB::table("tt_admin_profit")
                    ->where("admin_id",$par["admin_id"]) // 头条账户ID
                    ->where("select_time",$select_time)  // 提交时间
                    ->first();
                if(empty($ershou)){
                    // 增加
                    DB::table("tt_admin_profit")
                        ->insert([
                            "admin_id"=>$par["admin_id"],
                            "operate_id"=>$operate_id,
                            "moneysum"=>$moneysum,
                            "select_time"=>$select_time,
                            "times"=>$times
                        ]);
                }
            }else{
                // 修正
                DB::table("tt_admin_profit")
                    ->where("admin_id",$par["admin_id"]) // 头条账户ID
                    ->where("operate_id",$operate_id) // 小编ID
                    ->where("select_time",$select_time)  // 提交时间
                    ->update([
                        "moneysum"=>$moneysum,
                        "times"=>$times // 更新 or 最后提交时间
                    ]);
            }

            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function postAdminIndex($par){
        DB::beginTransaction();
        try{

            $operate_id = session("admin_xb")->id;
            $tindex = intval(strval($par["value"])); // 输入框的值

            // 修正
            DB::table("tt_admin")
                ->where("id",$par["pk"]) // 头条账户ID
                ->where("operator",$operate_id) // 小编ID
                ->update([
                    "tindex"=>$tindex,
                ]);

            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }



    // 更新提交每日数据
    public function updateProfit($par){
        DB::beginTransaction();
        try{
            unset($par["_token"]);
            unset($par["page"]);
            unset($par["pageSize"]);
            $id = $par["id"];
            unset($par["id"]);
            $par["times"] = Date("Y-m-d H:i:s");
            DB::table("tt_admin_profit")
                ->where("id",$id)
                ->update($par);
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 获取历史提交数据
    public function getProfitList($params){
        try{
            // 查询天数
            $daysum = diffBetweenTwoDays($params["createTimeStart"],$params["createTimeEnd"]);
            $searchDate = array(); // 时间轴【2017-12-8，2017-12-9】
            $endTime = strtotime($params["createTimeEnd"]); //
            for($s=0;$s<=$daysum;$s++){
                array_push($searchDate,Date("Y-m-d",$endTime));
                $endTime-=86400;
            }
            // 当前账户所管辖的账户
            $tt_admins = DB::table("tt_admin")->where("operator",session("admin_xb")->id);
            $tt_admins_count = DB::table("tt_admin")->where("operator",session("admin_xb")->id);
            if($params["keyword"]!=""){
                $tt_admins = $tt_admins->where("account","like","%".$params["keyword"]."%");
                $tt_admins_count = $tt_admins_count->where("account","like","%".$params["keyword"]."%");
            }
            $tt_admins = $tt_admins
                ->select("id","account","phone","field")
//                ->where("status","<>",1) // 不是新手号 // 一般来讲，不是新手号不会安排出去
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();
            $tt_admins_count = $tt_admins_count->count();
            $sumDataTable = array(); // 表格
            foreach ($tt_admins as $tt_admin){
                $dataTable = array(); // 表格
                for($k=0;$k<=$daysum;$k++){
                    $profit = DB::select("select count(id) counts,sum(moneysum) moneysums from tt_admin_profit where admin_id = ? and select_time >= ? and select_time <= ?",[$tt_admin->id,$searchDate[$k],$searchDate[$k]]);
                    array_push($dataTable,[
                        "admin_id"=>$tt_admin->id,
                        "select_time"=>$searchDate[$k],
                        "counts"=>empty($profit[0]->counts)?0:$profit[0]->counts,
                        "moneysums"=>empty($profit[0]->moneysums)?0:$profit[0]->moneysums
                    ]);
                }
                $tindex = DB::table("tt_admin")->select("tindex")->where("id",$tt_admin->id)->first();
                array_push($sumDataTable,[
                    "admin_id"=>$tt_admin->id,
                    "account"=>$tt_admin->account,
                    "field"=>$tt_admin->field,
                    "phone"=>$tt_admin->phone,
                    "tindex"=>$tindex->tindex,
                    "dataTable"=>$dataTable
                ]);
            }
            $data = [
                "searchDate"=>$searchDate, // 第一行
                "sumDataTable"=>$sumDataTable
            ];

            $last = ceil($tt_admins_count/$params["pageSize"]);
            // select moneysum from tt_admin_profit where select_time > 2017-12-01 and select_time < 2017-12-02 and admin_id = and operate_id =
            return jsonEncodeData(getCodes()["CODE_200"],$data,$last);
//            $daysum = diffBetweenTwoDays($params["createTimeStart"],$params["createTimeEnd"]);
//            $searchDate = array(); // 时间轴【2017-12-8，2017-12-9】
//            $endTime = strtotime($params["createTimeEnd"]); //
//            for($s=0;$s<=$daysum;$s++){
//                array_push($searchDate,Date("Y-m-d",$endTime));
//                $endTime-=86400;
//            }
//
//            $profit = DB::table("tt_admin_profit")
//                ->where("operate_id",session("admin_xb")->id)
//                ->skip($params["pageSize"]*($params['page']-1))
//                ->limit($params["pageSize"])
//                ->get()->toArray();
//
//            $sumDataTable = array(); // 表格
//            foreach ($profit as &$item){
//                $dataTable = array(); // 表格
//                $item->admin = DB::table("tt_admin")->select("account")->where("id",$item->admin_id)->first();
//                for($k=0;$k<=$daysum;$k++){
//                    $profit = DB::select("select count(id) counts,sum(moneysum) moneysums from tt_admin_profit where admin_id = ? and select_time >= ? and select_time <= ?",[$tt_admin->id,$searchDate[$k],$searchDate[$k]]);
//                    $moneys+=floatval(strval($profit[0]->moneysums));
//                    array_push($dataTable,[
////                        "times"=>$searchDate[$k],
//                        "counts"=>empty($profit[0]->counts)?0:$profit[0]->counts,
//                        "moneysums"=>empty($profit[0]->moneysums)?0:$profit[0]->moneysums
//                    ]);
//                }
//                array_push($sumDataTable,[
//                    "account"=>$tt_admin->account,
//                    "moneys"=>$moneys,// 总计
//                    "dataTable"=>$dataTable
//                ]);
//            }
//
//            $data = [
//                "profit"=>$profit
//            ];
//            $data = [
//                "searchDate"=>$searchDate, // 第一行
//                "sumDataTable"=>$sumDataTable
//            ];
//
//            return jsonEncodeData(getCodes()["CODE_200"],$data);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 获取单个数据详情
    public function profitDetail($par){
        try{
            $profit = DB::table("tt_admin_profit")->select("id","admin_id","moneysum","select_time")->where("id",$par["id"])->first();
            $account = DB::table("tt_admin")->select("account")->where("id",$profit->admin_id)->first();
            $profit->account = $account->account;
            return jsonEncodeData(getCodes()["CODE_200"],$profit);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 反馈
    public function saveOpinion($par){
        DB::beginTransaction();
        try{
            $operate_id = session("admin_xb")->id;
            $times = Date("Y-m-d H:i:s"); // 操作时间
            $context = $par["context"];
            $files = $par["file"];
            $status = intval(strval($par["status"]));

            DB::table("ht_admin_operate_opinion")
                ->insert([
                    "operate_id"=>$operate_id,
                    "context"=>$context,
                    "files"=>$files,
                    "status"=>$status,
                    "times"=>$times
                ]);

            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }


}
