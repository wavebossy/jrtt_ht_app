{{--竖着的菜单--}}
<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">

            <li>
                <a class="active-menu" href="/{{htname}}/homepage_xb"><i class="fa fa-user"></i> 我的桌面 </a>
            </li>
            <li>
                <a class="active-menu" target="_blank" onclick="$('#submit_').click()"><i class="fa fa-dashboard"></i> 采集平台 </a>
                {{--<a class="active-menu" target="_blank" href="http://xf.wangzherongyao.cn/"><i class="fa fa-dashboard"></i> 采集平台 </a>--}}
                {{--<a class="active-menu" target="_blank" href="/{{htname}}/toutiaoArticle"><i class="fa fa-dashboard"></i> 采集平台 </a>--}}
            </li>
            <li>
                <a class="active-menu" href="/{{htname}}/profitlist"><i class="fa fa-user"></i> 账号管理 </a>
            </li>
            {{--<li class="active">--}}
                {{--<a href="#"><i class="fa fa-sitemap"></i> 数据统计 <span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                    {{--<li>--}}
                        {{--<a href="/{{htname}}/profitlist"> 收入报表 </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#"> 申请提现 </a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            {{--<li>--}}
                {{--<a class="active-menu" target="_blank" href="/{{htname}}/notice/1"><i class="fa fa-book"></i> 基础教程 </a>--}}
            {{--</li>--}}
            <li>
                <a class="active-menu" href="/{{htname}}/videoTutorials"><i class="fa fa-video-camera"></i> 视频教程 </a>
            </li>
            <li>
                <a class="active-menu" target="_blank" href="/{{htname}}/notice/4"><i class="fa fa-info-circle"></i> 常见问题 </a>
            </li>
            {{--<li>--}}
                {{--<a class="active-menu" href="/{{htname}}/balance_xb"><i class="fa fa-dashboard"></i> 结算记录 </a>--}}
            {{--</li>--}}

            <li>
                <a class="active-menu" href="/{{htname}}/opinion"><i class="fa fa-dashboard"></i> 意见反馈 </a>
            </li>

            {{--<li>--}}
                {{--<a href="#"><i class="fa fa-sitemap"></i> 三级菜单 <span class="fa arrow"></span></a>--}}
                {{--<ul class="nav nav-second-level">--}}
                    {{--<li>--}}
                        {{--<a href="#"> 三级子二 </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#"> 三级子二 </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="#"> 三级子二菜单 <span class="fa arrow"></span></a>--}}
                        {{--<ul class="nav nav-third-level">--}}
                            {{--<li>--}}
                                {{--<a href="#"> 三级子二子一 </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#"> 三级子二子二 </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#"> 三级子二子三 </a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
        </ul>
    </div>
</nav>
<div style="display: none">
    <form id="J_Form" class="form-horizontal bui-form bui-form-field-container" method="post" action="http://xf.wangzherongyao.cn/user/insert.php" aria-disabled="false" aria-pressed="false">
        <span style="color:red">你好，真心对不起，如果是你11月4号-11月15号之间购买的激活码，并且激活了，那么请你需要重新在用以前购买过的激活码注册或者续费下，系统回档不好意思</span>
        <input type="hidden" name="submit" value="login" class="bui-form-field" aria-disabled="false">
        <div class="row show-grid" style="margin-bottom: 20px;">
            <div class="span12">
                <label class="control-label"><s>*</s>手机号：</label>
                <div class="controls control-row1"><input name="phone" value="15888888888" class="input-normal control-text bui-form-field" type="text" aria-disabled="false" aria-pressed="false"></div>
            </div>
        </div>
        <div class="row show-grid" style="margin-bottom: 20px;">
            <div class="span8">
                <label class="control-label"><s>*</s>密码：</label>
                <div class="controls control-row1"><input name="password" value="15888888888" class="input-normal control-text bui-form-field" type="password" aria-disabled="false" aria-pressed="false"></div>
            </div>
        </div>

        <div class="row show-grid">
            <div class="span8">
                <label class="control-label"></label>
                <div class="controls control-row1"><input type="submit" id="submit_" value="登陆" class="bui-form-field" aria-disabled="false" aria-pressed="false"></div>
            </div>
        </div>

    </form>
</div>

