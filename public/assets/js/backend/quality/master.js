define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quality/master/index',
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
                    url      : 'quality/vouchermaster/index',
                 },
                {
                    name     : 'select',
                    text     : '选择副站长',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/master/select',
                },
                {
                    name     : 'detail',
                    text     : '详细信息',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/master/detail',
                },
                {
                    name     : 'quality',
                    text     : '登记告知书',
                    icon     : 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail  download',
                    url      : 'quality/master/quality',
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
                        {field: 'a.nickname', title: '质监员', operate:false},
                        {field: 'z.nickname', title: '质监副站长', operate:false},
                        {field: 'i.project_kind', title: '工程类别', formatter:Controller.api.formatter.kind,searchList: {'0':'市政建设','1': '房建'}},
                        {field: 'i.situation', title: '工程概况', formatter:Controller.api.formatter.situation,searchList: {'0':'路基处理','1': '路面工程','2':'排水系统','3':'绿化照明','4':'标识标线','5':'完成','6':'竣工验收'}},
                        {field: 'i.status', title: '工程状态', formatter:Controller.api.formatter.status,searchList: {'0':'未开工','1': '在建','2':'质量停工','3':'安全停工','4':'局停工','5':'自停工'}},
                        {field: 'quality_progress', title: '工程进度', formatter:Controller.api.formatter.quality_progress,searchList: {'0':'未处理','1': '已申请竣工并已通知副站','2':'已通知站长','3':'同意'}},
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
                },quality_progress:function(value,row,index){
                    if(value == '0'){
                        return "<label class='label bg-orange'>未处理</label>"
                    }else if(value == '1'){
                        return "<label class='label bg-green'>已申请竣工并已通知副站</label>"
                    }else if(value == '2'){
                        return "<label class='label bg-red'>已通知站长</label>"
                    }else if(value == '3'){
                        return "<label class='label bg-red'>同意竣工</label>"
                    }
                },situation:function(value,row,index){
                    if(row['i.project_kind'] == '0'){
                        if(value == '0'){
                            return "<label class='label bg-orange'>路基处理</label>"
                        }else if(value == '1'){
                            return "<label class='label bg-green'>路面工程</label>"
                        }else if(value == '2'){
                            return "<label class='label bg-red'>排水系统</label>"
                        }else if(value == '3'){
                            return "<label class='label bg-red'>绿化照明</label>"
                        }else if(value == '4'){
                            return "<label class='label bg-red'>标识标线</label>"
                        }else if(value == '5'){
                            return "<label class='label bg-red'>完成</label>"
                        }else if(value == '6'){
                            return "<label class='label bg-red'>竣工验收</label>"
                        }
                    }else if(row['i.project_kind'] == '1'){
                        if(value == '0'){
                            return "<label class='label bg-orange'>基础阶段</label>"
                        }else if(value == '1'){
                            return "<label class='label bg-green'>主体阶段</label>"
                        }else if(value == '2'){
                            return "<label class='label bg-red'>装饰阶段</label>"
                        }else if(value == '3'){
                            return "<label class='label bg-red'>收尾</label>"
                        }else if(value == '4'){
                            return "<label class='label bg-red'>完工</label>"
                        }else if(value == '5'){
                            return "<label class='label bg-red'>竣工验收</label>"
                        }
                    }

                }
            }
        }
    };
    return Controller;
});