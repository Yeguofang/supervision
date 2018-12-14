define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quality/vouchermaster/list',
                 },
            });

            var buttons = [
               
                {
                    name     : 'applyEdit',
                    text     : '同意修改',
                    title    : '同意修改',
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    icon     : 'fa fa-pencil',
                    url      : 'safety/vouchermaster/statusEdit',
                    confirm  : '确认同意？',
                    visible  : function (row) {
                        if (row.edit_status == 2) {
                            return true;
                        }
                        return false;
                    },
                    success  : function (data, ret) {
                        Layer.alert(ret.msg);
                    },
                },
                {
                    name     : 'applyDel',
                    text     : '同意删除',
                    title    : '同意删除',
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    icon     : 'fa fa-trash',
                    url      : 'safety/vouchermaster/statusDel',
                    confirm  : '确认同意？',
                    visible  : function (row) {
                        if(row.del_status==2){
                             return true;
                        }
                        return false;
                    },
                    success  : function (data, ret) {
                        Layer.alert(ret.msg );
                        
                    },
                 },
               
            ];
           
            
            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url     : $.fn.bootstrapTable.defaults.extend.index_url,
                pk      : 'id',
                sortName: 'id',
                columns : [
                    [
                        {checkbox: true},
                       
                        {field: 'id', title: __('序号') },
                        {field: 'i.build_dept', title: __('建设单位')},
                        {field: 'i.project_name', title: __('工程名称') },
                        {field: 'project_images', title: __('项目图片'), formatter: Table.api.formatter.images},
                        {field: 'project_desc', title: __('图片说明')},
                        { field: 'push_time', title: __('上传时间'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime },
                        {field: 'edit_status', title: '审批',table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate, buttons: buttons},
                   ]
                    
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        statusEdit: function () {
            Controller.api.bindevent();
        },
        statusDel: function () {
            Controller.api.bindevent();
        },
        api     : {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
           
        }
    };
    return Controller;
});