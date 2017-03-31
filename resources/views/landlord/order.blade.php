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
            <li role="presentation" id="li-all"><a href="#all-order" onclick="changeContent('all-order')" aria-controls="home" role="tab" data-toggle="tab">全部订单</a></li>
            <li role="presentation" id="li-wait"><a href="#wait" onclick="changeContent('wait')" aria-controls="profile" role="tab" data-toggle="tab">待确认</a></li>
            <li role="presentation" id="li-waitcheckin"><a href="#waitcheckin" onclick="changeContent('waitcheckin')" aria-controls="messages" role="tab" data-toggle="tab">待入住</a></li>
            <li role="presentation" id="li-checkin"><a href="#checkin" onclick="changeContent('checkin')" aria-controls="messages" role="tab" data-toggle="tab">入住中</a></li>
            <li role="presentation" id="li-finish"><a href="#finish" onclick="changeContent('finish')" aria-controls="settings" role="tab" data-toggle="tab">已完成</a></li>
            <li role="presentation" id="li-cancel"><a href="#cancel" onclick="changeContent('cancel')" aria-controls="settings" role="tab" data-toggle="tab">已取消</a></li>

        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="all-order">
                @if($orderStatType == 'all')
                    @include('landlord.orderContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="wait">
                @if($orderStatType == 'wait')
                    @include('landlord.orderContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="waitcheckin">
                @if($orderStatType == 'waitcheckin')
                    @include('landlord.orderContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="checkin">
                @if($orderStatType == 'checkin')
                    @include('landlord.orderContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="finish">
                @if($orderStatType == 'finish')
                    @include('landlord.orderContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="cancel">
                @if($orderStatType == 'cancel')
                    @include('landlord.orderContent')
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade show-comment" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">查看点评</h4>
                </div>
                <div class="modal-body">
                    <form id="form-reply">
                        <p><b>评价：</b>@{{ comment_type }}</p>
                        <p><b>评价内容：</b>@{{ user_comment }}</p>
                        <input type="hidden" name="order_id" v-model="id">
                        <div class="form-group">
                            <label for="reply">回复：</label>
                            <textarea name="reply" id="reply" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" onclick="replyComment()" class="btn btn-primary">回复</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var comment = new Vue({
            el: ".show-comment",
            data: {
                id: '',
                user_comment: '',
                comment_type: '',
            }
        });
        @if($orderStatType == 'all')
        $("#all-order").addClass("active");
        @else
        $("#{{ $orderStatType }}").addClass("active");
        @endif
        $("#li-{{ $orderStatType }}").addClass("active");

        function replyComment() {
            $.ajax({
                url: "{{ url('fangdong/replycomment') }}",
                data: $("#form-reply").serialize(),
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    if (data == '回复成功')
                        window.location.reload();
                }
            })
        }
        function showComment(id) {
            $.ajax({
                url: "{{ url('fangdong/showcomment') }}",
                data:{'id': id},
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    data = $.parseJSON(data);
                    comment.id = id;
                    comment.user_comment = data.user_comment;
                    comment.comment_type = data.comment_type;
                    $(".show-comment").modal();
                }
            })
        }

        function finishOrder(id) {
            $.ajax({
                url: "{{ url('fangdong/finishorder') }}",
                data:{'id': id},
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    if (data == '订单已完成')
                        window.location.reload();
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
        function confirmOrder(id) {
            $.ajax({
                url: "{{ url('fangdong/confirmorder') }}",
                data:{'id': id},
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    if (data == '订单已确认')
                        window.location.reload();
                }
            })
        }
        function checkIn(id) {
            $.ajax({
                url: "{{ url('fangdong/checkin') }}",
                data:{'id': id},
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    if (data == '房客已入住')
                        window.location.reload();
                }
            })
        }
        function changeContent(id) {
            if ($("#"+id).children().length == 0)
                $.ajax({
                    url: "{{ url('fangdong/ajaxgetorder') }}",
                    data:{'orderStatType': id},
                    type: 'post',
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    success: function (data) {
                        $("#"+id).html(data);
                    }
                })
        }
    </script>
@endsection