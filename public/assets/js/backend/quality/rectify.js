define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quality/rectify/index',
                    // add_url: 'quality/master/add',
                    // edit_url: 'quality/master/edit',
                    // del_url: 'quality/master/del',
                    // multi_url: 'quality/master/multi',
                }
            });
            var buttons = [
                {
                    name     : 'detail',
                    text     : '查看整改书',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/rectifylist/index',
                },
              
            ];
            
            $(document).ready(function () {
                var identy = $("#ret").val();
                $("#ret").remove();
                var str = '申请下发整改书';  //质监员
                if(identy == 1 ){//站长
                    str = "同意下发";
                }else if(identy==2){//副站长
                    str = "同意下发";
                }
                buttons.push({
                    name     : 'notice',
                    text     : str,
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    url      : 'quality/rectify/rectifyApply?identy='+identy,
                    confirm  : '是否确认？',
                    visible  : function (row) {
                        //返回true时按钮显示,返回false隐藏
                        if(identy == 0){
                            //质检员
                            if(row.rectify_status == 0 )
                                return true;
                        }else if(identy==1){
                            //站长
                            if(row.rectify_status==2)
                                return true;
                        }else if(identy==2){
                            //副站
                            if(row.rectify_status==1)
                                return true;
                        }
                        return false;
                    },
                    success  : function (data, ret) {
                        Layer.alert(ret.msg);
                    },
                },
                {
                    name     : 'select',
                    text     : '下发整改书',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/rectify/add',
                    visible  : function (row) {
                        //返回true时按钮显示,返回false隐藏
                        if (identy == 0) {
                            //质检员
                            if (row.rectify_status == 3) {
                                return true;
                            }
                            return false;
                        }
                    }
                }
                );
            });
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
                        {field: 'build_dept', title: '建设单位', operate: "LIKE"},
                        {field: 'project_name', title: '工程名称', operate: "LIKE"},
                        {field: 'address', title: '建设地址', operate:false},
                        {field: 'a.nickname', title: '质监员', operate:false},
                        { field: 'z.nickname', title: '质监副站长', operate: false },
                        { field: 'rectify_status', title: '申请状态', operate: false ,formatter:Controller.api.formatter.status,},
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