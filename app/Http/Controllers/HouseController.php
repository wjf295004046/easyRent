<?php

namespace App\Http\Controllers;

use App\Models\House\Address;
use App\Models\House\Comment;
use App\Models\House\House;
use App\Models\House\RentInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showSearchHouse', 'show']]);
    }

    public function showSearchHouse(Request $request, $opt) {
        $arr = explode("_", $opt);
        $houses = House::where("city", $arr[0]);
        $search_params = ['city' => $arr[0]];
        for ($i = 1; $i < count($arr); $i++) {
            $type = substr($arr[$i], 0, 2);
            $content = substr($arr[$i], 2);
            switch ($type) {
                case 'ht':
                    $search_params['house_type'] = $content;
                    if ($content == 4)
                        $houses = $houses->where("house_type", ">", $content);
                    else
                        $houses = $houses->where("house_type", $content);
                    break;
                case 'rt':
                    $search_params['rent_type'] = $content;
                    $houses = $houses->where("rent_type", "$content");
                    break;
                case 'mp':
                    $search_params['max_people'] = $content;
                    if ($content == 9)
                        $houses = $houses->where("max_people", ">=", $content);
                    else
                        $houses = $houses->where("max_people", $content);
                    break;
                case 'rp':
                    $search_params['range_price'] = $content;
                    $price_arr = explode("-", $content);
                    $houses = $houses->whereBetween('price', [$price_arr[0], $price_arr[1]]);
                    break;
            }
        }
        if ($request->input('keyword') != '') {
            $search_params['keyword'] = $request->input('keyword');
            $houses->where("name", "like", "%" . $request->input('keyword') . "%");
        }
        if ($request->input('startdate') != '' && $request->input('enddate')){
            $search_params['startdate'] = $request->input('startdate');
            $search_params['enddate'] = $request->input('enddate');
            $search_params['date'] = $search_params['startdate'] . "至" . $search_params['enddate'];
        }

        $houses = $houses->where('status', 1)->orderBy("comment_num", "desc")->orderBy("price", "asc")->paginate(10);

        $address_arr = array();
        $api_info = array();
        foreach ($houses as $house) {
            $address_arr[$house->id] = Address::where('id', $house->address_id)->first();
            $api_info[$house->id]['pic_path'] = substr($house->pic_path, 0, strpos($house->pic_path, ","));
            $api_info[$house->id]['price'] = $house->price;
            $api_info[$house->id]['title'] = mb_strlen($house->name) > 10 ? mb_substr($house->name, 0, 10) . "..." : $house->name;

        }

        return view('house/houses', [
            'houses' => $houses,
            'search_params' => $search_params,
            'addresses' => $address_arr,
            'api_info' => $api_info
        ]);
    }

    public function index(Request $request) {
        echo "个人中心房源页面";
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id) {
        $houseInfo = House::where('id', $id)->first();
        $addressInfo = House::find($houseInfo->address_id)->address;
        $landlordInfo = User::select("users.id", "name", "sex", "pic_path", "real_name", "id_card", "phone")->join("user_detail", "users.id", "user_detail.user_id")->where("users.id", $houseInfo->landlord_id)->first();
        $house_type = explode(",", $houseInfo->house_type_detail);
        $bed_arr = explode(",", $houseInfo->bed_type);
        $bedInfo = array();
        $bed_sum = 0;
        $comments = '';
        $favorable_rate = 0;
        $comments_count = ['good' => 0, 'mid' => 0, 'bad' => 0];
        if ($houseInfo->comment_num != 0) {
            $comments = Comment::select("users.phone", "comments.id", "comment_type", "comment", "reply", "landlord_status", "comments.created_at", "comments.updated_at")->where("house_id", $houseInfo->id)->join("users", "users.id", "comments.user_id")->orderBy('comments.created_at', 'desc')->get();
            foreach ($comments as $comment) {
                if ($comment->comment_type == 1)
                    $comments_count['good']++;
                elseif ($comments_count->comment_type == 2)
                    $comments_count['mid']++;
                else
                    $comments_count['bad']++;
            }
            $favorable_rate = $comments_count['good']*100/array_sum($comments_count);
        }
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
        $rent_info = RentInfo::where('house_id', $houseInfo->id)->where('month', '>=', date("m"))->get();
        return view('house/show', [
            'request' => $request,
            'house' => $houseInfo,
            'address' => $addressInfo,
            'landlord' => $landlordInfo,
            'house_type' => $house_type,
            'bed' => $bedInfo,
            'bed_sum' => $bed_sum,
            'comments' => $comments,
            'comments_count' => $comments_count,
            'favorable_rate' => $favorable_rate,
            'supporting_facilities' => $supporting_facilities,
            'rent_info' => $rent_info,
        ]);
    }
    public function create() {
        $addressInfo = Address::where('user_id', Auth::user()->id)->get();
        return view('house/create',[
            'addresses' => $addressInfo,
            'support_facilities' => $this->support_facilities,
        ]);
    }
    public function store(Request $request) {
        $data = $request->all();
        $data['landlord_id'] = Auth::user()->id;
        $data['city'] = $data['city-py'];
        $data['house_type_detail'] = implode(",", $data['house_type']);
        $data['house_type'] = $data['house_type']['shi'];
        $temp = '';
        foreach ($data['bed_info'] as $bedInfo) {
            $temp .= $bedInfo['type'] . ":" . $bedInfo['num'] . ":" . $bedInfo['width'] . ":" . $bedInfo['height'] . ",";
        }
        $data['bed_type'] = rtrim($temp, ",");
        $data['supporting_facilities'] = implode(",", $data['support_facility']);
        if (House::create($data)) {
            return redirect()->action('HouseController@index');
        }
    }
}
