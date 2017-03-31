@extends('user.nav')

@section('main-content')
    <style>
        #comment-info { background-color: white; min-height: 300px;}
        .info { margin-left: 10px;position: relative; margin-bottom: 20px;border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: #fff; padding: 10px 10px; font-size: 12px;}
        /*.info p { margin-top: 30px; margin-bottom: 30px; font-size: 14px;}*/
        /*.info>h4 { color:#30c3a6;}*/
    </style>
    <div class="row info" id="comment-info">
        <table class="table table-hover">
            <thead>
            <tr>
                <td>名称</td>
                <td>类型</td>
                <td>点评时间</td>
                <td>入住时间</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->name }}</td>
                    <td>
                        @if($comment->comment_type == 1)
                            好评
                        @elseif($comment->comment_type == 2)
                            中评
                        @else
                            差评
                        @endif
                    </td>
                    <td>{{ $comment->comment_time }}</td>
                    <td>{{ $comment->startdate }}</td>
                    <td>
                        @if($comment->landlord_status == 1)
                            房东已回复
                        @else
                            房东未回复
                        @endif
                    </td>
                    <td>
                        <p><a href="javascript:void(0)" data-toggle="modal" data-target=".show-comment-detail{{ $comment->id }}">查看点评</a></p>
                        <div class="modal fade show-comment-detail{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">查看点评</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p><b>名称：</b>{{ $comment->name }}</p>
                                        <p><b>房客用户名：</b>{{ $comment->nickname }}</p>
                                        <p>
                                            <b>类型：</b>
                                            @if($comment->comment_type == 1)
                                                好评
                                            @elseif($comment->comment_type == 2)
                                                中评
                                            @else
                                                差评
                                            @endif
                                        </p>
                                        <p><b>点评时间：</b>{{ $comment->comment_time }}</p>
                                        <p><b>点评内容：</b>{{ $comment->comment }}</p>
                                        @if($comment->landlord_status == 1)
                                            <p><b>回复时间：</b>{{ $comment->reply_time }}</p>
                                            <p><b>回复内容：</b>{{ $comment->reply }}</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $comments->links() }}
    </div>

@endsection