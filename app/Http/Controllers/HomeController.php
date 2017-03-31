<?php

namespace App\Http\Controllers;

use App\Models\House\Address;
use App\Models\House\Comment;
use App\Models\House\House;
use App\Models\House\Liver;
use App\Models\Order;
use App\Models\User;
use App\Models\SMS;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userInfo = User::find(Auth::user()->id);
        $userDetail = UserDetail::where("user_id", Auth::user()->id)->first();
        return view('user.userinfo', [
            'type' => 'userinfo',
            'user' => $userInfo,
            'user_detail' => $userDetail,
        ]);
    }
    public function modifyPwd() {
        return view('user.modifypwd', [
            'type' => 'modifypwd',
        ]);
    }
    public function orderManage(Request $request) {
        $orders = Order::select("orders.*", "houses.name")
            ->where('orders.user_id', Auth::user()->id)
            ->join("houses", "houses.id", "orders.house_id")
            ->orderBy("created_at", "desc")
            ->paginate(10);

        return view('user.order',[
            'type' => 'order',
            'orders' => $orders,
        ]);

    }
    public function commentManage(Request $request) {
        $comments = Comment::select('comments.*', 'houses.name', 'orders.startdate')
            ->where("comments.user_id", Auth::user()->id)
            ->where("comments.user_status", 1)
            ->join("houses", "houses.id", "comments.house_id")
            ->join("orders", "orders.id", "comments.order_id")
            ->orderBy("updated_at", "desc")
            ->paginate(10);
        return view("user.comment", [
            'type' => 'comment',
            'comments' => $comments
        ]);
    }
    public function liverManage(Request $request) {
        $livers = Liver::where('user_id', Auth::user()->id)->orderBy('created_at')->paginate(15);
        return view('user.liver', [
            'type' => 'liver',
            'livers' => $livers
        ]);
    }

    public function saveComment(Request $request) {
        $data = $request->only('comment_type', 'comment');
        $data['comment_time'] = date("Y-m-d H:i:s");
        if ($data['comment'] != '')
            $data['user_status'] = 1;
        $id = $request->input('order_id');
        $comment = Comment::where("order_id", $id)->update($data);
        $order = Order::find($id);
        $order->comment_status = 1;
        $order->save();
        $house = House::find($order->house_id);
        $house->comment_num += 1;
        $house->save();
        return redirect()->back();
    }
    public function showComment(Request $request) {
        $id = $request->input('id');
        $comment = Comment::where('order_id', $id)->first();
        $data = ['user_comment' => $comment->comment, 'reply' => $comment->reply];
        if ($comment->comment_type == 1)
            $data['comment_type'] = '好评';
        elseif ($comment->comment_type == 2)
            $data['comment_type'] = '中评';
        else
            $data['comment_type'] = '差评';
        echo json_encode($data);
    }
    public function deleteLiver(Request $request) {
        $id = $request->input('id');
        Liver::where('id', $id)->delete();
        echo "删除成功";
    }
    public function saveEditLiver(Request $request) {
        $data = $request->only("name", "phone");
        if ($data['name'] == '')
            return json_encode(['code' => 1, 'msg' => '姓名不能为空']);
        if ($data['phone'] == '' || strlen($data['phone']) != 11)
            return json_encode(['code' => 2, 'msg' => '手机号码格式错误']);
        $liver = Liver::find($request->input('id'));
        $liver->name = $data['name'];
        $liver->phone = $data['phone'];
        $liver->save();
        return json_encode(['code' => 0, 'name' => $liver->name, 'phone' => $liver->phone]);
    }
    public function saveLiver(Request $request) {
        $data = $request->all();
        if ($data['name'] == '')
            return json_encode(['code' => 1, 'msg' => '姓名不能为空']);
        if ($data['idcard'] == '' || strlen($data['idcard']) != 18)
            return json_encode(['code' => 3, 'msg' => '身份证号码格式错误']);
        if ($data['phone'] == '' || strlen($data['phone']) != 11)
            return json_encode(['code' => 2, 'msg' => '手机号码格式错误']);
        $liver = new Liver();
        $liver->user_id = Auth::user()->id;
        $liver->name = $data['name'];
        $liver->idcard = $data['idcard'];
        $liver->phone = $data['phone'];
        $liver->save();
        return json_encode(['code' => 0,
            'id' => $liver->id,
            'name' => $liver->name,
            'idcard' => substr($liver->idcard, 0, 4) . "******" . substr($liver->idcard, 15),
            'phone' => $liver->phone]);
    }
}
