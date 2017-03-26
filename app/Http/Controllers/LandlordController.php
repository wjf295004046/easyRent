<?php

namespace App\Http\Controllers;

use App\Models\House\Comment;
use App\Models\Order;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandlordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('cert');
    }
    public function index() {
        $landlord_id = Auth::user()->id;
        $userInfo = UserDetail::where("user_id", $landlord_id)->first();
        $wait = Order::where('landlord_id', $landlord_id)->where('status', 1)->count();
        $waitcheckin = Order::where('landlord_id', $landlord_id)->where('status', 2)->count();
        $checkin = Order::where('landlord_id', $landlord_id)->where('status', 3)->count();
        $cancel = Order::where('landlord_id', $landlord_id)->where('status', 5)->where('updated_at', '>=', date("Y-m-d H:i:s", strtotime("-1 day")))->count();
        $comments = [
            'need_user_comment' => Comment::where('landlord_id', $landlord_id)->where('user_status', 0)->count(),
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

    public static function getOrderInfo($orderStatType) {
        $orderInfo = Order::select("orders.id", "orders.house_id", "houses.name", "comment_status", "number", "startdate", "sum_day", "sum_price", "orders.created_at", "orders.status")->where("orders.landlord_id", Auth::user()->id)->join("houses", "houses.id", "orders.house_id");
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
        return $orderInfo->orderBy("orders.updated_at", 'desc')->paginate(10);
    }
}
