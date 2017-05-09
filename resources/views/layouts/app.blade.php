<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title'){{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/cityselect.css">
    <link rel="stylesheet" href="/css/daterangepicker.css">
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <link rel="stylesheet" href="/css/jquery.Jcrop.min.css">
    {{--<link href="/css/app.css" rel="stylesheet">--}}

    <!-- Scripts -->
    <script src="/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <script src="/js/vue.min.js"></script>
    <script src="/js/jquery.Jcrop.min.js"></script>
    <script src="/js/cityselect.js"></script>
    <script src="/js/moment.js"></script>
    <script src="/js/jquery.daterangepicker.js"></script>
    <script src="/js/carousel.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=9925974522e4301ce20fccac55aab971&plugin=AMap.DistrictSearch"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>

    </script>
    <style>
        html,body {
            min-height: 100%;
            /*height: 100%;*/
            background-color: #efefef;
        }
        #app {
            min-height: 100%;
            height: auto;
            /*margin-bottom: 120px;*/
            /*height: 100%;*/
        }
        #footer {
            /*position: relative;*/
            clear: both;
            margin-top: -120px;
            height: 120px;
            padding-top: 10px;
            padding-bottom: 20px;
            background: grey;
            color: whitesmoke;
        }
        #footer a {
            color: whitesmoke;
        }
        #footer a:hover {
            text-decoration: none;
        }
        .carousel-fade .carousel-inner .item{
            opacity:0;
            -webkit-transition-property:opacity ;
            -moz-transition-property:opacity ;
            -ms-transition-property:opacity ;
            -o-transition-property:opacity ;
            transition-property:opacity ;
        }

        .carousel-fade .carousel-inner .active{
            opacity: 1;
        }

        .carousel-fade .carousel-inner .active.left,
        .carousel-fade .carousel-inner .active.right{
            left: 0;
            opacity: 0;
        }

        .carousel-fade .carousel-inner .next.left,
        .carousel-fade .carousel-inner .prev.right {
            opacity: 1;
        }
        #publish{padding-top: 10px; display: block}
        #totop { z-index: 100; width: 50px; height: 50px; position: fixed;right: 20px; bottom: 100px;}
        .line {border-top: 1px solid #ccc; width: 100%;display: block;margin-bottom: 10px}
        @media screen and (max-width: 768px) {
            #footer {
                margin-top: 0px;
            }
            #footer p{
                font-size: 10px;
            }
        }
        @media screen and (min-width: 992px) {
            #app {
                height: 100%;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top" style="padding-left: 3em;padding-right: 3em;">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}" style="padding-top: 0px">
                        {{--{{ config('app.name', 'Laravel') }}--}}
                        <img src="/images/common/logo.gif" alt="">
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">登陆</a></li>
                            <li><a href="{{ url('/register') }}">注册</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    @if(Auth::user()->is_landlord == 1)
                                        <li><a href="{{ url("/fangdong") }}">房东中心</a></li>
                                        <li role="separator" class="divider"></li>
                                    @endif
                                    <li><a href="{{ url("/home") }}">房客中心</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            退出
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button">
                                <i class="fa fa-weixin"></i> 关注公众号
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <img width="155px" src="/images/common/weixin.jpg" alt="">
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span id="publish">
                                <a href="{{ url("/house/create") }}" class="btn btn-success">点我发布房源</a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        <div style="margin-bottom: 120px;"></div>
        <div class="hidden" id="totop" onclick="toTop()">
            <a href="javascript:void(0)" >
                <img src="/images/common/top.png" alt="">
            </a>
        </div>
    </div>
    <div id="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-md-offset-2 col-sm-6 col-xs-6">
                    <h4>友情链接</h4>
                    <p>
                        <a target="_blank" class="stclick" clicktag="11" href="http://www.lvye.cn/" title="绿野户外网">绿野户外网</a>
                        <a target="_blank" class="stclick" clicktag="11" href="http://bj.5i5j.com/" title="北京二手房">北京二手房</a>
                        <a target="_blank" class="stclick" clicktag="11" href="http://www.mayi.com/zhusu/" title="旅游住宿">旅游住宿</a>
                        <a target="_blank" class="stclick" clicktag="11" href="http://www.homekoo.com" title="家具">家具</a>
                        <a target="_blank" class="stclick" clicktag="11" href="http://www.taiwandao.tw/" title="台湾自由行">台湾自由行</a>
                        <a target="_blank" class="stclick" clicktag="11" href="http://www.chunyun.cn/" title="火车票网上订票">火车票网上订票</a>
                        <a target="_blank" class="stclick" clicktag="11" href="http://www.3hk.cn/" title="香港自由行">香港自由行</a>
                        <a target="_blank" class="stclick" clicktag="11" href="http://www.mayi.com/shanghai_dibiao_s/" title="上海短租房">上海短租房</a>
                    </p>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-6">
                    <h4>联系我们</h4>
                    <p>Email:295004046@qq.com | QQ:295004046 | 联系电话:15168202013</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->

    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/bootstrap/js/bootstrap-hover-dropdown.min.js"></script>
    {{--<script src="/js/app.js"></script>--}}
    <script>
        $(document).ready(function () {
            if ($(document).scrollTop() > 50) {
                $("#totop").removeClass("hidden");
            }
            $(document).scroll(function () {
                if ($(document).scrollTop() > 50) {
                    $("#totop").removeClass("hidden");
                }
                else {
                    $("#totop").addClass("hidden");
                }
            })

        })
        function toTop() {
            $('html, body').animate({scrollTop:0}, 'slow');
        }
    </script>

</body>
</html>
