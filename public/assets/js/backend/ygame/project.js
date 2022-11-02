define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ygame/project/index',
                    add_url: 'ygame/project/add',
                    edit_url: 'ygame/project/edit',
                    del_url: 'ygame/project/del',
                    multi_url: 'ygame/project/multi',
                    table: 'ygame_project',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url+"?status=1",
                pk: 'id',

                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),operate:false},
                        {field: 'project_name', title: __('Project_name'),operate:'like'},
                        {field: 'start_time', title: __('Start_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'end_time', title: __('End_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'image', title: __('Image'),operate:false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'status', title: __('Status'),operate:false,formatter: Table.api.formatter.toggle},
                        {field: 'createtime', title: __('Createtime'), operate:false, addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate', align:'center', title: __('Operate'), table: table, events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    title: __('文章管理'),
                                    text:'文章管理',
                                    classname: 'btn btn-xs btn-default btn-dialog',
                                    extend:'data-area=\'["1000px","700px"]\'',
                                    icon: 'fa fa-th-list ',
                                    url: 'ygame/article/index?project_id={id}'
                                },
                                {
                                    name: 'detail',
                                    title: __('组别管理'),
                                    text:'组别管理',
                                    classname: 'btn btn-xs btn-warning btn-dialog',
                                    extend:'data-area=\'["1100px","700px"]\'',
                                    icon: 'fa fa-microchip',

                                    url: 'ygame/group/index?project_id={id}'
                                },
                                {
                                    name: 'detail',
                                    title: __('电子成绩证书'),
                                    text:'电子成绩证书',
                                    classname: 'btn btn-xs btn-danger btn-dialog',
                                    extend:'data-area=\'["1100px","700px"]\'',
                                    icon: 'fa fa-file-text-o',
                                    url: 'ygame/result/design?project_id={id}'
                                },

                                {
                                    name: 'detail',
                                    title: __('成绩管理'),
                                    text:'成绩管理',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    extend:'data-area=\'["1100px","700px"]\'',
                                    icon: 'fa fa-file-text-o',
                                    url: 'ygame/result/index?project_id={id}'
                                },

                                {
                                    name: 'detail',
                                    title: __('报名记录'),
                                    text:'报名记录',
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    extend:'data-area=\'["1000px","700px"]\'',
                                    icon: 'fa fa-th-list ',
                                    url: 'ygame/record/index?project_id={id}'
                                }],
                            formatter: Table.api.formatter.operate
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