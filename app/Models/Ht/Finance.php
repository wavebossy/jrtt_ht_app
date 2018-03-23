<?php

namespace App\Models\Ht;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Finance extends Model{


    // 还可将财务列表权限分配出去
    public function getFinanceList($params){
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
            $tt_admins = DB::table("tt_admin")->where("isauth",2);
            $tt_admins_count = DB::table("tt_admin")->where("isauth",2);
            $____power = session("admin")->id; // true
            $____role = session("admin")->role;
            if($____role!=-1 && $____role!=2 ){
                $tt_admins = $tt_admins->where("power",$____power);
                $tt_admins_count = $tt_admins_count->where("power",$____power);
            }
            if($params["keyType"]!="默认搜索"){
                switch ($params["keyType"]){
                    case "只搜主体" :
                        $tt_admins = $tt_admins->where("subject",$params["keyword"]);
                        $tt_admins_count = $tt_admins_count->where("subject",$params["keyword"]);
                        break;
                    case "只搜联系人" :
                        $tt_admins = $tt_admins->where("contacts",$params["keyword"]);
                        $tt_admins_count = $tt_admins_count->where("contacts",$params["keyword"]);
                        break;
                    case "只搜小编" :
                        $ht_admin_operate = DB::table("ht_admin_operate")->select("id")->where("usname",$params["keyword"])->first();
                        $tt_admins = $tt_admins->where("operator",empty($ht_admin_operate)?-1:$ht_admin_operate->id);
                        $ht_admin_operate = DB::table("ht_admin_operate")->select("id")->where("usname",$params["keyword"])->first();
                        $tt_admins_count = $tt_admins_count->where("operator",empty($ht_admin_operate)?-1:$ht_admin_operate->id);
                        break;
                    case "只搜手机" :
                        $tt_admins = $tt_admins->where("phone",$params["keyword"]);
                        $tt_admins_count = $tt_admins_count->where("phone",$params["keyword"]);
                        break;
                    case "只搜领域" :
                        $tt_admins = $tt_admins->where("field",$params["keyword"]);
                        $tt_admins_count = $tt_admins_count->where("field",$params["keyword"]);
                        break;
                    case "只搜归属" :
                        $ht_admin = DB::table("ht_admin")->select("id")->where("usname",$params["keyword"])->first();
                        $tt_admins = $tt_admins->where("power",empty($ht_admin)?-1:$ht_admin->id);
                        $ht_admin = DB::table("ht_admin")->select("id")->where("usname",$params["keyword"])->first();
                        $tt_admins_count = $tt_admins_count->where("power",empty($ht_admin)?-1:$ht_admin->id);
                        break;
                    default : break;
                }
            }else{
                $tt_admins = $tt_admins
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
                $tt_admins_count = $tt_admins_count
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
            }
            $tt_admins = $tt_admins
                ->select("id","account","field")
//                ->where("status","<>",1) // 不是新手号
                ->skip($params["pageSize"]*($params['page']-1))
                ->limit($params["pageSize"])
                ->get()->toArray();
            $tt_admins_count = $tt_admins_count
//                ->where("status","<>",1)
                ->count();
            $sumDataTable = array(); // 表格
            foreach ($tt_admins as $tt_admin){
                $dataTable = array(); // 表格
                $moneys = 0;
                for($k=0;$k<=$daysum;$k++){
                    $profit = DB::select("select count(id) counts,sum(moneysum) moneysums from tt_admin_profit where admin_id = ? and select_time >= ? and select_time <= ?",[$tt_admin->id,$searchDate[$k],$searchDate[$k]]);
                    $moneys+=floatval(strval($profit[0]->moneysums));
                    array_push($dataTable,[
//                        "times"=>$searchDate[$k],
                        "counts"=>empty($profit[0]->counts)?0:$profit[0]->counts,
                        "moneysums"=>empty($profit[0]->moneysums)?0:$profit[0]->moneysums
                    ]);
                }
                array_push($sumDataTable,[
                    "field"=>$tt_admin->field,
                    "account"=>$tt_admin->account,
                    "moneys"=>$moneys,// 总计
                    "dataTable"=>$dataTable
                ]);
            }
//            $sumDataTable = array_values(array_sort($sumDataTable, function ($value) {
//                return $value['moneys'];
//            }));
            $data = [
                "searchDate"=>$searchDate, // 第一行
                "sumDataTable"=>$sumDataTable
            ];
            $last = ceil($tt_admins_count/$params["pageSize"]);
            // select moneysum from tt_admin_profit where select_time > 2017-12-01 and select_time < 2017-12-02 and admin_id = and operate_id =
            return jsonEncodeData(getCodes()["CODE_200"],$data,$last);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    public function getOperateList($par){
        try{

            //==================单个号收益===============
            $rsMoney = function($resultMoney){
                if($resultMoney<=1000 && $resultMoney >0){
                    $deserved = $resultMoney * 0.50;
                }elseif ($resultMoney>1001 && $resultMoney <=2000){
                    $deserved = $resultMoney * 0.55;
                }elseif ($resultMoney>2001 && $resultMoney <=3000){
                    $deserved = $resultMoney * 0.65;
                }elseif ($resultMoney>3001){
                    $deserved = $resultMoney * 0.70;
                }else{
                    $deserved = 0;
                }
                return $deserved;
            };
            //==================时间范围=================
            $operateId = $par["operate_id"];
            $dateTime = $par["dateTime"];
            $selectTime = getThisMonth($dateTime); // 返回当前月的第一天和最后一天
            // 未结算
            // select admin_id , operate_id, sum(moneysum)  from tt_admin_profit where status = 1 and operate_id = 1 GROUP BY admin_id,operate_id
            $result = DB::table('tt_admin_profit')
                ->select(DB::raw('sum(moneysum) as moneysum, admin_id, operate_id'))
                ->where('status', 1)
                ->where('select_time', ">=",$selectTime[0])
                ->where('select_time', "<=", $selectTime[1])
                ->where("operate_id",$operateId)
                ->groupBy('admin_id','operate_id')
                ->get();
            $resultMoney = 0.00;
            $deserved = 0.00 ;// 应得算法;
            foreach ($result as &$item){
                $resultMoney += floatval(strval($item->moneysum));
                $deserved += $rsMoney(floatval(strval($item->moneysum)));
                $admin = DB::table("tt_admin")->select("account")->where("id",$item->admin_id)->first();
                if(!empty($admin->account)){
                    $item->account = $admin->account;
                }else{
                    $item->account = "[--头条号被删除--]";
                }
            }
            // 奖金记录
            $bonus = DB::table("ht_admin_operate_bonus")->select("moneys","remark","times")
                ->where("status",1)
                ->where("operate_id",$operateId)
                ->get()->toArray();
            $bonusSumMoney = DB::table("ht_admin_operate_bonus")
                ->where("status",1)
                ->where("operate_id",$operateId)->sum("moneys");

            $Money = sprintf("%.2f",floatval($resultMoney+strval($bonusSumMoney)));
            $deserved = sprintf("%.2f",floatval($deserved+strval($bonusSumMoney))); // 应得

            $data = [
                "profit"=>$result,
                "bonus"=>$bonus,
                "money"=>$Money,
                "deserved"=>$deserved
            ];
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"]);
        }
    }

    public function operateCost($par){
        DB::beginTransaction();
        try{

            unset($par["_token"]);
            unset($par["file"]);
            $operateId = $par["operate_id"];
            $result = webData($this->getOperateList($par)); // 入参 operate_id
            $money = sprintf("%.2f",floatval($result->deserved));
            $moneysum = sprintf("%.2f",floatval($par["moneysum"]));
            if($money !== $moneysum || $money <=0 || $moneysum <=0 ){
                Log::info([$money !== $moneysum,$money ,$moneysum]);
                return jsonEncodeData(getCodes()["CODE_202"]); // 请求拒绝
            }

            // 更改提交结算记录状态
            //==================时间范围=================
            DB::table("tt_admin_profit")
                ->where('status', 1)
                ->where('select_time','>=',getThisMonth($par["dateTime"])[0])
                ->where('select_time','<=',getThisMonth($par["dateTime"])[1])
                ->where("operate_id",$operateId)
                ->update([
                    "status"=>2  // 已结算
                ]);
            // 奖金
            //==================时间范围=================
            DB::table("ht_admin_operate_bonus")
                ->where('status', 1)
                ->where("operate_id",$operateId)
                ->update([
                    "status"=>2  // 已结算
                ]);
            // 新增提交记录
            DB::table("ht_admin_operate_balance")->insert([
                "admin_id"=>session("admin")->id, // true
                "operate_id"=>$operateId,
                "paytype"=>$par["status"],
                "moneys"=>$moneysum,
                "imgurl"=>$par["imgurl"],
                "remark"=>$par["remark"],
                "datetime"=>getThisMonth($par["dateTime"])[0] .' 到 '. getThisMonth($par["dateTime"])[1]
            ]);

            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"]);
        }
    }

    public function bonusForm($par){
        DB::beginTransaction();
        try{
            unset($par["_token"]);
            // 奖金
            DB::table("ht_admin_operate_bonus")
                ->insert([
                    "operate_id"=>$par["operate_id"],
                    "moneys"=>floatval(strval($par["moneysum"])),
//                    "status"=>1,// default 1
                    "remark"=>$par["remark"],
                    "times"=>Date("Y-m-d H:i:s"),
                ]);

            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"]);
        }
    }

}
