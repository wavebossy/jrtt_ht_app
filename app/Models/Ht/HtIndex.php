<?php

namespace App\Models\Ht;

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
            $adminData = DB::table("ht_admin")
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

}
