define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'safety/danger/deList',
                    add_url  : 'safety/danger/add',
                    // edit_url: 'quality/chief/edit',
                    // del_url: 'quality/chief/del',
                    // multi_url: 'quality/chief/multi',
                }
            });
            var buttons = [
           
                {
                    name     : 'detail',
                    text     : '查看',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'safety/danger/detail',
                }

            ];

            
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
                        {field: 'supervision_number', title: '监督编号', operate: "LIKE"},
                        {field: 'project_name', title: '工程名称', operate: "LIKE"},
                        {field: 'address', title: '工程地点', operate:false},
                        {field: 'proof_time', title: '论证时间', operate:false},
                        { field: 'proof_address', title: '论证地点', operate: false },
                        { field: 'proof_info', title: '论证情况', operate: false },
                        { field: 'proof_content', title: '论证内容', operate: false },
                        {field: 'build_info', title: '施工情况', operate:false },
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,buttons:buttons}
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
        deal:function(){
            Controller.api.bindevent();
        },
        select:function(){
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