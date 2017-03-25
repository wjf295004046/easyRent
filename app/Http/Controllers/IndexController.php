<?php

namespace App\Http\Controllers;

use App\Models\House\Address;
use App\Models\House\House;
use App\Models\ShowIndex;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index() {
        $slides = ShowIndex::where('type', 'slide')->get();
        foreach ($slides as $index => $slide) {
            $extra = unserialize($slide->extra);
            $house_id = $extra['house_id'];
            $price[$slide->id] = House::select("price")->where('id', $house_id)->first()->price;
        }
        $hotcitys = ShowIndex::where('type', 'city')->orderBy('updated_at', 'desc')->take(8)->get();
        $hothouses = House::where("status", 1)->orderBy("comment_num", 'desc')->take(6)->get();
        $landlord_info = array();
        $address_info = array();
        foreach ($hothouses as $house) {
            $landlord_id = $house->landlord_id;
            $address_id = $house->address_id;
            if (!isset($landlord_info[$landlord_id])) {
                $landlord_info[$landlord_id] = UserDetail::where("user_id", $landlord_id)->first();
            }
            if (!isset($address_info[$address_id])) {
                $address_info[$address_id] = Address::select("city")->where("id", $address_id)->first();
            }
        }
        return view("index", [
            'slides' => $slides,
            'price' => $price,
            'hotcitys' => $hotcitys,
            'hothouses' => $hothouses,
            'landlords' => $landlord_info,
            'addresses' => $address_info
        ]);
    }
}
