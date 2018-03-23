<?php

namespace App\Http\Controllers\Ht;

use App\Models\Ht\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePageController extends Controller{

    private $menu = "";

    function __construct(Menu $menu){
        $this->menu = $menu;
    }

    /***
     * 后台首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        // 显示数据报表
        // 登入IP等信息
        $formReport = webData($this->menu->formReport());
        return view("ht.homepage",compact('formReport'));
    }

    /***
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function menu(){
        $par = ["isshow"=>0]; // 全部显示
        $menu = webData($this->menu->getMenu($par));
        return view("ht.menu.menu",compact("menu"));
    }

    /***
     * 单个menu 数据
     * @param Request $request
     */
    public function menuUpdate(Request $request){
        $par = ["id"=>$request->input("id")];
        echo $this->menu->getMenu($par);
    }

    /***
     * 单个menu 数据
     * @param Request $request
     */
    public function menuSave(Request $request){
        $menu = $this->menu->saveMenu($request->all());
        $code = json_decode($menu);
        if($code->code==200){
            return redirect("/".htname."/menu")->with("success_info","操作成功");
        }else{
            return redirect("/".htname."/menu")->with("error_info","操作失败");
        }
    }

}
