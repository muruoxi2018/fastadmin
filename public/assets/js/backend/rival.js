define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'rival/index' + location.search,
                    add_url: 'rival/add',
                    edit_url: 'rival/edit',
                    del_url: 'rival/del',
                    multi_url: 'rival/multi',
                    import_url: 'rival/import',
                    table: 'rival',
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
                        {field: 'achievement_id', title: __('Achievement_id')},
                        {field: 'bout', title: __('Bout')},
                        {field: 'seat', title: __('Seat')},
                        {field: 'rival_id', title: __('Rival_id')},
                        {field: 'forfei', title: __('Forfei')},
                        {field: 'integral', title: __('Integral')},
                        {field: 'jf', title: __('Jf')},
                        {field: 'order', title: __('Order'), operate: 'LIKE'},
                        {field: 'result', title: __('Result'), searchList: {"胜":__('胜'),"负":__('负'),"平":__('平'),"双弃":__('双弃'),"先手弃":__('先手弃'),"后手弃":__('后手弃')}, formatter: Table.api.formatter.normal},
                        {field: 'warning', title: __('Warning')},
                        {field: 'score', title: __('Score')},
                        {field: 'move', title: __('Move')},
                        {field: 'umpire_id', title: __('Umpire_id')},
                        {field: 'achievement.number', title: __('Achievement.number')},
                        {field: 'achievement.name', title: __('Achievement.name'), operate: 'LIKE'},
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
