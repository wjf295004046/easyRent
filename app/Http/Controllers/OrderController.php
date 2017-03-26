<?php

namespace App\Http\Controllers;

use App\Models\House\House;
use App\Models\House\Liver;
use App\Models\House\RentInfo;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
//    1:整套出租 2:单间出租 3:床位出租 4:沙发出租
    private $rent_type = [
        1 => "整套出租",
        2 => "单间出租",
        3 => "床位出租",
        4 => "沙发出租",
    ];
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {

    }
    public function create(Request $request) {
        $house_id = $request->input("id");
        $startdate = strtotime($request->input("startdate"));
        $enddate = strtotime($request->input("enddate"));
        $rent_days = ($enddate - $startdate) / 86400;
        $houseInfo = House::select("houses.id", "houses.price", "houses.landlord_id", "users.name", "houses.name as house_name", "rent_type", "users.phone", "user_detail.real_name")->where("houses.id", $house_id)->join("users", "users.id", "houses.landlord_id")->join('user_detail', "users.id", "user_detail.user_id")->first();
        $liverInfo = Liver::where("user_id", Auth::user()->id)->get();
        return view("order.create", [
            'request' => $request,
            'house' => $houseInfo,
            'rent_type' => $this->rent_type,
            'rent_days' => $rent_days,
            'livers' => $liverInfo,
        ]);
    }
    public function store(Request $request) {
        $info = $request->all();
        $houseInfo = House::select("landlord_id", "price")->where("id", $info['house_id'])->first();
        $sum_people = 0;
        $livers = '';
        foreach ($info['liver'] as $liver) {
            if (isset($liver['checked'])) {
                $oliver = Liver::find($liver['checked']);
                if ($liver['name'] != '')
                    $oliver->name = $liver['name'];
                if ($liver['idcard'] != '' && strlen($liver['idcard']) == 18)
                    $oliver->idcard = $liver['idcard'];
                if ($liver['phone'] != '')
                    $oliver->phone = $liver['phone'];
                $oliver->save();
                $livers .= $livers == '' ? $oliver->id : "," . $oliver->id;
                $sum_people++;
            }
        }
        if (isset($info['new_liver'])) {
            foreach ($info['new_liver'] as $liver) {
                $oliver = new Liver();
                $oliver->name = $liver['name'];
                $oliver->idcard = $liver['idcard'];
                if ($liver['phone'] != '')
                    $oliver->phone = $liver['phone'];
                $oliver->user_id = Auth::user()->id;
                $oliver->save();
                $livers .= $livers == '' ? $oliver->id : "," . $oliver->id;
                $sum_people++;
            }
        }
        $data = [
            'user_id' => Auth::user()->id,
            'landlord_id' => $houseInfo->landlord_id,
            'house_id' => $info['house_id'],
            'status' => 1,
            'startdate' => $info['startdate'],
            'enddate' => $info['enddate'],
            'order_owner' => $info['real_name'],
            'owner_phone' => Auth::user()->phone,
            'number' => $info['num'],
            'sum_day' => $info['rent_days'],
            'sum_people' => $sum_people,
            'sum_price' => $houseInfo->price * $info['rent_days'] * $info['num'],
            'livers' => $livers,
        ];
        $sms_data = [
            'mobile' => User::where('id', $houseInfo->landlord_id)->first()->phone,
        ];
        $oCommon = new CommonController();
        $oCommon->smsRemind($sms_data, 3);
        if (Order::create($data) && CommonController::updateRentInfo($info['house_id'], $info['num'], $info['startdate'], $info['enddate']))
            return redirect()->action('OrderController@index');
        else
            return redirect()->back();
    }
    public function show() {

    }
    public function edit() {

    }
    public function update() {

    }
    public function destory(){

    }
}
