@extends('layouts.app')

@section('content')
    <style>
        .carousel-control.right,.carousel-control.left {
            opacity: 0;
        }
        .carousel-control:hover{
            opacity: 1;
            background-image: none;
        }
        .carousel-input {
            position: absolute;
            width: 50%;
            left: 30%;
            bottom: 10%;
        }
        #form-md .cityinput,.dateinput {
            background-color: white;
            margin-left: 0px;
            margin-right: 0px;
            padding-left: 10px;
            border: 1px solid #c1bcbc;
        }
        #form-md .cityinput {
            width: 50%;
        }
        #form-md .dateinput {
            width: 30%;
        }
        #form-md .submitinput {
            background-color: #4cae4c;
            color: white;
            width: 10%;
            margin-left: 0px;
            margin-right: 0px;
        }
        #form-md .cityinput,.dateinput,.submitinput {
            height: 30px;
            border:1px solid #c1bcbc;
            font-size: 16px;
        }
        .main-content {
            width: 100%;
            background-color: white;
            padding-bottom: 30px;
            padding-top: 20px;
        }
        .main-content>.container {
            margin-top: 0;
        }
        .ads-room {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .hot-city {
            margin-top: 30px;
        }
        .hotcity-item {
            padding: 0px;
        }
        .hotcity-item a{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            font-size: 2em;
            color: white;
            text-align: center;
            line-height: 5em;
        }
        .hotcity-item a:hover {
            text-decoration: none;
            background-color: rgba(0,0,0,0.2);
        }
        .hot-house {
            margin-top: 30px;
        }
        .house-item {
            padding-top: 10px;
        }
        .house-item:hover {
            border: 1px solid #c1bcbc;
        }
        .house-item h4 {
            text-overflow:ellipsis;
            white-space:nowrap;
            overflow:hidden;
        }
        .house-item a {
            text-decoration: none;
        }
        .house-item .house-price{
            background-color: rgba(0,0,0,0.5);
            padding: 5px;
            color: white;
            position: absolute;
            top: 60%;
        }
        #safe-plan {
            color: white;
            padding-top: 20px;
            padding-bottom: 20px;
            background: #00897b;
        }
        #safe-plan div div{
            text-align: center;
        }
        #safe-plan img,h4 {
            margin-top: 20px;
        }

        #form-xs {
            display: none;
        }
        #form-xs .col-xs-10 input{
            width: 100%;
            height: 50px;
            font-size: 16px;
            padding-left: 10px;
        }
        @media screen and (max-width: 768px) {
            #form-xs {
                display: block;
            }
            #safe-plan {
                display: none;
            }
            .house-item .house-price{
                top: 50%;
            }
            .hotcity-item a {
                line-height: 3em;
            }
            .ads-room {
                display: none;
            }
            .carousel-caption {
                bottom:5px;
            }
            #carousel-slide h3 {
                font-size: 10px;
            }
            #carousel-slide h4 {
                font-size: 8px;
            }
            .carousel-input {
                display: none;
            }
        }
        @media (max-width: 992px) and (min-width: 768px) {

            .carousel-caption {
                left: 50%;
                padding-bottom: 5%;
            }
        }
        @media screen and (min-width: 992px) {
            .carousel-caption {
                left: 50%;
                padding-bottom: 10%;
            }
            #form-md .cityinput,.dateinput,.submitinput {
                height: 50px;
            }
        }

    </style>
    <div id="carousel-slide" class="carousel slide carousel-fade" data-interval="5000" data-ride="carousel" style="margin-top: -20px;">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            @foreach($slides as $index => $slide)
            <div class="item{{ $index == 0 ? ' active' : '' }}">
                <a href="{{ url($slide->target) }}"><img src="/images{{ $slide->pic_path }}" alt="{{$slide->title}}"></a>
                <div class="carousel-caption">
                    <h3>{{ $slide->title }}</h3>
                    <h4>{{ $slide->desc }}&nbsp;&nbsp;{{ $price[$slide->id] }}/晚</h4>
                </div>
            </div>
            @endforeach
        </div>

        <div class="carousel-input">
            <form method="get" id="form-md" action="{{ url("/show") }}">
                <input type="text" class="cityinput" id="citySelect" placeholder="请输入目的地" required oninvalid="setCustomValidity('请输入关键字！');" oninput="setCustomValidity('');"><input type="text" placeholder="入住退房时间" class="dateinput" id="dateSelect"><input type="submit" class="submitinput" onclick="submitInfo('#form-md')" value="提交">
                <input type="hidden" class="startdate" name="startdate">
                <input type="hidden" name="enddate" class="enddate">
            </form>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-slide" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-slide" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <div class="container ads-room">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h4>满足一家人住宿</h4>
                <p>不论一家老小还是朋友几人</p>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h4>家一般的舒适</h4>
                <p>有客厅、有厨房、能洗衣、能做饭</p>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h4>比酒店便宜50%</h4>
                <p>一套短租公寓=2间酒店房间</p>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <h4>体验当地人的生活</h4>
                <p>本地房东帮你规划行程、做向导</p>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="container">
            <form method="get" id="form-xs" action="{{ url("/show") }}">
                <div class="col-xs-10 col-xs-offset-1">
                    <input type="text" class="cityinput" id="citySelect-xs" placeholder="请输入目的地" required oninvalid="setCustomValidity('请输入关键字！');" oninput="setCustomValidity('');">
                </div>
                <div class="col-xs-10 col-xs-offset-1">
                    <input type="text" placeholder="入住退房时间" class="dateinput" id="dateSelect-xs">
                </div>
                <div class="col-xs-10 col-xs-offset-1">
                    <input type="submit" class="submitinput" onclick="submitInfo('#form-xs')" value="提交">
                </div>
                <input type="hidden" name="enddate" class="enddate">
                <input type="hidden" class="startdate" name="startdate">
            </form>
        </div>
        <div class="container hot-city">
            <div class="row" style="text-align: center">
                <h1>热门城市</h1>
                <p>和你在另一个地方遇见美好</p>
            </div>
            <div class="row">
                @foreach($hotcitys as $cityinfo)
                    <div class="col-md-3 col-sm-4 col-xs-6 hotcity-item"><img src="/images{{ $cityinfo->pic_path }}" width="100%" alt="{{ $cityinfo->title }}"><a href="{{ url("/show" . $cityinfo->target) }}">{{ $cityinfo->title }}</a></div>
                @endforeach
            </div>
        </div>
        <div class="container hot-house">
            <div class="row" style="text-align: center">
                <h1>短租公寓推荐</h1>
                <p>相知无远近，万里尚为邻</p>
            </div>
            <div class="row">
                @foreach($hothouses as $house)
                    <div class="col-md-4 col-sm-6 col-xs-12 house-item">
                        <a href="{{ url("house/" . $house->id) }}">
                            <img width="100%" src="/images/houses{{ substr($house->pic_path, 0, strpos($house->pic_path, ","))}}.jpg_732x480cw.jpg" alt="">
                        </a>
                        <a href="{{ url("house/" . $house->id) }}">
                            <h4>{{ $addresses[$house->address_id]->city }} <b>·</b> {{ $house->name }}</h4>
                            <p>
                                {{ $house->comment_num }}条评论 可住{{ unserialize($house->extra)['max_people'] ?? 0 }}人 已定{{ $house->ordered_num }}晚
                            </p>
                        </a>
                        <span class="house-price">￥{{ $house->price }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container-fluid"  id="safe-plan">
        <div class="container">
            <div class="row">
                <h1>房客安心计划</h1>
                <div class="col-md-3 col-xs-3 col-sm-3">
                    <img width="40%" src="/images/common/safe1.png" alt="">
                    <h4>保证入住 平台补差价</h4>
                </div>
                <div class="col-md-3 col-xs-3 col-sm-3">
                    <img width="40%" src="/images/common/safe2.png" alt="">
                    <h4>入住前一天 无条件退款</h4>
                </div>
                <div class="col-md-3 col-xs-3 col-sm-3">
                    <img width="40%" src="/images/common/safe3.png" alt="">
                    <h4>到店无房 赔首晚房费</h4>
                </div>
                <div class="col-md-3 col-xs-3 col-sm-3">
                    <img width="40%" src="/images/common/safe4.png" alt="">
                    <h4>付款到平台 资金有保障</h4>
                </div>
            </div>
        </div>
    </div>
    <script>
        var test=new Vcity.CitySelector({input:'citySelect'});
        new Vcity.CitySelector({input:'citySelect-xs'});
        function submitInfo(form) {
            var city = $(form + " .cityinput").val();
            if (city !== '')
            {
                for (var i = 0; i < Vcity.allCity.length; i++) {
                    var info = Vcity.regEx.exec(Vcity.allCity[i])
                    var tempChinese = info[1];
                    if (tempChinese == city) {
                        $(form).prop("action", "{{ url("/show") }}/" + info[2]);
//                    $(form + " .city").val(info[2]);
                        $(form).submit();
                    }
                }
            }
            else {
                $(form).checkValidity();
            }
        }
        var d = new Date();
        var today = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate();
        $('#dateSelect').dateRangePicker({
            autoClose: true,
            startDate: today,
            showTopbar: false,
            extraClass: 'date-range-picker19',
            separator : ' 至 ',
            setValue: function(s,s1,s2)
            {
                $('#form-md .dateinput').val(s);
                $('#form-md .startdate').val(s1);
                $('#form-md .enddate').val(s2);
            }
        });
        $("#dateSelect-xs").dateRangePicker({
            autoClose: true,
            startDate: today,
            showTopbar: false,
            extraClass: 'date-range-picker19',
            separator : ' 至 ',
            setValue: function(s,s1,s2)
            {
                $('#form-xs .dateinput').val(s);
                $('#form-xs .startdate').val(s1);
                $('#form-xs .enddate').val(s2);
            }
        })
    </script>
@endsection