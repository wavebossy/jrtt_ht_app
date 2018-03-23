<?php

namespace App\Models\XbHt;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/***
 * 小编账户
 * Class Operate
 * @package App\Models\XbHt
 */
class Operate extends Model
{
    public function operateInfo($par){
        // 获取数据
        if(intval(strval($par['types'])) == 0){
            return jsonEncodeData(getCodes()["CODE_200"],DB::table("ht_admin_operate")->select("usname","uaccount","alipay","bankcard")->where("id",session("admin_xb")->id)->first());
        }else{
            DB::beginTransaction();
            try{
                if(intval(strval($par['types'])) == 1){
                    unset($par["_token"]);
                    unset($par["types"]);
                    if(isset($par["uspwd"]) && !empty($par["uspwd"])){
                        $par["uspwd"] = md5($par["uspwd"]);
                    }else{
                        unset($par["uspwd"]);
                    }
                    DB::table("ht_admin_operate")
                        ->where("id",session("admin_xb")->id)
                        ->update($par);//$all
                    DB::commit();
                    return jsonEncodeData(getCodes()["CODE_200"]);
                }
                DB::rollback();
                return jsonEncodeData(getCodes()["CODE_202"]);
            }catch (QueryException $exception){
                DB::rollback();
                return jsonEncodeData(getCodes()["CODE_205"]);
            }
        }
    }

    public function formReport(){
        try{
            $admin_xb = session("admin_xb")->id;
            // 结算& 未结算收益
            $profits1 = DB::table("tt_admin_profit")->where("status",1)->where("operate_id",$admin_xb)->sum("moneysum");
            $profits2 = DB::table("tt_admin_profit")->where("operate_id",$admin_xb)->sum("moneysum");
            $bonus1 = DB::table("ht_admin_operate_bonus")->where("status",1)->where("operate_id",$admin_xb)->sum("moneys");
            $bonus2 = DB::table("ht_admin_operate_bonus")->where("operate_id",$admin_xb)->sum("moneys");
            $data = [
                "profits1"=>$profits1, // 未结算收益
                "profits2"=>$profits2, // 总收益
                "bonus1"=>$bonus1, // 未结算奖金
                "bonus2"=>$bonus2, // 总奖金
            ];
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"]);
        }
    }

    // 结算记录
    public function getBalance($params){
        try{
            $admin_xb = session("admin_xb")->id;
            $bonus = DB::table("ht_admin_operate_balance")->where("operate_id",$admin_xb)
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();
            $count = DB::table("ht_admin_operate_balance")->where("operate_id",$admin_xb)
                ->count();
            $last = ceil($count/$params["pageSize"]);
            return jsonEncodeData(getCodes()["CODE_200"],$bonus,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"]);
        }
    }

}
