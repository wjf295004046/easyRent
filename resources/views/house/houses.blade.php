@extends("layouts.app")

@section('content')
    <style>

        .info {
            border: solid 1px silver;
        }
        div.info-top {
            position: relative;
            background: none repeat scroll 0 0 #F9F9F9;
            border-bottom: 1px solid #CCC;
            border-radius: 5px 5px 0 0;
        }
        div.info-top div {
            display: inline-block;
            color: #333333;
            font-size: 14px;
            font-weight: bold;
            line-height: 31px;
            padding: 0 10px;
        }
        div.info-top img {
            position: absolute;
            top: 10px;
            right: 10px;
            transition-duration: 0.25s;
        }
        div.info-top img:hover {
            box-shadow: 0px 0px 5px #000;
        }
        div.info-middle {
            font-size: 12px;
            padding: 6px;
            line-height: 20px;
        }
        div.info-bottom {
            height: 0px;
            width: 100%;
            clear: both;
            text-align: center;
        }
        div.info-bottom img {
            position: relative;
            z-index: 104;
        }
        span {
            margin-left: 5px;
            font-size: 11px;
        }
        .info-middle img {
            float: left;
            margin-right: 6px;
        }
        .wraper {
            margin-top: -20px;
            min-height: 100%;
        }
        #search {
            margin-top: 20px;
        }
        #search input {
            width: 80%;
            height: 30px;
            padding-left: 10px;
            background-color: white;
        }
        #search label {
            margin-right: 5px;
        }
        #search select {
            width: 50%;
            height: 30px;
            padding-left: 10px;
        }
        #search>div {
            margin-top: 10px;
        }
        .carousel-control.right,.carousel-control.left {
            opacity: 0;
        }
        .carousel-control:hover{
            opacity: 1;
            background-image: none;
        }
        #house-info {
            margin-top: 20px;
        }
        .carousel-price {
            padding: 5px;
            background-color: rgba(0,0,0,0.5);
            position: absolute;
            top: 75%;
            color: whitesmoke;
            font-size: 20px;
        }
        .house-item {
            padding-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #efefef;
        }
        .house-item:hover {
            border: 1px solid #c1bcbc;
        }
        .house-item a:hover {
            text-decoration: none;
        }
        .house-item h4{
            text-overflow:ellipsis;
            white-space:nowrap;
            overflow:hidden;
        }
        .gaode-api {
            background-color: black;
        }
        .api-fix {
            position: fixed;
            right: 0px;
            top: 0px;
        }
        @media screen and (max-width: 992px) {
            .gaode-api {
                display: none;
            }
        }
        @media screen and (min-width: 992px) {
            #app {
                height: 100%;
            }
        }
    </style>
    <div class="container-fluid wraper">
        <div class="row">
            <div class="col-md-7 main-content">
                <div class="container-fluid">
                    <form method="get" id="form-search">
                        <div class="row" id="search">
                            <div class="col-md-4 col-xs-12 col-sm-6">
                                <label for="">目的地</label>
                                <input type="text" class="cityinput" id="citySelect" v-model="city" placeholder="请输入目的地" required oninvalid="setCustomValidity('请输入关键字！');" oninput="setCustomValidity('');">
                            </div>
                            <div class="col-md-4 col-xs-12 col-sm-6">
                                <label for="">日&nbsp;&nbsp;&nbsp;期</label>
                                <input type="text" placeholder="入住退房时间" value="{{ $search_params['date'] ?? '' }}" class="dateinput" id="dateSelect">
                                <input type="hidden" name="enddate" value="{{ $search_params['enddate'] ?? '' }}" class="enddate">
                                <input type="hidden" class="startdate" value="{{ $search_params['startdate'] ?? '' }}" name="startdate">
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <label for="">关键字</label>
                                <input type="text" value="{{ $search_params['keyword'] ?? '' }}" name="keyword" placeholder="标题">
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="">价&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;格</label>
                                <select id="range-price">
                                    <option value="0">不限</option>
                                    <option value="0-100" {{ isset($search_params['range_price']) && $search_params['range_price'] == '0-100' ? 'selected' : '' }}>100以内</option>
                                    <option value="100-300" {{ isset($search_params['range_price']) && $search_params['range_price'] == '100-300' ? 'selected' : '' }}>100-300</option>
                                    <option value="300-500" {{ isset($search_params['range_price']) && $search_params['range_price'] == '300-500' ? 'selected' : '' }}>300-500</option>
                                    <option value="500-1000" {{ isset($search_params['range_price']) && $search_params['range_price'] == '500-1000' ? 'selected' : '' }}>500-1000</option>
                                    <option value="1000-9999" {{ isset($search_params['range_price']) && $search_params['range_price'] == '1000-9999' ? 'selected' : '' }}>1000以上</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="">户型</label>
                                <select id="house-type">
                                    <option value="0">不限</option>
                                    <option value="1" {{ isset($search_params['house_type']) && $search_params['house_type'] == 1 ? 'selected' : '' }}>一居</option>
                                    <option value="2" {{ isset($search_params['house_type']) && $search_params['house_type'] == 2 ? 'selected' : '' }}>二居</option>
                                    <option value="3" {{ isset($search_params['house_type']) && $search_params['house_type'] == 3 ? 'selected' : '' }}>三居</option>
                                    <option value="4" {{ isset($search_params['house_type']) && $search_params['house_type'] == 4 ? 'selected' : '' }}>三居以上</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="">出租类型</label>
                                <select id="rent-type">
                                    <option value="0">不限</option>
                                    <option value="1" {{ isset($search_params['rent_type']) && $search_params['rent_type'] == 1 ? 'selected' : '' }}>整套</option>
                                    <option value="2" {{ isset($search_params['rent_type']) && $search_params['rent_type'] == 2 ? 'selected' : '' }}>单间</option>
                                    <option value="3" {{ isset($search_params['rent_type']) && $search_params['rent_type'] == 3 ? 'selected' : '' }}>床位</option>
                                    <option value="4" {{ isset($search_params['rent_type']) && $search_params['rent_type'] == 4 ? 'selected' : '' }}>沙发</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-6 col-sm-4">
                                <label for="">人数</label>
                                <select id="select-people">
                                    <option value="0">不限</option>
                                    <option value="1" {{ isset($search_params['max_people']) && $search_params['max_people'] == 1 ? 'selected' : '' }}>1人</option>
                                    <option value="2" {{ isset($search_params['max_people']) && $search_params['max_people'] == 2 ? 'selected' : '' }}>2人</option>
                                    <option value="3" {{ isset($search_params['max_people']) && $search_params['max_people'] == 3 ? 'selected' : '' }}>3人</option>
                                    <option value="4" {{ isset($search_params['max_people']) && $search_params['max_people'] == 4 ? 'selected' : '' }}>4人</option>
                                    <option value="5" {{ isset($search_params['max_people']) && $search_params['max_people'] == 5 ? 'selected' : '' }}>5人</option>
                                    <option value="6" {{ isset($search_params['max_people']) && $search_params['max_people'] == 6 ? 'selected' : '' }}>6人</option>
                                    <option value="7" {{ isset($search_params['max_people']) && $search_params['max_people'] == 7 ? 'selected' : '' }}>7人</option>
                                    <option value="8" {{ isset($search_params['max_people']) && $search_params['max_people'] == 8 ? 'selected' : '' }}>8人</option>
                                    <option value="9" {{ isset($search_params['max_people']) && $search_params['max_people'] == 9 ? 'selected' : '' }}>9人+</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-2 col-xs-2 col-md-offset-0 col-xs-offset-4">
                                <button type="submit" onclick="submitForm()" class="btn btn-success">搜索</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="container-fluid" id="house-info">
                    @foreach($houses as $index => $house)
                        <div class="col-md-6 col-sm-6 col-xs-12 house-item" data-position="{{ $addresses[$house->id]->co_ordinates }}">
                            <div id="carousel-house{{ $index }}" class="carousel slide" data-interval="false" data-ride="carousel">

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">
                                    @foreach(explode(",", $house->pic_path) as $key => $pic)
                                    <div class="item{{ $key == 0 ? ' active' : '' }}">
                                        <a href="{{ url("house/" . $house->id) }}{{ isset($search_params['startdate']) ? "?startdate=" . $search_params['startdate'] . "&enddate=" . $search_params['enddate'] : ''}}"><img width="100%" src="/images/houses{{ $pic }}.jpg_732x480cw.jpg" alt="..."></a>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Controls -->
                                <a class="left carousel-control" href="#carousel-house{{ $index }}" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#carousel-house{{ $index }}" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                                <span class="carousel-price">
                                    ￥{{ $house->price }}
                                </span>
                            </div>
                            <a href="{{ url("house/" . $house->id) }}{{ isset($search_params['startdate']) ? "?startdate=" . $search_params['startdate'] . "&enddate=" . $search_params['enddate'] : ''}}">
                                <h4>{{ $house->name }}</h4>
                                <p>
                                    @if($house->rent_type == 1)
                                        整套出租
                                    @elseif($house->rent_type == 2)
                                        单间出租
                                    @elseif($house->rent_type == 3)
                                        床位出租
                                    @else
                                        沙发出租
                                    @endif
                                    <b>·</b>
                                    @if($house->house_type == 1)
                                        一居
                                    @elseif($house->house_type == 2)
                                        二居
                                    @elseif($house->house_type == 3)
                                        三居
                                    @elseif($house->house_type == 4)
                                        四居
                                    @else
                                        四居以上
                                    @endif
                                    <b>·</b>宜住{{ $house->max_people }}人<b>·</b>已定{{ $house->ordered_num }}晚
                                    - {{ $house->comment_num }}条评论
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 col-xs-12 col-sm-12">
                            {{ $houses->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 gaode-api" id="gaode-api">

            </div>
        </div>
    </div>
    <script>
        $(".gaode-api").css("height", document.body.clientHeight - 50);
        $(".main-content").css('min-height', document.body.clientHeight - 50);
        window.onscroll = function(){
            var scrollTop = document.body.scrollTop;
            var maxHeight = document.body.scrollHeight - document.body.clientHeight - 120;
            if(scrollTop >= 50){
                $(".gaode-api").addClass("api-fix");
            }
            else {
                $(".gaode-api").removeClass("api-fix");
            }
            if (scrollTop >= maxHeight) {
                $(".gaode-api").removeClass("api-fix");
                $(".gaode-api").css("margin-top", $(".main-content").height() - $(".gaode-api").height() - 20);
            }
            else {
                $(".gaode-api").removeClass("api-relative");
                $(".gaode-api").css("margin-top", 0);
            }
        }

//        地图相关

        var map = new AMap.Map('gaode-api', {
            resizeEnable: true,
            zoom:11,
        });
        map.on("click", function () {
            map.clearInfoWindow();
        })
        @if(empty($addresses))
            var mapCity = getCityName('{{ $search_params['city'] }}')
            map.setCity(mapCity);
        @endif
//        map.clearMap();  // 清除地图覆盖物
        var markers = [
                @foreach($addresses as $index => $address)
                {
                    icon: 'http://webapi.amap.com/theme/v1.3/markers/n/mark_b.png',
                    position: [{{ $address->co_ordinates }}],
                    house_id: {{ $index }},
                    pic_path: '/images/houses{{ $api_info[$index]['pic_path'] }}.jpg_90x60c.jpg',
                    title: '{{ $api_info[$index]['title'] }}',
                    price: '{{ $api_info[$index]['price'] }}',
                    address: '{{ $address->province . $address->city . $address->area . $address->address }}'
                },
                @endforeach
        ];
        // 添加一些分布不均的点到地图上,地图上添加三个点标记，作为参照
        markers.forEach(function(marker) {
            var temp =new AMap.Marker({
                map: map,
                icon: marker.icon,
                position: [marker.position[0], marker.position[1]],
                offset: new AMap.Pixel(-12, -36),
            });
            temp.on("click", function () {
                map.setZoomAndCenter(13, marker.position);
                showInfoWindow(map, temp, marker);
            })
        });
        map.setFitView();
        function showInfoWindow(map, temp, marker) {
            var title = marker.title + '<span style="font-size:11px;color:#F00;">价格:' + marker.price + '</span>',
                    content = [];
            content.push("<img src='" + marker.pic_path + "'>地址：" + marker.address);

            content.push("<a href='{{ url("/house") }}/" + marker.house_id + "{{ isset($search_params['startdate']) ? "?startdate=" . $search_params['startdate'] . "&enddate=" . $search_params['enddate'] : ''}}'>详细信息</a>");
            var infoWindow = new AMap.InfoWindow({
                isCustom: true,  //使用自定义窗体
                content: createInfoWindow(title, content.join("<br/>")),
                offset: new AMap.Pixel(16, -45)
            });
            infoWindow.open(map, temp.getPosition());
        }

        //构建自定义信息窗体
        function createInfoWindow(title, content) {
            var info = document.createElement("div");
            info.className = "info";

            //可以通过下面的方式修改自定义窗体的宽高
            //info.style.width = "400px";
            // 定义顶部标题
            var top = document.createElement("div");
            var titleD = document.createElement("div");
            var closeX = document.createElement("img");
            top.className = "info-top";
            titleD.innerHTML = title;
            closeX.src = "http://webapi.amap.com/images/close2.gif";
            closeX.onclick = closeInfoWindow;

            top.appendChild(titleD);
            top.appendChild(closeX);
            info.appendChild(top);

            // 定义中部内容
            var middle = document.createElement("div");
            middle.className = "info-middle";
            middle.style.backgroundColor = 'white';
            middle.innerHTML = content;
            info.appendChild(middle);

            // 定义底部内容
            var bottom = document.createElement("div");
            bottom.className = "info-bottom";
            bottom.style.position = 'relative';
            bottom.style.top = '0px';
            bottom.style.margin = '0 auto';
            var sharp = document.createElement("img");
            sharp.src = "http://webapi.amap.com/images/sharp.png";
            bottom.appendChild(sharp);
            info.appendChild(bottom);
            return info;
        }

        //关闭信息窗体
        function closeInfoWindow() {
            map.clearInfoWindow();
        }
        
        $(".house-item").hover(function () {
            position = this.attributes["data-position"].value;
            position = position.split(',');
            timer = setTimeout(function(){

                map.setZoomAndCenter(13, position);
            },500);
        },function(){
            clearTimeout(timer);
        });


//        其他
        var cityVue = new Vue({
            el: '#citySelect',
            data: {
                city: getCityName('{{ $search_params['city'] }}'),
            }
        });
        new Vcity.CitySelector({input:'citySelect'});
        var d = new Date();
        var today = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate();
        $('#dateSelect').dateRangePicker({
            autoClose: true,
            startDate: today,
            showTopbar: false,
            extraClass: 'date-range-picker19',
            separator : '至',
            setValue: function(s,s1,s2)
            {
                $('#dateSelect').val(s);
                $('.startdate').val(s1);
                $('.enddate').val(s2);
            }
        });

        function getCityName(city) {
            for (var i = 0; i < Vcity.allCity.length; i++) {
                var info = Vcity.regEx.exec(Vcity.allCity[i])
                var cityName = info[2];
                if (cityName == city) {
                    return info[1];
                }
            }
        }
        function submitForm() {
            var city = $("#citySelect").val();
            if(city != "") {
                for (var i = 0; i < Vcity.allCity.length; i++) {
                    var info = Vcity.regEx.exec(Vcity.allCity[i])
                    var tempChinese = info[1];
                    if (tempChinese == city) {
                        var res = info[2];
                        var house_type = $("#house-type").val();
                        var range_price = $("#range-price").val();
                        var rent_type = $("#rent-type").val();
                        var max_people = $("#select-people").val();
                        if (house_type !== '0') {
                            res += "_ht" + house_type;
                        }
                        if (range_price !== '0') {
                            res += "_rp" + range_price;
                        }
                        if (rent_type !== '0') {
                            res += "_rt" + rent_type;
                        }
                        if (max_people !== '0') {
                            res += "_mp" + max_people;
                        }
                        console.log(res);
                        $('#form-search').prop("action", "{{ url("/show") }}/" + res);
//                        $(form).submit();
                    }
                }
            }
        }
    </script>
@endsection