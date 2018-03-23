<?php
header("Access-Control-Allow-Origin: *");
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/",function(){
    return redirect('/'.htname.'/index');
});

Route::get("/testJob","Vtest\TestController@testJob");

// 需要登入后才能使用的功能（web 添加了中间件，检测是否登入）
Route::group(['namespace' => 'JrTt'], function () {

    // 今日头条
    Route::any('/search', 'IndexController@search');
    Route::any('/grab', 'IndexController@universalGrab');
    Route::any('/test', 'IndexController@test');

});

define("htname","ht");

// http://fontawesome.dashgame.com/ 菜单图标，备份下，可能考虑换阿里

Route::group(['namespace' => 'Ht'], function () {

    // 这些功能不需要登入
    Route::get('/'.htname.'/index', 'IndexController@index');
    Route::post('/'.htname.'/login', 'IndexController@login');
    Route::get('/'.htname.'/loginout', function (){
        session()->forget('admin');
        session()->flush();
        return redirect('/'.htname.'/index');
    });

    // 需要登入后才能使用的功能（web 添加了中间件，检测是否登入）
    Route::group(['middleware'=>'htchecklogin'], function () {
        // Page
        // 后台系统主页
        Route::get("/".htname."/homepage", 'HomePageController@index');
        // 菜单页面
        Route::get("/".htname."/menu", 'HomePageController@menu');
        // 头条账号管理
        Route::get("/".htname."/toutiaoAccountList", 'JrTtController@getToutiaoAccountList');
        // 更变记录
        Route::get("/".htname."/toutiaoAccountLog", 'JrTtController@getToutiaoAccountLog');
        // 增加头条号
        Route::get("/".htname."/toutiaoAccountAdd", 'JrTtController@getToutiaoAccountAdd');
        // 头条账户详情
        Route::get("/".htname."/toutiaoAccountDetail", 'JrTtController@detailToutiaoAccount');
        // 头条账户资金明细
        Route::get("/".htname."/toutiaoAccountCapitalDetailed", 'JrTtController@getCapitalDetailed');
        // 后台账户管理
        Route::get("/".htname."/accountList", 'JrTtController@getAccountAdminList');
        // 操作修改，账户【加盟商】
        Route::get("/".htname."/htAccountDetail", 'JrTtController@getHtAccountDetail');
        // 小编账户
        Route::get("/".htname."/accountAscription", 'JrTtController@getAccountAscription');
        // 公告列表
        Route::get("/".htname."/accountNotice", 'JrTtController@getAccountNotice');
        Route::get("/".htname."/accountNoticeAdd", function (){
            return view("ht.noticeAdd");
        });
        Route::get("/".htname."/accountNoticeDetail",'JrTtController@getAccountNoticeDetail');

        // 公告，设置置顶，取消置顶
        Route::get("/".htname."/accountNoticeTop",'JrTtController@getAccountNoticeTop');

        // 操作修改，账户【小编】
        Route::get("/".htname."/htAscriptionDetail", 'JrTtController@getHtAscriptionDetail');

        // 财务系统
        Route::get("/".htname."/financeList", 'JrTtFinanceController@getFinanceList');
        Route::get("/".htname."/xiaobianList", "JrTtFinanceController@getXiaoBianList");
        Route::get("/".htname."/operateSettlement", "JrTtFinanceController@getOperateSettlement");
        Route::post("/".htname."/operateList", "JrTtFinanceController@getOperateList"); // 获取提交记录，未结算金额总计，所有运营的账号

        // =============================================================================================================
        // 功能

        // 菜单获取&&保存
        Route::post("/".htname."/menuUpdate", 'HomePageController@menuUpdate');
        Route::post("/".htname."/menuSave", 'HomePageController@menuSave');

        // 新增头条账户
        Route::post("/".htname."/toutiaoAccountSave", 'JrTtController@saveToutiaoAccount');
        // 修改头条账户
        Route::post("/".htname."/toutiaoAccountUpdate", 'JrTtController@updateToutiaoAccount');

        // 加盟商账户
        Route::post("/".htname."/saveHtAccount", 'JrTtController@saveHtAccount');
        // 加盟商 添加小编
        Route::post("/".htname."/saveHtAscription", 'JrTtController@saveHtAscription');
        // 获取当前下拉加盟商的小编
        Route::post("/".htname."/getDetailAccountAscription", 'JrTtController@getDetailAccountAscription');
        Route::post("/".htname."/subNotice", 'JrTtController@subNotice');

        // 小编费用结算
        Route::post("/".htname."/operateCost", 'JrTtFinanceController@operateCost');
        // 小编奖金发放
        Route::post("/".htname."/bonusForm", 'JrTtFinanceController@bonusForm');

        // 非可见型，非菜单型的路由
        // 异步队列打包文档

        // 文档下载


    });
});


Route::group(['namespace' => 'XbHt'], function () {

    // 小编后台
    Route::get('/'.htname.'/index_xb', 'IndexController@index');
    Route::post('/'.htname.'/login_xb', 'IndexController@login');
    Route::get('/'.htname.'/loginout_xb', function (){
        session()->forget('admin_xb');
        session()->flush();
        return redirect('/'.htname.'/index_xb');
    });

    // 需要登入后才能使用的功能（web 添加了中间件，检测是否登入）
    Route::group(['middleware'=>'htchecklogin_xb'], function () {
        Route::post("/".htname."/postProfit","IndexController@postProfit"); // 小编收益
        Route::post("/".htname."/postAdminIndex","IndexController@postAdminIndex"); // 小编头条号指数
        // Page
        // 小编后台
        Route::get("/".htname."/homepage_xb", "IndexController@homePage");
        // 公告详情
        Route::get("/".htname."/notice/{id}", "IndexController@notice");
        // 意见反馈
        Route::get("/".htname."/opinion", function (){return view("xbht.opinion");});
        // 提交反馈
        Route::post("/".htname."/saveOpinion", "IndexController@saveOpinion");
        // 提交每日收益
        Route::post("/".htname."/saveProfit",'IndexController@saveProfit');
        // 修改账户资料
        Route::post("/".htname."/operateInfo",'OperateController@operateInfo');// *** 2017-12-25 18:29:56 还没做

        // 历史提交记录
        Route::get("/".htname."/profitlist",'IndexController@getProfitList');
        // 获取单个详情
        Route::post("/".htname."/profitDetail",'IndexController@profitDetail');
        Route::post("/".htname."/updateProfit",'IndexController@updateProfit');

        // 头条数据采集（ajax方式请求接口，抓取数量redis）
        Route::get("/".htname."/toutiaoArticle", 'JrTtController@getToutiaoArticle');
        // 结算记录
        Route::get("/".htname."/balance_xb", 'OperateController@getBalance');


        // 视频教程
        Route::get("/".htname."/videoTutorials", function (){return view("xbht.videoTutorials");});

    });

});

