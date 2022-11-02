define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    $.validator.config({
        rules: {
            userid: function (element) {
                //如果直接返回文本，则表示失败的提示文字
                //如果返回true表示成功
                //如果返回Ajax对象则表示远程验证
                if (!element.value.toString().match(/^\d+$/)) {
                    return '请输入用户ID';
                }
                return $.ajax({
                    url: 'league/admin/checkid',
                    type: 'POST',
                    data: { user_id: element.value },
                    dataType: 'json'
                });
            }
        }
    });

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'league/admin/index',
                    add_url: 'league/admin/add',
                    edit_url: 'league/admin/edit',
                    del_url: 'league/admin/del',
                    multi_url: 'league/admin/multi',
                }
            });

            var table = $("#table");

            //在表格内容渲染完成后回调的事件
            table.on('post-body.bs.table', function (e, json) {

            });
            var columnss = [
                { field: 'state', checkbox: true, },
                { field: 'id', title: 'ID', sortable: true, },

                { field: 'username', title: __('Username'), operate: "LIKE", },
                { field: 'nickname', title: __('Nickname'), operate: "LIKE", },
                { field: 'email', title: __('Email'), operate: "LIKE", },
                {
                    field: 'league_id',
                    title: __('Department'),
                    visible: false,
                    addclass: 'selectpage',
                    extend: 'data-source="league/index/index" data-field="name"',
                    operate: 'in',
                    formatter: Table.api.formatter.search
                },
                {
                    field: 'dadmin',
                    title: __('Department'),
                    formatter: function (value, row, index) {
                        if (value.length == 0)
                            return '-';
                        var league = "";
                        $.each(value, function (i, v) {  //arrTmp数组数据
                            if (v.league) {
                                league += league ? ',' + v.league.name : v.league.name;
                            }
                        });
                        return Table.api.formatter.flag.call(this, league, row, index);
                    }
                    , operate: false
                },

                

                {
                    field: 'dadmin', title: __('Principaler'), operate: false,
                    formatter: function (value, row, index) {
                        var str = __('No');
                        if (value.length == 0)
                            return str;
                        $.each(value, function (i, v) {  //arrTmp数组数据
                            if (v.is_principal == 1) {
                                str = '<span class="text-success">' + __('Yes') + '</span>';
                            }
                        });
                        return str;
                    }
                },

            ];
            if (Config.exits_mobile) {
                //如果是选择
                columnss.push({
                    field: 'mobile', title: __('Mobile'), operate: "LIKE",
                });
            }
            columnss.push(
                { field: 'status', title: __("Status"), searchList: { "normal": __('Normal'), "hidden": __('Hidden') }, formatter: Table.api.formatter.status },
                { field: 'logintime', title: __('Login time'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true },
                {
                    field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate,
                    buttons: [
                        {
                            name: 'principal',
                            text: __('Principal'),
                            title: __('Principal set'),
                            icon: 'fa fa-street-view',
                            classname: 'btn btn-xs btn-danger btn-dialog',
                            url: 'league/admin/principal',
                        },
                    ],
                    formatter: function (value, row, index) {
                        return Table.api.formatter.operate.call(this, value, row, index);
                    }
                });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [columnss],
                //启用固定列
                fixedColumns: true,
                //固定右侧列数
                fixedRightNumber: 1,
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            require(['jstree'], function () {
                //全选和展开
                $(document).on("click", "#checkall", function () {
                    $("#leaguetree").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
                });
                $(document).on("click", "#expandall", function () {
                    $("#leaguetree").jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
                $('#leaguetree').on("changed.jstree", function (e, data) {
                    console.log(data.selected.join(","));
                    $(".commonsearch-table input[name=league_id]").val(data.selected.join(","));
                    table.bootstrapTable('refresh', {});
                    return false;
                });
                $('#leaguetree').jstree({
                    "themes": {
                        "stripes": true
                    },
                    "checkbox": {
                        "keep_selected_style": false,
                    },
                    "types": {
                        "channel": {
                            "icon": false,
                        },
                        "list": {
                            "icon": false,
                        },
                        "link": {
                            "icon": false,
                        },
                        "disabled": {
                            "check_node": false,
                            "uncheck_node": false
                        }
                    },
                    'plugins': ["types", "checkbox"],
                    "core": {
                        "multiple": true,
                        'check_callback': true,
                        "data": Config.leagueList
                    }
                });
            });

        },
        add: function () {
            
            Form.api.bindevent($("form[role=form]"));
        },
        principal: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});
