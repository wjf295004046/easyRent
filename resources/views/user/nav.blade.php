@extends('layouts.app')

@section('content')
    <style>
        #slide-title{background-color: white;}
        .nav-stacked {margin-bottom: 20px; margin-top: 20px;}
    </style>
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li class="active">我是房客</li>
                <li class="active">
                    @if($type == 'order')
                        我的订单
                    @elseif($type == 'comment')
                        我的点评
                    @elseif($type == 'liver')
                        常用入住人
                    @elseif($type == 'userinfo')
                        个人资料
                    @elseif($type == 'modifypwd')
                        密码设置
                    @endif
                </li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-2" id="slide-title">
                <ul class="nav nav-pills nav-stacked">
                    <li class="{{ $type == 'order' ? 'active' : '' }}"><a href="{{ url('/home/order') }}"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> 我的订单</a></li>
                    <li class="{{ $type == 'comment' ? 'active' : '' }}"><a href="{{ url('/home/comment') }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> 我的点评</a></li>
                    <li class="{{ $type == 'liver' ? 'active' : '' }}"><a href="{{ url('/home/liver') }}"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> 常用入住人</a></li>
                    <li class="{{ $type == 'userinfo' ? 'active' : '' }}"><a href="{{ url('/home') }}"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> 个人资料</a></li>
                    <li class="{{ $type == 'modifypwd' ? 'active' : '' }}"><a href="{{ url('/home/modifypwd') }}"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> 密码设置</a></li>
                </ul>
            </div>
            <div class="col-sm-9">
                @yield('main-content')
            </div>
        </div>
    </div>

@endsection