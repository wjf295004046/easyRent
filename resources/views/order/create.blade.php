@extends("layouts.app")

@section('content')
    <style>
        .info { border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: whitesmoke; padding: 30px 30px;}
        .info p { margin-top: 20px; margin-bottom: 20px;}
        #liver-info,#user-info,#order-info { margin-top: 20px; margin-bottom: 10px}
        @media screen and (max-width: 768px) {
            #sum-price h3 {font-size: 18px;}
        }
    </style>
    <div class="container">
        <form action="{{ url("/orders") }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="house_id" value="{{ $house->id }}">
            <input type="hidden" name="num" value="{{ $request->input("num") }}">
            <input type="hidden" name="rent_days" value="{{ $rent_days }}">
            <input type="hidden" name="startdate" value="{{ $request->input("startdate") }}">
            <input type="hidden" name="enddate" value="{{ $request->input("enddate") }}">
            <div class="row" id="rent-info">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                    <h3>入住信息</h3>
                    <p>房源信息：<a href="{{ url("/house/" . $house->id) }}">{{ $house->house_name }}</a> - {{ $rent_type[$house->rent_type] }}</p>
                    <p>房东用户名：<a href="{{ url("/fangodng/" . $house->landlord_id) }}">{{ $house->name }}</a></p>
                    <p>入住时段： 入住 <b>{{ $request->input("startdate") }}</b> 14:00  退房 <b>{{ $request->input("enddate") }}</b> 12:00 共 <b>{{ $rent_days }}</b> 晚</p>
                    <p>预订数量：{{ $request->input("num") }}</p>
                </div>
            </div>
            <div class="row" id="liver-info">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                    <h3>入住人信息</h3>
                    <div class="panel panel-default" id="liver-detail">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span>选择入住人：</span>
                                @foreach($livers as $liver)
                                    <input onchange="showLiver({{ $liver->id }})" type="checkbox" value="{{ $liver->id }}" name="liver[{{ $liver->id }}][checked]" id="liver-select{{ $liver->id }}">
                                    <label for="liver-select{{ $liver->id }}">{{ $liver->name }}</label>
                                @endforeach
                            </h3>
                        </div>
                        @foreach($livers as $liver)
                            <div class="panel-body hidden" id="liver{{ $liver->id }}">
                                <div class="col-md-5 col-sm-5 col-xs-10 form-group">
                                    <label for="liver-name">*姓名</label>
                                    <input type="text" name="liver[{{ $liver->id }}][name]" class="form-control" v-model="name" id="liver-name{{ $liver->id }}">
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-10 form-group">
                                    <label for="liver-name">*身份证号码</label>
                                    <input type="text" name="liver[{{ $liver->id }}][idcard]" class="form-control" v-model="idcard" id="liver-idcard{{ $liver->id }}">
                                </div>
                                <div class="col-md-5 col-sm-6 col-xs-12 form-group">
                                    <label for="liver-phone">电话</label>
                                    <input type="text" name="liver[{{ $liver->id }}][phone]" class="form-control" v-model="phone" id="liver-phone{{ $liver->id }}">
                                </div>
                                <div class="col-md-2 col-md-offset-5 col-sm-3 col-sm-offset-1 col-xs-6 col-xs-offset-6">
                                    <a href="javascript:void(0)" onclick="deleteLiver({{ $liver->id }}, 'liver')">删除</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-3 col-md-offset-6 col-sm-4 col-sm-offset-4 col-xs-6 col-xs-offset-3">
                        <a href="javascript:void(0)" class="btn btn-success btn-lg" onclick="addLiver()">添加入住人</a>
                    </div>
                </div>
            </div>
            <div class="row" id="user-info">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                    <h3>预订人信息</h3>
                    <p>真实姓名：<input type="text" name="real_name" value="{{ $house->real_name ?? $house->name }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 手机号码：{{ $house->phone }}</p>
                </div>
            </div>
            <div class="row" id="order-info">
                <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 info">
                    <h3>订单费用信息</h3>
                    <table class="table table-hover">
                        <tr>
                            <td>名称</td>
                            <td>日均价</td>
                            <td>预订数量</td>
                            <td>天数</td>
                            <td>总价</td>
                        </tr>
                        <tr>
                            <td>1.房租</td>
                            <td>￥ {{ $house->price }}.00</td>
                            <td>{{ $request->input('num') }}</td>
                            <td>{{ $rent_days }}</td>
                            <td>{{ $house->price * $request->input('num') * $rent_days }}</td>
                        </tr>
                    </table>
                    <div class="row" id="sum-price">
                        <div class="col-md-6 col-md-offset-6 col-sm-7 col-sm-offset-5 col-xs-8 col-xs-offset-4">
                            <h3>应收金额 <span>￥ {{ $house->price * $request->input('num') * $rent_days }}.00</span></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-3">
                            <input type="submit" value="确认订单" class="btn btn-success">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        @foreach($livers as $liver)
        var liver{{ $liver->id }} = new Vue({
            el: '#liver{{ $liver->id }}',
            data: {
                name: '{{ $liver->name }}',
                idcard: '{{ $liver->idcard }}',
                phone: '{{ $liver->phone }}'
            }
        });
        @endforeach
        function showLiver(id) {
            if ($("#liver-select" + id).is(':checked'))
                $("#liver" + id).removeClass("hidden");
            else
                $("#liver" + id).addClass("hidden");
        }

        function deleteLiver(id, type) {
            if (type == 'liver') {
                $('#' + type + id).addClass("hidden");
                $('#' + type + "-select" + id).attr("checked", false);
            }
            else {
                $("#" + type + id).remove();
            }
        }
        var addCount = 1;
        function addLiver() {
            var html = '<div class="panel-body" id="newLiver'+addCount+'">\
                    <div class="col-md-5 col-sm-5 col-xs-10 form-group">\
                    <label for="liver-name">*姓名</label>\
                    <input type="text" name="new_liver['+addCount+'][name]" class="form-control" id="liver-name">\
                    </div>\
                    <div class="col-md-5 col-sm-5 col-xs-10 form-group">\
                    <label for="liver-name">*身份证号码</label>\
                    <input type="text" name="new_liver['+addCount+'][idcard]" class="form-control" id="liver-idcard">\
                    </div>\
                    <div class="col-md-5 col-sm-6 col-xs-12 form-group">\
                    <label for="liver-phone">电话</label>\
                    <input type="text" name="new_liver['+addCount+'][phone]" class="form-control" id="liver-phone">\
                    </div>\
                    <div class="col-md-2 col-md-offset-5 col-sm-3 col-sm-offset-1 col-xs-6 col-xs-offset-6">\
                    <a href="javascript:void(0)" onclick="deleteLiver('+addCount+', \'newLiver\')">删除</a>\
                    </div>\
                    </div>';
            addCount++;
            $("#liver-detail").append(html);
        }

    </script>
@endsection