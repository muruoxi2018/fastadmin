define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/grading/index' + location.search,
                    add_url: 'user/grading/add',
                    edit_url: 'user/grading/edit',
                    del_url: 'user/grading/del',
                    multi_url: 'user/grading/multi',
                    import_url: 'user/grading/import',
                    table: 'user_grading',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'user.nickname', title: __('User.nickname'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'state', title: __('State'), searchList: {"待审核":__('待审核'),"审核通过":__('审核通过'),"审核未通过":__('审核未通过')}, formatter: Table.api.formatter.normal},
                        {field: 'category.name', title: __('Type')},
                        {field: 'unit', title: __('Unit'), operate: 'LIKE'},
                        {field: 'num', title: __('Num'), operate: 'LIKE'},
                        {field: 'file', title: __('File'), operate: false, formatter: Table.api.formatter.file},
                        {field: 'memo', title: __('Memo'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
