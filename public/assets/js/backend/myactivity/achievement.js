define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'myactivity/achievement/index' + location.search,
                    add_url: 'myactivity/achievement/add',
                    edit_url: 'myactivity/achievement/edit',
                    del_url: 'myactivity/achievement/del',
                    multi_url: 'myactivity/achievement/multi',
                    import_url: 'myactivity/achievement/import',
                    table: 'achievement',
                },
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
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'orgevent.name', title: __('Orgevent.name'), operate: 'LIKE' },
                        { field: 'number', title: __('Number') },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        { field: 'troop', title: __('Troop'), operate: 'LIKE' },
                        { field: 'sex', title: __('Sex'), formatter: Table.api.formatter.normal },
                        { field: 'bseedsswitch', title: __('Bseedsswitch'), searchList: { "1": __('Yes'), "0": __('No') }, table: table, formatter: Table.api.formatter.normal },
                        { field: 'bcomposswitch', title: __('Bcomposswitch'), searchList: { "1": __('Yes'), "0": __('No') },table: table, formatter: Table.api.formatter.normal },
                        { field: 'tel', title: __('Tel') },
                        { field: 'idnumber', title: __('Idnumber') },
                        { field: 'group', title: __('Group') },

                        { field: 'native', title: __('Native'), operate: 'LIKE' },
                        { field: 'zjf', title: __('Zjf') },
                        { field: 'cumulative', title: __('Cumulative') },
                        { field: 'dszjf', title: __('Dszjf') },
                        { field: 'cyclesum', title: __('Cyclesum') },
                        { field: 'victorysum', title: __('Victorysum') },
                        { field: 'deucesum', title: __('Deucesum') },
                        { field: 'sumwarning', title: __('Sumwarning') },
                        { field: 'failsum', title: __('Failsum') },
                        { field: 'summation', title: __('Summation') },
                        
                        { field: 'vicsum', title: __('Vicsum') },
                        { field: 'backbou', title: __('Backbou') },
                        { field: 'victory', title: __('Victory') },
                        { field: 'conpositor', title: __('Conpositor') },
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ],
                queryParams: function (params) {
                    //这里可以追加搜索条件
                    params.orgevent_id = Fast.api.query('ids');
                    var filter = JSON.parse(params.filter);
                    var op = JSON.parse(params.op);
                    filter.orgevent_id = Fast.api.query('ids');
                    op.orgevent_id = "=";
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;
                }
            });
            $('.create').click(function(){
                Layer.confirm(__('是否创建比赛？如果已经存在比赛，此操作会造成数据覆盖。'), function () {
                    Fast.api.ajax({
                        url: 'myactivity/achievement/create',
                        data: {orgevent_id: Fast.api.query('ids')}
                    }, function () {
                        // table.trigger("uncheckbox");
                        // table.bootstrapTable('refresh');
                    });
                });
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
