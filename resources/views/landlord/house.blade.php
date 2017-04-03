@extends('landlord.nav')

@section('main-content')
    <style>
        #main-content,#manage-info {background: #fff; margin-bottom: 20px;}
        table { margin-top: 10px;}
        .order-info td {
            text-overflow:ellipsis;
            white-space:nowrap;
            overflow:hidden;
            max-width: 20%;
        }
    </style>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ url('/fangdong') }}">我是房东</a></li>
            <li class="active">房源</li>
            <li class="active">房源管理</li>
        </ol>
    </div>
    <div class="row" id="main-content">
        <table class="table table-hover">
            <thead>
            <tr>
                <td>名称</td>
                <td>图片</td>
                <td>创建时间</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>
            @foreach($houses as $house)
                <tr>
                    <td><a href="javascript:void(0)" onclick="showHouse({{ $house->id }})">{{ $house->name }}</a></td>
                    <td>
                        @if($house->pic_path != "")
                        <img src="/images/houses{{ explode(",", $house->pic_path)[0] }}.jpg_90x60c.jpg" width="90px" alt="">
                        @endif
                    </td>
                    <td>{{ $house->created_at }}</td>
                    @if($house->status == -1)
                        <td>审核不通过</td>
                    @elseif($house->status == 0)
                        <td>审核中</td>
                    @elseif($house->status == 1)
                        <td>已上架</td>
                    @elseif($house->status == 2)
                        <td>已下架</td>
                    @endif
                    <td>
                        @if($house->status == 1)
                            <button class="btn btn-warning btn-xs"{{ $has_orders[$house->id] == 1 ? 'disabled="disabled"' : "" }} onclick="updateHouseStatus({{ $house->id }}, 2)">下架</button>
                            <br>
                        @elseif($house->status == 2)
                            <button class="btn btn-success btn-xs" onclick="updateHouseStatus({{ $house->id }}, 1)">上架</button>
                            <br>
                        @endif
                        <button type="button" class="btn btn-info btn-xs" onclick="showEditHouse({{ $house->id }})">修改</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="col-sm-10 col-sm-offset-1">
            {{ $houses->links() }}
        </div>
    </div>
    <div class="modal fade show-house-info" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">详细信息</h4>
                </div>
                <div class="modal-body">
                    <p><b>名称：</b>@{{ name }}</p>
                    <p><b>所在城市：</b>@{{ city }}</p>
                    <p><b>地址：</b>@{{ address }}</p>
                    <p><b>价格：</b>@{{ price }}</p>
                    <p><b>押金：</b>@{{ deposit }}</p>
                    <p><b>最大宜居人数：</b>@{{ max_people }}</p>
                    <p><b>同类型房源数量：</b>@{{ num }}</p>
                    <p><b>户型：</b>@{{ house_type }}</p>
                    <p><b>出租类型：</b>@{{ rent_type }}</p>
                    <p><b>床铺类型：</b>@{{ bed_type }}</p>
                    <p><b>被单更换：</b>@{{ change_bed }}</p>
                    <p><b>配套设施：</b>@{{ supporting_facilities }}</p>
                    <p><b>个性描述：</b>@{{ desc }}</p>
                    <p><b>内部设施：</b>@{{ internal_situation }}</p>
                    <p><b>交通设施：</b>@{{ traffic_condition }}</p>
                    <p><b>周边情况：</b>@{{ peripheral_condition }}</p>
                    <p><b>煮饭：</b>@{{ cook_fee }}</p>
                    <p><b>清洁费：</b>@{{ clean_fee }}</p>
                    <p><b>其他费用：</b>@{{ other_fee }}</p>
                    <p><b>状态：</b>@{{ status }}</p>
                    <p v-if="status == '审核未通过'"><b>原因：</b>@{{ reason }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-edit-house" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">编辑</h4>
                </div>
                <div class="modal-body">
                    <form id="form-edit-house" class="form-horizontal">
                        <input type="hidden" name="id" v-model="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">名称</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" name="name" v-model="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-sm-3 control-label">价格</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="price" name="price" v-model="price">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="deposit" class="col-sm-3 control-label">押金</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="deposit" name="deposit" v-model="deposit">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cook_fee" class="col-sm-3 control-label">煮饭</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="cook_fee" name="cook_fee" v-model="cook_fee">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="clean_fee" class="col-sm-3 control-label">清洁费</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="clean_fee" name="clean_fee" v-model="clean_fee">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="other_fee" class="col-sm-3 control-label">其他费用</label>
                            <div class="col-sm-8">
                                <textarea name="other_fee" id="other_fee" v-model="other_fee" cols="30" rows="1" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_people" class="col-sm-3 control-label">最大宜居人数</label>
                            <div class="col-sm-4">
                                <select name="max_people" v-model="max_people" id="max_people" class="form-control">
                                    <option v-for="n in 10" v-bind:selected="n == max_people ? 'selected' : 'false'" v-bind:value="n">@{{ n }}人</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="change_bed" class="col-sm-3 control-label">被单更换</label>
                            <div class="col-sm-4">
                                <select name="change_bed" v-model="change_bed" id="change_bed" class="form-control">
                                    <option value="1" v-bind:selected="change_bed == 1 ? 'selected': 'false'">每日一换</option>
                                    <option value="2" v-bind:selected="change_bed == 2 ? 'selected': 'false'">每客一换</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="desc" class="col-sm-3 control-label">个性描述</label>
                            <div class="col-sm-8">
                                <textarea name="desc" id="desc" v-model="desc" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="internal_situation" class="col-sm-3 control-label">内部设施</label>
                            <div class="col-sm-8">
                                <textarea name="internal_situation" id="internal_situation" v-model="internal_situation" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="traffic_condition" class="col-sm-3 control-label">交通情况</label>
                            <div class="col-sm-8">
                                <textarea name="traffic_condition" id="traffic_condition" v-model="traffic_condition" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="peripheral_condition" class="col-sm-3 control-label">周边设施</label>
                            <div class="col-sm-8">
                                <textarea name="peripheral_condition" id="peripheral_condition" v-model="peripheral_condition" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" v-on:click="editHouse()" class="btn btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var support_facilities = [
            "网络", "空调", "热水淋浴",
            "电视", "电梯", "洗衣机",
            "停车位", "饮水设备", "暖气",
            "有线网络", "拖鞋", "手纸",
            "牙具", "毛巾", "浴液 洗发水",
            "香皂", "允许做饭", "门禁系统",
            "浴缸", "允许吸烟", "允许聚会",
            "允许带动物"
        ];
        var editHouse = new Vue({
            el: ".modal-edit-house",
            data: {
                id: "",
                name: "",
                price: "",
                max_people: 1,
                deposit: "",
                change_bed: 1,
                desc: "",
                internal_situation: "",
                traffic_condition: "",
                peripheral_condition: "",
                cook_fee: "",
                clean_fee: "",
                other_fee: ""
            },
            methods: {
                editHouse: function () {
                    $.ajax({
                        url: "{{ url("fangdong/edithouse") }}",
                        type: "post",
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        data: $("#form-edit-house").serialize(),
                        success:function (data) {
                            if (data == '修改成功'){
                                window.location.reload();

                            }
                        }
                    })
                }
            }
        });
        var houseInfo = new Vue({
            el: ".show-house-info",
            data: {
                name: "",
                city: "",
                address: '',
                price: '',
                deposit: '',
                max_people:'',
                num: '',
                house_type: '',
                rent_type: '',
                bed_type: '',
                change_bed: '',
                supporting_facilities: '',
                desc: '',
                internal_situation: '',
                traffic_condition: '',
                peripheral_condition: '',
                cook_fee: '',
                clean_fee: '',
                other_fee: '',
                status: '',
                reason: ''
            }
        });
        function updateHouseStatus(id, status) {
            $.ajax({
                url: "{{ url("fangdong/edithouse") }}",
                type: "post",
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                data: {'id': id, 'status': status},
                success:function (data) {
                    if (data == '修改成功'){
                        window.location.reload();

                    }
                }
            })
        }

        function showEditHouse(id) {
            $.ajax({
                url: "{{ url("fangdong/showedithouse") }}",
                type: "post",
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                data: {'id': id},
                success: function (data) {
                    $(".modal-edit-house").modal();
                    data = $.parseJSON(data);
                    editHouse.id = data.id;
                    editHouse.name = data.name;
                    editHouse.price = data.price;
                    editHouse.max_people = data.max_people;
                    editHouse.deposit = data.deposit;
                    editHouse.change_bed = data.change_bed;
                    editHouse.desc = data.desc;
                    editHouse.internal_situation = data.internal_situation;
                    editHouse.traffic_condition = data.traffic_condition;
                    editHouse.peripheral_condition = data.peripheral_condition;
                    editHouse.cook_fee = data.cook_fee;
                    editHouse.clean_fee = data.clean_fee;
                    editHouse.other_fee = data.other_fee;

                }
            })
        }
        function showHouse(id) {
            $.ajax({
                url: "{{ url("fangdong/gethouse") }}",
                type: "post",
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                data: {'id': id},
                success: function (data) {
                    $(".show-house-info").modal();
                    data = $.parseJSON(data);
                    houseInfo.name = data.name;
                    houseInfo.city = data.city;
                    houseInfo.address = data.province + data.city + data.area + data.address + data.detail;
                    houseInfo.price = "￥" + data.price + ".00";
                    houseInfo.deposit = "￥" + data.deposit + ".00";
                    houseInfo.max_people = data.max_people + "人";
                    houseInfo.num = data.sum;
                    var house_type = data.house_type_detail.split(',');
                    houseInfo.house_type = house_type[0] + "室" + house_type[1] + "厅" + house_type[2] + "厨" + house_type[3] + "卫" + house_type[4] + "阳台";
                    switch (data.rent_type)
                    {
                        case 1:
                            houseInfo.rent_type = "整套出租";
                            break;
                        case 2:
                            houseInfo.rent_type = "单间出租";
                            break;
                        case 3:
                            houseInfo.rent_type = "床位出租";
                            break;
                        case 4:
                            houseInfo.rent_type = "沙发出租";
                            break;
                    }
                    var bedInfo = data.bed_type.split(",");
                    houseInfo.bed_type = "";
                    $.each(bedInfo ,function (index, data) {
                        var bed = data.split(":");
                        switch (bed[0]) {
                            case '1':
                                houseInfo.bed_type += "单人床:";
                                break;
                            case '2':
                                houseInfo.bed_type += "双人床:";
                                break;
                            case '3':
                                houseInfo.bed_type += "沙发:";
                                break;
                            case '4':
                                houseInfo.bed_type += "双层床:";
                                break;
                            case '5':
                                houseInfo.bed_type += "榻榻米:";
                                break;
                            case '6':
                                houseInfo.bed_type += "其他:";
                                break;
                        }
                        houseInfo.bed_type += bed[1] + "m×" + bed[2] + "m×" + bed[3] + "张 ";
                    });
                    houseInfo.change_bed = data.change_bed == '1' ? '每日一换' : '每客一换';
                    var supporting_facility = data.supporting_facilities.split(',');
                    houseInfo.supporting_facility = "";
                    $.each(supporting_facility, function (index, item) {
                        item = parseInt(item);
                        houseInfo.supporting_facilities += support_facilities[item] + " ";
                    });
                    houseInfo.desc = data.desc;
                    houseInfo.internal_situation = data.internal_situation;
                    houseInfo.traffic_condition = data.traffic_condition;
                    houseInfo.peripheral_condition = data.peripheral_condition;
                    houseInfo.cook_fee = "￥" + data.cook_fee + ".00";
                    houseInfo.clean_fee = "￥" + data.clean_fee + ".00";
                    houseInfo.other_fee = data.other_fee;
                    console.log(data.status);
                    switch (data.status) {
                        case -1:
                            houseInfo.status = "审核未通过";
                            houseInfo.reason = data.reason;
                            break;
                        case 0:
                            houseInfo.status = "审核中";
                            break;
                        case 1:
                            houseInfo.status = '上架中';
                            break;
                        case 2:
                            houseInfo.status = '已下架';
                            break;
                    }
                }
            })
        }
    </script>
@endsection