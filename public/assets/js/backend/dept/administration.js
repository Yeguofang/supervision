define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'dept/administration/index',
                    add_url: 'dept/administration/add',
                    edit_url: 'dept/administration/edit',
                    del_url: 'dept/administration/del',
                    // multi_url: 'user/user/multi',
                }
            });
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk      : 'id',
                sortName: 'id',
                columns: [
                    [
                        //id,worker_code,nickname,mobile,supervisor_card,admin_code,is_law,username,admin_level
                        {checkbox: true},
                        {field: 'id', title: '序号', sortable: true, operate: false},
                        {field: 'worker_code', title: '工号', operate: "LIKE"},
                        {field: 'nickname', title: '姓名', operate: "LIKE"},
                        {field: 'mobile', title: '联系方式', operate: "LIKE"},
                        {field: 'username', title: '登录账号', operate: "LIKE"},
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