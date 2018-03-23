<?php

namespace App\Http\Middleware;

use App\Models\Ht\Menu;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HtCheckLogin{

    private $paths = [
        "laravel-u-editor-server/server"  // UE 图片上传不验证
    ];

    /***
     * 是否存在要过滤的路由
     * @param $request_path
     * @return bool
     */
    private function returnPath($request_path){
        foreach ($this->paths as $path){
            if($request_path == $path){
                return true;
            }
        }
        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if($this->returnPath($request->path())){
            return $next($request);
        }
        // 登入后，做的中间件，在登入成功后跳转 /homepage
        $admin = session("admin");
        if(empty($admin)){
            return redirect('/'.htname.'/index');
        }
        // 获取当前角色的菜单权限
        $menu = session("menu");
        if(empty($menu)){
            $menu = new Menu();
            // 显示的菜单，排除功能菜单（存储session -> menu 主要是页面的面板）
            $menu = $menu->getMenu(["isshow"=>1]);
            $menu = json_decode($menu);
            $menu = $menu->data->result;
            session(["menu"=>$menu]); // 首次进来系统，正常情况，不会有任何数据操作
        }else{
            // 存储session -> controller 是真实的权限控制
            $menu_controller = session("menu_controller");
            if(empty($menu_controller)){
                $menu_controller = new Menu();
                $menu_controller = $menu_controller->getMenu();
                $menu_controller = json_decode($menu_controller);
                $menu = $menu_controller->data->result;
                session(["menu_controller"=>$menu]); // 功能菜单（权限菜单），将在下面的循环进行判断，是否允许该操作
            }else{
                $menu = session("menu_controller");
            }
        }
        $is_power = false;
        foreach ($menu as $m){
            if($request->path() === htname.$m->path){
//            if(strpos($request->path(),$m->path) !== false){
//                echo $request->path() . PHP_EOL;
//                echo $m->menuname . PHP_EOL;
//                echo $m->smalltext . PHP_EOL;
//                echo $m->path . PHP_EOL;
//                echo $m->breadcrumb . PHP_EOL;
//                exit;
                $is_power = true;
                session(["menuName"=>$m->menuname]); // 菜单名字
                session(["smallText"=>$m->smalltext]); // 菜单附属备注
                session(["breadcrumb"=>json_decode($m->breadcrumb)]); // 路径 【系统主页/菜单管理】
            }
        }
        if(!$is_power){
            // 非法访问，强制退出
            session(["admin"=>null]);
            session(["menu"=>null]);
            session(["menu_controller"=>null]);
            return redirect('/'.htname.'/index');
        }else{
            return $next($request);
        }
    }
}
