@extends('layouts.app')

@section('content')
    <style>
        .nav-tabs li{
            width: 50%;
        }

        .nav-tabs>li>a {
            text-align: center;
            font-family: "Microsoft Yahei", Verdana, Simsun, "Segoe UI Web Light", "Segoe UI Light", "Segoe UI Web Regular", "Segoe UI", "Segoe UI Symbol", "Helvetica Neue", Arial;
            font-size: 20px;
        }
        .tab-pane {
            padding-top: 20px;
        }
        .form-group label {
            color: #848484;
        }
        .btn-login {
            padding: 6px 25px;
            font-family: "Microsoft Yahei", Verdana, Simsun, "Segoe UI Web Light", "Segoe UI Light", "Segoe UI Web Regular", "Segoe UI", "Segoe UI Symbol", "Helvetica Neue", Arial;
            color: white;
            background-color: #749063;
        }
        @media screen and (max-width: 768px) {
            .nav-tabs>li>a {
                font-size: 16px;
            }
        }
    </style>
<div class="container">
    <div class="row">
        {{--<div class="col-md-8 col-md-offset-2">--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading">Login</div>--}}
                {{--<div class="panel-body">--}}

                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="col-md-5 col-md-offset-3 col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
            <h2>用户登陆</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 col-md-offset-3 col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2" style="background-color: #fff">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="{{ !$errors->has('phone') && !$errors->has('password') ? 'active' : '' }}"><a href="#qlogin" aria-controls="qlogin" role="tab" data-toggle="tab">快速登陆</a></li>
                <li role="presentation" class="{{ $errors->has('phone') || $errors->has('password') ? 'active' : '' }}"><a href="#ologin" aria-controls="ologin" role="tab" data-toggle="tab">其他方式登陆</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane{{ !$errors->has('phone') && !$errors->has('password') ? ' active' : '' }}" id="qlogin">
                    <form class="form-horizontal" role="form" id="form-qlogin" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group" id="quick-phone">
                            <label for="phone" class="col-md-4 col-sm-4 control-label">手机号码</label>

                            <div class="col-md-6 col-sm-6">
                                <input id="phone" type="tel" class="form-control" name="phone" value="{{ old('phone') }}" maxlength="11" placeholder="手机号码" required autofocus>

                                <span class="help-block">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group" id="quick-verify">
                            <label for="verify" class="col-md-4 col-sm-4 control-label col-xs-12">动态验证码</label>

                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <input id="verify" type="text" class="form-control" name="verify" maxlength="6" placeholder="验证码" required>
                                <span class="help-block">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">
                                <input type="button" onclick="ajaxGetVerify()" id="btn-verify" v-model="content" class="btn btn-sm" style="color: white;background-color: #749063;">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2 col-sm-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 记住我
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 col-sm-6 col-sm-offset-4 col-xs-7 col-xs-offset-4" style="text-align: right">
                                <button type="button" onclick="quickLogin()" class="btn btn-login">
                                    登 陆
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane{{ $errors->has('phone') || $errors->has('password') ? ' active' : '' }}" id="ologin">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 col-sm-4 control-label">手机号码</label>

                            <div class="col-md-6 col-sm-6">
                                <input id="phone" type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="手机号码" maxlength="11" required autofocus>

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-sm-4 control-label">密码</label>

                            <div class="col-md-6 col-sm-6">
                                <input id="password" type="password" class="form-control" name="password" placeholder="密码" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 记住我
                                    </label>
                                    &nbsp;|&nbsp;
                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                        忘记密码？
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4" style="text-align: right">
                                <button type="submit" class="btn btn-login">
                                    登 陆
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
    <script>
        var verify = new Vue({
            el: '#btn-verify',
            data: {
                content: '获取验证码',
            }
        });
        var wait = 60;
        function ajaxGetVerify() {
            var phone = $("#form-qlogin #phone").val();
            if (phone.length == 11) {
                $.ajax({
                    url: "{{ url("/common/getverify") }}",
                    data: { "phone": $("#phone").val(), "type": 1 },
                    type: "post",
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    success: function (data) {

                    }
                });
                $("#btn-verify").prop("disabled", true);
                time();

            }
        }
        function quickLogin() {
            $.ajax({
                url: "{{ url("/quickLogin") }}",
                data: $("#form-qlogin").serialize(),
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                type: "post",
                success: function (data) {
                    data = $.parseJSON(data);
                    if (data.code === 0) {
                        location.href = data.redirect;
                    }
                    else if (data.code === 1) {
                        $("#quick-verify .help-block strong").html(data.msg);
                        $("#quick-phone").removeClass("has-error");
                        $("#quick-verify").addClass("has-error");
                    }
                    else {
                        $("#quick-phone .help-block strong").html(data.msg);
                        $("#quick-phone").addClass("has-error");
                        $("#quick-verify").removeClass("has-error");

                    }
                }
            })
        }

        function time() {
            if (wait == 0) {
                $("#btn-verify").prop("disabled", false);
                verify.content = "获取验证码";
                wait = 60;
            }
            else {
                verify.content = "重新发送(" + wait + ")";
                wait--;
                setTimeout(function () {
                    time()
                }, 1000);
            }
        }

    </script>
@endsection
