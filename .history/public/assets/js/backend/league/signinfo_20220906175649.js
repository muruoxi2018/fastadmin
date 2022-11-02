define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'qrcode', 'clipboard'], function ($, undefined, Backend, Table, Form, Qrcode, ClipboardJS) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'league/signinfo/index' + location.search,
                    add_url: 'league/signinfo/add?league_signin_id=' + Fast.api.query('league_signin_id'),
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                    import_url: 'league/signinfo/import',
                    table: 'league_signinfo',
                }
            });

            //绑定事件
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var panel = $($(this).attr("href"));
                if (panel.size() > 0) {
                    Controller.table[panel.attr("id")].call(this);
                    $(this).on('click', function (e) {
                        $($(this).attr("href")).find(".btn-refresh").trigger("click");
                    });
                }
                //移除绑定的事件
                $(this).unbind('shown.bs.tab');
            });

            //必须默认触发shown.bs.tab事件
            $('ul.nav-tabs li.active a[data-toggle="tab"]').trigger("shown.bs.tab");

            table: {
                first: function() {
                    var table1 = $("#table1");
                    table1.bootstrapTable({
                        url: $.fn.bootstrapTable.defaults.extend.index_url,
                        pk: 'id',
                        sortName: 'id',
                        columns: [
                            [
                                { checkbox: true },
                                { field: 'user.nickname', title: __('User.nickname'), operate: 'LIKE' },
                                { field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                                { field: 'address', title: __('Address'), operate: 'LIKE', formatter: function (val) { if (val == '') { return '-' } else { return val } } },
                                { field: 'channel', title: __('Channel'), searchList: { "Web": __('网页'), "WeChat": __('微信客户端'), "Manual": __('手动签到') }, formatter: Table.api.formatter.normal },
                                // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                            ]
                        ],
                        queryParams: function (params) {
                            //这里可以追加搜索条件
                            var filter = JSON.parse(params.filter);
                            var op = JSON.parse(params.op);
                            filter.league_signin_id = Fast.api.query('league_signin_id');
                            op.league_signin_id = "=";
                            params.filter = JSON.stringify(filter);
                            params.op = JSON.stringify(op);
                            return params;
                        }
                    });
                    // 为表格绑定事件
                    Table1.api.bindevent(table);
                }, second: function() {

                }
            };


            // 初始化表格



        },
        add: function () {
            Controller.api.bindevent();
        },
        signin: function () {
            Controller.api.bindevent();
        },
        share: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                // 生成二维码
                new QRCode("qrcode", {
                    render: "canvas",
                    width: 200,
                    height: 200,
                    text: $("#qrcode_src").val()
                });
                // 复制网址到剪贴板
                let clipboard = new ClipboardJS('.btn-copy', {
                    text: function () { return $('#output').val() }
                });
                clipboard.on('success', function (e) {
                    Layer.msg("复制成功");
                    e.clearSelection();
                });
                // 提交签到
                $('#signin-btn').click(function () {
                    console.log('666')
                })
            }
        }
    };
    return Controller;
});
