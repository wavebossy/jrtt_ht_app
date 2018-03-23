<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="今日头条账户后台管理系统" name="description" />
    <meta content="尧涛" name="author" />
    <title>今日头条账户后台管理系统</title>
    <!-- Bootstrap Styles-->
    {{--<link href="/css/bootstrap.min.css" rel="stylesheet" />--}}
    <link href="/css/bootstrap3.0.css" rel="stylesheet" />
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Styles-->
    <link href="/css/ht/font-awesome.css" rel="stylesheet" />
    <link href="/css/ht/custom-styles.css" rel="stylesheet" />
    <link href="/css/ht/checkbox3.min.css" rel="stylesheet" >
    <!-- Jquery -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="/js/custom-scripts.js"></script>
    @yield('_layout_style')
    @yield('style')
</head>

<body>
<div id="wrapper">
    @include("ht.layouts._nav_row")
    <!--/. NAV TOP  -->
    @include("ht.layouts._nav_line")
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            @include("ht.layouts._data_header")
        </div>
        <div id="page-inner">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
{{--模板里面的脚本--}}
@yield('_layout_script')
@yield('_layout_pagination')
@yield('script')