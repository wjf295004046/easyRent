@extends('layouts.app')

@section('content')
    <style>
        .info { border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: whitesmoke; padding: 30px 30px;}
        .info p { margin-top: 20px; margin-bottom: 20px;}
        #name-info { margin-top: 30px;}
        #cert-info button { width: 100%;}
    </style>
<div class="container">
    <form action="{{ url('common/certsave') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="x" id="x">
        <input type="hidden" name="y" id="y">
        <input type="hidden" name="w" id="w">
        <input type="hidden" name="h" id="h">
        <div class="row" id="cert-info">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                <h3>头像修改</h3>
                <div class="form-group" id="photo-info">
                    <label class="col-sm-2 control-label" for="photo">真实照片上传</label>
                    <div class="col-sm-8">
                        <input type="file" id="photo" name="photo">
                        <p class="help-block">照片不超过2MB</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-8 col-xs-offset-2 col-sm-offset-2">
                    <img src="/images{{ $user_info->pic_path }}" id="preview" width="100%" alt="">
                </div>
            </div>
        </div>
        <div class="row" id="cert-info">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                <h3>实名认证</h3>
                <div class="form-group" id="name-info">
                    <label class="col-sm-2 control-label" for="max_people">姓名</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="10" class="form-control" value="{{ isset($user_info->real_name) ? $user_info->real_name : '' }}" name="real_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">身份证号码</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="18" class="form-control" value="{{ isset($user_info->id_card) ? $user_info->id_card : '' }}" name="id_card" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3 col-sm-offset-3">
                        <button class="btn btn-success">
                            保存
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
    <script>
        var jcrop_api;
        var $img = $("#preview");
        $('#photo').change(function () {
            if(jcrop_api){
                jcrop_api.destroy();
                $img.attr('style', null);
            }
            var $file = $(this);
            var fileObj = $file[0];
            var windowURL = window.URL || window.webkitURL;
            var dataURL;
            if(fileObj && fileObj.files && fileObj.files[0]){
                dataURL = windowURL.createObjectURL(fileObj.files[0]);
                $img.attr('src',dataURL);
            }

            $('#preview').Jcrop({
                aspectRatio:1,
                allowSelect:false,
                minSize:[50,50],
                setSelect:[0,0,300,300],
                onSelect: function selectChange(c) {
                    var rat = $img[0].naturalWidth/$img.width();
                    $('#x').attr('value',c.x*rat);
                    $('#y').attr('value',c.y*rat);
                    $('#w').attr('value',c.w*rat);
                    $('#h').attr('value',c.h*rat);
                },
            },function () {
                jcrop_api = this;
            });
        });
    </script>
@endsection