@extends('landlord.nav')

@section('main-content')
    <style>
        #main-content,#manage-info {background: #fff; margin-bottom: 20px;}
        table { margin-top: 10px;}
        /*.order-info td {*/
            /*text-overflow:ellipsis;*/
            /*white-space:nowrap;*/
            /*overflow:hidden;*/
            /*max-width: 20%;*/
        /*}*/
    </style>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ url('/fangdong') }}">我是房东</a></li>
            <li class="active">点评</li>
            <li class="active">我的点评</li>
        </ol>
    </div>
    <div class="row" id="main-content">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" id="li-all"><a href="#all" onclick="changeContent('all')" aria-controls="home" role="tab" data-toggle="tab">全部评价</a></li>
            <li role="presentation" id="li-good"><a href="#good" onclick="changeContent('good')" aria-controls="profile" role="tab" data-toggle="tab">好评</a></li>
            <li role="presentation" id="li-mid"><a href="#mid" onclick="changeContent('mid')" aria-controls="messages" role="tab" data-toggle="tab">中评</a></li>
            <li role="presentation" id="li-bad"><a href="#bad" onclick="changeContent('bad')" aria-controls="messages" role="tab" data-toggle="tab">差评</a></li>
            <li role="presentation" id="li-to-roomer"><a href="#to-roomer" onclick="changeContent('to-roomer')" aria-controls="settings" role="tab" data-toggle="tab">未评论</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane" id="all">
                @if($commentType == 'all')
                    @include('landlord.commentContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="good">
                @if($commentType == 'good')
                    @include('landlord.commentContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="mid">
                @if($commentType == 'mid')
                    @include('landlord.commentContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="bad">
                @if($commentType == 'bad')
                    @include('landlord.commentContent')
                @endif
            </div>
            <div role="tabpanel" class="tab-pane" id="to-roomer">
                @if($commentType == 'to-roomer')
                    @include('landlord.commentContent')
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
        $("#{{ $commentType }}").addClass("active");
        $("#li-{{ $commentType }}").addClass("active");
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
        function changeContent(id) {
            if ($("#"+id).children().length == 0)
                $.ajax({
                    url: "{{ url('fangdong/ajaxgetcomment') }}",
                    data:{'commentType': id},
                    type: 'post',
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    success: function (data) {
                        $("#"+id).html(data);
                    }
                })
        }
    </script>
@endsection