@extends('landlord.nav')

@section('main-content')
    <style>
        #main-content,#manage-info {background: #fff; margin-bottom: 20px; padding-top: 20px;}
        #manage-info .stat-info { margin-left: 15px; margin-right: 15px; margin-top: 20px; padding-left: 0px; padding-right: 0px; }
        #order-info .panel-body a {font-size: 16px;}
    </style>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ url('/fangdong') }}">我是房东</a></li>
        </ol>
    </div>
    <div class="row" id="main-content">
        <div class="col-sm-2 col-sm-offset-1">
            <div class="thumbnail">
                <img src="/images{{ $user->pic_path }}" alt="...">
                <div class="caption" style="text-align: center">
                    <p><a href="#" role="button" data-toggle="modal" data-target=".change-photo">修改照片</a></p>
                </div>
                <div class="modal fade change-photo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="photo-upload" method="post" action="{{ url('/common/changephoto') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">修改照片</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-1">
                                            <h4>预览</h4>
                                            <img src="/images/common/mrtx.jpg" width="100%" id="preview" alt="">
                                        </div>
                                        <div class="col-sm-offset-1 col-sm-4">
                                            {{--<h4>照片上传</h4>--}}
                                            <input type="hidden" name="x" id="x">
                                            <input type="hidden" name="y" id="y">
                                            <input type="hidden" name="w" id="w">
                                            <input type="hidden" name="h" id="h">
                                            <div class="form-group" style="margin-top: 40px;">
                                                <label for="#photo">照片上传</label>
                                                <input type="file" name="photo" id="photo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                    <button type="submit" class="btn btn-primary">保存</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <h5><b>{{ Auth::user()->name }}</b>，欢迎回来</h5>
            <div class="col-sm-6"><h5>用户名：{{ Auth::user()->name }}</h5></div>
            <div class="col-sm-6"><h5>真实姓名：{{ $user->real_name }}</h5></div>
            <div class="col-sm-6"><h5>所在城市：{{ $user->city }}</h5></div>
            <div class="col-sm-6"><h5>手机号码：{{ Auth::user()->phone }}</h5></div>
        </div>
    </div>
    <div class="row" id="manage-info">
        <div class="panel panel-default col-sm-3 stat-info" id="order-info">
            <div class="panel-heading">
                <h3 class="panel-title">订单管理</h3>
            </div>
            <div class="panel-body">
                <h5><a href="{{ url('/fangdong/order?orderStatType=wait') }}">{{ $wait }}</a> 张新订单</h5>
                <h5><a href="{{ url('/fangdong/order?orderStatType=waitcheckin') }}">{{ $waitcheckin }}</a> 张待入住订单</h5>
                <h5><a href="{{ url('/fangdong/order?orderStatType=checkin') }}">{{ $checkin }}</a> 张进行中订单</h5>
                <h5><a href="{{ url('/fangdong/order?orderStatType=cancel') }}">{{ $cancel }}</a> 张24小时内取消订单</h5>
            </div>
        </div>
        <div class="panel panel-default col-sm-3 stat-info" id="comment-info">
            <div class="panel-heading">
                <h3 class="panel-title">评价管理</h3>
            </div>
            <div class="panel-body">
                <h5><a href="{{ url('fangdong/comment?commentType=to-roomer') }}">{{ $comment['need_landlord_comment'] }}</a> 条房东点评需完成</h5>
                <h5><a href="{{ url('fangdong/comment') }}">{{ $comment['need_user_comment'] }}</a> 条房客点评需完成</h5>
                <h5 style="margin-top: 20px;"><b>您现阶段已拥有</b></h5>
                <h5><a href="{{ url('fangdong/comment') }}">{{ $comment['num_landlord_comment'] }}</a> 条您对房客的点评</h5>
                <h5><a href="{{ url('fangdong/comment') }}">{{ $comment['num_user_comment'] }}</a> 条房客对您的点评</h5>
            </div>
        </div>
        <div class="panel panel-default col-sm-3 stat-info" id="stat-info">
            <div class="panel-heading">
                <h3 class="panel-title">历史统计</h3>
            </div>
            <div class="panel-body">
                <p>加入宜租已经 <b style="font-size: 16px">{{ $stat['days'] }}</b> 天了，</p>
                <p>已获得了 <a style="font-size: 16px; font-weight: bold;" href="{{ url('/fangdong/order') }}">{{ $stat['orders'] }}</a> 个订单和</p>
                <p> <b>{{ $stat['money'] }}.00</b> 元的</p>
                <p>网上销售额</p>
            </div>
        </div>
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