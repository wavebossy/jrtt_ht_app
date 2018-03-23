{{--横着的菜单--}}

<nav class="navbar navbar-default top-navbar" role="navigation">
    {{--<div style="width: 100%;height: 4px;background: green;position: absolute;display: none;bottom: 0;" id="progress_div"></div>--}}
    <div style="width: 100%;height: 4px;position: absolute;display: none;bottom: 0;" id="progress_div" >
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" id="progress"></div>
    </div>
    {{--网站标题部分--}}
    <div class="navbar-header">
        <!--
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar">1</span>
            <span class="icon-bar">2</span>
            <span class="icon-bar"></span>
        </button>
        -->
        <a class="navbar-brand" href="#"><strong><i class="icon fa fa-plane"></i> {{session("webht")[0]->v}} </strong></a>
        <div id="sideNav">
            <i class="fa fa-bars icon"></i>
        </div>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#"><i class="fa fa-user fa-fw"></i> 您好，{{session("admin_xb")->usname}}</a></li>
                <li><a onclick="getOperate()" href="#"><i class="fa fa-gear fa-fw"></i> 账户设置</a></li>
                <li class="divider"></li>
                <li><a href="/{{htname}}/loginout_xb"><i class="fa fa-sign-out fa-fw"></i> 退出登入</a></li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
</nav>
