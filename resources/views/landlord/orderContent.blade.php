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
        <td>
            <a href="javascript:void(0)" data-toggle="modal" data-target=".order-detail-{{ $orderStatType }}-{{ $order->id }}">{{ $order->name }}</a>
            <div class="modal fade order-detail-{{ $orderStatType }}-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">订单详情</h4>
                        </div>
                        <div class="modal-body">
                            <h5>订单信息</h5>
                            <p><b>房源名称：</b>{{ $order->name }}</p>
                            <p><b>数量：</b>{{ $order->number }}</p>
                            <p><b>单价：</b>{{ $order->price }}</p>
                            <p><b>开始时间：</b>{{ $order->startdate }}</p>
                            <p><b>结束时间：</b>{{ $order->enddate }}</p>
                            <p><b>总天数：</b>{{ $order->sum_day }}</p>
                            <p><b>总金额：</b>{{ $order->sum_price }}</p>
                            <p><b>总金额：</b>{{ $order->sum_price }}</p>
                            <p><b>订单创建时间：</b>{{ $order->created_at }}</p>
                            <p>
                                <b>订单状态：</b>
                                @if($order->status == 1)
                                等待确认...
                                @elseif($order->status == 2)
                                等待入住...
                                @elseif($order->status == 3)
                                订单进行中...
                                @elseif($order->status == 4)
                                订单完成
                                @else
                                订单已取消
                                @endif
                            </p>
                            @if($order->status == 5)
                            <p><b>取消理由：</b>{{ $order->reason }}</p>
                            @else
                            <h5>房客信息</h5>
                            <p><b>房客用户名：</b>{{ $order->nickname }}</p>
                            <p><b>联系人姓名：</b>{{ $order->order_owner }}</p>
                            <p><b>手机号码：</b>{{ $order->owner_phone }}</p>
                            <p><b>入住人数：</b>{{ $order->sum_people }}</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td>{{ $order->number }}</td>
        <td>{{ date("Y-m-d", strtotime($order->startdate)) }}</td>
        <td>{{ $order->sum_day }}</td>
        <td>{{ $order->sum_price }}</td>
        <td>{{ $order->created_at }}</td>
        @if ($order->status == 1)
        <td>等待确认...</td>
        <td>
            <button class="btn btn-sm btn-success" onclick="confirmOrder({{ $order->id }})">确认订单</button>
            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target=".cancel-{{ $orderStatType }}-{{ $order->id }}">拒绝接单</button>
            <div class="modal fade cancel-{{ $orderStatType }}-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">拒绝理由</h4>
                        </div>
                        <div class="modal-body">
                            <form id="form-cancel{{ $order->id }}">
                                <div class="form-group">
                                    <label for="cancel_reason">请输入拒绝理由</label>
                                    <textarea class="form-control" name="cancel_reason" id="cancel_reason" cols="30" rows="10" placeholder="请输入拒绝理由"></textarea>
                                    <span id="cancel_error{{ $order->id }}" class="help-block"></span>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" onclick="cancelOrder({{ $order->id }})" class="btn btn-primary">确认</button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
        @elseif($order->status == 2)
        <td>待入住</td>
        <td>
            <button class="btn btn-sm btn-success" onclick="checkIn({{ $order->id }})">房客入住</button>
        </td>
        @elseif($order->status == 3)
        <td>房客入住中</td>
        <td>
            <button class="btn btn-sm btn-success" onclick="finishOrder({{ $order->id }})">完成订单</button>
        </td>
        @elseif($order->status == 4)
        <td>订单完成</td>
        <td>
            @if($order->comment_status == 0)
            <p>未评价</p>
            @elseif($order->comment_status == 1)
            <p>房客已评价</p>
            <button class="btn btn-sm" onclick="showComment({{ $order->id }})">查看并回复</button>
            @else
            <p>已评价</p>
            @endif
        </td>
        @elseif($order->status == 5)
        <td>已取消</td>
        <td><button class="btn btn-sm" data-toggle="modal" data-target=".show-reason-{{ $orderStatType }}-{{ $order->id }}">查看原因</button></td>
        <div class="modal fade show-reason-{{ $orderStatType }}-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">拒绝理由</h4>
                    </div>
                    <div class="modal-body">
                        <p><b>拒绝理由：</b>{{ $order->reason }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </tr>
    @endforeach
    </tbody>
</table>
{{ $orders->appends(['orderStatType' => $orderStatType])->links() }}