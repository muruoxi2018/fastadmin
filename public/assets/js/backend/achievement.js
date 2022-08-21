define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'achievement/index' + location.search,
                    add_url: 'achievement/add',
                    edit_url: 'achievement/edit',
                    del_url: 'achievement/del',
                    multi_url: 'achievement/multi',
                    import_url: 'achievement/import',
                    table: 'achievement',
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
                        {field: 'orgevent_id', title: __('Orgevent_id')},
                        {field: 'number', title: __('Number')},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'troop', title: __('Troop'), operate: 'LIKE'},
                        {field: 'sex', title: __('Sex'), searchList: {"男":__('男'),"女":__('女')}, formatter: Table.api.formatter.normal},
                        {field: 'bseedsswitch', title: __('Bseedsswitch'), searchList: {"1":__('Yes'),"0":__('No')}, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'bcomposswitch', title: __('Bcomposswitch'), table: table, formatter: Table.api.formatter.toggle},
                        {field: 'tel', title: __('Tel')},
                        {field: 'idnumber', title: __('Idnumber')},
                        {field: 'native', title: __('Native'), operate: 'LIKE'},
                        {field: 'zjf', title: __('Zjf')},
                        {field: 'cumulative', title: __('Cumulative')},
                        {field: 'dszjf', title: __('Dszjf')},
                        {field: 'cyclesum', title: __('Cyclesum')},
                        {field: 'victorysum', title: __('Victorysum')},
                        {field: 'deucesum', title: __('Deucesum')},
                        {field: 'group', title: __('Group')},
                        {field: 'sumwarning', title: __('Sumwarning')},
                        {field: 'failsum', title: __('Failsum')},
                        {field: 'summation', title: __('Summation')},
                        {field: 'conpositor', title: __('Conpositor')},
                        {field: 'vicsum', title: __('Vicsum')},
                        {field: 'backbou', title: __('Backbou')},
                        {field: 'victory', title: __('Victory')},
                        {field: 'orgevent.name', title: __('Orgevent.name'), operate: 'LIKE'},
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
