<?php

namespace App\Http\Controllers\Admin;

use Cache, Event;
use App\Events\permChangeEvent;
use App\Models\House\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index(Request $request) {
        $comments = Comment::select("comments.*", "users.name")
            ->whereIn('comments.comment_type', [2, 3])
            ->where('user_status', 1)
            ->join("users", "comments.user_id", "users.id")
            ->orderBy('comment_type', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        return view('admin.comment.index', [
            'comments' => $comments
        ]);
    }
    public function show(Request $request, $id) {
        $commentInfo = Comment::select("comments.*", "landlord.name as landlord_name", "landlord.phone as landlord_phone", "users.name as user_name", "users.phone as user_phone")
            ->join("users", "comments.user_id", "users.id")
            ->join("users as landlord", "comments.landlord_id", "landlord.id")
            ->first();
        echo $commentInfo;
    }
    public function delete(Request $request) {
        $id = $request->input('id');
        Comment::where("id", $id)->update([
            'user_status' => 0,
        ]);
        $comment = Comment::select("comments.*", "users.name")
            ->where("comments.id", $id)
            ->join("users", "users.id", "comments.user_id")
            ->first();
        $comment_type = $comment->comment_type == 2 ? "中评" : "差评";
        Event::fire(new permChangeEvent());
        event(new \App\Events\userActionEvent('\App\Models\House\Comment', $id, 2, '删除了用户' . $comment->name . "的" . $comment_type . ':' . $comment->comment ));

        return redirect()->back()->with('success', '删除成功');
    }
}
