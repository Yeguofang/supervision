define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'safety/device/list',
                    add_url  : 'safety/device/add',
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
                    url      : 'safety/device/detail',
                }
            ];

            
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url         : $.fn.bootstrapTable.defaults.extend.index_url,
                pk          : 'id',
                sortName    : 'id',
                escape      : false,
                showToggle  : false,
                search      : false,
                commonSearch: false,
                showExport  : false,
                columns     : [
                    [	

                        //id,worker_code,nickname,mobile,supervisor_card,admin_code,is_law,username,admin_level
                        {checkbox: true},
                        {field: 'id', title: '序号', sortable: true, operate: false},
                        {field: 'supervision_number', title: '监督编号', operate: "LIKE"},
                        {field: 'project_name', title: '工程名称', operate: false},
                        {field: 'type', title: '起重机械类别',operate: false },
                        { field: 'device_record', title: '设备备案号', operate: false },
                        { field: 'install_unit', title: '安装单位', operate: false },
                        { field: 'install_time', title: '安装告知时间', operate: false },
                        { field: 'test_time', title: '检测时间', operate: false },
                        {field: 'test_end_time', title: '检测到期时间', operate:false},
                        { field: 'handle_time', title: '办理使用登记牌时间',  operate:false},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,buttons:buttons}
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
            },
           
        }
    };
    return Controller;
});