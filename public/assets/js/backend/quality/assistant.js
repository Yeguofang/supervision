define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quality/assistant/index',
                    // add_url: 'quality/master/add',
                    // edit_url: 'quality/master/edit',
                    // del_url: 'quality/master/del',
                    // multi_url: 'quality/master/multi',
                }
            });
            var buttons = [
                {
                    name     : 'detail',
                    text     : '项目图片',
                    icon     : 'fa fa-image',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/voucherapply/index',
                 },
                {
                    name     : 'select',
                    text     : '选择主责',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/assistant/select',
                },
                {
                    name     : 'detail',
                    text     : '详细信息',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/assistant/detail',
                },
                {
                    name     : 'quality',
                    text     : '登记告知书',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail  download',
                    url      : 'quality/assistant/quality',
                    extend   : 'target="_blank"',
                    visible  : function (row) {
                        //下发了告知书才显示
                        if(row.quality_code!=null){
                            return true;
                        }
                        return false;
                    }
                }

            ];
            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url        : $.fn.bootstrapTable.defaults.extend.index_url,
                pk      : 'id',
                sortName: 'id',
                columns    : [
                    [
                        //id,worker_code,nickname,mobile,supervisor_card,admin_code,is_law,username,admin_level
                        {checkbox: true},
                        {field: 'id', title: '序号', sortable: true, operate: false},
                        {field: 'build_dept', title: '建设单位', operate: "LIKE"},
                        {field: 'project_name', title: '工程名称', operate: "LIKE"},
                        {field: 'address', title: '建设地址', operate:false},
                        {field: 'i.project_kind', title: '工程类别', formatter:Controller.api.formatter.kind,searchList: {'0':'市政建设','1': '房建'}},
                        {field: 'i.status', title: '工程状态', formatter:Controller.api.formatter.status,searchList: {'0':'未开工','1': '在建','2':'质量停工','3':'安全停工','4':'局停工','5':'自停工'}},
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
                kind:function (value,row,index) {
                    if(value == '0'){
                        return "<label class='label bg-green'>市政建设</label>"
                    }else if(value == '1'){
                        return "<label class='label bg-green'>房建</label>"
                    }
                },status:function (value,row,index) {
                    if(value == '0'){
                        return "<label class='label bg-orange'>未开工</label>"
                    }else if(value == '1'){
                        return "<label class='label bg-green'>在建</label>"
                    }else if(value == '2'){
                        return "<label class='label bg-red'>质量停工</label>"
                    }else if(value == '3'){
                        return "<label class='label bg-red'>安全停工</label>"
                    }else if(value == '4'){
                        return "<label class='label bg-red'>局停工</label>"
                    }else if(value == '5'){
                        return "<label class='label bg-red'>自停工</label>"
                    }
                }
            }
        }
    };
    return Controller;
});