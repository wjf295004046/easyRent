<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Models\House\House;
use App\Models\House\Address;
use App\Models\User;
use Cache, Event;
use App\Events\permChangeEvent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HouseController extends Controller
{
    public $support_facilities = [
        "网络", "空调", "热水淋浴",
        "电视", "电梯", "洗衣机",
        "停车位", "饮水设备", "暖气",
        "有线网络", "拖鞋", "手纸",
        "牙具", "毛巾", "浴液 洗发水",
        "香皂", "允许做饭", "门禁系统",
        "浴缸", "允许吸烟", "允许聚会",
        "允许带动物"
    ];
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $data['recordsTotal'] = House::where('status', 0)->count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = House::where('houses.status', 0)->where(function ($query) use ($search) {
                    $query
                        ->where('houses.name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('user_detail.real_name', 'like', '%' . $search['value'] . '%')
                        ->orWhere('users.phone', 'like', '%' . $search['value'] . '%')
                        ->orWhere('address.city', 'like', '%' . $search['value'] . '%');
                })->join("user_detail", "user_detail.user_id", "houses.landlord_id")
                    ->join("users", "users.id", "houses.landlord_id")
                    ->join("address", "address.id", "houses.address_id")
                    ->count();
                $data['data'] = House::select("houses.id", "houses.name", "houses.price", "user_detail.real_name", "users.phone", "address.city")->where('houses.status', 0)->where(function ($query) use ($search) {
                    $query->where('houses.name', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('user_detail.real_name', 'like', '%' . $search['value'] . '%')
                        ->orWhere('users.phone', 'like', '%' . $search['value'] . '%')
                        ->orWhere('address.city', 'like', '%' . $search['value'] . '%');
                })->join("user_detail", "user_detail.user_id", "houses.landlord_id")
                    ->join("users", "users.id", "houses.landlord_id")
                    ->join("address", "address.id", "houses.address_id")
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = House::where('status', 0)->count();
                $data['data'] = House::select("houses.id", "houses.name", "houses.price", "user_detail.real_name", "users.phone", "address.city")->where('status', 0)->
                skip($start)->take($length)
                    ->join("user_detail", "user_detail.user_id", "houses.landlord_id")
                    ->join("users", "users.id", "houses.landlord_id")
                    ->join("address", "address.id", "houses.address_id")
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }

            return response()->json($data);
        }

        $datas['data'] = House::select("houses.id", "houses.name", "houses.price", "user_detail.real_name", "users.phone", "address.city")
            ->where('status', 0)
            ->join("user_detail", "user_detail.user_id", "houses.landlord_id")
            ->join("users", "users.id", "houses.landlord_id")
            ->join("address", "address.id", "houses.address_id")
            ->orderBy("houses.created_at", "desc")
            ->get();

        return view('admin.house.index', $datas);
    }
    public function eidt(Request $request, $id) {
        $reason = $request->input("reason");
        if($reason) {
            $house = House::where("id", $id)->first();
            $house->status = -1;
            $house->reason = $reason;
            $house->save();
            Event::fire(new permChangeEvent());
            event(new \App\Events\userActionEvent('\App\Models\House\House', $id, 3, "房源(" . $house->id . ")" . $house->name . " 审核不通过。原因：" . $house->reason . "。" ));
            return redirect()->back()->with("success", "审核完成");
        }
        else {
            $info = $request->only('photo');
            $houseInfo = House::select("users.phone", 'houses.name')->where("houses.id", $id)->join("users", "users.id", "houses.landlord_id")->first();
            $phone = $houseInfo->phone;
            $oCommon = new CommonController();
            $pic_path = array();
            foreach ($info['photo'] as $index => $photo) {
                if (empty($photo['photo']))
                    continue;
                $extension = $photo['photo']->getClientOriginalExtension();
                if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                    $name = 'house_' . $id . '_' . date("YmdHis") . $index;
                    $file_name = $name . ".jpg_732x480cw.jpg";
                    $file_name_small = $name . ".jpg_90x60c.jpg";
                    $save_path = 'images/houses/' . $phone . "/";
                    $photo['photo']->move($save_path, $file_name);
                    unset($photo['photo']);
                    $oCommon->imgDeal($save_path . $file_name, $photo, $save_path . $file_name, 732, 480);
                    $oCommon->imgDeal($save_path . $file_name, ['x' => 0, 'y' => 0, 'w' => 732, 'h' => 480], $save_path . $file_name_small, 90, 60);
                    $pic_path[] = "/" . $phone . "/" . $name;
                }
            }
            $pic_path = implode(",", $pic_path);
            $res = House::where('id', $id)->update([
                'pic_path' => $pic_path,
                'status' => 1
            ]);
            if ($res){
                Event::fire(new permChangeEvent());
                event(new \App\Events\userActionEvent('\App\Models\House\House', $id, 3, "房源(" . $id . ")" . $houseInfo->name . " 审核通过。" ));
                return redirect("/admin/house/index")->with("success", "审核完成");
            }
        }
    }
    public function show(Request $request, $id) {
        $houseInfo = House::where('id', $id)->first();
        if ($houseInfo->status == -1)
            return redirect("/admin/house/index")->with("success", "审核完成");
        $addressInfo = Address::where("id", $houseInfo->address_id)->first();
        $landlordInfo = User::select("users.id", "name", "sex", "pic_path", "real_name", "id_card", "phone")->join("user_detail", "users.id", "user_detail.user_id")->where("users.id", $houseInfo->landlord_id)->first();
        $house_type = explode(",", $houseInfo->house_type_detail);
        $bed_arr = explode(",", $houseInfo->bed_type);
        $bedInfo = array();
        $bed_sum = 0;


        foreach ($bed_arr as $bed) {
            $temp = explode(':', $bed);
            $bedInfo[] = $temp;
            $bed_sum += $temp[1];
        }
        $arr = explode(",", $houseInfo->supporting_facilities);
        $supporting_facilities = array();

        foreach ($arr as $value) {
            $supporting_facilities[] = $this->support_facilities[$value];
        }
        return view('admin.house.show', [
            'request' => $request,
            'house' => $houseInfo,
            'address' => $addressInfo,
            'landlord' => $landlordInfo,
            'house_type' => $house_type,
            'beds' => $bedInfo,
            'bed_sum' => $bed_sum,
            'supporting_facilities' => $supporting_facilities,
        ]);
    }
    public function upload(Request $request, $id) {
        return view('admin.house.upload', [
            'id' => $id
        ]);
    }
}
