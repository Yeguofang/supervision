define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'projectfiling/filing/index',
                }
            });
            var buttons = [
                {
                    name     : 'applyEdit',
                    text     : '工程备案',
                    title    : '工程备案',
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    icon     : 'fa fa-list',
                    url      : 'projectfiling/filing/recodeStatus',
                    confirm  : '确认备案？',
                    visible  : function (row) {
                        //没有验收日期，备案按钮不显示
                        if (row.check_time != null) {
                            //已备案工程，备案按钮隐藏
                            if (row.recode_status == 1) {
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
            ];
            
            var table = $("#table");
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".small").data("area", ["200px","90px"]);

            });
            // 初始化表格
            table.bootstrapTable({
                url        : $.fn.bootstrapTable.defaults.extend.index_url,
                escape     : false,
                sortName   : 'id',
                pagination : false,
                showToggle : false,
                showColumns: false,
                showExport : false,
                search     : false,
                columns    : [
                    [
                        //id,worker_code,nickname,mobile,supervisor_card,admin_code,is_law,username,admin_level
                        {checkbox: true},
                        {field: 'id', title: '序号', sortable: true, operate: false},
                        {field: 'build_dept', title: '建设单位', operate: "LIKE"},
                        {field: 'project_name', title: '工程名称', operate: "LIKE"},
                        {field: 'address', title: '建设地址', operate:false},
                        {field: 'push_time', title: '报建日期', operate:false},
                        {field: 'supervise_time', title: "报监日期", operate: false },
                        {field: 'begin_time', title: "开工日期", operate: false },
                        {field: 'finish_time', title: "竣工日期",operate:false},
                        {field: 'check_time', title: "验收日期", operate: "false" },
                        {field: 'recode_status', title: "备案状态",formatter:Controller.api.formatter.recode_status,searchList: {'0':'未备案','1': '已备案'}},
                        {field: 'record_time', title: "备案日期",operate:"like"},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,buttons:buttons}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        notice:function(){
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter:{
                recode_status:function (value,row,index) {
                    if(value == '0'){
                        return "<label class='label bg-red'>未备案</label>";
                    }else if(value == '1'){
                        return "<label class='label bg-green'>已备案</label>";
                    }
                }
            }
        }
    };
    return Controller;
});