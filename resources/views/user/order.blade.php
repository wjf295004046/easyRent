@extends('user.nav')

@section('main-content')
    <style>
        #order-info { background-color: white; min-height: 300px;}
        .info { margin-left: 10px;position: relative; margin-bottom: 20px;border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: #fff; padding: 10px 10px; font-size: 12px;}
        /*.info p { margin-top: 30px; margin-bottom: 30px; font-size: 14px;}*/
        /*.info>h4 { color:#30c3a6;}*/
    </style>
<div class="row info" id="order-info">
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
                    <a href="{{ url("/house/" . $order->house_id) }}">{{ $order->name }}</a>
                </td>
                <td>{{ $order->number }}</td>
                <td>{{ date("Y-m-d", strtotime($order->startdate)) }}</td>
                <td>{{ $order->sum_day }}</td>
                <td>{{ $order->sum_price }}</td>
                <td>{{ $order->created_at }}</td>
                @if ($order->status == 1)
                    <td>等待确认...</td>
                    <td>
                        <button class="btn btn-xs btn-warning" data-toggle="modal" data-target=".cancel{{ $order->id }}">取消订单</button>
                        <div class="modal fade cancel{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
                        <button class="btn btn-xs btn-warning" data-toggle="modal" data-target=".cancel{{ $order->id }}">取消订单</button>
                        <div class="modal fade cancel{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
                @elseif($order->status == 3)
                    <td>房客入住中</td>
                    <td>
                    </td>
                @elseif($order->status == 4)
                    <td>订单完成</td>
                    <td>
                        @if($order->comment_status == 0)
                            <button class="btn btn-xs btn-success" data-toggle="modal" data-target=".comment-order{{ $order->id }}">点评</button>
                            <div class="modal fade comment-order{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                <div class="modal-dialog modal-sm" role="document">
                                    <div class="modal-content">
                                        <form class="form-horizontal" action="{{ url('/home/savecomment') }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">点评</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">评价</label>
                                                    <div class="col-sm-9">
                                                        <label class="radio-inline">
                                                            <input type="radio" checked name="comment_type" value="1"> 好评
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="comment_type" value="2"> 中评
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="comment_type" value="3"> 差评
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="comment{{ $order->id }}" class="col-sm-3 control-label">评价</label>
                                                    <div class="col-sm-8">
                                                        <textarea name="comment" id="comment{{ $order->id }}" class="form-control" cols="30" rows="3" placeholder="请输入评价"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                                <button type="submit" class="btn btn-primary">修改</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->comment_status == 1)
                            <p>已评价</p>
                        @else
                            <p>已评价</p>
                            <button class="btn btn-xs btn-info" onclick="showComment({{ $order->id }})">查看回复</button>
                        @endif
                    </td>
                @elseif($order->status == 5)
                    <td>已取消</td>
                    <td><button class="btn btn-xs btn-danger" data-toggle="modal" data-target=".show-reason{{ $order->id }}">查看原因</button></td>
                    <div class="modal fade show-reason{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
    {{ $orders->links() }}
</div>
    <div class="modal fade show-comment" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">评论</h4>
                </div>
                <div class="modal-body">
                    <p><b>评价：</b>@{{ comment_type }}</p>
                    <p><b>评价内容：</b>@{{ user_comment }}</p>
                    <p><b>房东回复:</b>@{{ reply }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        var comment = new Vue({
            el: ".show-comment",
            data: {
                user_comment: '',
                comment_type: '',
                reply: '',
            }
        });
        function showComment(id) {
            $.ajax({
                url: "{{ url('/home/showcomment') }}",
                data:{'id': id},
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    data = $.parseJSON(data);
                    comment.user_comment = data.user_comment;
                    comment.comment_type = data.comment_type;
                    comment.reply = data.reply;
                    $(".show-comment").modal();
                }
            })
        }
        function cancelOrder(id) {
            if ($("#cancel_reason").val() == '')
            {
                $("#form-cancel" + id).addClass("has-error");
                $("#cancel_error" + id).html("拒绝理由不能为空");
            }
            else {
                $("#form-cancel" + id).removeClass("has-error");
                $("#cancel_error" + id).html("");
                $.ajax({
                    url: "{{ url('fangdong/cancelorder') }}",
                    data:{'id': id, 'reason': $("#cancel_reason").val()},
                    type: 'post',
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    success: function (data) {
                        if (data == '订单已取消')
                            window.location.reload();
                    }
                })
            }
        }
    </script>
@endsection