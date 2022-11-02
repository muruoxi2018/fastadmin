define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'league/signin/index' + location.search,
                    add_url: 'league/signin/add',
                    edit_url: 'league/signin/edit',
                    del_url: 'league/signin/del',
                    multi_url: 'league/signin/multi',
                    import_url: 'league/signin/import',
                    table: 'league_signin',
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
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        { field: 'user.nickname', title: __('User_id'), operate: 'LIKE' },
                        { field: 'daterange', title: __('Daterange'), operate: 'LIKE' },
                        { field: 'address', title: __('Address'), operate: 'LIKE' },
                        {
                            field: 'league_ids', title: __('League_ids'), operate: 'LIKE',
                            formatter: function (value, row, index) {
                                if (value.length == 0) {
                                    return '-'
                                }
                                let league_text = "";
                                $.each(value, function (i, v) {  //arrTmp数组数据
                                    league_text += '<br>' + v;
                                });
                                return league_text;
                            }
                        },
                        {
                            field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'share',
                                    text: __('分享'),
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-share',
                                    url: 'league/signinfo/share/league_signin_id/{ids}'
                                },
                                {
                                    name: 'view',
                                    text: __('View'),
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-users',
                                    url: 'league/signinfo/index/league_signin_id/{ids}'
                                },
                                {
                                    name:'signin', title: __('签到')
                                }
                            ]
                        }
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
