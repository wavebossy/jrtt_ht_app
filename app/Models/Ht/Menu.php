<?php

namespace App\Models\Ht;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;


class Menu extends Model{

    /***
     * 允许带 id 查询
     * @param string $par
     * @return string
     */
    public function getMenu($par=""){
        try{
            //return jsonEncodeData(getCodes()["CODE_200"],DB::table("tt_admin")->whereIn("id",[11,12,13,14])->get()->toArray());
            $menu = DB::table("ht_menu");
            $____power = session("admin")->id;
            // 一般用户，只显示公共的菜单权限
            if($____power!=1){
                // 根据账户设置的权限，返回 1,2,3,4,5 菜单权限ID，in 的形式，返回符合当前设置条件的菜单
                $menu = $menu->whereIn("id",unserialize(session("admin")->power));
            }
            // 存在 isshow 参数 并且 等于 1 的时候显示，系统左侧菜单使用
            if(isset($par["isshow"]) && $par["isshow"] == 1){
                $menu = $menu->where("isshow",1);
            }
            if(isset($par["id"]) && !empty($par["id"])){
                // 获取单个详情
                $menu = $menu->where("id",$par["id"]);
            }
            $menu = $menu->orderBy("id","asc")->get()->toArray();
            return jsonEncodeData(getCodes()["CODE_200"],$menu);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }
    /***
     * 保存更改
     * @param string $par
     * @return string
     */
    public function saveMenu($all=[]){
        DB::beginTransaction();
        try{
            DB::table("ht_menu")
                ->where("id",$all["id"])
                ->update([
                    "breadcrumb"=>$all["breadcrumb"],
                    "icon"=>$all["icon"],
                    "isshow"=>$all["isshow"],
                    "menuname"=>$all["menuname"],
                    "parentid"=>$all["parentid"],
                    "path"=>$all["path"],
                    "smalltext"=>$all["smalltext"]
                ]);//$all
            DB::commit();
            return jsonEncodeData(getCodes()["CODE_200"]);
        }catch (QueryException $exception){
            DB::rollback();
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

    // 首页报表数据
    public function formReport(){
        try{
            // 获取当前账户管辖的所有头条号
            $tt_admins =  DB::table("tt_admin")->select("id");

            $____power = session("admin")->id; // true
            $____role = session("admin")->role;
            // 一般用户，只显示公共的菜单权限
            if($____role!=-1 && $____role!=2 ){ // 炒鸡管理员和财务
                // 根据账户设置的权限，返回 1,2,3,4,5 菜单权限ID，in 的形式，返回符合当前设置条件的菜单
                $tt_admins = $tt_admins->where("power",$____power);
            }
            $tt_admins = $tt_admins->get()->toArray(); //
            $ids = [];
            foreach ($tt_admins as $admin){
                array_push($ids,$admin->id);
            }
//            echo date("Y-m-d",strtotime("-1 week Monday")). "\n";
//            echo date("Y-m-d",strtotime("+0 week Monday"))."\n";
//            echo date("Y-m-d",strtotime("+1 week Monday"))."\n";
            // 昨天收益
            $profits1 = DB::table("tt_admin_profit")->where("select_time",Date("Y-m-d",strtotime("-1 day")))->whereIn("admin_id",$ids)->sum("moneysum");
            // 本周收益
            $profits2 = DB::table("tt_admin_profit")
                ->where("select_time",">=",Date("Y-m-d",strtotime("-1 week Monday")))
                ->where("select_time","<",Date("Y-m-d",strtotime("+0 week Monday")))
                ->whereIn("admin_id",$ids)->sum("moneysum");
            // 本月收益
            $profits3 = DB::table("tt_admin_profit")
                ->where("select_time",">=",Date('Y-m-01', strtotime('+0 month')))
                ->where("select_time","<=",Date('Y-m-t', strtotime('+0 month')))
                ->whereIn("admin_id",$ids)->sum("moneysum");
            // 所有收益
            $profits4 = DB::table("tt_admin_profit")->whereIn("admin_id",$ids)->sum("moneysum");
            $data = [
                "profits1"=>$profits1,
                "profits2"=>$profits2,
                "profits3"=>$profits3,
                "profits4"=>$profits4,
            ];
            return jsonEncodeData(getCodes()["CODE_200"],$data);
        }catch (QueryException $exception){
            return jsonEncodeData(getCodes()["CODE_205"],"","20501","数据库查询错误");
        }
    }

}
