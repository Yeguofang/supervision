define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quality/rectifylist/list',
                }
            });
            var buttons = [
                {
                    name     : 'detail',
                    text     : '详情',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/rectifylist/detail',
                },
              
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
                        {field: 'number', title: '编号', operate: "LIKE"},
                        {field: 'images', title: '图片',  operate:false,formatter: Table.api.formatter.images},
                        {field: 'desc', title: '说明', operate:false},
                        {field: 'time', title: '下发时间',operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
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
        select:function(){
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter:{
                status: function (value, row, index) {
                    if (value == 0) {
                        return "<label class='label bg-green'>未申请</label>"
                    } else if (value == 1) {
                        return "<label class='label bg-green'>已申请</label>"
                    } else if (value == 2) {
                        return "<label class='label bg-green'>副站同意</label>"
                    } else if (value == 3) {
                        return "<label class='label bg-green'>主站同意</label></label>"
                    }
                    
                }
                
            }
        }
    };
    return Controller;
});