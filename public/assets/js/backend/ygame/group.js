define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {

            var project_id = $("#table").attr('data-project_id');


            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ygame/group/index?project_id='+project_id,
                    add_url: 'ygame/group/add?project_id='+project_id,
                    edit_url: 'ygame/group/edit?project_id='+project_id,
                    del_url: 'ygame/group/del',
                    multi_url: 'ygame/group/multi',
                    import_url: 'ygame/group/import',
                    table: 'ygame_group',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                search:false,
                commonSearch:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'group_name', title: __('Group_name'), operate: 'LIKE'},
                        {field: 'sign', title: __('Sign'), operate: 'LIKE'},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'num', title: __('Num')},
                        {field: 'allow_team', title: __('Allow_team'),formatter: Table.api.formatter.toggle},
                        {field: 'team_price', title: __('Team_price'), operate:'BETWEEN'},

                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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