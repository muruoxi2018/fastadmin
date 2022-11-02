define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'league.signin.info/index' + location.search,
                    add_url: 'league.signin.info/add',
                    edit_url: 'league.signin.info/edit',
                    del_url: 'league.signin.info/del',
                    multi_url: 'league.signin.info/multi',
                    import_url: 'league.signin.info/import',
                    table: 'league_signin_info',
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
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'address', title: __('Address'), operate: 'LIKE'},
                        {field: 'channel', title: __('Channel'), searchList: {"Web":__('Web'),"WeChat":__('Wechat'),"manual":__('Manual')}, formatter: Table.api.formatter.normal},
                        {field: 'signin.name', title: __('Signin.name'), operate: 'LIKE'},
                        {field: 'user.nickname', title: __('User.nickname'), operate: 'LIKE'},
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
