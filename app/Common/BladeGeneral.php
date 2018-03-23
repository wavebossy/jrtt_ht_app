<?php

define("___accessKey","WLNI2zsyIDKO1MTf5hCXajt7gX5Il6pp4xJIxk9k");
define("___secretKey","GC548AMYn09j45EhHvuOnSTZacGfiyIoM4Dd8NzE");
define("___host","http://ltt.tuike520.com");
define("___qiniu","http://odxq81dcn.bkt.clouddn.com/");
//define("___state","6612366");
//define("___scope","snsapi_base");
//define("___wap_name","邮来一九");
//define("___wx_appid","wxa7f88494bef30015");
//define("___wx_appsecret","dc0c964ecf5dcf9115007d64beb71d62");
//define("___wx_pay_key","YLYJAPPH5JGANDYT6666666666666666");
//define("___wx_mch_id","1488382392");
if(!function_exists('webData')){
    function webData($data){
        $d = json_decode($data);
        $d = $d->data->result;
        return $d;
    }
}


if(!function_exists('diffBetweenTwoDays')){
    //$day1 = "2013-07-27";
    //$day2 = "2013-08-04";
    //$diff = diffBetweenTwoDays($day1, $day2);
    //echo $diff."\n";
    function diffBetweenTwoDays($day1, $day2){
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }
}


if(!function_exists('getMonth')){
    function getThisMonth($date){
        $firstday = date("Y-m-01",strtotime($date));
        $lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
        return array($firstday,$lastday);
    }
}

if(!function_exists('getNextMonthDays')){
    function getNextMonthDays($date){
        $timestamp=strtotime($date);
        $arr=getdate($timestamp);
        if($arr['mon'] == 12){
            $year=$arr['year'] +1;
            $month=$arr['mon'] -11;
            $firstday=$year.'-0'.$month.'-01';
            $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
        }else{
            $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)+1).'-01'));
            $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
        }
        return array($firstday,$lastday);
    }
}


if(!function_exists('getUid')){
    //得到用户uid
    function getUid() {
        $uid = session("uid");
        if(!empty($uid)){
            return $uid;
        }else{
//            return "您还没登入";
            return "";
        }
    }
}


if(!function_exists('ToUrlParams')){
    /**
     * 格式化参数格式化成url参数
     */
    function ToUrlParams($data){
        $buff = "";
        foreach ($data as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
}


if(!function_exists('ToXml')){
    /**
     * 格式化参数格式化成url参数
     */
    function ToXml($data){
        if(is_array($data) && count($data) > 0) {
            $xml = "<xml>";
            foreach ($data as $key=>$val)
            {
//                if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
//                }else{
//                    $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
//                }
            }
            $xml.="</xml>";
            return $xml;
        }
        return "";
    }
}


function getJsApiTicket(){
    $data = json_decode(get_php_file(app_path()."/Common/token/jsapi_ticket.php"));
    if ($data->expire_time < time()) {
        $accessToken = getAccessToken();
        // 如果是企业号用以下 URL 获取 ticket
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
        $res = json_decode(requestAncient($url));
        $ticket = $res->ticket;
        if ($ticket) {
            $data->expire_time = time() + 7000;
            $data->jsapi_ticket = $ticket;
            set_php_file(app_path()."/Common/token/jsapi_ticket.php", json_encode($data));
        }
    } else {
        $ticket = $data->jsapi_ticket;
    }
    return $ticket;
}

function getAccessToken(){
    $data = json_decode(get_php_file(app_path()."/Common/token/access_token.php"));
    if ($data->expire_time < time()) {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".___wx_appid."&secret=".___wx_appsecret;
        $res = json_decode(requestAncient($url));
        $access_token = $res->access_token;
        if ($access_token) {
            $data->expire_time = time() + 7000;
            $data->access_token = $access_token;
            set_php_file(app_path()."/Common/token/access_token.php", json_encode($data));
        }
    } else {
        $access_token = $data->access_token;
    }
    return $access_token;
}

function get_php_file($filename) {
    return trim(substr(file_get_contents($filename), 15));
}
function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
}
function requestAncient($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
    curl_close($curl);
    return $data;
}
// 未格式化，post 请求
function requestPost($url,$postData){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}