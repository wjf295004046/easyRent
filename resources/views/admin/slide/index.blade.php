@extends('admin.layouts.base')

@section('title','轮播列表')

@section('pageHeader','控制面板')

@section('pageDesc','DashBoard')

@section('content')
    <div class="row page-title-row" id="dangqian" style="margin:5px;">
        <div class="col-md-6">
            <span style="margin:3px;" class="btn-flat text-info"> 顶级菜单</span>
        </div>

        <div class="col-md-6 text-right">
            <a href="/admin/slide/create" class="btn btn-success btn-md"><i class="fa fa-plus-circle"></i> 添加首页展示 </a>
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
                            <th class="hidden-sm">描述</th>
                            <th class="hidden-sm">连接地址</th>
                            <th class="hidden-md">图片</th>
                            <th class="hidden-md">是否有效</th>
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
@endsection

@section('js')
    <script>
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
                    url: '/admin/slide/index',
                    type: 'POST',
                    data: function (d) {

                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "title"},
                    {"data": "desc"},
                    {"data": "target"},
                    {"data": "pic"},
                    {"data": "is_valid"},
                    {"data": "action"}
                ],
                columnDefs: [
                    {
                        'targets': -1, "render": function (data, type, row) {
                            var str = '';

                            //编辑
                            str += '<a style="margin:3px;" href="/admin/slide/' + row['id'] + '/edit" class="X-Small btn-xs text-success "><i class="fa fa-edit"></i> 编辑</a>';


                            return str;

                        }
                    },
                    {
                        'targets': -2, "render": function (data, type, row) {
                            if (row['is_valid'] == 1)
                                return "<span style='color: green'>有效</span>";
                            else
                                return "<span style='color: red'>无效</span>";

                        }
                    },
                    {
                        'targets': -3, "render": function (data, type, row) {
                            return "<img width='150' src='/images" + row['pic_path'] + "' />"

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


        });
    </script>
@endsection