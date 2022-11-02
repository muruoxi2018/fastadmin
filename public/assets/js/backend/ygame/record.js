define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ygame/record/index' + location.search,
                    table: 'ygame_record',
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
                        {field: 'id', title: __('Id'),operate: false},
                        {field: 'team_name', title: __('Team_name'), operate: false,visible:false},
                        {field: 'leader', title: __('Team_person'), operate: false,visible:false},
                        {field: 'team_mobile', title: __('Team_mobile'), operate: false,visible:false},
                        {field: 'group_name', title: __('组别'), operate: false},
                        {field: 'name', title: __('Name'), operate: 'LIKE',},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'idcard', title: __('Idcard'), operate: 'LIKE'},
                        {field: 'price', title: __('Price'), operate: false}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            $(".nav-tabs a").click(function(){
                type = $(this).attr('data-type');

                switch($(this).attr('data-type')){
                    case "1":
                        $('#table').bootstrapTable('hideColumn', "team_name");
                        $('#table').bootstrapTable('hideColumn', "leader");
                        $('#table').bootstrapTable('hideColumn', "team_mobile");
                        break;
                    case "2":
                        $('#table').bootstrapTable('showColumn', "team_name");
                        $('#table').bootstrapTable('showColumn', "leader");
                        $('#table').bootstrapTable('showColumn', "team_mobile");
                        break;
                }
                table.bootstrapTable('refresh', {url:$.fn.bootstrapTable.defaults.extend.index_url+'&type='+$(this).attr('data-type')});
            })


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