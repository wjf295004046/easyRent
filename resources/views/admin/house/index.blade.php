@extends('admin.layouts.base')

@section('title','房源审核')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="row page-title-row" id="dangqian" style="margin:5px;">
        <div class="col-md-6">
        </div>

        <div class="col-md-6 text-right">
        </div>
    </div>
    <div class="row page-title-row" style="margin:5px;">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-right">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                @include('admin.partials.errors')
                @include('admin.partials.success')
                <div class="box-body">
                    <table id="tags-table" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th data-sortable="false" class="hidden-sm"></th>
                            <th class="hidden-sm">标题</th>
                            <th class="hidden-sm">房东姓名</th>
                            <th class="hidden-sm">手机号码</th>
                            <th class="hidden-md">所在城市</th>
                            <th class="hidden-md">价格</th>
                            <th data-sortable="false">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-refuse" tabIndex="-1">
        <div class="modal-dialog modal-warning">
            <div class="modal-content">
                <form class="refuse-form" method="POST" action="/admin/list">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            ×
                        </button>
                        <h4 class="modal-title">提示</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea name="reason" id="reason" cols="30" rows="5" class="form-control" placeholder="请输入审核不通过原因"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-times-circle"></i> 确认
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var refuse = new Vue({
            el: ".refuse-form",
            data: {
                reason: "",
            },
            methods: {
            }
        });
        $(function () {
            var cid = $('#cid').attr('attr');
            var table = $("#tags-table").DataTable({
                language: {
                    "sProcessing": "处理中...",
                    "sLengthMenu": "显示 _MENU_ 项结果",
                    "sZeroRecords": "没有匹配结果",
                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                    "sInfoPostFix": "",
                    "sSearch": "搜索:",
                    "sUrl": "",
                    "sEmptyTable": "表中数据为空",
                    "sLoadingRecords": "载入中...",
                    "sInfoThousands": ",",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上页",
                        "sNext": "下页",
                        "sLast": "末页"
                    },
                    "oAria": {
                        "sSortAscending": ": 以升序排列此列",
                        "sSortDescending": ": 以降序排列此列"
                    }
                },
                order: [[5, "asc"]],
                serverSide: true,

                ajax: {
                    url: '/admin/house/index',
                    type: 'POST',
                    data: function (d) {

                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "name"},
                    {"data": "real_name"},
                    {"data": "phone"},
                    {"data": "city"},
                    {"data": "price"},
                    {"data": "action"}
                ],
                columnDefs: [
                    {
                        'targets': -1, "render": function (data, type, row) {
                        var str = '';

                        str += '<a style="margin:3px;"  href="/admin/house/'+ row['id'] +'/show" class="X-Small btn-xs text-success "><i class="fa fa-adn"></i>查看</a>';


                        str += '<a style="margin:3px;" href="#" attr="' + row['id'] + '" class="delBtn X-Small btn-xs text-danger"><i class="fa fa-times-circle"></i> 审核不通过</a>';

                        return str;

                    }
                    }
                ]
            });

            table.on('preXhr.dt', function () {
                loadShow();
            });

            table.on('draw.dt', function () {
                loadFadeOut();
            });

            table.on('order.dt search.dt', function () {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            $("table").delegate('.delBtn', 'click', function () {
                var id = $(this).attr('attr');
                $('.refuse-form').attr('action', '/admin/house/' + id);
                $("#modal-refuse").modal();
            });

        });
    </script>
@endsection