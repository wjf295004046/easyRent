@extends('admin.layouts.base')

@section('title','城市管理')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('css')
<link rel="stylesheet" href="/css/jquery.Jcrop.min.css">
<style>
    .item { margin-top: 30px; margin-bottom: 30px;}
    #photo {margin-bottom: 10px;}
    .form-group img {border: 1px solid #ccc;}
</style>

@endsection

@section('content')
    <form action="/admin/city/{{ isset($city) ? $city->id : 0 }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row page-title-row" id="dangqian" style="margin:5px;">
            <div class="col-md-6">
                <a style="margin:3px;" href="/admin/city/index"
                   class="btn btn-warning btn-md animation-shake reloadBtn"><i class="fa fa-mail-reply-all"></i> 返回上一级
                </a>
            </div>

            <div class="col-md-6 text-right">
                <button type="submit" class="btn btn-success btn-md"><i class="fa fa-plus-circle"></i> 添加推荐城市 </button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box">
                    @include('admin.partials.errors')
                    @include('admin.partials.success')
                    <div class="row item">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="form-group">
                                <label for="title">标题</label>
                                <input class="form-control" type="text" value="{{ isset($city) ? $city->title : '' }}" id="title" name="title">
                            </div>
                            <div class="form-group">
                                <label for="pinyin">拼音</label>
                                <input type="text" class="form-control" value="{{ isset($city) ? substr($city->target, 1) : '' }}" id="pinyin" name="pinyin">
                            </div>
                            <div class="form-group">
                                <label>是否有效</label>
                                <br>
                                <label class="radio-inline">
                                    <input type="radio" name="is_valid" id="inlineRadio1" value="1"{{ !isset($city->is_valid) || $city->is_valid != 0 ? " checked" : "" }}> 有效
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="is_valid" id="inlineRadio2" value="0"{{ isset($city->is_valid) && $city->is_valid == 0 ? " checked" : "" }}> 无效
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="photo">图片</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                                <img id="preview" src="/images{{ isset($city) ? $city->pic_path : "/common/mrtp.jpg" }}" alt="" width="100%">
                            </div>
                            <input type="hidden" name="x" id="x">
                            <input type="hidden" name="y" id="y">
                            <input type="hidden" name="w" id="w">
                            <input type="hidden" name="h" id="h">
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
                aspectRatio:350/180,
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