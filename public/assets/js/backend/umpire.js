define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'umpire/index' + location.search,
                    add_url: 'umpire/add',
                    edit_url: 'umpire/edit',
                    del_url: 'umpire/del',
                    multi_url: 'umpire/multi',
                    import_url: 'umpire/import',
                    table: 'umpire',
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
                        {field: 'achievement_id', title: __('Achievement_id')},
                        {field: 'identity', title: __('Identity'), searchList: {"裁判长":__('裁判长'),"编排长":__('编排长'),"裁判员":__('裁判员')}, formatter: Table.api.formatter.normal},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'tel', title: __('Tel')},
                        {field: 'user.id', title: __('User.id')},
                        {field: 'user.group_id', title: __('User.group_id')},
                        {field: 'user.username', title: __('User.username'), operate: 'LIKE'},
                        {field: 'user.nickname', title: __('User.nickname'), operate: 'LIKE'},
                        {field: 'user.password', title: __('User.password'), operate: 'LIKE'},
                        {field: 'user.salt', title: __('User.salt'), operate: 'LIKE'},
                        {field: 'user.email', title: __('User.email'), operate: 'LIKE'},
                        {field: 'user.mobile', title: __('User.mobile'), operate: 'LIKE'},
                        {field: 'user.avatar', title: __('User.avatar'), operate: 'LIKE', events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'user.level', title: __('User.level')},
                        {field: 'user.vip', title: __('User.vip')},
                        {field: 'user.gender', title: __('User.gender')},
                        {field: 'user.birthday', title: __('User.birthday'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'user.bio', title: __('User.bio'), operate: 'LIKE'},
                        {field: 'user.money', title: __('User.money'), operate:'BETWEEN'},
                        {field: 'user.score', title: __('User.score')},
                        {field: 'user.successions', title: __('User.successions')},
                        {field: 'user.maxsuccessions', title: __('User.maxsuccessions')},
                        {field: 'user.prevtime', title: __('User.prevtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'user.logintime', title: __('User.logintime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'user.loginip', title: __('User.loginip'), operate: 'LIKE'},
                        {field: 'user.loginfailure', title: __('User.loginfailure')},
                        {field: 'user.joinip', title: __('User.joinip'), operate: 'LIKE'},
                        {field: 'user.jointime', title: __('User.jointime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'user.createtime', title: __('User.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'user.updatetime', title: __('User.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'user.token', title: __('User.token'), operate: 'LIKE'},
                        {field: 'user.status', title: __('User.status'), operate: 'LIKE', formatter: Table.api.formatter.status},
                        {field: 'user.verification', title: __('User.verification'), operate: 'LIKE'},
                        {field: 'achievement.id', title: __('Achievement.id')},
                        {field: 'achievement.orgevent_id', title: __('Achievement.orgevent_id')},
                        {field: 'achievement.number', title: __('Achievement.number')},
                        {field: 'achievement.name', title: __('Achievement.name'), operate: 'LIKE'},
                        {field: 'achievement.troop', title: __('Achievement.troop'), operate: 'LIKE'},
                        {field: 'achievement.sex', title: __('Achievement.sex')},
                        {field: 'achievement.bseedsswitch', title: __('Achievement.bseedsswitch'), table: table, formatter: Table.api.formatter.toggle},
                        {field: 'achievement.bcomposswitch', title: __('Achievement.bcomposswitch'), table: table, formatter: Table.api.formatter.toggle},
                        {field: 'achievement.tel', title: __('Achievement.tel')},
                        {field: 'achievement.idnumber', title: __('Achievement.idnumber'), operate: 'LIKE'},
                        {field: 'achievement.native', title: __('Achievement.native'), operate: 'LIKE'},
                        {field: 'achievement.zjf', title: __('Achievement.zjf')},
                        {field: 'achievement.cumulative', title: __('Achievement.cumulative')},
                        {field: 'achievement.dszjf', title: __('Achievement.dszjf')},
                        {field: 'achievement.cyclesum', title: __('Achievement.cyclesum')},
                        {field: 'achievement.victorysum', title: __('Achievement.victorysum')},
                        {field: 'achievement.deucesum', title: __('Achievement.deucesum')},
                        {field: 'achievement.group', title: __('Achievement.group'), operate: 'LIKE'},
                        {field: 'achievement.sumwarning', title: __('Achievement.sumwarning')},
                        {field: 'achievement.failsum', title: __('Achievement.failsum')},
                        {field: 'achievement.summation', title: __('Achievement.summation')},
                        {field: 'achievement.conpositor', title: __('Achievement.conpositor')},
                        {field: 'achievement.vicsum', title: __('Achievement.vicsum')},
                        {field: 'achievement.backbou', title: __('Achievement.backbou')},
                        {field: 'achievement.victory', title: __('Achievement.victory')},
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
