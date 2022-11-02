define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {


            var project_id = $("#table").attr('data-project_id');



            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ygame/article/index?project_id=' + project_id,
                    add_url: 'ygame/article/add?project_id=' + project_id,
                    edit_url: 'ygame/article/edit?project_id=' + project_id,
                    del_url: 'ygame/article/del',
                    multi_url: 'ygame/article/multi',
                    import_url: 'ygame/article/import',
                    table: 'ygame_article',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'article_title', title: __('Article_title'), operate: 'LIKE'},
                        {field: 'datetime', title: __('Datetime'), operate: false},
                        {field: 'author', title: __('Author'),operate: false},
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