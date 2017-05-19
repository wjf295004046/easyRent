@extends('admin.layouts.base')

@section('title','房源审核')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('css')
    <link rel="stylesheet" href="/css/jquery.Jcrop.min.css">

    <style>
        .item { border: 1px solid #ccc; position: relative; margin-top: 10px; min-height: 450px;}
        .item .form-group{margin-top: 30px;}
        .item img { padding: 5px; margin: 5px;}
        .item .delete-item { position: absolute; right: 5%; height: 10px; font-size: 30px;}
    </style>
@endsection

@section('content')

    <form action="/admin/house/{{ $id }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
    <div class="row page-title-row" id="dangqian" style="margin:5px;">
        <div class="col-md-6">
            <a style="margin:3px;" href="/admin/house/{{ $id }}/show"
               class="btn btn-warning btn-md animation-shake reloadBtn"><i class="fa fa-mail-reply-all"></i> 返回上一级
            </a>
        </div>

        <div class="col-md-6 text-right">
            <button type="submit" class="btn btn-success btn-md"><i class="fa fa-plus-circle"></i> 审核通过 </button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">

                    <div class="row" id="form-content">
                        <div class="col-sm-4 col-sm-offset-1 item" id="item0">
                            <div class="form-group">
                                <label for="photo0">图片上传</label>
                                <input type="file" name="photo[0][photo]" onchange="previewPhoto(0)" id="photo0" class="form-control">
                            </div>
                            <img src="/images/common/mrtp.jpg" id="preview0" width="100%" alt="">
                            <input type="hidden" name="photo[0][x]" id="x0">
                            <input type="hidden" name="photo[0][y]" id="y0">
                            <input type="hidden" name="photo[0][w]" id="w0">
                            <input type="hidden" name="photo[0][h]" id="h0">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px; margin-bottom: 10px;">
                        <div class="col-sm-offset-5 col-sm-4 col-xs-offset-2 col-xs-8">
                            <button type="button" class="btn btn-success btn-sm" onclick="addPhotoItem()">
                                添加图片
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection


@section('js')
    <script src="/js/jquery.Jcrop.min.js"></script>

    <script>
        var jcrop_api = new Array(30);
        var number = 1;
        function previewPhoto(id) {

            var $img = $("#preview"+id);
            if(jcrop_api[id]){
                jcrop_api[id].destroy();
                $img.attr('style', null);
            }
            var $file = $("#photo" + id);
            var fileObj = $file[0];
            var windowURL = window.URL || window.webkitURL;
            var dataURL;
            if(fileObj && fileObj.files && fileObj.files[0]){
                dataURL = windowURL.createObjectURL(fileObj.files[0]);
                $img.attr('src',dataURL);
            }

            $('#preview'+id).Jcrop({
                aspectRatio:1.5,
                allowSelect:false,
                minSize:[50,50],
                setSelect:[0,0,300,300],
                onSelect: function selectChange(c) {
                    var rat = $img[0].naturalWidth/$img.width();
                    $('#x' + id).attr('value',c.x*rat);
                    $('#y' + id).attr('value',c.y*rat);
                    $('#w' + id).attr('value',c.w*rat);
                    $('#h' + id).attr('value',c.h*rat);
                },
            },function () {
                jcrop_api[id] = this;
            });
        }

        function addPhotoItem() {
            var html = '<div class="col-sm-4 col-sm-offset-1 item" id="item'+number+'">\
                    <a href="javascript:void(0)" onclick="deletePhotoItem('+number+')" class="delete-item">×</a>\
                    <div class="form-group">\
                    <label for="photo'+number+'">图片上传</label>\
                    <input type="file" name="photo['+number+'][photo]" onchange="previewPhoto('+number+')" id="photo'+number+'" class="form-control">\
                    </div>\
                    <img src="/images/common/mrtp.jpg" id="preview'+number+'" width="100%" alt="">\
                    <input type="hidden" name="photo['+number+'][x]" id="x'+number+'">\
                    <input type="hidden" name="photo['+number+'][y]" id="y'+number+'">\
                    <input type="hidden" name="photo['+number+'][w]" id="w'+number+'">\
                    <input type="hidden" name="photo['+number+'][h]" id="h'+number+'">\
                    </div>';
            $("#form-content").append(html);
            number++;
        }

        function deletePhotoItem(id) {
            $("#item" + id).remove();
        }
    </script>

@endsection