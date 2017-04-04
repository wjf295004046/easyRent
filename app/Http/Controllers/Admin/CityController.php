<?php

namespace App\Http\Controllers\Admin;

use Cache, Event;
use App\Events\permChangeEvent;
use App\Http\Controllers\CommonController;
use App\Models\ShowIndex;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $data = array();
            $data['draw'] = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $order = $request->get('order');
            $columns = $request->get('columns');
            $search = $request->get('search');
            $cid = $request->get('cid', 0);
            $data['recordsTotal'] = ShowIndex::where('type', 'city')->count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = ShowIndex::where('type', 'city')->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('target', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = ShowIndex::where('type', 'city')->where(function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('target', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = ShowIndex::where('type', 'city')->count();
                $data['data'] = ShowIndex::where('type', 'city')->
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        $datas['data'] = ShowIndex::where('type', 'city')->get();

        return view("admin.city.index", $datas);
    }
    public function edit(Request $request, $id) {
        $city = ShowIndex::where("id", $id)->first();
        return view("admin.city.form", [
            'city' => $city
        ]);
    }
    public function create(Request $request) {
        return view("admin.city.form");
    }
    public function store(Request $request, $id = 0) {
        if ($id != 0) {
            $info = ShowIndex::where('id', $id)->first();
            $is_valid = $request->input('is_valid');
            if ($is_valid != $info->is_valid && $is_valid == 1) {
                $count = ShowIndex::where("type", "city")->where("is_valid", 1)->count();
                if ($count >= 8)
                    return redirect("/admin/city/index")->withErrors("首页推荐城市不能超过8个");
            }

            $info->is_valid = $is_valid;
            $info->title = $request->input('title');
            $info->target = "/" . $request->input('pinyin');
            if ($request->hasFile("photo")) {
                $photo = $request->file("photo");
                $extension = $photo->getClientOriginalExtension();
                if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                    $file_name = 'city_' . date("YmdHis") . ".png";
                    $save_path = 'images/common/';
                    $photo->move($save_path, $file_name);
                    $oCommon = new CommonController();
                    $photo = $oCommon->imgDeal($save_path . $file_name, $request->only('x', 'y', 'w', 'h'), $save_path . $file_name, 350, 180);
                    $info->pic_path = "/common/" . $file_name;
                }
            }
            $info->save();

            Event::fire(new permChangeEvent());
            event(new \App\Events\userActionEvent('\App\Models\ShowIndex', $id, 3, '修改了推荐城市:' . $info->title . '(' . $info->id . ')'));

            return redirect("/admin/city/index")->with("success", "修改成功");

        }
        else {
            $info = new ShowIndex();
            $info->type = 'city';
            $is_valid = $request->input('is_valid');
            if ($is_valid == 1) {
                $count = ShowIndex::where("type", "city")->where("is_valid", 1)->count();
                if ($count >= 5)
                    $info->is_valid = 0;
                else
                    $info->is_valid = 1;
            }else {
                $info->is_valid = $is_valid;
            }
            $info->title = $request->input('title');
            $info->target = "/" . $request->input('pinyin');
            if ($request->hasFile("photo")) {
                $photo = $request->file("photo");
                $extension = $photo->getClientOriginalExtension();
                if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                    $file_name = 'city_' . date("YmdHis") . ".png";
                    $save_path = 'images/common/';
                    $photo->move($save_path, $file_name);
                    $oCommon = new CommonController();
                    $photo = $oCommon->imgDeal($save_path . $file_name, $request->only('x', 'y', 'w', 'h'), $save_path . $file_name, 350, 180);
                    $info->pic_path = "/common/" . $file_name;
                }
            }
            $info->save();

            if ($info->is_valid == 1)
                $status = "有效";
            else
                $status = "无效";
            Event::fire(new permChangeEvent());
            event(new \App\Events\userActionEvent('\App\Models\ShowIndex', $info->id, 1, '添加了推荐城市:' . $info->title . '(' . $status . ')'));

            return redirect("/admin/city/index")->with("success", "添加成功，状态为" . $status);
        }
    }

}
