define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'dept/quality/index',
                    add_url  : 'dept/quality/add',
                    edit_url : 'dept/quality/edit',
                    del_url  : 'dept/quality/del',
                    // multi_url: 'user/user/multi',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url       : $.fn.bootstrapTable.defaults.extend.index_url,
                pk        : 'id',
                sortName  : 'id',
                escape    : false,
                showToggle: false,
                search    : false,
                showExport: false,
                columns   : [
                    [
                        //id,worker_code,nickname,mobile,supervisor_card,admin_code,is_law,username,admin_level
                        {checkbox: true},
                        {field: 'id', title: '序号', sortable: true, operate: false},
                        {field: 'worker_code', title: '工号', operate: "LIKE"},
                        {field: 'nickname', title: '姓名', operate: "LIKE"},
                        {field: 'mobile', title: '联系方式', operate: "LIKE"},
                        {field: 'supervisor_card', title: '监督员证', operate: "LIKE"},
                        {field: 'admin_code', title:'编号', operate: "="},
                        {field: 'is_law', title: '是否有执法员证', formatter:Controller.api.formatter.law,searchList: {'0':'没有','1': '有'}},
                        {field: 'username', title: '登录账号', operate: "LIKE"},
                        {field: 'group_id', title: '权限', formatter:Controller.api.formatter.level},
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
            },
            formatter:{
                law:function (value,row,index) {
                    if(value == '0'){
                        return "<label class='label bg-red'>没有</label>"
                    }else if(value == '1'){
                        return "<label class='label bg-green'>有</label>"
                    }
                },
                level:function (value,row,index) {
                    if(value == '10'){
                        return "<label class='label bg-green'>质监站长</label>"
                    }else if(value == '11'){
                        return "<label class='label bg-green'>质监副站长</label>"
                    }else if(value == '12'){
                        return "<label class='label bg-green'>质监员</label>"
                    }else if(value == '13'){
                        return "<label class='label bg-green'>质监资料录入员</label>"
                    }
                }
            }
        }
    };
    return Controller;
});