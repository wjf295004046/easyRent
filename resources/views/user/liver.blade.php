@extends('user.nav')

@section('main-content')
    <style>
        #liver-info { background-color: white; min-height: 300px;}
        .info { margin-left: 10px;position: relative; margin-bottom: 20px;border-bottom: 5px solid #e5e5e5; border-right: 5px solid #e5e5e5; border-radius: 10px; background-color: #fff; padding: 10px 10px; font-size: 12px;}
        /*.info p { margin-top: 30px; margin-bottom: 30px; font-size: 14px;}*/
        .info>h4 { color:#30c3a6;}
        .btn-edit {position: absolute; top: 20px; right: 5%; z-index: 100;}
    </style>
    <div class="row info" id="liver-info">
        <h4>常用入住人</h4>
        <a href="javascript:void(0)" v-on:click="showAddLiver()" class="btn-edit btn btn-info btn-xs">添加入住人</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <td>姓名</td>
                <td>身份证号码</td>
                <td>手机号</td>
                <td>操作</td>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(liver, index) in livers">
                    <td>@{{ liver.name }}</td>
                    <td>@{{ liver.idcard }}</td>
                    <td>@{{ liver.phone }}</td>
                    <td>
                        <a href="javascript:void(0)" v-on:click="editLiver(index)" class="btn btn-warning btn-xs">编辑</a>
                        <a href="javascript:void(0)" v-on:click="deleteLiver(liver.id, index)" class="btn btn-warning btn-xs">删除</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="modal fade show-edit-liver" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <form id="form-edit-liver" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" v-model="edit_info.id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">编辑入住人</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" v-bind:class="edit_error.name ? 'has-error' : ''">
                                <label for="name" class="col-sm-3 control-label">姓名</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="edit_info.name" name="name" id="name" placeholder="请输入入住人姓名">
                                    <span id="helpBlock2" class="help-block">@{{ edit_error.name_msg }}</span>
                                </div>
                            </div>
                            <div class="form-group" v-bind:class="edit_error.phone ? 'has-error' : ''">
                                <label for="phone" class="col-sm-3 control-label">手机号</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="edit_info.phone" name="phone" id="phone" placeholder="请输入手机号">
                                    <span id="helpBlock2" class="help-block">@{{ edit_error.phone_msg }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" v-on:click="saveEditLiver()">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade show-add-liver" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <form id="form-add-liver" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">编辑入住人</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" v-bind:class="add_error.name ? 'has-error' : ''">
                                <label for="add-name" class="col-sm-3 control-label">姓名</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="add-name" placeholder="请输入入住人姓名">
                                    <span id="helpBlock2" class="help-block">@{{ add_error.name_msg }}</span>
                                </div>
                            </div>
                            <div class="form-group" v-bind:class="add_error.idcard ? 'has-error' : ''">
                                <label for="add_idcard" class="col-sm-3 control-label">身份证</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="idcard" id="add_idcard" placeholder="请输入入住人姓名">
                                    <span id="helpBlock2" class="help-block">@{{ add_error.idcard_msg }}</span>
                                </div>
                            </div>
                            <div class="form-group" v-bind:class="add_error.phone ? 'has-error' : ''">
                                <label for="add_phone" class="col-sm-3 control-label">手机号</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="phone" id="add_phone" placeholder="请输入手机号">
                                    <span id="helpBlock2" class="help-block">@{{ add_error.phone_msg }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" v-on:click="addLiver()">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var livers = new Vue({
            el: "#liver-info",
            data: {
                livers: [
                    @foreach($livers as $liver)
                    {
                        id: {{ $liver->id }},
                        name: '{{ $liver->name }}',
                        idcard: '{{ substr($liver->idcard, 0, 4) . "******" . substr($liver->idcard, 15) }}',
                        phone: '{{ $liver->phone }}'
                    },
                    @endforeach
                ],
                edit_info: {
                    index: '',
                    id: "",
                    name: "",
                    phone: ""
                },
                edit_error: {
                    name: false,
                    name_msg: "",
                    phone: false,
                    phone_msg: ""
                },
                add_error: {
                    name:false,
                    name_msg: '',
                    idcard: false,
                    idcard_msg: "",
                    phone: false,
                    phone_msg: ""
                }
            },
            methods: {
                showAddLiver: function () {
                    $(".show-add-liver").modal();
                },
                addLiver: function () {
                    $.ajax({
                        url: "{{ url('/home/saveliver') }}",
                        data: $("#form-add-liver").serialize(),
                        type: 'post',
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        success: function (data) {
                            data = $.parseJSON(data);
                            if (data.code == 1)
                            {
                                livers.add_error.name = true;
                                livers.add_error.name_msg = data.msg;
                                livers.add_error.idcard = false;
                                livers.add_error.idcard_msg = "";
                                livers.add_error.phone = false;
                                livers.add_error.phone_msg = "";
                            }
                            else if (data.code == 2)
                            {
                                livers.add_error.name = false;
                                livers.add_error.name_msg = "";
                                livers.add_error.idcard = false;
                                livers.add_error.idcard_msg = "";
                                livers.add_error.phone = true;
                                livers.add_error.phone_msg = data.msg;
                            }
                            else if (data.code == 3) {
                                livers.add_error.name = false;
                                livers.add_error.name_msg = "";
                                livers.add_error.idcard = true;
                                livers.add_error.idcard_msg = data.msg;
                                livers.add_error.phone = false;
                                livers.add_error.phone_msg = "";
                            }
                            else {
                                livers.add_error.name = false;
                                livers.add_error.name_msg = "";
                                livers.add_error.idcard = false;
                                livers.add_error.idcard_msg = "";
                                livers.add_error.phone = false;
                                livers.add_error.phone_msg = "";
                                livers.livers.push({
                                    id: data.id,
                                    name: data.name,
                                    idcard: data.idcard,
                                    phone: data.phone
                                });
                                $(".show-add-liver").modal('toggle');
                            }
                        }
                    })
                },
                saveEditLiver: function () {
                    $.ajax({
                        url: "{{ url('/home/saveeditliver') }}",
                        data: $("#form-edit-liver").serialize(),
                        type: 'post',
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        success: function (data) {
                            data = $.parseJSON(data);
                            if (data.code == 1)
                            {
                                livers.edit_error.name = true;
                                livers.edit_error.name_msg = data.msg;
                                livers.edit_error.phone = false;
                                livers.edit_error.phone_msg = "";
                            }
                            else if (data.code == 2)
                            {
                                livers.edit_error.name = false;
                                livers.edit_error.name_msg = "";
                                livers.edit_error.phone = true;
                                livers.edit_error.phone_msg = data.msg;
                            }
                            else {
                                livers.edit_error.name = false;
                                livers.edit_error.name_msg = "";
                                livers.edit_error.phone = false;
                                livers.edit_error.phone_msg = "";
                                var index = livers.edit_info.index;
                                livers.livers[index].name = data.name;
                                livers.livers[index].phone = data.phone;
                                $(".show-edit-liver").modal('toggle');
                            }
                        }
                    })
                },
                editLiver: function (index) {
                    $(".show-edit-liver").modal();
                    this.edit_info.id = this.livers[index].id;
                    this.edit_info.name = this.livers[index].name;
                    this.edit_info.phone = this.livers[index].phone;
                    this.edit_info.index = index;

                },
                deleteLiver: function (id, index) {
                    $.ajax({
                        url: "{{ url('/home/deleteliver') }}",
                        data: {'id': id},
                        type: 'post',
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        success: function (data) {
                            if (data == '删除成功')
                                livers.livers.splice(index, 1);
                        }
                    })
                }
            }
        })
    </script>

@endsection