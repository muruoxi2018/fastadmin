define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'information/zyz/index' + location.search,
                    add_url: 'information/zyz/add',
                    edit_url: 'information/zyz/edit',
                    del_url: 'information/zyz/del',
                    multi_url: 'information/zyz/multi',
                    import_url: 'information/zyz/import',
                    table: 'information_zyz',
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
                        {field: 'user_id', title: __('User_id')},
                        {field: 'ygame_project_id', title: __('Ygame_project_id')},
                        {field: 'work', title: __('Work'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'user.username', title: __('User.username'), operate: 'LIKE'},
                        {field: 'user.nickname', title: __('User.nickname'), operate: 'LIKE'},
                        {field: 'project.name', title: __('Project.name'), operate: 'LIKE'},
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
