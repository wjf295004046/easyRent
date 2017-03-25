@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">新用户注册</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" id="form-register" method="POST"">
                    {{ csrf_field() }}

                    <div class="form-group" id="register-name">
                        <label for="name" class="col-md-4 col-sm-4 control-label">用户名</label>

                        <div class="col-md-6 col-sm-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>


                            <span class="help-block">
                                <strong></strong>
                            </span>

                        </div>
                    </div>

                    <div class="form-group" id="register-phone">
                        <label for="phone" class="col-md-4 col-sm-4 control-label">手机号码</label>

                        <div class="col-md-6 col-sm-6">
                            <input id="phone" type="phone" onblur="userexists()" maxlength="11" class="form-control" name="phone" value="{{ old('phone') }}" required>


                            <span class="help-block">
                                        <strong></strong>
                                    </span>

                        </div>
                    </div>
                    <div class="form-group" id="register-verify">
                        <label for="email" class="col-md-4 col-sm-4 col-xs-8 control-label">动态验证码</label>

                        <div class="col-md-4 col-sm-4 col-xs-7">
                            <input id="verify" type="text" maxlength="6" class="form-control" name="verify" value="{{ old('verify') }}" required>


                            <span class="help-block">
                                        <strong></strong>
                                    </span>

                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <input type="button" v-model="content" onclick="ajaxGetVerify()" id="btn-verify" class="btn btn-primary">
                        </div>
                    </div>
                    <div class="form-group" id="register-password">
                        <label for="password" class="col-md-4 col-sm-4 control-label">新密码</label>

                        <div class="col-md-6 col-sm-6">
                            <input id="password" type="password" class="form-control" name="password" required>


                            <span class="help-block">
                                        <strong></strong>
                                    </span>

                        </div>
                    </div>

                    <div class="form-group" id="register-confirmation">
                        <label for="password-confirm" class="col-md-4 col-sm-4 control-label">重复密码</label>
                        <div class="col-md-6 col-sm-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>


                            <span class="help-block">
                                        <strong></strong>
                                    </span>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4 col-sm-6 col-sm-offset-4">
                            <button type="button" onclick="register()" class="btn btn-primary">
                                注 册
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
            content: '发送验证码',
        }
    });

    function ajaxGetVerify() {
        var phone = $("#phone").val();
        var flag = 1;
        if (phone.length == 11) {
            $.ajax({
                url: "{{ url("/common/userexists") }}",
                data: { "phone" : phone},
                type: "post",
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                async: false,
                success: function (data) {
                    data = $.parseJSON(data);
                    flag = data.code;
                    console.log(flag);
                }
            })
            console.log(flag);
            if ( flag === 0 ) {
                $.ajax({
                    url: "{{ url("/common/getverify") }}",
                    data: { "phone": phone, "type": 3 },
                    type: "post",
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    success: function (data) {

                    }
                });
                $("#btn-verify").prop("disabled", true);
                time();
            }


        }

    }
    var wait = 60;
    function time() {
        if (wait == 0) {
            $("#btn-verify").prop("disabled", false);
            verify.content = "发送验证码";
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
    function userexists() {
        var phone = $("#phone").val();
        if (phone.length == 11) {
            $.ajax({
                url: "{{ url("/common/userexists") }}",
                data: {"phone": phone},
                type: "post",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function (data) {
                    data = $.parseJSON(data);
                    if (data.code === 1) {
                        $("#register-phone .help-block strong").html("手机号码已注册");
                        $("#register-verify .help-block strong").html("");
                        $("#register-name .help-block strong").html("");
                        $("#register-password .help-block strong").html("");
                        $("#register-verify").removeClass("has-error");
                        $("#register-phone").addClass("has-error");
                        $('#register-password').removeClass("has-error");
                        $('#register-name').removeClass("has-error");
                    }
                    else {
                        $("#register-phone .help-block strong").html("");
                        $("#register-phone").removeClass("has-error");
                    }
                }
            })

        }
        else {
            $("#register-phone .help-block strong").html("手机号码格式错误");
            $("#register-verify .help-block strong").html("");
            $("#register-name .help-block strong").html("");
            $("#register-password .help-block strong").html("");
            $("#register-verify").removeClass("has-error");
            $("#register-phone").addClass("has-error");
            $('#register-password').removeClass("has-error");
            $('#register-name').removeClass("has-error");
        }
    }
    function register() {
        $.ajax({
            url: "{{ url("/register") }}",
            data: $("#form-register").serialize(),
            type: "post",
            success: function (data, status) {
                data = $.parseJSON(data);
                if (status == 'success') {
                    if (data.code === 0) {
                        location.href = data.redirect;
                    }
                    else if (data.code === 1) {
                        $("#register-verify .help-block strong").html(data.msg);
                        $("#register-name .help-block strong").html("");
                        $("#register-phone .help-block strong").html("");
                        $("#register-password .help-block strong").html("");
                        $("#register-phone").removeClass("has-error");
                        $("#register-verify").addClass("has-error");
                        $('#register-password').removeClass("has-error");
                        $('#register-name').removeClass("has-error");
                    }
                    else if (data.code === 2) {
                        $("#register-phone .help-block strong").html(data.msg);
                        $("#register-verify .help-block strong").html("");
                        $("#register-password .help-block strong").html("");
                        $("#register-name .help-block strong").html("");
                        $("#register-verify").removeClass("has-error");
                        $("#register-phone").addClass("has-error");
                        $('#register-password').removeClass("has-error");
                        $('#register-name').removeClass("has-error");
                    }
                }
            },
            error: function (data) {
                data = $.parseJSON(data.responseText);
                if (data.password) {
                    $("#register-phone .help-block strong").html("");
                    $("#register-verify .help-block strong").html("");
                    $("#register-password .help-block strong").html(data.password[0]);
                    $("#register-name .help-block strong").html("");
                    $("#register-verify").removeClass("has-error");
                    $("#register-password").addClass("has-error");
                    $('#register-phone').removeClass("has-error");
                    $('#register-name').removeClass("has-error");
                }
                else if (data.phone) {
                    $("#register-phone .help-block strong").html(data.phone[0]);
                    $("#register-verify .help-block strong").html("");
                    $("#register-password .help-block strong").html("");
                    $("#register-name .help-block strong").html("");
                    $("#register-verify").removeClass("has-error");
                    $("#register-phone").addClass("has-error");
                    $('#register-password').removeClass("has-error");
                    $('#register-name').removeClass("has-error");
                }
                else if (data.name) {
                    $("#register-name .help-block strong").html(data.name[0]);
                    $("#register-verify .help-block strong").html("");
                    $("#register-password .help-block strong").html("");
                    $("#register-phone .help-block strong").html("");
                    $("#register-verify").removeClass("has-error");
                    $("#register-name").addClass("has-error");
                    $('#register-password').removeClass("has-error");
                    $('#register-phone').removeClass("has-error");
                }
            }
        })
    }
</script>
@endsection
