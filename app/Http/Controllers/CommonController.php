<?php

namespace App\Http\Controllers;

use App\Models\House\Address;
use App\Models\House\House;
use App\Models\House\RentInfo;
use App\Models\SMS;
use App\Models\SMSRemind;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class CommonController extends Controller
{
    public function getVerify(Request $request) {
        $mobile = $request->input('phone');
        $verify = rand(100000, 999999);
        $res = $this->sendSMS(array('mobile' => $mobile, 'verify' => $verify));
        $res_arr = json_decode($res);
        print_r($res_arr);
        if (isset($res_arr->errorno) || isset($res_arr->errormsg))
            $status = 0;
        else
            $status = 1;
        $data = array(
            'mobile' => $mobile,
            'verify' => $verify,
            'status' => $status,
            'type' => $request->input('type'),
            'result' => $res,
        );
        SMS::create($data);
    }
    public function smsRemind($data, $type) {
//        $res = $this->sendSMS($data, $type);
//        $res_arr = json_decode($res);
        $res = '';
        $res_arr = '';
        if (isset($res_arr->errorno) || isset($res_arr->errormsg))
            $status = 0;
        else
            $status = 1;
        $data = array(
            'mobile' => $data['mobile'],
            'date' => isset($data['date']) ? $data['date'] : date('Y-m-d'),
            'status' => $status,
            'type' => $type,
            'result' => $res,
        );
        SMSRemind::create($data);
    }
    public function sendSMS($data, $type = 1) {
        $ch = curl_init();
        $data['type'] = $type;
        curl_setopt($ch, CURLOPT_URL, env("SMS_URL"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function userExists(Request $request) {
        $phone = $request->input("phone");
        $res = User::select('id')->where("phone", $phone)->first();
        if ($res)
            echo json_encode(array('code' => 1));
        else
            echo json_encode(array('code' => 0));
    }

    public function checkRentInfo(Request $request) {
        $id = $request->input("id");
        $startdate = $request->input("startdate");
        $enddate = $request->input("enddate");
        $num = $request->input("num");
        $sum = House::select("sum")->where("id", $id)->first()->sum;
        $syear = substr($startdate, 0, 4);
        $eyear = substr($enddate, 0, 4);
        $smonth = substr($startdate, 5, 2);
        $emonth = substr($enddate, 5, 2);
        $rentInfo = RentInfo::where("house_id", $id)->where("year", ">=", $syear)->where("year", "<=", $eyear)->where("month", ">=", $smonth)->where("month", "<=", $emonth)->get();
        if ($rentInfo->isEmpty())
            return json_encode(array('code' => 1));
        foreach ($rentInfo as $info) {
            $days = unserialize($info->detail);
            foreach ($days as $day => $value) {
                $date = $info->year . "-" . $info->month . "-" . $day;
                if (strtotime($startdate) <= strtotime($date) && strtotime($enddate) > strtotime($date) && $sum - $value - $num < 0) {
                    return json_encode(array('code' => 0, 'msg' => $startdate . "到" . $enddate . "中有无房状态，请更改日期或房源"));
                }
            }
        }
        return json_encode(array('code' => 1));

    }

    public function saveAddress(Request $request) {
        $address = new Address();
        $address->user_id = Auth::user()->id;
        $address->province = $request->input('province');
        $address->city = $request->input('city');
        $address->area = $request->input('district');
        $address->address = $request->input('street') . $request->input('address');
        $address->detail = $request->input('detail');
        $address->co_ordinates = $request->input('co_ordinates');
        $address->save();
        $data = [
            'id' => $address->id,
            'province' => $address->province,
            'city' => $address->city,
            'area' => $address->area,
            'address' => $address->address,
            'detail' => $address->detail,
            'co_ordinates' => $address->co_ordinates,
        ];
        return json_encode($data);
    }

    public static function updateRentInfo($house_id, $number, $startdate, $enddate){
        $startdate = strtotime($startdate);
        $enddate = strtotime($enddate);
        $sYear = date("Y", $startdate);
        $eYear = date("Y", $enddate);
        $sMonth = date("m", $startdate);
        $eMonth = date("m", $enddate);
        $sDay = date("d", $startdate);
        $eDay = date("d", $enddate);
        $rentInfo = RentInfo::where("house_id", $house_id);
        if ($sYear == $eYear) {
            $rentInfo = $rentInfo->where("year", $sYear);
            if ($sMonth == $eMonth) {
                $rentInfo = $rentInfo->where("month", $sMonth)->first();
                if ($rentInfo){
                    $detail = unserialize($rentInfo->detail);
                    for ($i = $sDay; $i <= $eDay; $i++) {
                        if (isset($detail[$i]))
                            $detail[$i] += $number;
                        else
                            $detail[$i] = $number;
                    }
                    $rentInfo->detail = serialize($detail);
                    $rentInfo->save();
                }
                else {
                    $detail = array();
                    for ($i = $sDay; $i <= $eDay; $i++) {
                        if (isset($detail[$i]))
                            $detail[$i] += $number;
                        else
                            $detail[$i] = $number;
                    }
                    $rentInfo = new RentInfo();
                    $rentInfo->year = $sYear;
                    $rentInfo->month = $sMonth;
                    $rentInfo->house_id = $house_id;
                    $rentInfo->detail = serialize($detail);
                    $rentInfo->save();
                }

            }
            else {
                for ($i = $sMonth; $i <= $eMonth; $i++){
                    $t = date("t", strtotime($sYear . "-" . $i . "-01"));
                    $rentInfo = $rentInfo->where("month", $i)->first();
                    if ($rentInfo)
                        $detail = unserialize($rentInfo->detail);
                    else
                        $detail = array();

                    if ($i != $eMonth && $i != $sMonth){
                        for ($j = 1; $j <= $t; $j++) {
                            if (isset($detail[$j]))
                                $detail[$j] += $number;
                            else
                                $detail[$j] = $number;
                        }
                        if ($rentInfo){
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();

                        }
                        else {
                            $rentInfo = new RentInfo();
                            $rentInfo->year = $sYear;
                            $rentInfo->month = $i;
                            $rentInfo->house_id = $house_id;
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();

                        }
                    }
                    elseif ($i == $sMonth) {
                        for ($j = $sDay; $j <= $t; $j++){
                            if (isset($detail[$j]))
                                $detail[$j] += $number;
                            else
                                $detail[$j] = $number;
                        }
                        if ($rentInfo){
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();
                        }
                        else {
                            $rentInfo = new RentInfo();
                            $rentInfo->year = $sYear;
                            $rentInfo->month = $sMonth;
                            $rentInfo->house_id = $house_id;
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();
                        }
                    }
                    else {
                        for ($j = 1; $j <= $eDay; $j++) {
                            if (isset($detail[$j]))
                                $detail[$j] += $number;
                            else
                                $detail[$j] = $number;
                        }
                        if ($rentInfo){
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();

                        }
                        else {
                            $rentInfo = new RentInfo();
                            $rentInfo->year = $sYear;
                            $rentInfo->month = $eMonth;
                            $rentInfo->house_id = $house_id;
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();
                        }
                    }
                }
            }
        }
        else {
            for ($i = $sYear; $i <= $eYear; $i++) {
                $rentInfo = $rentInfo->where("year", $i);
                if ($i == $sYear) {
                    for ($j = $sMonth; $j <= 12; $j++) {
                        $rentInfo = $rentInfo->where("month", $j)->first();
                        $t = date("t", strtotime($sYear . "-" . $j . "-01"));
                        if ($rentInfo)
                            $detail = unserialize($rentInfo->detail);
                        else
                            $detail = array();
                        if ($j == $sMonth) {
                            for($k = $sDay; $k <= $t; $k++)
                            {
                                if (isset($detail[$k]))
                                    $detail[$k] += $number;
                                else
                                    $detail[$k] = $number;
                            }
                            if ($rentInfo){
                                $rentInfo->detail = serialize($detail);
                            }
                            else {
                                $rentInfo = new RentInfo();
                                $rentInfo->year = $sYear;
                                $rentInfo->month = $sMonth;
                                $rentInfo->house_id = $house_id;
                                $rentInfo->detail = serialize($detail);
                            }
                        }
                        else {
                            for ($k = 1; $k <= $t; $k++) {
                                if (isset($detail[$k]))
                                    $detail[$k] += $number;
                                else
                                    $detail[$k] = $number;
                            }
                            if ($rentInfo){
                                $rentInfo->detail = serialize($detail);
                            }
                            else {
                                $rentInfo = new RentInfo();
                                $rentInfo->year = $sYear;
                                $rentInfo->month = $j;
                                $rentInfo->house_id = $house_id;
                                $rentInfo->detail = serialize($detail);
                            }
                        }
                    }
                }
                elseif ($i == $eYear) {
                    for ($j = 1; $j <= $eMonth; $j++) {
                        $rentInfo = $rentInfo->where("month", $j)->first();
                        $t = date("t", strtotime($sYear . "-" . $j . "-01"));
                        if ($rentInfo)
                            $detail = unserialize($rentInfo->detail);
                        else
                            $detail = array();
                        if ($j == $eMonth) {
                            for ($k = 1; $k <= $eDay; $k++) {
                                if (isset($detail[$i]))
                                    $detail[$k] += $number;
                                else
                                    $detail[$k] = $number;
                            }
                            if ($rentInfo){
                                $rentInfo->detail = serialize($detail);
                                $rentInfo->save();

                            }
                            else {
                                $rentInfo = new RentInfo();
                                $rentInfo->year = $eYear;
                                $rentInfo->month = $eMonth;
                                $rentInfo->house_id = $house_id;
                                $rentInfo->detail = serialize($detail);
                                $rentInfo->save();

                            }
                        }
                        else {
                            for ($k = 1; $k <= $t; $k++) {
                                if (isset($detail[$i]))
                                    $detail[$k] += $number;
                                else
                                    $detail[$k] = $number;
                            }
                            if ($rentInfo){
                                $rentInfo->detail = serialize($detail);
                                $rentInfo->save();

                            }
                            else {
                                $rentInfo = new RentInfo();
                                $rentInfo->year = $eYear;
                                $rentInfo->month = $j;
                                $rentInfo->house_id = $house_id;
                                $rentInfo->detail = serialize($detail);
                                $rentInfo->save();

                            }
                        }
                    }
                }
                else {
                    for ($j = 1; $j <= 12; $j++) {
                        $rentInfo = $rentInfo->where("month", $j)->first();
                        $t = date("t", strtotime($sYear . "-" . $j . "-01"));
                        if ($rentInfo)
                            $detail = unserialize($rentInfo->detail);
                        else
                            $detail = array();
                        for ($k = 1; $k <= $t; $k++) {
                            if (isset($detail[$k]))
                                $detail[$k] += $number;
                            else
                                $detail[$k] = $number;
                        }
                        if ($rentInfo){
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();

                        }
                        else {
                            $rentInfo = new RentInfo();
                            $rentInfo->year = $i;
                            $rentInfo->month = $j;
                            $rentInfo->house_id = $house_id;
                            $rentInfo->detail = serialize($detail);
                            $rentInfo->save();
                        }
                    }
                }
            }
        }
        return true;
    }

    public static function updateOrderNum($house_id) {
        $house = House::where("id", $house_id)->first();
        $house->ordered_num += 1;
        $house->save();
    }

    public function cert() {
        $userInfo = UserDetail::select('real_name', 'id_card', 'pic_path')->where('user_id', Auth::user()->id)->first();
        if (!$userInfo)
            $userInfo = '';
        else if ($userInfo->pic_path != '/common/mrtx.jpg' && $userInfo->id_card != '' && $userInfo->real_name != '') {
            echo 'test';
            return redirect()->action('HomeController@fangodng');
        }
        return view('cert', [
            'user_info' => $userInfo
        ]);
    }
    public function certSave(Request $request) {
        $userInfo = UserDetail::where('user_id', Auth::user()->id)->first();
        if (!$userInfo) {
            $userInfo = new UserDetail();
            $userInfo->user_id = Auth::user()->id;
        }
        $userInfo->real_name = $request->input('real_name');
        $userInfo->id_card = $request->input('id_card');

        if ($request->hasFile("photo"))
        {
            $photo = $request->photo;
            $extension = $photo->getClientOriginalExtension();
            if ($extension == 'png' || $extension == 'jpeg') {
                $file_name = 'yhtx_' . date("YmdHis") . ".png";
                $save_path = 'images/users/' . Auth::user()->phone . "/";
                $photo->move($save_path, $file_name);
                $photo = $this->imgDeal($save_path . $file_name, $request->only('x', 'y', 'w', 'h'), $save_path . $file_name);

                $userInfo->pic_path = "/users/" . Auth::user()->phone . "/" . $file_name;
            }

        }
        $userInfo->save();
        User::where('id', Auth::user()->id)->update(['is_landlord' => 1]);
        return redirect()->action('HouseController@create');
    }
    public function imgDeal($photo, $data, $path, $res_w = 160, $res_h = 160) {
        $x = round($data['x']);
        $y = round($data['y']);
        $w = round($data['w']);
        $h = round($data['h']);
        $img = Image::make($photo)->crop($w, $h, $x, $y)->resize($res_w, $res_h)->save($path);
    }
}