@extends('layouts.app')

@section('content')
    <style>
        .green-title {
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#a9db80+0,96c56f+78 */
            background: #a9db80; /* Old browsers */
            background: -moz-linear-gradient(top,  #a9db80 0%, #96c56f 78%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top,  #a9db80 0%,#96c56f 78%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom,  #a9db80 0%,#96c56f 78%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a9db80', endColorstr='#96c56f',GradientType=0 ); /* IE6-9 */
        }
        .grey-title {
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#dbdbdb+1,cccccc+78 */
            background: #dbdbdb; /* Old browsers */
            background: -moz-linear-gradient(top,  #dbdbdb 1%, #cccccc 78%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top,  #dbdbdb 1%,#cccccc 78%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom,  #dbdbdb 1%,#cccccc 78%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#dbdbdb', endColorstr='#cccccc',GradientType=0 ); /* IE6-9 */
        }
        .slide-tilte {
            text-align: center;
            padding: 5px;
        }
        .slide-tilte a,span {
            color: #fff;
            font-family: "Microsoft Yahei";
            font-size: 18px;
        }
        .slide-tilte a:hover{ text-decoration: none;}
        /*.menu-item a{font-size: 12px;}*/
    </style>
    <div class="container">
        <div class="row">
            <div class="col-sm-2">
                <div class="row slide-tilte{{ $type == 'index' ? ' grey-title' : ' green-title' }}">
                    @if($type == 'index')
                        <span>房东个人中心</span>
                    @else
                        <a href="{{ url('/fangdong') }}">房东个人中心</a>
                    @endif
                </div>
                <div class="row">
                    <div class="panel panel-default menu-info">
                        <div class="panel-heading">交易</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6 menu-item">
                                    @if($type == 'order')
                                        <b>订单管理</b>
                                    @else
                                        <a href="{{ url('/fangdong/order') }}">订单管理</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default menu-info">
                        <div class="panel-heading">点评</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6 menu-item">
                                    @if($type == 'comment')
                                        <b>我的点评</b>
                                    @else
                                        <a href="{{ url('fangdong/comment') }}">我的点评</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default menu-info">
                        <div class="panel-heading">房源</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6 menu-item">
                                    @if($type == 'house')
                                        <b>房源管理</b>
                                    @else
                                        <a href="{{ url('fangdong/house') }}">房源管理</a>
                                    @endif
                                </div>
                                <div class="col-sm-6 menu-item">
                                    @if($type == 'address')
                                        <b>常用地址</b>
                                    @else
                                        <a href="{{ url('fangdong/address') }}">常用地址</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default menu-info">
                        <div class="panel-heading">我的设置</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6 menu-item">
                                    @if($type == 'userinfo')
                                        <b>个人资料</b>
                                    @else
                                        <a href="{{ url('fangdong/userinfo') }}">个人资料</a>
                                    @endif
                                </div>
                                <div class="col-sm-6 menu-item">
                                    @if($type == 'modifypwd')
                                        <b>密码修改</b>
                                    @else
                                        <a href="{{ url('fangdong/modifypwd') }}">密码修改</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9" style="margin-left: 15px;">
                @yield('main-content')
            </div>
        </div>
    </div>

@endsection