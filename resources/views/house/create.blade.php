@extends('layouts.app')

@section('content')
    <style>
        .info { border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: whitesmoke; padding: 30px 30px;}
        .info p { margin-top: 20px; margin-bottom: 20px;}
        #base-info,#house-desc,#fee-info {margin-top: 20px;}
        #address-info .radio>label{display: block; margin-top: 5px; margin-bottom: 5px;}
        .form-inline { padding-left: 20px;}
        #bed span{ padding-left: 5px; padding-right: 5px;}
        #bed .col-sm-1 {padding-left: 0px; padding-top: 5px;}
        #bed button {margin-top: 10px;}
        .form-inline .form-group {
            margin-top: 5px;
            margin-bottom: 5px;
        }
        #btn-submit button{ width: 100%}
    </style>


    <div id="add-address-model" class="modal fade add-address-model" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">添加新地址</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form class="form-inline" id="form-new-address">
                            <div class="form-group">
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
                            <div class="form-group">
                                <input type="text" class="form-control" name="address" placeholder="详细地址" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="detail" placeholder="门牌号" required>
                            </div>
                            <input type="hidden" name="co_ordinates" id="co_ordinates">
                        </form>
                    </div>
                    <div class="row" id="ditu"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" onclick="saveAddress()" class="btn btn-primary">确定</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



<div class="container">
    <form class="form-horizontal" action="{{ url('house') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="city-py" id="city-py">
        <div class="row" id="address-info">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                <h3>房源地址</h3>
                <div class="form-group">
                    <label for="address" class="col-sm-2 control-label">房源位于</label>
                    <div class="col-sm-10 radio">
                        <label v-for="(address,index) in addresses">
                            <input type="radio" name="address_id" v-on:change="changeCityPY(address.city)" v-bind:value="address.id" class="address">
                            @{{ address.province }}@{{ address.city }}@{{ address.area }}@{{ address.address }},@{{ address.detail }}
                        </label>
                        <label>
                            <button type="button" data-toggle="modal" data-target=".add-address-model" class="btn btn-warning"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加新地址</button>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="base-info">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                <h3>房源基本情况</h3>
                <div class="form-group">
                    <label class="col-sm-2 control-label">出租类型</label>
                    <div class="col-sm-10 radio">
                        <label>
                            <input type="radio" name="rent_type" value="1">
                            整套出租
                        </label>
                        <label>
                            <input type="radio" name="rent_type" value="2">
                            单间出租
                        </label>
                        <label>
                            <input type="radio" name="rent_type" value="3">
                            床位出租
                        </label>
                        <label>
                            <input type="radio" name="rent_type" value="4">
                            沙发出租
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">最多宜住人数</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="max_people">
                            <option v-for="n in 10" v-bind:value="n">@{{ n }}</option>
                            <option value="11">10人以上</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">房屋户型</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="house_type[shi]" aria-describedby="helpBlock" required>
                            <span class="input-group-addon">室</span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="house_type[ting]" required>
                            <span class="input-group-addon">厅</span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="house_type[chu]" required>
                            <span class="input-group-addon">厨</span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="house_type[wei]" required>
                            <span class="input-group-addon">卫</span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control" name="house_type[yangtai]" required>
                            <span class="input-group-addon">阳台</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">房屋面积</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" maxlength="4" class="form-control" name="house_area"  required>
                            <span class="input-group-addon">平米</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">同类房源</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" maxlength="4" class="form-control" name="sum"  required>
                            <span class="input-group-addon">个</span>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="bed">
                    <label class="col-sm-2 control-label" for="">床铺</label>
                    <div class="col-sm-10" v-for="(bed,index) in beds" v-bind:class="index == 0 ? '' : 'col-sm-offset-2'">
                        <div class="col-sm-3">
                            <select v-bind:name="bed.type" class="form-control">
                                <option value="1">双人床</option>
                                <option value="2">单人床</option>
                                <option value="3">沙发</option>
                                <option value="4">双层床</option>
                                <option value="5">榻榻米</option>
                                <option value="6">其他</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" maxlength="4" class="form-control" v-bind:name="bed.height" required>
                                <span class="input-group-addon">长</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" maxlength="4" class="form-control" v-bind:name="bed.width"  required>
                                <span class="input-group-addon">宽</span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="text" maxlength="4" class="form-control" v-bind:name="bed.num" required>
                                <span class="input-group-addon">张</span>
                            </div>
                        </div>
                        <div class="col-sm-1" v-if="index != 0">
                            <a href="javascript:void(0)" v-on:click="deleteBed(index)">删除</a>
                        </div>
                    </div>
                    <div class="col-sm-4 col-sm-offset-3">
                        <button class="btn btn-warning" v-on:click="addBed" type="button">添加床铺</button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">被单更换</label>
                    <div class="col-sm-10 radio">
                        <label>
                            <input type="radio" name="change_bed" value="1">
                            每日一换
                        </label>
                        <label>
                            <input type="radio" name="change_bed" value="2">
                            每客一换
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="house-desc">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                <h3>房源描述</h3>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">标题</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="20" class="form-control" name="name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">个性描述</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="3" name="desc"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">内部情况</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="3" name="internal_situation"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">交通情况</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="3" name="traffic_condition"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">周边情况</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="3" name="peripheral_condition"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="support-info">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                <h3>配套设施</h3>
                @foreach($support_facilities as $index => $support_facility)
                <label class="checkbox col-sm-4 col-xs-6">
                    <input type="checkbox" name="support_facility[{{ $index }}]" value="{{ $index }}"> {{ $support_facility }}
                </label>
                @endforeach
            </div>
        </div>
        <div class="row" id="fee-info">
            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                <h3>费用相关</h3>
                <div class="form-group">
                    <label class="col-sm-2 control-label">价格</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">￥</span>
                            <input type="text" name="price" maxlength="4" class="form-control" aria-label="Amount (to the nearest dollar)" required>
                            <span class="input-group-addon">.00</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">押金</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">￥</span>
                            <input type="text" name="deposit" maxlength="4" class="form-control" aria-label="Amount (to the nearest dollar)" required>
                            <span class="input-group-addon">.00</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">做饭</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">￥</span>
                            <input type="text" name="cook_fee" maxlength="4" class="form-control" aria-label="Amount (to the nearest dollar)" required>
                            <span class="input-group-addon">.00</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">清洁费</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">￥</span>
                            <input type="text" name="clean_fee" maxlength="4" class="form-control" aria-label="Amount (to the nearest dollar)" required>
                            <span class="input-group-addon">.00</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="max_people">其他费用</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="3" name="other_fee"></textarea>
                    </div>
                </div>
                <div class="form-group" id="btn-submit">
                    <div class="col-sm-offset-2 col-sm-3">
                        <button type="submit" onclick="doSubmit()" class="btn btn-success">提 交</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
    <script>
        var base = new Vue({
            el: '#base-info',
            data: {
                count: 1,
                beds: [
                    {
                        type: 'bed_info[0][type]',
                        height: 'bed_info[0][height]',
                        width: 'bed_info[0][width]',
                        num: 'bed_info[0][num]',
                    }
                ],
            },
            methods: {
                addBed: function () {
                    var bed_info = 'bed_info[' + this.count + ']';
                    var newbed = {
                        type: bed_info + '[type]',
                        height: bed_info + '[height]',
                        width: bed_info + '[width]',
                        num: bed_info + '[num]',
                    };
                    this.beds.push(newbed);
                    this.count++;
                },
                deleteBed: function (index) {
                    this.beds.splice(index,1);
                }
            }
        })
        var address = new Vue({
            el:'#address-info',
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
                    },
                    @endforeach
                ]
            },
            methods: {
                changeCityPY: function (city) {
                    for (var i = 0; i < Vcity.allCity.length; i++) {
                        var info = Vcity.regEx.exec(Vcity.allCity[i])
                        var tempChinese = info[1];
                        if (tempChinese == city) {
                            $("#city-py").val(info[2]);
                            console.log(info[2]);
                        }
                    }
                }
            }
        })


        function saveAddress() {
            $.ajax({
                url: "{{ url("/common/saveaddress") }}",
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                data: $("#form-new-address").serialize(),
                type: "post",
                success: function (data) {
                    data = $.parseJSON(data);
                    address.addresses.push(data);
                    $('.add-address-model').modal('hide')
                }
            })
        }



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