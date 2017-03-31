@extends('landlord.nav')

@section('main-content')
    <style>
        #address-info { background-color: white; min-height: 300px;}
        .info { position: relative; margin-bottom: 20px;border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: #fff; padding: 30px 30px;}
        /*.info p { margin-top: 30px; margin-bottom: 30px; font-size: 14px;}*/
        .info>h4 { color:#30c3a6;}
        .btn-edit {position: absolute; top: 20px; right: 5%; z-index: 100;}
    </style>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ url('/fangdong') }}">我是房东</a></li>
            <li class="active">房源</li>
            <li class="active">常用地址</li>
        </ol>
    </div>
    <div class="row info info" id="address-info">
        <h4>常用入住人</h4>
        <a href="javascript:void(0)" v-on:click="showAddAddress()" class="btn-edit btn btn-info btn-xs">添加地址</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <td>省</td>
                <td>市</td>
                <td>区</td>
                <td>详细地址</td>
                <td>门牌号</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(address, index) in addresses">
                    <td>@{{ address.province }}</td>
                    <td>@{{ address.city }}</td>
                    <td>@{{ address.area }}</td>
                    <td>@{{ address.address }}</td>
                    <td>@{{ address.detail }}</td>
                    <td><button v-bind:disabled="address.used ? 'disabled' : false" v-on:click="deleteAddress(address.id, index)" class="btn btn-warning btn-xs">@{{ address.used ? '使用中' : '删除' }}</button></td>
                </tr>
            </tbody>
        </table>
        <div class="modal fade show-add-address" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <form id="form-add-address" class="form-inline">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">添加地址</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group" style="margin-left: 10px;">
                                    <label for="province">省：</label>
                                    <select id='province' name="province" class="form-control" style="width:100px" onchange='search(this)'></select>
                                </div>
                                <div class="form-group">
                                    <label for="province">市：</label>
                                    <select id='city' name="city" class="form-control" style="width:100px" onchange='search(this)'></select>                            </div>
                                <div class="form-group">
                                    <label for="district">区：</label>
                                    <select id='district' name="district" class="form-control" style="width:100px" onchange='search(this)'></select>
                                </div>
                                <div class="form-group">
                                    <label for="street">街道：</label>
                                    <select id='street' name="street" class="form-control" style="width:100px" onchange= 'setCenter(this)'></select>
                                </div>
                                <div class="form-group" style="margin:10px 20px;">
                                    <input type="text" class="form-control" name="address" placeholder="详细地址" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="detail" placeholder="门牌号" required>
                                </div>
                                <input type="hidden" name="co_ordinates" id="co_ordinates">
                            </div>
                            <div class="row" id="ditu"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" v-on:click="addAddress()">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var addresses = new Vue({
            el: '#address-info',
            data: {
                addresses: [
                    @foreach($addresses as $address)
                    {
                        id: {{ $address->id }},
                        province: '{{ $address->province }}',
                        city: '{{ $address->city }}',
                        area: '{{ $address->area }}',
                        address: '{{ $address->address }}',
                        detail: '{{ $address->detail }}',
                        used: {{ $used_arr[$address->id] == 1 ? 'true' : 'false' }}
                    },
                    @endforeach
                ]
            },
            methods: {
                showAddAddress: function () {
                    $(".show-add-address").modal();
                },
                addAddress: function () {
                    $.ajax({
                        url: "{{ url('fangdong/addaddress') }}",
                        data: $("#form-add-address").serialize(),
                        type: 'post',
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        success: function (data) {
                            data = $.parseJSON(data);
                            addresses.addresses.push({
                                id: data.id,
                                province: data.province,
                                city: data.city,
                                area: data.area,
                                address: data.address,
                                detail: data.detail,
                                used: false
                            });
                            $(".show-add-address").modal('toggle');
                        }
                    })
                },
                deleteAddress: function (id, index) {
                    $.ajax({
                        url: "{{ url('fangdong/deleteaddress') }}",
                        data: {'id': id},
                        type: 'post',
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        success: function (data) {
                            if (data == "删除成功")
                                addresses.addresses.splice(index, 1);
                        }
                    })
                }
            }
        })


        $("#ditu").css("height", $(window).height()*0.4);
        var map, district, polygons = [], citycode;
        var citySelect = document.getElementById('city');
        var districtSelect = document.getElementById('district');
        var areaSelect = document.getElementById('street');

        map = new AMap.Map('ditu', {
            resizeEnable: true,
            center: [116.30946, 39.937629],
            zoom: 3
        });
        //行政区划查询
        var opts = {
            subdistrict: 1,   //返回下一级行政区
            level: 'city',
            showbiz:false  //查询行政级别为 市
        };
        district = new AMap.DistrictSearch(opts);//注意：需要使用插件同步下发功能才能这样直接使用
        district.search('中国', function(status, result) {
            if(status=='complete'){
                getData(result.districtList[0]);
            }
        });
        function getData(data) {
            var bounds = data.boundaries;
            if (bounds) {
                for (var i = 0, l = bounds.length; i < l; i++) {
                    var polygon = new AMap.Polygon({
                        map: map,
                        strokeWeight: 1,
                        strokeColor: '#CC66CC',
                        fillColor: '#CCF3FF',
                        fillOpacity: 0.5,
                        path: bounds[i]
                    });
                    polygons.push(polygon);
                }
                map.setFitView();//地图自适应
                for (var i = 0, l = polygons.length; i < l; i++) {
                    polygons[i].setMap(null);
                }
            }

            var subList = data.districtList;
            var level = data.level;

            //清空下一级别的下拉列表
            if (level === 'province') {
                nextLevel = 'city';
                citySelect.innerHTML = '';
                districtSelect.innerHTML = '';
                areaSelect.innerHTML = '';
            } else if (level === 'city') {
                nextLevel = 'district';
                districtSelect.innerHTML = '';
                areaSelect.innerHTML = '';
            } else if (level === 'district') {
                nextLevel = 'street';
                areaSelect.innerHTML = '';
            }
            if (subList) {
                var contentSub =new Option('--请选择--');
                for (var i = 0, l = subList.length; i < l; i++) {
                    var name = subList[i].name;
                    var levelSub = subList[i].level;
                    var cityCode = subList[i].citycode;
                    if(i==0){
                        document.querySelector('#' + levelSub).add(contentSub);
                    }
                    contentSub=new Option(name);
                    contentSub.setAttribute("value", name);
                    contentSub.center = subList[i].center;
                    contentSub.adcode = subList[i].adcode;
                    document.querySelector('#' + levelSub).add(contentSub);
                }
            }

        }
        var marker;
        //地图点击事件
        map.on('click', function(e) {
            if (marker)
                marker.setMap(null);
            marker = new AMap.Marker({
                icon: "http://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
                position: [e.lnglat.getLng(), e.lnglat.getLat()]
            });
            marker.setMap(map);
            $("#co_ordinates").val(e.lnglat.getLng()+","+e.lnglat.getLat());
        });

        function search(obj) {
            //清除地图上所有覆盖物
            for (var i = 0, l = polygons.length; i < l; i++) {
                polygons[i].setMap(null);
            }
            var option = obj[obj.options.selectedIndex];
            var keyword = option.text; //关键字
            var adcode = option.adcode;
            district.setLevel(option.value); //行政区级别
            district.setExtensions('all');
            //行政区查询
            //按照adcode进行查询可以保证数据返回的唯一性
            district.search(adcode, function(status, result) {
                if(status === 'complete'){
                    getData(result.districtList[0]);
                }
            });
        }
        function setCenter(obj){
            map.setCenter(obj[obj.options.selectedIndex].center)
        }
    </script>
@endsection