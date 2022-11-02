define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {

            var project_id = $("#table").attr('data-project_id');

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ygame/result/index?project_id=' + project_id,
                    add_url: 'ygame/result/add?project_id=' + project_id,
                    edit_url: 'ygame/result/edit?project_id=' + project_id,
                    del_url: 'ygame/result/del?project_id=' + project_id,
                    multi_url: 'ygame/result/multi?project_id=' + project_id,
                    table: 'ygame_result',
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
                        {field: 'rank', title: __('Rank'), operate: 'LIKE'},
                        {field: 'code', title: __('Code'), operate: 'LIKE'},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'idcard', title: __('Idcard'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'group', title: __('Group'), operate: 'LIKE'},

                        {field: 'result', title: __('Result'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            require(['upload'], function(Upload){

                Upload.api.plupload($(".btn-import_excel"), function(data, ret){
                    Layer.msg('导入中', {
                        icon: 16
                        ,shade: 0.1,
                        time:100000
                    });
                    $.ajax({
                        type:'POST',
                        url:'ygame/result/import?project_id='+$(".btn-import_excel").attr('data-project_id'),
                        data:{file:data.url},
                        success:function (response) {
                            if(response.code){
                                Toastr.success("导入成功");
                                table.bootstrapTable('refresh');
                            }else{
                                Toastr.error(response.msg);
                                table.bootstrapTable('refresh');
                            }
                            Layer.closeAll()
                        }
                    })
                }, function(data, ret){

                });
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        design: function () {

            require(['tdrag'], function(Tdrag){
                //插件逻辑代码
                var fields = $("#field_input").val() == ""?[]:JSON.parse($("#field_input").val());
                var fontsize = $("#c-fontsize").val()+"px";
                var color = $("#c-color").val();

                fields.forEach(function (item,index) {
                    $(".field[data-field="+item.field+"]").addClass("btn-danger").removeClass("btn-default");
                    $("#fixed_area").append('<span class="fields" data-field="'+item.field+'" data-value="'+item.value+'" style="top:'+item.top+' ;left: '+item.left+'">'+item.value+'</span>');

                    $(".fields[data-field="+item.field+"]").Tdrag({
                        scope:"#fixed_area",
                        //dragChange:true,
                        cbEnd:function(){
                            fields = [];
                            $(".fields").map(function () {
                                fields.push({field:$(this).data('field'),value:$(this).data('value'),top:$(this).css('top'),left:$(this).css('left')})
                            })
                            $("#field_input").val(JSON.stringify(fields));
                        }
                    });
                    $(".fields").css('font-size',fontsize).css('color',color);
                });


                $("#c-fontsize").blur(function(){
                    fontsize = $(this).val()+"px";
                    $(".fields").css('font-size',fontsize).css('color',color);
                })

                $("#c-color").blur(function(){
                    color = $(this).val();
                    $(".fields").css('font-size',fontsize).css('color',color);
                })

                $(".field").click(function(){
                    if($(this).hasClass("btn-danger")){
                        $(this).addClass("btn-default").removeClass("btn-danger");
                        $("#fixed_area").find("span[data-field="+$(this).data('field')+"]").remove();
                        var that = this;
                        fields.forEach(function (item,index) {

                            if (item.field == $(that).data('field')) {
                                fields.splice(index,1);
                            }
                        });
                    }else{
                        $(this).addClass("btn-danger").removeClass("btn-default");

                        fields.push({field:$(this).data('field'),left:'0px',top:'0px',value:$(this).data('value')})
                        $("#fixed_area").append('<span class="fields" data-field="'+$(this).data('field')+'" data-value="'+$(this).data('value')+'" >'+$(this).data('value')+'</span>');
                        $(".fields[data-field="+$(this).data('field')+"]").Tdrag({
                            scope:"#fixed_area",

                            cbEnd:function(){
                                fields = [];
                                $(".fields").map(function () {
                                    fields.push({field:$(this).data('field'),value:$(this).data('value'),top:$(this).css('top'),left:$(this).css('left')})
                                })
                                $("#field_input").val(JSON.stringify(fields));
                            }
                        });
                        $(".fields").css('font-size',fontsize).css('color',color);
                    }
                    $("#field_input").val(JSON.stringify(fields));
                })

                $("#plupload-image").data("upload-success", function(data){
                    $("#design_bg").attr('src',data.url);
                });
            });



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