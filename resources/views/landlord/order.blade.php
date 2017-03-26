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
            <li class="active">交易</li>
            <li class="active">订单管理</li>
        </ol>
    </div>
    <div class="row" id="main-content">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#all-order" onclick="changeContent('all-order')" aria-controls="home" role="tab" data-toggle="tab">全部订单</a></li>
            <li role="presentation"><a href="#wait" onclick="changeContent('wait')" aria-controls="profile" role="tab" data-toggle="tab">待确认</a></li>
            <li role="presentation"><a href="#waitcheckin" onclick="changeContent('waitcheckin')" aria-controls="messages" role="tab" data-toggle="tab">待入住</a></li>
            <li role="presentation"><a href="#checkin" onclick="changeContent('checkin')" aria-controls="messages" role="tab" data-toggle="tab">入住中</a></li>
            <li role="presentation"><a href="#finish" onclick="changeContent('finish')" aria-controls="settings" role="tab" data-toggle="tab">已完成</a></li>
            <li role="presentation"><a href="#cancel" onclick="changeContent('cancel')" aria-controls="settings" role="tab" data-toggle="tab">已取消</a></li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="all-order">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td>名称</td>
                        <td>数量</td>
                        <td>开始时间</td>
                        <td>天数</td>
                        <td>总价</td>
                        <td>创建时间</td>
                        <td>交易状态</td>
                        <td>交易操作</td>
                    </tr>
                    </thead>
                    <tbody class="order-info">
                        @foreach($orders as $order)
                            <tr>
                                <td><a href="{{ url("house/".$order->house_id) }}">{{ $order->name }}</a></td>
                                <td>{{ $order->number }}</td>
                                <td>{{ date("Y-m-d", strtotime($order->startdate)) }}</td>
                                <td>{{ $order->sum_day }}</td>
                                <td>{{ $order->sum_price }}</td>
                                <td>{{ $order->created_at }}</td>
                                @if ($order->status == 1)
                                    <td>等待确认...</td>
                                    <td>
                                        <button class="btn btn-sm btn-success">确认订单</button>
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target=".cancel{{ $order->id }}">拒绝接单</button>
                                        <div class="modal fade cancel{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    ...
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @elseif($order->status == 2)
                                    <td>待入住</td>
                                    <td>
                                        <button class="btn btn-sm btn-success">房客入住</button>
                                    </td>
                                @elseif($order->status == 3)
                                    <td>房客入住中</td>
                                    <td>
                                        <button class="btn btn-sm btn-success">完成订单</button>
                                    </td>
                                @elseif($order->status == 4)
                                    <td>订单完成</td>
                                    <td>
                                        @if($order->comment_status == 0)
                                            <p>未评价</p>
                                        @elseif($order->comment_status == 1)
                                            <p>房客已评价</p>
                                            <button class="btn btn-sm">查看并回复</button>
                                        @else
                                            <p>已评价</p>
                                        @endif
                                    </td>
                                @elseif($order->status == 5)
                                    <td>已取消</td>
                                    <td><button class="btn btn-sm">查看原因</button></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->appends(['orderStatType' => $orderStatType])->links() }}
            </div>
            <div role="tabpanel" class="tab-pane" id="wait">...</div>
            <div role="tabpanel" class="tab-pane" id="waitcheckin">...</div>
            <div role="tabpanel" class="tab-pane" id="finish">...</div>
            <div role="tabpanel" class="tab-pane" id="cancel">...</div>
        </div>
    </div>
    <script>
        function changeContent(id) {
            $("#" + id).html("test");
        }
    </script>
@endsection