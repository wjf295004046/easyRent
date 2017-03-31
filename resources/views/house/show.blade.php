@extends("layouts.app")

@section('title')
    {{ $house->name }}
@endsection

@section('content')
    <style>
        html,body{background-color: whitesmoke;}
        .large_box ul li {list-style: none;}
        .large_box{margin-bottom:10px;width:100%; overflow:hidden;}
        .large_box img{display:block;}
        .small_box{width:100%;height:73px;overflow:hidden;}
        .small_list{position:relative;float:left;height:73px;overflow:hidden;}
        .small_list ul{height:73px;overflow:hidden;}
        .small_list ul li{position:relative;float:left;margin-right:10px;width:110px;list-style: none;}
        .small_list ul li img{display:block;}
        .small_list ul li .bun_bg{display:none;position:absolute;top:0;left:0;width:110px;height:73px;background:#000;filter:alpha(opacity=60);-moz-opacity:0.6;-khtml-opacity:0.6;opacity:0.6;}
        .small_list ul li.on .bun_bg{display:block;}
        .glyphicon{display:block;width:20px;height:73px;padding-top:30px;font-size:16px;color:white;background-color:#777;background-repeat:no-repeat;background-position:center center;cursor:pointer;}
        .glyphicon:hover{background-color:#e7000e;}
        .glyphicon-menu-left{float:left;margin-right:10px;}
        .glyphicon-menu-right{float:right;}
        #base-introduce{text-align: center;}
        .description {margin-top: 0; min-height: 100px; margin-bottom: 0px; padding-top: 20px; padding-bottom: 10px; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc;}
        .description p { padding-top: 10px;}
        .base-info { text-align: center; border:1px solid #ccc; padding-top: 10px; padding-bottom: 10px;}
        #comment {margin-top: 30px;}
        #comment h2 {font-size: 3rem; color: grey}
        .comment-info {padding-top: 10px; padding-bottom: 10px;}
        .comment-user img{border-radius: 50%}
        #ditu{ margin-bottom: 30px; margin-top: 20px;}
        #price{  border: 1px solid #dce0e0; padding-left: 10px;background-color: #eee; color: red}
        #price span { font-size: 14px;}
        #option-select{ border: 1px solid #dce0e0; border-bottom: 0px;}
        #option-select>div{margin-top: 20px;}
        #option-select input {width: 100%; height: 30px}
        #option-select select {width: 100%; height: 30px}
        #price-info { border: 1px solid #dce0e0; border-top: 0px;}
        #price-info button {width: 80%; margin-left: 10%; margin-bottom: 10px;}
        #price-info>div{ margin-top: 20px; }
        #error_show{color: red;}
        #dateSelect>div {
            padding-left:5px;
            padding-right: 5px;
        }
        #fangdong-info {border: 1px solid #dce0e0; margin-top: 20px; text-align: center; padding-top: 10px; padding-bottom: 10px;}
        @media screen and (max-width: 768px) {
            #option-select div{margin-top: 10px;}
            #option-select input {width: 100%; height: 50px}
            #option-select select {width: 100%; height: 50px}
            h3 {font-size: 16px;}
            .comment-user img{padding: 0px}
            .comment-user p { padding-top: 10px;}
            #base-introduce h3{font-size: 16px;}
            #base-introduce p {font-size: 10px;}
        }
        @media screen and (min-width: 992px) {
            #option-select p {padding-top: 5px;}
            #app {
                height: 100%;
            }
        }
    </style>
    <div class="container">
        <div class="row">
            <p>
                <a href="{{ url("/") }}">首页</a> > <a href="{{ url("/show/" . $house->city) }}?startdate={{ $request->input("startdate") ? $request->input("startdate") : ''}}&enddate={{ $request->input("enddate") ? $request->input("enddate") : '' }}">{{ $address->city }}租房网</a>
                > 房间编号：{{ $house->id }}
            </p>
            <h3>{{ $house->name }}</h3>
            <p>{{ $address->province . $address->city . $address->area . $address->address . $address->detail }} &nbsp;&nbsp;
                <a href="#ditu">查看地图</a></p>
        </div>
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="row banner">
                    <div class="large_box col-md-12 col-sm-12 col-xs-12">
                        <ul>
                            @foreach(explode(",", $house->pic_path) as $path)
                            <li>
                                <img width="100%" src="/images/houses/{{ $path }}.jpg_732x480cw.jpg" alt="">
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="small_box col-md-12 col-sm-12 col-xs-12">
                        <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
                        <div class="small_list">
                            <ul>
                                @foreach(explode(",", $house->pic_path) as $index => $path)
                                    <li>
                                        <img width="100%" src="/images/houses/{{ $path }}.jpg_90x60c.jpg" alt="">
                                        <div class="bun_bg"></div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="row" id="base-introduce">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <h3>
                            @if($house->rent_type == 1)
                                整套出租
                            @elseif($house->rent_type == 2)
                                单间出租
                            @elseif($house->rent_type == 3)
                                床位出租
                            @else
                                沙发出租
                            @endif
                        </h3>
                        <p>
                            {{ $house_type[0] }}室{{ $house_type[1] }}厅{{ $house_type[2] }}厨{{ $house_type[3] }}卫{{ $house_type[4] }}阳台
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <h3>
                            宜住{{ $house->max_people }}人
                        </h3>
                        <p>
                            共 {{ $bed_sum }} 张床
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <h3>{{ $favorable_rate }}%好评率</h3>
                        <p>
                            共 {{ $house->comment_num }} 条评论
                        </p>
                    </div>
                </div>
                <div class="row description">
                    <div class="col-md-2 col-sm-4">
                        <h4>个性描述</h4>
                    </div>
                    <div class="col-md-10 col-sm-8">
                        <p>{{ $house->desc }}</p>
                    </div>
                </div>
                <div class="row description">
                    <div class="col-md-2 col-sm-4">
                        <h4>内部情况</h4>
                    </div>
                    <div class="col-md-10 col-sm-8">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <h5>被单更换:</h5>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                @if($house->change_bed == 1)
                                    <p>每日一换</p>
                                @else
                                    <p>每客一换</p>
                                @endif
                            </div>
                            @foreach($bed as $bedInfo)
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <h5>
                                    @if($bedInfo[0] == 1)
                                        单人床:
                                    @elseif($bedInfo[0] == 2)
                                        双人床:
                                    @elseif($bedInfo[0] == 3)
                                        沙发:
                                    @elseif($bedInfo[0] == 4)
                                        双层床:
                                    @elseif($bedInfo[0] == 5)
                                        榻榻米:
                                    @else
                                        其他:
                                    @endif
                                </h5>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <p>{{ $bedInfo[2] }}m×{{ $bedInfo[3] }}m ({{ $bedInfo[1] }}张)</p>
                            </div>
                            @endforeach
                        </div>
                        <p>{{ $house->internal_situation }}</p>
                    </div>
                </div>
                <div class="row description">
                    <div class="col-md-2 col-sm-4">
                        <h4>交通情况</h4>
                    </div>
                    <div class="col-md-10 col-sm-8">
                        <p>{{ $house->traffic_condition }}</p>
                    </div>
                </div>
                <div class="row description">
                    <div class="col-md-2 col-sm-4">
                        <h4>周边情况</h4>
                    </div>
                    <div class="col-md-10 col-sm-8">
                        <p>{{ $house->peripheral_condition }}</p>
                    </div>
                </div>
                <div class="row description">
                    <div class="col-md-2 col-sm-4">
                        <h4>配套设施</h4>
                    </div>
                    <div class="col-md-10 col-sm-8">
                        @foreach($supporting_facilities as $sf)
                        <div class="col-md-3 col-sm-4 col-xs-6">
                            <p>{{ $sf }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row description">
                    <div class="col-md-2 col-sm-4">
                        <h4>入住须知</h4>
                    </div>
                    <div class="col-md-10 col-sm-8">
                        <p>
                            预付订金：100%  提交订单后，支付总房费的100%作为预付订金交付平台
                        </p>
                        <p>
                            以下费用由房东线下额外收取，不包含在房费中
                        </p>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <h5>押金:</h5>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <p>{{ $house->deposit }} 元</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <h5>做饭:</h5>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                @if($house->cook_fee == 0)
                                    <p>不收费</p>
                                @else
                                    <p>{{ $house->cook_fee}} 元/顿</p>
                                @endif
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <h5>清洁:</h5>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                @if($house->clean_fee == 0)
                                    <p>不收费</p>
                                @else
                                    <p>{{ $house->clean_fee}} 元/天</p>
                                @endif
                            </div>
                        </div>
                        @if($house->other_fee != '')
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <h5>其他费用:</h5>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <p>{{ $house->other_fee }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="row" id="comment">
                    <h4>本房源评价({{ $house->comment_num }})</h4>
                    <div class="container-fluid">
                        @if($house->comment_num == 0)
                            <div class="row" style="text-align: center">
                                <h2>暂时没有评价！</h2>
                            </div>
                            <span class="line"></span>
                        @else
                            <div class="row base-info">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <h4>总好评率</h4>
                                    <h3 style="color: #4e8000;">{{ $favorable_rate }}%</h3>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <h4>好评数</h4>
                                    <h3 style="color: #4e8000">{{ $comments_count['good'] }}</h3>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <h4>中评数</h4>
                                    <h3 style="color: #ffa203">{{ $comments_count['mid'] }}</h3>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <h4>中评数</h4>
                                    <h3 style="color: red">{{ $comments_count['bad'] }}</h3>
                                </div>
                            </div>
                            @foreach($comments as $comment)
                            @if($comment->user_status != 0)
                            <div class="row comment-info">
                                <div class="col-md-3 col-sm-4 col-xs-6 comment-user">
                                    <img class="col-md-6 col-md-offset-3 col-xs-3" width="100%" src="/images/common/mrtx.jpg" alt="">
                                    <p class="col-md-6 col-md-offset-2 col-xs-9">{{ substr($comment->phone, 0, 3) . "******" . substr($comment->phone, 9) }}</p>
                                </div>
                                <div class="col-md-9 col-sm-8 col-md-offset-0 col-xs-11 col-xs-offset-1 comment-content">
                                    <p>
                                        {{ $comment->comment }}
                                    </p>
                                    <p>
                                        {{ $comment->comment_time }}
                                    </p>
                                    @if($comment->landlord_status)
                                        <span class="line"></span>
                                        <p>房东回复：</p>
                                        <p>
                                            {{ $comment->reply }}
                                        </p>
                                        <p>
                                            {{ $comment->reply_time }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <span class="line"></span>
                            @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="row" id="ditu"></div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12" id="order-info">
                <form action="{{ url("/orders/create") }}" id="form-order">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $house->id }}">
                    <div class="row" id="price">
                        <h3><span>￥</span>{{ $house->price }}<span>/晚</span></h3>
                    </div>
                    <div class="row" id="option-select">
                        <div class="col-md-9 col-sm-9 col-xs-12" id="dateSelect">
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input type="text" name="startdate" id="startdate" onchange="countSumPrice()" value="{{ $request->input("startdate") ?? date("Y-m-d") }}">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <p>至</p>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-12">
                                <input type="text" name="enddate" id="enddate" onchange="countSumPrice()" value="{{ $request->input("enddate") ?? date("Y-m-d", strtotime("+1 day")) }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <select name="num" id="num" onchange="countSumPrice()">
                                @for($i = 1; $i <= $house->sum; $i++)
                                    <option value="{{ $i }}">{{ $i }}套</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="row" id="price-info">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <p>总计￥<span id="sum_price" v-model="sum_price">@{{ sum_price }}</span></p>
                            <p id="error_show"></p>
                            <button type="button" onclick="checkDate()" class="btn btn-lg btn-success">
                                立即订购
                            </button>
                        </div>
                    </div>
                </form>
                <div class="row" id="fangdong-info">
                    <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                        <a href="{{ url("/fangdong/" . $landlord->id) }}">
                            <img width="100%" src="/images{{ $landlord->pic_path }}" alt="">
                            <h4>{{ $landlord->name }}</h4>
                        </a>
                        <h5>性别：{{ $landlord->sex  }}</h5>
                        @if($landlord->real_name && $landlord->id_card)
                            <h5 style="color: green">已实名认证</h5>
                        @endif
                        <a href="tel:{{ $landlord->phone }}" class="btn btn-success">电话联系</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(function(){
        /* 商品轮播图（带缩略图的轮播效果） */
        $('.large_box').css("height", $(".large_box img").height());
        $('.small_list').css("width", $(".small_box").width() - 60);
        $(".banner").thumbnailImg({
            large_elem: ".large_box",
            small_elem: ".small_list",
            left_btn: ".glyphicon-menu-left",
            right_btn: ".glyphicon-menu-right",
        });
        $("#ditu").css("height", $("#ditu").width()*3/4);
        //高德地图
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

        var d = new Date();
        var today = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate();
        var rent_info = [
            @foreach($rent_info as $info)
            [
                {{ $info->month }},
                @foreach(unserialize($info->detail) as $day => $num)
                [
                    {{ $day }}, {{ $num }}
                ],
                @endforeach
            ],
            @endforeach
        ];
        $('#dateSelect').dateRangePicker({
            autoClose: false,
            startDate: today,
            showTopbar: false,
            extraClass: 'date-range-picker19',
            separator : '至',
            showDateFilter: function(time, date)
            {
                date1 = new Date(time);
                day = date1.getDate();
                month = date1.getMonth() + 1;
                var res = {{ $house->sum }};
                for (var i = 0; i < rent_info.length; i++) {
                    if (month == rent_info[i][0]) {
                        for (var j = 1; j < rent_info[i].length; j++) {
//                            console.log(rent_info[i][j]);
                            if (day == rent_info[i][j][0]) {
                                res -= rent_info[i][j][1];
                                if (res <= 0) {
                                    res = '暂缺';
                                }
                                break;
                            }
                        }
                        break;
                    }
                }
                if (res == '暂缺')
                    res = '<div style="opacity:0.3;  width: 30px; text-align: center">'+res+'</div>';
                else
                    res = '<div style="opacity:1;  width: 30px; text-align: center">'+res+'</div>'
                return '<div style="padding:0 5px;">\
					    <span style="font-weight:bold">'+date+'</span>'+res+'\
				        </div>';
            },
            getValue: function()
            {
                if ($('#startdate').val() && $('#enddate').val()) {
                    return $('#startdate').val() + '至' + $('#enddate').val();
                }
                else {
                    return '';
                }
                countSumPrice();
            },
            setValue: function(s,s1,s2)
            {
                $('#startdate').val(s1);
                $('#enddate').val(s2);
                countSumPrice();
            }
        });
        var sumPrice = new Vue({
            el: '#price-info',
            data: {
                sum_price: DateDiff($("#startdate").val(), $("#enddate").val())*$("#num").val()*{{ $house->price }},
            }
        });
        function countSumPrice() {
            sumPrice.sum_price = DateDiff($("#startdate").val(), $("#enddate").val())*$("#num").val()*{{ $house->price }};
            console.log(sumPrice.sum_price);
        }
        function DateDiff(sDate1, sDate2) {  //sDate1和sDate2是yyyy-MM-dd格式

            var aDate, oDate1, oDate2, iDays;
            if (sDate1 == '' || sDate2 == '')
                return 0;
            aDate = sDate1.split("-");
            oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);  //转换为yyyy-MM-dd格式
            aDate = sDate2.split("-");
            oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
            iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
            return iDays;  //返回相差天数
        }


    });
    function checkDate() {
        $.ajax({
            url: '{{ url("/common/checkrent") }}',
            type: "post",
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
            data: {'startdate': $("#startdate").val(), 'enddate': $("#enddate").val(), 'id': {{ $house->id }}, 'num': $("#num").val()},
            success: function (data) {
                data = $.parseJSON(data);
                if (data.code == 1) {
                    $("#form-order").submit();
                }
                else {
                    $("#error_show").html(data.msg);
                }
            }
        });

    }
    </script>
@endsection