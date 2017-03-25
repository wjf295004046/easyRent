@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">密码找回</div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" id="form-reset" method="POST"">
                            {{ csrf_field() }}

                            <div class="form-group" id="reset-phone">
                                <label for="phone" class="col-md-4 col-sm-4 control-label">手机号码</label>

                                <div class="col-md-6 col-sm-6">
                                    <input id="phone" type="phone" class="form-control" name="phone" value="{{ old('phone') }}" required>


                                    <span class="help-block">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>
                            <div class="form-group" id="reset-verify">
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
                            <div class="form-group" id="reset-password">
                                <label for="password" class="col-md-4 col-sm-4 control-label">新密码</label>

                                <div class="col-md-6 col-sm-6">
                                    <input id="password" type="password" class="form-control" name="password" required>


                                    <span class="help-block">
                                        <strong></strong>
                                    </span>

                                </div>
                            </div>

                            <div class="form-group" id="reset-confirmation">
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
                                    <button type="button" onclick="resetPassword()" class="btn btn-primary">
                                        密码找回
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
            if (phone.length == 11) {
                $.ajax({
                    url: "{{ url("/common/getverify") }}",
                    data: { "phone": phone, "type": 2 },
                    type: "post",
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    success: function (data) {

                    }
                });
                $("#btn-verify").prop("disabled", true);
                time();

            }

        }
        function resetPassword() {
            $.ajax({
                url: "{{ url("password/phone") }}",
                data: $("#form-reset").serialize(),
                type: "post",
                success: function (data, status) {
                    data = $.parseJSON(data);
                    if (status == 'success') {
                        if (data.code === 0) {
                            location.href = data.redirect;
                        }
                        else if (data.code === 1) {
                            $("#reset-verify .help-block strong").html(data.msg);
                            $("#reset-phone .help-block strong").html("");
                            $("#reset-password .help-block strong").html("");
                            $("#reset-phone").removeClass("has-error");
                            $("#reset-verify").addClass("has-error");
                            $('#reset-password').removeClass("has-error");
                        }
                        else if (data.code === 2) {
                            $("#reset-phone .help-block strong").html(data.msg);
                            $("#reset-verify .help-block strong").html("");
                            $("#reset-password .help-block strong").html("");
                            $("#reset-verify").removeClass("has-error");
                            $("#reset-phone").addClass("has-error");
                            $('#reset-password').removeClass("has-error");
                        }
                    }
                },
                error: function (data) {
                    data = $.parseJSON(data.responseText);
                    if (data.password) {
                        $("#reset-phone .help-block strong").html("");
                        $("#reset-verify .help-block strong").html("");
                        $("#reset-password .help-block strong").html(data.password[0]);
                        $("#reset-verify").removeClass("has-error");
                        $("#reset-password").addClass("has-error");
                        $('#reset-phone').removeClass("has-error");
                    }
                    else if (data.phone) {
                        $("#reset-phone .help-block strong").html(data.phone[0]);
                        $("#reset-verify .help-block strong").html("");
                        $("#reset-password .help-block strong").html("");
                        $("#reset-verify").removeClass("has-error");
                        $("#reset-phone").addClass("has-error");
                        $('#reset-password').removeClass("has-error");
                    }
                }
            })
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
    </script>
@endsection
