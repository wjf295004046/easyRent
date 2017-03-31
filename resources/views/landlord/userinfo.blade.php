@extends('landlord.nav')

@section('main-content')
    <style>
        .info { position: relative; margin-bottom: 20px;border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: #fff; padding: 30px 30px;}
        .info p { margin-top: 30px; margin-bottom: 30px; font-size: 14px;}
        .info p span {color: #959ea7;font-size: 14px; margin-right: 10px;}
        .info>h4 { color:#30c3a6;}
        .btn-edit {position: absolute; top: 40px; right: 5%; z-index: 100;}
        select {padding-top: 2px;padding-bottom: 5px; height: 30px;}
    </style>
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{ url('/fangdong') }}">我是房东</a></li>
            <li class="active">我的设置</li>
            <li class="active">个人资料</li>
        </ol>
    </div>
    <div class="row info" id="base-info">
        <h4>基本信息</h4>
        <div class="col-md-9 col-sm-8">
            <p><span>用户名：</span> {{ $user->name }} &nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target=".edit-name">修改</a></p>
            <div class="modal fade edit-name" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <form id="form-edit-name">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">编辑用户名</h4>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control" name="name" id="name" placeholder="请输入新用户名">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                <button type="button" class="btn btn-primary" onclick="editUser('form-edit-name')">修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <p><span>手机号码：</span> {{ $user->phone }}&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target=".edit-phone">修改</a></p>
            <div class="modal fade edit-phone" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <form id="form-edit-phone" class="form-horizontal">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">编辑手机号</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                   <div class="col-sm-12">
                                       <input type="text" class="form-control" name="phone" id="phone" placeholder="请输入新手机号码">
                                   </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-7">
                                        <input type="text" maxlength="6" class="form-control" name="verify" id="verify" placeholder="6位动态密码">
                                    </div>
                                    <div class="col-sm-4"><button type="button" onclick="ajaxGetVerify()" class="btn btn-success btn-sm" id="btn-verify">@{{ content }}</button></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                <button type="button" class="btn btn-primary" onclick="editUser('form-edit-phone')">修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <p>
                <span>邮箱：</span>
                @if($user->email)
                    {{ $user->email }} &nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" data-toggle="modal" data-target=".edit-email">修改</a>
                @else
                    <a href="javascript:void(0)" data-toggle="modal" data-target=".edit-email">添加邮箱地址</a>
                @endif
            <div class="modal fade edit-email" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <form id="form-edit-email">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">编辑邮箱</h4>
                            </div>
                            <div class="modal-body">
                                <input type="email" class="form-control" name="email" id="email" placeholder="请输入邮箱地址">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                <button type="button" class="btn btn-primary" onclick="editUser('form-edit-email')">修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </p>
        </div>
        <div class="col-md-3 col-sm-4">
            <div class="thumbnail">
                <img src="/images{{ $user_detail->pic_path }}" alt="...">
                <div class="caption" style="text-align: center">
                    <p style="margin: 0px;"><a href="#" role="button" data-toggle="modal" data-target=".change-photo">修改照片</a></p>
                </div>
                <div class="modal fade change-photo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="photo-upload" method="post" action="{{ url('/common/changephoto') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">修改照片</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-1">
                                            <h4>预览</h4>
                                            <img src="/images/common/mrtx.jpg" width="100%" id="preview" alt="">
                                        </div>
                                        <div class="col-sm-offset-1 col-sm-4">
                                            {{--<h4>照片上传</h4>--}}
                                            <input type="hidden" name="x" id="x">
                                            <input type="hidden" name="y" id="y">
                                            <input type="hidden" name="w" id="w">
                                            <input type="hidden" name="h" id="h">
                                            <div class="form-group" style="margin-top: 40px;">
                                                <label for="#photo">照片上传</label>
                                                <input type="file" name="photo" id="photo">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                    <button type="submit" class="btn btn-primary">保存</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row info" id="cert-info">
        <h4>身份信息</h4>
        <a href="javascript:void(0)" v-on:click="showEdit" class="btn-edit">@{{ edit_btn }}</a>
        <div class="col-sm-12">
            <p v-if="!edit"><span>真实姓名：</span>@{{ real_name }}</p>
            <p v-if="!edit"><span>身份证号码：</span>@{{ id_card }}</p>
            <p v-if="!edit"><span>护照：</span>@{{ passport }}</p>
            <form v-if="edit" id="form-cert">
                <p><span>真实姓名：</span><input type="text" name="real_name" id="real_name" v-model="real_name"></p>
                <p><span>身份证号码：</span><input type="text" name="id_card" id="id_card" v-model="id_card"></p>
                <p><span>护照：</span><input type="text" name="passport" id="passport" v-model="passport"></p>
            </form>
        </div>
    </div>
    <div class="row info" id="other-info">
        <h4>社交信息</h4>
        <a href="javascript:void(0)" v-on:click="showEdit" class="btn-edit">@{{ edit_btn }}</a>
        <div class="col-sm-12">
            <p v-if="!edit"><span>性别：</span>@{{ sex }}</p>
            <p v-if="!edit"><span>出生日期：</span>@{{ birth }}</p>
            <p v-if="!edit"><span>所在地区：</span>@{{ country }} @{{ province }} @{{ city }}</p>
            <p v-if="!edit"><span>教育：</span>@{{ education }}</p>
            <p v-if="!edit"><span>工作：</span>@{{ job }}</p>
        </div>
        <form v-if="edit" id="form-other">
            <p><span>性别：</span><input type="text" name="sex" id="sex" v-model="sex"></p>
            <p><span>出生日期：</span><input type="text" name="birth" id="birth" v-model="birth"></p>
            <p>
                <span>所在区域：</span> 中国
                <select id='province' name="province" style="width:100px" onchange='search(this)'></select>
                <select id='city' name="city" style="width:100px"></select>
            </p>
            <p>
                <span>教育：</span>
                <select name="education" id="education" v-model="education">
                    <option value="博士">博士</option>
                    <option value="硕士">硕士</option>
                    <option value="本科">本科</option>
                    <option value="大专">大专</option>
                    <option value="中专">中专</option>
                    <option value="高中">高中</option>
                    <option value="初中">初中</option>
                </select>
            </p>
            <p><span>工作：</span><input type="text" name="job" id="job" v-model="job"></p>
        </form>
    </div>
    <script>
        var cert = new Vue({
            el: "#cert-info",
            data: {
                edit: false,
                edit_btn: "编辑",
                real_name: '{{ $user_detail->real_name }}',
                id_card: '{{ $user_detail->id_card }}',
                passport: '{{ $user_detail->passport }}',
            },
            methods: {
                showEdit: function () {
                    if (this.edit) {
                        this.edit = false;
                        this.edit_btn = "编辑";
                        editUserInfo('form-cert');
                        this.real_name = $("#real_name").val();
                        this.id_card = $("#id_card").val();
                        this.passport = $("#passport").val();
                    }
                    else {
                        this.edit = true;
                        this.edit_btn = "保存";
                    }
                }
            }
        });
        var other = new Vue({
            el: "#other-info",
            data: {
                edit: false,
                edit_btn: "编辑",
                sex: '{{ $user_detail->sex }}',
                birth: '{{ $user_detail->birth }}',
                country: '{{ $user_detail->country }}',
                province: '{{ $user_detail->province }}',
                city: '{{ $user_detail->city }}',
                education: '{{ $user_detail->education ?? '未填写' }}',
                job: '{{ $user_detail->job }}'
            },
            methods: {
                showEdit: function () {
                    if (this.edit) {
                        this.edit = false;
                        this.edit_btn = "编辑";
                        editUserInfo('form-other');
                        this.sex = $("#sex").val();
                        this.birth = $("#birth").val();
                        this.province = $("#province").val();
                        this.city = $("#city").val();
                        this.education = $("#education").val();
                        this.job = $("#job").val();
                    }
                    else {
                        this.edit = true;
                        this.edit_btn = "保存";
                        //行政区划查询
                        var opts = {
                            subdistrict: 1,   //返回下一级行政区
                            level: 'city',
                            showbiz:false  //查询行政级别为 市
                        };
                        district = new AMap.DistrictSearch(opts);//注意：需要使用插件同步下发功能才能这样直接使用
                        district.search('中国', function(status, result) {
                            if(status=='complete'){
                                getData(result.districtList[0]);
                            }
                        });
                    }
                }
            }
        });
        function editUserInfo(form) {
            $.ajax({
                url: "{{ url('fangdong/edituserinfo') }}",
                data: $("#" + form).serialize(),
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {

                }
            })
        }
        function editUser(form) {
            $.ajax({
                url: "{{ url('fangdong/edituser') }}",
                data: $("#" + form).serialize(),
                type: 'post',
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                success: function (data) {
                    if (data == '修改成功') {
                        window.location.reload();
                    }
                }
            })
        }
        var jcrop_api;
        var $img = $("#preview");
        $('#photo').change(function () {
            if(jcrop_api){
                jcrop_api.destroy();
                $img.attr('style', null);
            }
            var $file = $(this);
            var fileObj = $file[0];
            var windowURL = window.URL || window.webkitURL;
            var dataURL;
            if(fileObj && fileObj.files && fileObj.files[0]){
                dataURL = windowURL.createObjectURL(fileObj.files[0]);
                $img.attr('src',dataURL);
            }

            $('#preview').Jcrop({
                aspectRatio:1,
                allowSelect:false,
                minSize:[50,50],
                setSelect:[0,0,300,300],
                onSelect: function selectChange(c) {
                    var rat = $img[0].naturalWidth/$img.width();
                    $('#x').attr('value',c.x*rat);
                    $('#y').attr('value',c.y*rat);
                    $('#w').attr('value',c.w*rat);
                    $('#h').attr('value',c.h*rat);
                },
            },function () {
                jcrop_api = this;
            });
        });


        var citySelect = $("#city");
        function getData(data) {
            var bounds = data.boundaries;

            var subList = data.districtList;
            var level = data.level;

            //清空下一级别的下拉列表
            if (level === 'province') {
                console.log(level);
                nextLevel = 'city';
                $("#city").empty();
                keyword = other.city;
            }
            else if (level == 'country') {
                keyword = other.province;
            }

            if (subList) {
                var contentSub =new Option('--请选择--');
                for (var i = 0, l = subList.length; i < l; i++) {
                    var name = subList[i].name;
                    var levelSub = subList[i].level;
                    var cityCode = subList[i].citycode;
                    if(i==0){
                        document.querySelector('#' + levelSub).add(contentSub);
                    }
                    contentSub=new Option(name);
                    contentSub.setAttribute("value", name);
                    contentSub.center = subList[i].center;
                    contentSub.adcode = subList[i].adcode;
                    if (name == keyword) {
                        contentSub.setAttribute("selected", true);
                        if (level === 'country') {
                            //行政区划查询
                            var opts = {
                                subdistrict: 1,   //返回下一级行政区
                                level: 'city',
                                showbiz:false  //查询行政级别为 市
                            };
                            district1 = new AMap.DistrictSearch(opts);//注意：需要使用插件同步下发功能才能这样直接使用
                            district1.search(name, function(status, result) {
                                if(status=='complete'){
                                    getData(result.districtList[0]);
                                }
                            });
                        }
                    }
                    document.querySelector('#' + levelSub).add(contentSub);
                }
            }

        }
        function search(obj) {

            var option = obj[obj.options.selectedIndex];
            var keyword = option.text; //关键字
            var adcode = option.adcode;
            district.setLevel(option.value); //行政区级别
            district.setExtensions('all');
            //行政区查询
            //按照adcode进行查询可以保证数据返回的唯一性
            district.search(adcode, function(status, result) {
                if(status === 'complete'){
                    getData(result.districtList[0]);
                }
            });
        }

        var verify = new Vue({
            el: '#btn-verify',
            data: {
                content: '获取验证码',
            }
        });
        var wait = 60;
        function ajaxGetVerify() {
            var phone = $("#phone").val();
            if (phone.length == 11) {
                $.ajax({
                    url: "{{ url("/common/getverify") }}",
                    data: { "phone": $("#phone").val(), "type": 3 },
                    type: "post",
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    success: function (data) {

                    }
                });
                $("#btn-verify").prop("disabled", true);
                time();

            }
        }
        function time() {
            if (wait == 0) {
                $("#btn-verify").prop("disabled", false);
                verify.content = "获取验证码";
                wait = 60;
            }
            else {
                verify.content = "重新发送(" + wait + ")";
                wait--;
                setTimeout(function () {
                    time()
                }, 1000);
            }
        }
    </script>
@endsection