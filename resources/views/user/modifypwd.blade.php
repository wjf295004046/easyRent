@extends('user.nav')

@section('main-content')
    <style>
        .info { margin-left: 10px; position: relative; margin-bottom: 20px;border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: #fff; padding: 30px 30px;}
        .info p { margin-top: 30px; margin-bottom: 30px; font-size: 14px;}
        .info p span {color: #959ea7;font-size: 14px; margin-right: 10px;}
        .info>h4 { color:#30c3a6;}
        .btn-edit {position: absolute; top: 40px; right: 5%; z-index: 100;}
        .help-block{font-size: 12px;}
        select {padding-top: 2px;padding-bottom: 5px; height: 30px;}
    </style>
    <div class="row info">
        <h4>修改密码</h4>
        <form method="post" action="{{ url('/fangdong/modifypwd') }}" class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group{{ session('error') ? ' has-error' : '' }}">
                <label for="old-password" class="col-sm-2 control-label">原密码</label>
                <div class="col-sm-5">
                    <input type="password" value="{{ old('old_password') }}"  class="form-control" id="old-password" name="old_password" placeholder="请输入旧密码">
                    @if (session('error'))
                        <span class="help-block">
                            <strong>{{ session('error') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-sm-2 control-label">新密码</label>
                <div class="col-sm-5">
                    <input type="password" value="{{ old('password') }}"  class="form-control" id="password" name="password" placeholder="请输入新密码">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="ppassword_confirmation" class="col-sm-2 control-label">重复密码</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="请再次输入新密码">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-success">保存</button>
                </div>
            </div>
        </form>
    </div>
@endsection