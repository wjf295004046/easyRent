<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\Models\House\House;
use App\Models\ShowIndex;
use App\Models\User;
use Cache, Event;
use App\Events\permChangeEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SlideController extends Controller
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
            $data['recordsTotal'] = ShowIndex::where('type', 'slide')->count();
            if (strlen($search['value']) > 0) {
                $data['recordsFiltered'] = ShowIndex::where('type', 'slide')->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('desc', 'like', '%' . $search['value'] . '%')
                        ->orWhere('target', 'like', '%' . $search['value'] . '%');
                })->count();
                $data['data'] = ShowIndex::where('type', 'slide')->where(function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search['value'] . '%')
                        ->orWhere('desc', 'like', '%' . $search['value'] . '%')
                        ->orWhere('target', 'like', '%' . $search['value'] . '%');
                })
                    ->skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            } else {
                $data['recordsFiltered'] = ShowIndex::where('type', 'slide')->count();
                $data['data'] = ShowIndex::where('type', 'slide')->
                skip($start)->take($length)
                    ->orderBy($columns[$order[0]['column']]['data'], $order[0]['dir'])
                    ->get();
            }
            return response()->json($data);
        }
        $datas['data'] = ShowIndex::where('type', 'slide')->get();

        return view("admin.slide.index", $datas);
    }
    public function edit(Request $request, $id) {
        $slide = ShowIndex::where("id", $id)->first();
        return view("admin.slide.form", [
            'slide' => $slide
        ]);
    }
    public function create(Request $request) {
        return view("admin.slide.form");
    }
    public function store(Request $request, $id = 0) {
        if ($id != 0) {
            $info = ShowIndex::where('id', $id)->first();
            $is_valid = $request->input('is_valid');
            if ($is_valid != $info->is_valid && $is_valid == 1) {
                $count = ShowIndex::where("type", "slide")->where("is_valid", 1)->count();
                if ($count >= 5)
                    return redirect("/admin/slide/index")->withErrors("首页轮播不能超过5个");
            }

            $info->is_valid = $is_valid;
            $info->title = $request->input('title');
            $info->desc = $request->input('desc');
            $info->target = "/house/" . $request->input('house_id');
            $info->extra = serialize(['house_id' => $request->input('house_id')]);
            if ($request->hasFile("photo")) {
                $photo = $request->file("photo");
                $userInfo = House::select("users.phone")->where("houses.id", $request->input('house_id'))->join("users", "users.id", "houses.landlord_id")->first();

                $extension = $photo->getClientOriginalExtension();
                if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                    $file_name = 'slide_' . $request->input("house_id"). "_" . date("YmdHis") . ".png";
                    $save_path = 'images/houses/' . $userInfo->phone . "/";
                    $photo->move($save_path, $file_name);
                    $oCommon = new CommonController();
                    $photo = $oCommon->imgDeal($save_path . $file_name, $request->only('x', 'y', 'w', 'h'), $save_path . $file_name, 1920, 700);
                    $info->pic_path = "/houses/" . $userInfo->phone . "/" . $file_name;
                }
            }
            $info->save();

            Event::fire(new permChangeEvent());
            event(new \App\Events\userActionEvent('\App\Models\ShowIndex', $id, 3, '修改了轮播信息:' . $info->title . '(' . $info->id . ')'));

            return redirect("/admin/slide/index")->with("success", "修改成功");

        }
        else {
            $info = new ShowIndex();
            $info->type = 'slide';
            $is_valid = $request->input('is_valid');
            if ($is_valid == 1) {
                $count = ShowIndex::where("type", "slide")->where("is_valid", 1)->count();
                if ($count >= 5)
                    $info->is_valid = 0;
                else
                    $info->is_valid = 1;
            }else {
                $info->is_valid = $is_valid;
            }
            $info->title = $request->input('title');
            $info->desc = $request->input('desc');
            $info->target = "/house/" . $request->input('house_id');
            $info->extra = serialize(['house_id' => $request->input('house_id')]);
            if ($request->hasFile("photo")) {
                $photo = $request->file("photo");
                $userInfo = House::select("users.phone")->where("houses.id", $request->input('house_id'))->join("users", "users.id", "houses.landlord_id")->first();

                $extension = $photo->getClientOriginalExtension();
                if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg') {
                    $file_name = 'slide_' . $request->input("house_id"). "_" . date("YmdHis") . ".png";
                    $save_path = 'images/houses/' . $userInfo->phone . "/";
                    $photo->move($save_path, $file_name);
                    $oCommon = new CommonController();
                    $photo = $oCommon->imgDeal($save_path . $file_name, $request->only('x', 'y', 'w', 'h'), $save_path . $file_name, 1920, 700);
                    $info->pic_path = "/houses/" . $userInfo->phone . "/" . $file_name;
                }
            }
            $info->save();

            if ($info->is_valid == 1)
                $status = "有效";
            else
                $status = "无效";
            Event::fire(new permChangeEvent());
            event(new \App\Events\userActionEvent('\App\Models\ShowIndex', $info->id, 1, '添加了轮播信息:' . $info->title . '(' . $status . ')'));

            return redirect("/admin/slide/index")->with("success", "修改成功，状态为" . $status);
        }
    }
}
