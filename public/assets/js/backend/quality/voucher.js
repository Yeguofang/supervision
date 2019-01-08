define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quality/voucher/index',
                    add_url  : 'quality/voucher/add',
                    
                   
                },
            });

            var buttons = [
                {
                    name     : 'detail',
                    text     : '查看详情',
                    icon     : 'fa fa-image',
                    classname: 'btn btn-info btn-xs btn-detail btn-addtabs',
                    url      : 'administration/project/checkinfo',
                 },
                {
                    name     : 'statusEdit',
                    text     : '修改',
                    title    : '修改',
                    classname: 'btn btn-xs btn-success btn-dialog',
                    icon     : 'fa fa-pencil',
                    url      : 'quality/voucher/edit',
                    visible  : function (row) {
                        if (row['edit_status'] == 3) {
                            return true;
                        }
                        return false;
                    },
                },
                {
                    name     : 'applyEdit',
                    text     : '申请修改',
                    title    : '申请修改',
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    icon     : 'fa fa-paper-plane-o',
                    url      : 'quality/voucher/statusEdit',
                    confirm  : '确认申请？',
                    visible  : function (row) {
                        if (row.edit_status == 0) {
                            if (row.edit_status == 1) {
                                return false;
                            }
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
                    text     : '申请删除',
                    title    : '申请删除',
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    icon     : 'fa fa-paper-plane-o',
                    url      : 'quality/voucher/statusDel',
                    confirm  : '确认申请？',
                    visible  : function (row) {
                        if(row.del_status==0){
                             return true;
                        }
                        return false;
                    },
                    success  : function (data, ret) {
                        Layer.alert(ret.msg );
                        
                    },
                 },
                {
                    name     : 'statusDel',
                    text     : '删除',
                    title    : '删除',
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    icon     : 'fa fa-trash',
                    url      : 'quality/voucher/del',
                    confirm  : '确认删除？',
                    visible  : function (row) {
                        if(row['del_status']==3){
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
                url       : $.fn.bootstrapTable.defaults.extend.index_url,
                pk        : 'id',
                sortName  : 'id',
                escape    : false,
                showToggle: false,
                search    : false,
                showExport: false,
                columns   : [
                    [
                        {checkbox: true},
                       
                        {field: 'id', title: __('序号') ,operate: false,},
                        {field: 'i.project_name', title: __('工程名称') },
                        {field: 'i.build_dept', title: __('建设单位')},
                        {field: 'project_images', title: __('项目图片'), operate: false,formatter: Table.api.formatter.images},
                        {field: 'project_desc', title: __('检查说明')},
                        { field: 'push_time', title: __('上传时间'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime },
                        { field: 'edit_status', title: '修改权限', formatter:Controller.api.formatter.edit_status, },
                        { field: 'del_status', title: '删除权限', formatter:Controller.api.formatter.del_status},
                        {field: 'edit_status', title: '操作',operate: false,table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate, buttons: buttons},
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
        applyEdit: function () {
            Controller.api.bindevent();
        },
        statusEdit: function () {
            Controller.api.bindevent();
        },
        applyDel: function () {
            Controller.api.bindevent();
        },
        statusDel: function () {
            Controller.api.bindevent();
        },
        api     : {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                edit_status: function (value, row, index) {
                    if (value == '0') {
                        return "<label class='label bg-red'>不可修改</label>"
                    } else if (value == '1') {
                        return "<label class='label bg-blue'>已申请并通知副站长</label>"
                    }else if (value == '2') {
                        return "<label class='label bg-orange'>副站长已同意并通知站长</label>"
                    }else if (value == '3') {
                        return "<label class='label bg-green'>站长同意修改</label>"
                    }
                },
                del_status: function (value, row, index) {
                    if (value == '0') {
                        return "<label class='label bg-red'>不可删除</label>"
                    } else if (value == '1') {
                        return "<label class='label bg-blue'>已申请并通知副站长</label>"
                    }else if (value == '2') {
                        return "<label class='label bg-orange'>副站长已同意并通知站长</label>"
                    }else if (value == '3') {
                        return "<label class='label bg-green'>站长同意删除</label>"
                    }
                },
            }
        }
    };
    return Controller;
});