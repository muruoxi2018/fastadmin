define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'information/disqualify/index' + location.search,
                    add_url: 'information/disqualify/add',
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                    import_url: '',
                    table: 'information_disqualify',
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
                        {field: 'id', title: __('Id')},
                        // {field: 'user_id', title: __('User_id')},
                        {field: 'user.username', title: __('User.username'), operate: 'LIKE'},
                        {field: 'user.nickname', title: __('User.nickname'), operate: 'LIKE'},
                        {field: 'user.gender', title: __('User.gender'), operate: 'LIKE'},
                        {field: 'user.email', title: __('User.email'), operate: 'LIKE'},
                        {field: 'user.mobile', title: __('User.mobile'), operate: 'LIKE'},
                        {field: 'school', title: __('School'), operate: 'LIKE'},
                        {field: 'cpy_id', title: __('Cpy_id')},
                        {field: 'bpy_id', title: __('Bpy_id')},
                        {field: 'cpz_id', title: __('Cpz_id')},
                        {field: 'arbitration', title: __('Arbitration'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
