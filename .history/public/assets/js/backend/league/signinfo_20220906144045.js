define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'qrcode','clipboard'], function ($, undefined, Backend, Table, Form, Qrcode,ClipboardJS) {

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

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
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
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        signin: function () {
            Controller.api.bindevent();
            $('#signin-btn').click(function () {
                console.log('666')
            })
        },
        share: function () {
            Controller.api.bindevent();
            new QRCode("qrcode", {
                render: "canvas",
                width: 200,
                height: 200,
                text: $("#qrcode_src").val()
            });
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                var clipboard = new ClipboardJS('.btn-copy', {
                    text: $('.output').val()
                });
                clipboard.on('success', function (e) {
                    Layer.msg("复制成功");
                    e.clearSelection();
                });
            }
        }
    };
    return Controller;
});
