@extends('admin.layouts.base')

@section('title','房源详情')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="row page-title-row" id="dangqian" style="margin:5px;">
        <div class="col-md-6">
            <a style="margin:3px;" href="/admin/house/index"
               class="btn btn-warning btn-md animation-shake reloadBtn"><i class="fa fa-mail-reply-all"></i> 返回列表
            </a>
        </div>

        <div class="col-md-6 text-right">

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-7 col-xs-10 col-sm-offset-2">
                            <h4>基本信息</h4>
                        </div>
                        <div class="col-sm-7 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                            <p><b>标题：</b>{{ $house->name }}</p>
                            <p><b>所在城市：</b>{{ $address->city }}</p>
                            <p>
                                <b>地址：</b>{{ $address->province . $address->city . $address->area . $address->address . $address->detail }}
                                <a href="#" data-toggle="modal" data-target="#modal-ditu">查看地图</a>
                            </p>
                            <div class="modal fade" id="modal-ditu" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">地图</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="ditu" style="margin-left: 10px; margin-right: 10px">

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <p><b>最大宜居人数：</b>{{ $house->max_people }} 人</p>
                            <p><b>同类型房源数量：</b>{{ $house->sum }} </p>
                            <p><b>户型：</b> {{ $house_type[0] }}室 {{ $house_type[1] }}厅  {{ $house_type[2] }}厨  {{ $house_type[3] }}卫  {{ $house_type[4] }}阳台</p>
                            <p>
                                <b>出租类型：</b>
                                @if($house->rent_type == 1)
                                    整套出租
                                @elseif($house->rent_type == 2)
                                    单间出租
                                @elseif($house->rent_type == 3)
                                    床位出租
                                @elseif($house->rent_type == 4)
                                    沙发出租
                                @endif
                            </p>
                            <p>
                                <b>床铺类型：</b>
                                @foreach($beds as $bedInfo)
                                    @if($bedInfo[0] == 1)
                                        单人床
                                    @elseif($bedInfo[0] == 2)
                                        双人床
                                    @elseif($bedInfo[0] == 3)
                                        沙发
                                    @elseif($bedInfo[0] == 4)
                                        双层床
                                    @elseif($bedInfo[0] == 5)
                                        榻榻米
                                    @elseif($bedInfo[0] == 6)
                                        其他
                                    @endif
                                @endforeach
                                 : {{ $bedInfo[2] }}m × {{ $bedInfo[3] }}m × {{ $bedInfo[1] }} 张 &nbsp;&nbsp;&nbsp;&nbsp;
                            </p>
                            <p><b>被单更换：</b>{{ $house->change_bed == 1 ? "每日一换" : "每客一换" }}</p>
                        </div>
                        <div class="col-sm-7 col-xs-10 col-sm-offset-2">
                            <h4>费用相关</h4>
                        </div>
                        <div class="col-sm-7 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                            <p><b>价格：</b>{{ $house->price }} 元/晚</p>
                            <p><b>押金：</b>{{ $house->deposit }} 元</p>
                            <p><b>煮饭：</b>{{ $house->cook_fee }} 元/次</p>
                            <p><b>清洁费：</b>{{ $house->clean_fee }}元/次</p>
                            <p><b>其他费用：</b>{{ $house->other_fee }}</p>
                        </div>
                        <div class="col-sm-7 col-xs-10 col-sm-offset-2">
                            <h4>其他信息</h4>
                        </div>
                        <div class="col-sm-7 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                            <p><b>配套设施：</b>{{ implode(" , ", $supporting_facilities) }}</p>
                            <p><b>个性描述：</b>{{ $house->desc }}</p>
                            <p><b>内部设施：</b>{{ $house->internal_situation }}</p>
                            <p><b>交通设施：</b>{{ $house->traffic_condition }}</p>
                            <p><b>周边情况：</b>{{ $house->peripheral_condition }}</p>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-3 col-sm-offset-3 col-xs-5 col-xs-offset-1">
                            <a href="/admin/house/{{ $house->id }}/upload" class="btn-success btn btn-lg" id="btn-pass">审核通过并上传照片</a>
                        </div>
                        <div class="col-sm-3 col-xs-5">
                            <button class="btn btn-warning btn-lg" id="btn-refuse" data-toggle="modal" data-target="#modal-refuse">不通过</button>
                            <div class="modal fade" id="modal-refuse" tabIndex="-1">
                                <div class="modal-dialog modal-warning">
                                    <div class="modal-content">
                                        <form class="refuse-form" method="POST" action="/admin/house/{{ $house->id }}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    ×
                                                </button>
                                                <h4 class="modal-title">提示</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <textarea name="reason" id="reason" cols="30" rows="5" class="form-control" placeholder="请输入审核不通过原因"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-times-circle"></i> 确认
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=9925974522e4301ce20fccac55aab971&plugin=AMap.DistrictSearch"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>
    <script>
        if ($(window).width() < 992)
        {
            $("#btn-pass").removeClass("btn-lg");
            $("#btn-pass").addClass("btn-sm");
            $("#btn-refuse").removeClass("btn-lg");
            $("#btn-refuse").addClass("btn-sm");
        }
        $("#ditu").css("height", $(window).height()*0.4);
        var map = new AMap.Map('ditu', {
            zoomEnable:false,
            dragEnable: false,
            zoom:14,
            center: [{{ $address->co_ordinates }}]
        });

        var marker = new AMap.Marker({
            map: map,
            icon: 'http://webapi.amap.com/theme/v1.3/markers/n/mark_b.png',
            position: [{{ $address->co_ordinates }}],
            offset: new AMap.Pixel(-12, -36),
        });
    </script>

@endsection