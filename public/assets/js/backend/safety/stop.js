define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'safety/stop/stoplist',
                    add_url  : 'safety/stop/add',
                }
            });
            var buttons = [
           
                {
                    name     : 'detail',
                    text     : '查看',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'safety/stop/detail',
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
                        { field: 'id', title: '序号', sortable: true, operate: false },
                        { field: 'project_name', title: '工程名称', operate: "LIKE" },
                        {field: 'number', title: '监督编号', operate: "LIKE"},
                        {field: 'images', title: '图片', operate: false, formatter: Table.api.formatter.images},
                        {field: 'desc', title: '说明', operate:false},
                        { field: 'now_time', title: '日期',operate: 'RANGE', addclass: 'datetimerange',},
                        {field: 'expire_time', title: '到期提醒时间', operate: 'RANGE', addclass: 'datetimerange',},
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