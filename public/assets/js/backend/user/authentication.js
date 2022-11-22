define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/authentication/index' + location.search,
                    add_url: 'user/authentication/add',
                    edit_url: 'user/authentication/edit',
                    del_url: 'user/authentication/del',
                    multi_url: 'user/authentication/multi',
                    import_url: 'user/authentication/import',
                    table: 'user_authentication',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        // {field: 'user_id', title: __('User_id')},
                        {field: 'user.username', title: __('User.username'), operate: 'LIKE'},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'user.gender', title: __('User.gender')},
                        {field: 'user.email', title: __('User.email'), operate: 'LIKE'},
                        {field: 'user.mobile', title: __('User.mobile'), operate: 'LIKE'},
                        {field: 'idcard', title: __('Idcard'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1'),"-1":__('Status -1')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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
