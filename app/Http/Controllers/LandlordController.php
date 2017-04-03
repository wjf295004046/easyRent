<?php

namespace App\Http\Controllers;

use App\Models\House\Address;
use App\Models\House\Comment;
use App\Models\House\House;
use App\Models\Order;
use App\Models\SMS;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class LandlordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cert', ['except' => ['editUserInfo', 'editUser', 'doModifyPwd', 'cancelOrder']]);
    }
    public function index() {
        $landlord_id = Auth::user()->id;
        $userInfo = UserDetail::where("user_id", $landlord_id)->first();
        $wait = Order::where('landlord_id', $landlord_id)->where('status', 1)->count();
        $waitcheckin = Order::where('landlord_id', $landlord_id)->where('status', 2)->count();
        $checkin = Order::where('landlord_id', $landlord_id)->where('status', 3)->count();
        $cancel = Order::where('landlord_id', $landlord_id)->where('status', 5)->where('updated_at', '>=', date("Y-m-d H:i:s", strtotime("-1 day")))->count();
        $comments = [
            'need_user_comment' => Comment::where('landlord_id', $landlord_id)->where('comment_type', 0)->count(),
            'need_landlord_comment' => Comment::where('landlord_id', $landlord_id)->where('user_status', 1)->where('landlord_status', 0)->count(),
            'num_user_comment' => Comment::where('landlord_id', $landlord_id)->where('user_status', 1)->count(),
            'num_landlord_comment' => Comment::where('landlord_id', $landlord_id)->where('landlord_status', 1)->count()
        ];
        $sumDays = (time() - strtotime(Auth::user()->created_at)) / 86400;
        $stat = [
            'days' => round($sumDays),
            'orders' => Order::where('landlord_id', $landlord_id)->where('status', 4)->count(),
            'money' => Order::where('landlord_id', $landlord_id)->where('status', 4)->sum('sum_price'),
        ];
        return view('landlord.center',[
            'type' => 'index',
            'user' => $userInfo,
            'wait' => $wait,
            'waitcheckin' => $waitcheckin,
            'checkin' => $checkin,
            'cancel' => $cancel,
            'comment' => $comments,
            'stat' => $stat,
        ]);
    }
    public function orderManage(Request $request) {
        $orderStatType = $request->input("orderStatType");
        if (!$orderStatType)
            $orderStatType = 'all';
        $orderInfo = self::getOrderInfo($orderStatType);
        return view('landlord.order',[
            'type' => 'order',
            'orderStatType' => $orderStatType,
            'orders' => $orderInfo,
        ]);

    }
    public function commentManage(Request $request) {
        $commentType = $request->input('commentType');
        if (!$commentType)
            $commentType = 'all';
        $commentInfo = self::getCommentInfo($commentType);
        return view('landlord.comment',[
            'type' => 'comment',
            'commentType' => $commentType,
            'comments' => $commentInfo,
        ]);
    }
    public function houseList(Request $request) {
        $houseInfo = House::where("landlord_id", Auth::user()->id)->paginate(10);
        $has_orders = [];
        foreach ($houseInfo as $house)
        {
            $order = Order::where("house_id", $house->id)->where("status", ">", "0")->where("status", "<", "4")->first();
            if ($order)
                $has_orders[$house->id] = 1;
            else
                $has_orders[$house->id] = 0;
        }
        return view("landlord.house", [
            'type' => 'house',
            'houses' => $houseInfo,
            'has_orders' => $has_orders
        ]);
    }
    public function userInfo(Request $request){
        $userInfo = User::find(Auth::user()->id);
        $userDetail = UserDetail::where("user_id", Auth::user()->id)->first();
        return view('landlord.userinfo', [
            'type' => 'userinfo',
            'user' => $userInfo,
            'user_detail' => $userDetail,
        ]);
    }
    public function modifyPwd(Request $request) {
        return view('landlord.modifypwd',[
            'type' => 'modifypwd',
        ]);
    }
    public function addressManage(Request $request) {
        $addresses = Address::where('user_id', Auth::user()->id)->orderBy('created_at')->paginate(15);
        $used_arr = [];
        foreach ($addresses as $address) {
            $temp = House::where('address_id', $address->id)->first();
            if ($temp)
                $used_arr[$address->id] = 1;
            else
                $used_arr[$address->id] = 0;
        }
        return view('landlord.address', [
            'type' => 'address',
            'addresses' => $addresses,
            'used_arr' => $used_arr,
        ]);
    }

    public function deleteAddress(Request $request) {
        $id = $request->input("id");
        Address::where("id", $id)->delete();
        echo "删除成功";
    }
    public function addAddress(Request $request) {
        $info = $request->all();
        $address = new Address();
        $address->user_id = Auth::user()->id;
        $address->province = $info['province'];
        $address->city = $info['city'];
        $address->area = $info['district'];
        $address->address = $info['street'] . $info['address'];
        $address->detail = $info['detail'];
        $address->co_ordinates = $info['co_ordinates'];
        $address->save();
        echo $address;
    }
    public function doModifyPwd(Request $request) {
        $old_password = $request->input('old_password');
        if (!$old_password) {
            return redirect()->back()->with('error', '旧密码不能为空');
        }
        $real_password = User::where("id", Auth::user()->id)->first()->password;
        if (!Hash::check($old_password, $real_password)) {
            return redirect()->back()->with('error', '旧密码不正确');
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        else{
            $password = $request->input('password');
            User::where("id", Auth::user()->id)->update(["password" => bcrypt($password)]);
            Auth::guard()->logout();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect('/login');
        }

    }
    public function ajaxGetOrder(Request $request) {
        $orderStatType = $request->input("orderStatType");
        $orderInfo = self::getOrderInfo($orderStatType);
        $orderInfo->setPath('/fangdong/order');
        return view('landlord.orderContent', [
            'orderStatType' => $orderStatType,
            'orders' => $orderInfo,
        ]);
    }
    public function ajaxGetComment(Request $request) {
        $commentType = $request->input('commentType');
        $commentInfo = self::getCommentInfo($commentType);
        $commentInfo->setPath('/fangdong/comment');
        return view('landlord.commentContent',[
            'commentType' => $commentType,
            'comments' => $commentInfo,
        ]);
    }
    public function editUserInfo(Request $request) {
        $info = $request->all();
        if ($info['birth'] == '')
            unset($info['birth']);
        UserDetail::where("user_id", Auth::user()->id)->update($info);
    }
    public function editUser(Request $request) {
        $info = $request->all();
        if (isset($info['verify'])) {
            $user = User::where("phone", $info['phone'])->first();
            if ($user)
                return "号码已注册";
            $verify = SMS::where("mobile", $info['phone'])->where("created_at", ">=", time() - 1800)->where("status", 1)->where("type", 3)->orderBy('created_at', 'desc')->first();
            if (!$verify || $verify->verify != $info['verify'])
                return "验证码错误";
            $verify->status = 2;
            $verify->save();
            unset($info['verify']);
        }
        User::where("id", Auth::user()->id)->update($info);
        echo "修改成功";
    }

    public function confirmOrder(Request $request) {
        $id = $request->input('id');
        $orderInfo = Order::where('id', $id)->first();
        $orderInfo->status = 2;
        $orderInfo->save();
        $comment = Comment::create([
            'user_id' => $orderInfo->user_id,
            'house_id' => $orderInfo->house_id,
            'order_id' => $orderInfo->id,
            'landlord_id' => $orderInfo->landlord_id,
            'comment_type' => 0,
        ]);
        $sms_data = [
            'mobile' => User::where('id', $orderInfo->user_id)->first()->phone,
            'date' => date("Y-m-d", strtotime($orderInfo->startdate)),
        ];
        $oCommon = new CommonController();
        $oCommon->smsRemind($sms_data, 2);
        echo "订单已确认";
    }
    public function cancelOrder(Request $request) {
        $id = $request->input('id');
        $reason = $request->input('reason');
        $orderInfo = Order::where('id', $id)->first();
        $orderInfo->status = 5;
        $orderInfo->reason = $reason;
        $orderInfo->save();
        CommonController::updateRentInfoReduce($orderInfo->house_id, $orderInfo->number, $orderInfo->startdate, $orderInfo->enddate);
        return "订单已取消";
    }
    public function checkIn(Request $request) {
        $id = $request->input('id');
        Order::where('id', $id)->update(['status' => 3]);
        echo  "房客已入住";
    }
    public function finishOrder(Request $request) {
        $id = $request->input('id');
        $orderInfo = Order::where('id', $id)->first();
        $orderInfo->status = 4;
        $orderInfo->save();
        $house = House::where('id', $orderInfo->house_id)->first();
        $house->ordered_num = $house->ordered_num + 1;
        $house->save();
        echo "订单已完成";
    }

    public function showComment(Request $request) {
        $id = $request->input('id');
        $comment = Comment::where('order_id', $id)->first();
        $data = ['user_comment' => $comment->comment];
        if ($comment->comment_type == 1)
            $data['comment_type'] = '好评';
        elseif ($comment->comment_type == 2)
            $data['comment_type'] = '中评';
        else
            $data['comment_type'] = '差评';
        echo json_encode($data);
    }
    public function replyComment(Request $request) {
        $id = $request->input('order_id');
        $reply = $request->input('reply');
        $comment = Comment::where('order_id', $id)->first();
        $comment->reply = $reply;
        $comment->reply_time = date("Y-m-d H:i:s");
        $comment->landlord_status = 1;
        $comment->save();
        Order::where('id', $id)->update(['comment_status' => 2]);
        echo "回复成功";
    }
    public function editHouse(Request $request) {
        House::where('id', $request->id)->update($request->all());
        echo "修改成功";
    }

    public function getHouseInfo(Request $request) {
        $id = $request->input('id');
        echo House::select("houses.*", "address.*")->where("houses.id", $id)->join("address", "address.id", "houses.address_id")->first();
    }
    public function showEditHouse(Request $request) {
        $id = $request->input('id');
        echo House::find($id);
    }
    public static function getOrderInfo($orderStatType) {
        $orderInfo = Order::select("orders.id", "orders.user_id", "orders.house_id", "orders.sum_people", "houses.name", "comment_status", "number", "startdate", "sum_day", "sum_price", "orders.created_at", "orders.status", "houses.price", "enddate", "orders.order_owner", "orders.owner_phone", "users.name as nickname", "orders.reason")
            ->where("orders.landlord_id", Auth::user()->id)
            ->join("houses", "houses.id", "orders.house_id")
            ->join("users", "users.id", "orders.user_id");
        switch ($orderStatType) {
            case 'wait':
                $orderInfo = $orderInfo->where("orders.status", 1);
                break;
            case 'waitcheckin':
                $orderInfo = $orderInfo->where("orders.status", 2);
                break;
            case 'checkin':
                $orderInfo = $orderInfo->where('orders.status', 3);
                break;
            case 'finish':
                $orderInfo = $orderInfo->where('orders.status', 4);
                break;
            case 'cancel':
                $orderInfo = $orderInfo->where('orders.status', 5);
                break;
        }
        return $orderInfo->orderBy("orders.created_at", 'desc')->paginate(10);
    }

    public static function getCommentInfo($commentType) {
        $commentInfo = Comment::select("comments.id", "houses.name", "comments.comment_type", "comments.comment_time", "users.name as nickname", "orders.startdate", "comments.landlord_status", "comments.order_id", "comments.comment", "comments.reply_time", "comments.reply")
            ->where('comments.landlord_id', Auth::user()->id)
            ->where('comments.user_status', 1)
            ->join('orders', "orders.id", "comments.order_id")
            ->join("users", "users.id", "comments.user_id")
            ->join("houses", "houses.id", "comments.house_id");
        switch ($commentType) {
            case 'good':
                $commentInfo = $commentInfo->where("comments.comment_type", 1);
                break;
            case 'mid':
                $commentInfo = $commentInfo->where("comments.comment_type", 2);
                break;
            case 'bad':
                $commentInfo = $commentInfo->where("comments.comment_type", 3);
                break;
            case 'to-roomer':
                $commentInfo = $commentInfo->where("comments.landlord_status", 0);
                break;
        }
        return $commentInfo->orderBy("comments.updated_at", "desc")->paginate(10);
    }

}
