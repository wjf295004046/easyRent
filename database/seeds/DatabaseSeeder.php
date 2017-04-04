<?php

use Illuminate\Database\Seeder;
use App\Models\ShowIndex;
use App\Models\House\City;
use App\Models\House\House;
use App\Models\House\Address;
use App\Models\UserDetail;
use App\Models\User;
use App\Models\House\Comment;
use App\Models\House\RentInfo;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminInitSeeder::class);
        ShowIndex::insert([
            ['id' => 1, 'title' => '北京', 'pic_path' => '/common/beijing.jpg', 'target' => '/beijing', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 2, 'title' => '上海', 'pic_path' => '/common/shanghai.jpg', 'target' => '/shanghai', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 3, 'title' => '成都', 'pic_path' => '/common/chengdu.jpg', 'target' => '/chengdu', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 4, 'title' => '三亚', 'pic_path' => '/common/sanya.jpg', 'target' => '/sanya', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 5, 'title' => '重庆', 'pic_path' => '/common/chongqing.jpg', 'target' => '/chongqing', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 6, 'title' => '西安', 'pic_path' => '/common/xian.jpg', 'target' => '/xian', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 7, 'title' => '杭州', 'pic_path' => '/common/hangzhou.jpg', 'target' => '/hangzhou', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 8, 'title' => '厦门', 'pic_path' => '/common/xiamen.jpg', 'target' => '/xiamen', 'type' => 'city', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
        ]);
        ShowIndex::insert([
            ['id' => 9, 'title' => '上海雨巷，庭院怀旧情', 'pic_path' => "/houses/exp1/slide.jpg", 'desc' => '上海嘉善路236弄', 'target' => '/house/1', 'extra' => serialize(array('house_id' => 1)), 'type' => 'slide', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 10, 'title' => '沿海第一排，临近鼓浪屿', 'pic_path' => "/houses/exp2/slide.jpg", 'desc' => '厦门豪华夜景房', 'target' => '/house/2', 'extra' => serialize(array('house_id' => 2)), 'type' => 'slide', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 11, 'title' => '西安尚可民宿，凤城五路', 'pic_path' => "/houses/exp3/slide.jpg", 'desc' => '90平米两居室套房', 'target' => '/house/3', 'extra' => serialize(array('house_id' => 3)), 'type' => 'slide', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
        ]);
        Address::insert([
            ['id' => 1, 'user_id' => 1, 'province' => '浙江省', 'city' => '杭州市', 'area' => '拱墅区', 'address' => '浙江大学城市学院', 'detail' => '明德楼', 'co_ordinates' => '120.184356,30.242367', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 2, 'user_id' => 1, 'province' => '浙江省', 'city' => '杭州市', 'area' => '拱墅区', 'address' => '浙江大学城市学院', 'detail' => '明德楼', 'co_ordinates' => '120.142477,30.318977', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 3, 'user_id' => 1, 'province' => '浙江省', 'city' => '杭州市', 'area' => '拱墅区', 'address' => '浙江大学城市学院', 'detail' => '明德楼', 'co_ordinates' => '120.154751,30.307492', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
        ]);
        UserDetail::insert([
            ['id' => 1, 'user_id' => 1, 'real_name' => '王剑峰', 'id_card' => '331081199506073511', 'sex' => '男', 'birth' => '1995-06-07', 'province' => '浙江省', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")]
        ]);
        House::insert([
            ['id' => 1, 'name' => '新国际博览中心|迪士尼|11号线地铁', 'price' => 500, 'landlord_id' => 1, 'address_id' => 1, 'status' => 1, 'sum' => 1, 'pic_path' => "/exp1/house1,/exp1/house2,/exp1/house3,/exp1/house4,/exp1/house5,/exp1/house6,/exp1/house7", 'city' => 'hangzhou', 'max_people' => 4, 'deposit' => '200', 'house_type' => 1, 'house_type_detail' => '1,1,1,1,1', 'house_area' => 100, 'rent_type' => 1, 'bed_type' => '1:2:1.8:2.0,2:1:1.8:2.0', 'change_bed' => 1, 'supporting_facilities' => '0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21', 'desc' => "测试", 'internal_situation' => '内部设施', 'traffic_condition' => '交通情况', 'peripheral_condition' => '周边设施', 'cook_fee' => '20', 'clean_fee' => '20', 'other_fee' => '其他费用', 'comment_num' => 1, 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 2, 'name' => '沿海第一排临近鼓浪屿、高层豪华夜景、海景三房', 'price' => 508, 'landlord_id' => 1, 'address_id' => 2, 'status' => 1, 'sum' => 1, 'pic_path' => "/exp2/house1,/exp2/house2,/exp2/house3,/exp2/house4,/exp2/house5,/exp2/house6,/exp2/house7", 'max_people' => 4, 'city' => 'hangzhou', 'deposit' => '200', 'house_type' => 2, 'house_type_detail' => '2,1,1,1,1', 'house_area' => 100, 'rent_type' => 1, 'bed_type' => '1:2:1.8:2.0,2:1:1.8:2.0', 'change_bed' => 1, 'supporting_facilities' => '0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21', 'desc' => "测试", 'internal_situation' => '内部设施', 'traffic_condition' => '交通情况', 'peripheral_condition' => '周边设施', 'cook_fee' => '20', 'clean_fee' => '20', 'other_fee' => '其他费用', 'comment_num' => 0, 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
            ['id' => 3, 'name' => '沿海第一排临近鼓浪屿、高层豪华夜景、海景三房', 'price' => 288, 'landlord_id' => 1, 'address_id' => 3, 'status' => 1, 'sum' => 1, 'pic_path' => "/exp3/house1,/exp3/house2,/exp3/house3,/exp3/house4,/exp3/house5,/exp3/house6,/exp3/house7", 'max_people' => 4, 'city' => 'hangzhou', 'deposit' => '200', 'house_type' => 4, 'house_type_detail' => '4,1,1,1,1', 'house_area' => 100, 'rent_type' => 1, 'bed_type' => '1:2:1.8:2.0,2:1:1.8:2.0', 'change_bed' => 1, 'supporting_facilities' => '0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21', 'desc' => "测试", 'internal_situation' => '内部设施', 'traffic_condition' => '交通情况', 'peripheral_condition' => '周边设施', 'cook_fee' => '20', 'clean_fee' => '20', 'other_fee' => '其他费用', 'comment_num' => 0, 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
        ]);
        User::insert([
            ['id' => 1, 'name' => '夜乄', 'email' => '295004046@qq.com', 'phone' => '15168202013', 'password' => bcrypt('123456'), 'is_landlord' => 1, 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")]
        ]);
        Comment::insert([
            ['id' => 1, 'user_id' => 1, 'house_id' => 1, 'order_id' => 1, 'landlord_id' => 1, 'comment_type' => 1, 'comment' => '房间很干净卫生，住的也很舒服，出门不远就是杭州大厦，吃饭购物非常方便，就是小区没有电梯有点不方便，其他都很好，房东mm也超赞，有机会还会再来！', 'reply' => '谢谢您能选择我家，欢迎再来～','user_status' => 1, 'landlord_status' => 1, 'created_at' => '2016-12-21 16:00:00', 'updated_at' => '2016-12-30 16:00:00'],
        ]);
        RentInfo::insert([
            ['id' => 1, 'house_id' => 1, 'year' => date("Y"), 'month' => date("m"), 'detail' => serialize(array(28 => 1, 27 => 1)), 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
        ]);
        \App\Models\House\Liver::insert([
            ['id' => 1, 'user_id' => 1, 'name' => '王剑峰', 'idcard' => '331081199506073511', 'phone' => '15168202013', 'created_at' => date("Y-m-d"), 'updated_at' => date("Y-m-d")],
        ]);
    }
}
