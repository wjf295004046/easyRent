@extends('admin.layouts.base')

@section('title','中差评列表')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <style>
    </style>
    <div class="row page-title-row" id="dangqian" style="margin:5px;">

    </div>
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th data-sortable="false" class="hidden-sm"></th>
                            <th class="hidden-sm">房客用户名</th>
                            <th class="hidden-sm">评价类型</th>
                            <th style="max-width: 30%" class="hidden-sm">评价内容</th>
                            <th class="hidden-md">评价创建日期</th>
                            <th data-sortable="false">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($comments as $index => $comment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $comment->name }}</td>
                                <td>{{ $comment->comment_type == 2 ? '中评' : '差评' }}</td>
                                <td>{{ $comment->comment }}</td>
                                <td>{{ $comment->comment_time }}</td>
                                <td>
                                    <a style="margin:3px;"  href="#" onclick="showComment({{ $comment->id }})" class="X-Small btn-xs text-success "><i class="fa fa-adn"></i>查看</a>
                                    <a style="margin:3px;" href="#" onclick="deleteComment({{ $comment->id }})" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 删除</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-sm-offset-2">
            {{ $comments->links() }}
        </div>
    </div>
    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog modal-warning">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                    <h4 class="modal-title">提示</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        <i class="fa fa-question-circle fa-lg"></i>
                        确认要删除这个这个评论吗?
                    </p>
                </div>
                <div class="modal-footer">
                    <form class="deleteForm" method="POST" action="{{ url("/admin/comment/delete") }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="delete-comment-id" name="id" value="">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i> 确认
                        </button>
                    </form>
                </div>


            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-show" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">点评信息</h4>
                </div>
                <div class="modal-body">
                    <p><b>房客用户名：</b>@{{ user_name }}</p>
                    <p><b>房客手机号：</b><a v-bind:href="user_phone_href">@{{ user_phone }}</a></p>
                    <p><b>点评时间：</b>@{{ comment_time }}</p>
                    <p><b>评论：</b>@{{ comment_type }}</p>
                    <p><b>评论内容：</b>@{{ comment }}</p>
                    <p><b>房东用户名：</b>@{{ landlord_name }}</p>
                    <p><b>房东手机号：</b><a v-bind:href="landlord_phone_href">@{{ landlord_phone }}</a></p>
                    <p v-if="is_reply"><b>回复时间：</b>@{{ reply_time }}</p>
                    <p v-if="is_reply"><b>回复内容：</b>@{{ reply }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var comment = new Vue({
            el: "#modal-show",
            data: {
                user_name: '',
                user_phone_href: '',
                user_phone: '',
                comment_time: '',
                comment_type: '',
                comment: '',
                landlord_name: '',
                landlord_phone_href: '',
                landlord_phone: '',
                is_reply: false,
                reply_time: '',
                reply: ''
            }
        });
        
        function showComment(id) {
            $.ajax({
                url: "{{ url('/admin/comment') }}/" + id,
                data:{},
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    data = $.parseJSON(data);
                    comment.user_name = data.user_name;
                    comment.user_phone_href = "tel:" + data.user_phone;
                    comment.user_phone = data.user_phone;
                    comment.comment_time = data.comment_time;
                    comment.comment_type = data.comment_type == 2 ? '中评' : '差评';
                    comment.comment = data.comment;
                    comment.landlord_name = data.landlord_name;
                    comment.landlord_phone_href = "tel:" + data.landlord_phone;
                    comment.landlord_phone = data.landlord_phone;
                    if (data.landlord_status == 1) {
                        comment.is_reply = true;
                        comment.reply_time = data.reply_time;
                        comment.reply = data.reply;
                    }
                    else {
                        comment.is_reply = false;
                    }
                    $("#modal-show").modal();
                }
            })
        }

        function deleteComment(id) {
            $("#delete-comment-id").val(id);
            $("#modal-delete").modal();
        }
    </script>

@endsection