<?php

namespace App\Http\Controllers\JrTt;

use App\Models\JrTt\AllDb;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller{

    // 头条搜索
    public function search(){
//        输出html ，但没卵用
//        $url = "https://www.toutiao.com/search_content/";
//        $data = [
//            "offset"=>0,
//            "format"=>"xml",
//            "keyword"=>"那英",
//            "autoload"=>"true",
//            "count"=>10,
//            "cur_tab"=>3   // 1,2,3,4 (综合 视频 图集 用户)
//        ];
//        $rs = http($url,$data);
//        $rs = json_decode($rs);
//        echo $rs->html;exit;
        $url = "https://www.toutiao.com/search_content/";
        $data = [
            "offset"=>0,
            "format"=>"json",
            "keyword"=>"那英",
            "autoload"=>"true",
            "count"=>10,
            "cur_tab"=>3   // 1,2,3,4 (综合 视频 图集 用户)
        ];
        return json_encode(json_decode(http($url,$data)),JSON_UNESCAPED_UNICODE);
    }

    public function test(){
        echo json_encode($_SERVER);
    }

    // 抓作者！收集作者的ID，他们的文章！
    // 万能抓取（前端传入抓取类别）
    public function universalGrab(Request $request){

        echo 1;exit;

//        https://www.toutiao.com/api/pc/feed/?category=gossip&utm_source=toutiao&widen=1&max_behot_time=0&max_behot_time_tmp=0&tadrequire=true&as=A1B5C9CF0EBD3B2&cp=59FEDD83FBE29E1
//        https://www.toutiao.com/api/pc/feed/?category=gossip&utm_source=toutiao&widen=1&max_behot_time=1509868992&max_behot_time_tmp=1509868992&tadrequire=true&as=A1A539CFEE1D3C2&cp=59FE6D336C629E1
//        https://www.toutiao.com/api/pc/feed/?category=gossip&utm_source=toutiao&widen=1&max_behot_time=1509868382&max_behot_time_tmp=1509868382&tadrequire=true&as=A115C92FBE2D3C3&cp=59FEAD33FC83FE1
//        https://www.toutiao.com/api/pc/feed/?category=gossip&utm_source=toutiao&widen=1&max_behot_time=1509867181&max_behot_time_tmp=1509867181&tadrequire=true&as=A175595F7EAD3C4&cp=59FE7DC3FC54FE1



//        $url='http://y.ylyj.com/test';
//        $result = http($url,[],"get",$this->getHeader());
//        dd($result);
////        $_SERVER
//        dd ($_SERVER);exit;
////        dd ($_REQUEST);exit;
//        var_dump($request);exit;
//        foreach (getallheaders() as $name => $value) {
//            echo "$name: $value <br/>";
//        }
//        exit;
        $grab = []; // 数据
        // 每次拉取都是有35分钟的间隔
//        $time = strtotime("2017-10-3");
//        $time = strtotime("2017/10/2 23:25:0");
//        $time = strtotime("2017/10/2 22:50:0");
//        $time = strtotime("2017/10/2 22:15:0");
//        $time = strtotime("2017/10/2 21:40:0");
//        $time = strtotime("2017/10/2 21:5:0");
        $time_begin = strtotime("2017/08/1 20:30:0");
        $time_end = strtotime("2017/08/1 22:30:0");

        $result = $this->getUserData("1763768068",$time_begin);
        array_push($grab,$result->data);
        $max_behot_time = $result->next->max_behot_time;
        $result = $this->getUserData("1763768068",$max_behot_time);
        array_push($grab,$result->data);

        dd($grab);
//        echo "begin_______" . PHP_EOL;
        $count = 0;
        while (true){
            $count++;
            if($time_end>$time_begin){
                $result = $this->getData($time_begin);
                $message = $result->message; // success
                if($message == "success"){
                    $data = $result->data;
                    array_shift($data); // 删除广告
                    $max_behot_time = $result->next->max_behot_time; // 1501588500 1501677000
                    foreach ($data as $datum){
                        array_push($grab,[
                            "title"=>$datum->title,// 标题
                            "comments_count"=>$datum->comments_count,// 评论数
                            "source_url"=>$datum->source_url,// http://www.toutiao.com/+ 原文链接
                        ]);
                    }
                    $time_begin=$max_behot_time;
                }
            }else{
                break;
            }
            if($count==5){
                echo json_encode($grab);exit;
            }
        }
//        echo "_______end" . PHP_EOL;
        dd($grab);

        $aa = function (&$grab,$time_begin,$time_end){
            $result = $this->getData($time_begin);
            dd($time_end);
            dd($result);
            $message = $result->message; // success
            if($message == "success"){
                $data = $result->data;
                array_shift($data); // 删除广告
                $max_behot_time = $result->next->max_behot_time; // 1501588500 1501677000

                foreach ($data as $datum){
                    $grab = [
                        "title"=>$datum->title,// 标题
                        "comments_count"=>$datum->comments_count,// 评论数
                        "source_url"=>$datum->source_url,// http://www.toutiao.com/+ 原文链接
                    ];
                }
                if($max_behot_time<$time_end){
                    $this->aa($grab,$time_begin,$time_end);
                }
            }
        };
        $aa($grab,$time_begin,$time_end);


        return json_encode(json_decode(http($url,$data)),JSON_UNESCAPED_UNICODE);

    }

    //
    private function getData($time=""){
        !empty($time) or $time = time();
        Log::info($time);
        // 暂定抓取类别
        $types = [
            "news_entertainment",     // 娱乐
            "news_tech",               // 科技
        ];
        $url = "https://www.toutiao.com/api/pc/feed/"; // 大分类抓取
        $data = [
            "category"=>$types[0],
            "utm_source"=>"toutiao",
            "widen"=>1,
            "max_behot_time"=>$time,  // 根据查询的天数，循环请求
            "max_behot_time_tmp"=>$time, // 2个参数值一样，时间倒叙搜索
            "tadrequire"=>true,
        ];
        $data = array_merge($data,$this->getAsCp()); // 拼上 as cp
        Log::info($data);
        return json_decode(http($url,$data,"get"));
    }

    // 作者的文章
    private function getUserData($user_id,$time=""){
        !empty($time) or $time = time();
        Log::info($time);
        $url = "https://www.toutiao.com/c/user/article/"; // 大分类抓取
        $data = [
            "page_type"=>1,
            "user_id"=>$user_id,
            "max_behot_time"=>$time,  // 根据查询的天数，循环请求
            "count"=>15,
            "offset"=>15,
        ];
        $data = array_merge($data,$this->getAsCp()); // 拼上 as cp
        Log::info($data);
        return json_decode(http($url,$data,"get"));
    }



    private function getHeader(){
//        $headers = array();
//        $headers[] = 'X-Apple-Tz: 1';
//        $headers[] = 'X-Apple-Store-Front: 143444,12';
//        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
//
//        $headers[] = 'Accept-Encoding: gzip, deflate';
//        $headers[] = 'Accept-Language: en-US,en;q=0.5';
//        $headers[] = 'Cache-Control: no-cache';
//        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
//        $headers[] = 'User-Agent: Mozilla/5.2 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
//        $headers[] = 'X-MicrosoftAjax: Delta=true';
        switch (rand(1,4)){
            case 1 :
                return $Google = array(
                    "Connection: keep-alive ",
                    "Accept: */*",
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5 ",
                    "Accept-Language: zh-CN,zh;q=0.8 ",
                    "Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3",

                );
            case 2 :
                return $Firefox = array(
                    "Connection: keep-alive ",
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8 ",
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:11.0) Gecko/20100101 Firefox/11.0 ",
                    "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3 ",
                    "DNT: 1",
                );
            case 3 :
                return $IE = array(
                    "Connection: keep-alive ",
                    "Accept: application/x-ms-application, image/jpeg, application/xaml+xml, image/gif, image/pjpeg, application/x-ms-xbap, */*",
                    "User-Agent: Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E) ",
                    "Accept-Language: zh-CN ",
                    "Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3",

                );
            case 4 :
                return $Opera = array(
                    "Connection: keep-alive ",
                    "Accept: text/html, application/xml;q=0.9, application/xhtml+xml, image/png, image/webp, image/jpeg, image/gif, image/x-xbitmap, */*;q=0.1",
                    "User-Agent: Opera/9.80 (Windows NT 6.1; WOW64; U; zh-cn) Presto/2.10.229 Version/11.62 ",
                    "Accept-Language: zh-CN,zh;q=0.9,en;q=0.8 ",
                    "Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3",

                );
            default :
                return $Google = array(
                    "Connection: keep-alive ",
                    "Accept: */*",
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5 ",
                    "Accept-Language: zh-CN,zh;q=0.8 ",
                    "Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3",

                );
        }
    }


}
