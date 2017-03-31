<table class="table table-hover">
    <thead>
    <tr>
        <td>名称</td>
        <td>类型</td>
        <td>点评时间</td>
        <td>用户名</td>
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
            <td>{{ $comment->nickname }}</td>
            <td>{{ $comment->startdate }}</td>
            <td>
                @if($comment->landlord_status == 1)
                    已回复
                @else
                    未回复
                @endif
            </td>
            <td>
                @if($comment->landlord_status == 0)
                    <button onclick="showComment({{ $comment->order_id }})" class="btn btn-success btn-sm">查看并回复</button>
                @endif
                <p><a href="javascript:void(0)" data-toggle="modal" data-target=".show-comment-detail-{{$commentType}}-{{ $comment->id }}">查看点评</a></p>
                <div class="modal fade show-comment-detail-{{$commentType}}-{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">查看点评</h4>
                            </div>
                            <div class="modal-body">
                                <p><b>名称：</b>{{ $comment->name }}</p>
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
{{ $comments->appends(['commentType' => $commentType])->links() }}