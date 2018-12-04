define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'check/quality/index',
                    // add_url: 'project/application/add',
                    // edit_url: 'project/application/edit',
                    // del_url: 'project/application/del',
                    // multi_url: 'user/user/multi',
                }
            });
            var buttons = [
                {
                    name: 'detail',
                    text: '五大责任主体详情',
                    icon: 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url: 'check/quality/detail',
                }
            ];
            $(document).ready(function () {
                var identy = $("#ret").val();
                $("#ret").remove();
                var str = "同意竣工";
                if(identy==0){
                    str="申请竣工";
                }else if(identy==2){
                    str=str+"并通知主站";
                }
                buttons.push({
                    name: 'notice',
                    text: str,
                    icon: 'fa fa-list',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog small',
                    url: 'check/quality/notice?ret='+identy,
                    visible: function (row) {
                        //返回true时按钮显示,返回false隐藏
                        if(identy==0){
                            //质检员
                            if(row.quality_progress==0)
                                return true;
                        }else if(identy==2){
                            //副站
                            if(row.quality_progress==1)
                                return true;
                        }else if(identy==1){
                            //站长
                            if(row.quality_progress==2)
                                return true;
                        }
                        return false;
                    }
                });
            });
            var table = $("#table");
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".small").data("area", ["200px","90px"]);

            });
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                sortName: 'id',
                pagination: false,
                showToggle: false,
                showColumns: false,
                showExport: false,
                search:false,
                columns: [
                    [
                        //id,worker_code,nickname,mobile,supervisor_card,admin_code,is_law,username,admin_level
                        {checkbox: true},
                        {field: 'id', title: '序号', sortable: true, operate: false},
                        {field: 'build_dept', title: '建设单位', operate: "LIKE"},
                        {field: 'project_name', title: '工程名称', operate: "LIKE"},
                        {field: 'address', title: '建设地址', operate:false},
                        {field: 'quality_progress', title: '质监进度', formatter:Controller.api.formatter.progress,searchList: {'0':'未处理','1': '已通知副站','2': '已通知主站','3': '主站同意'}},
                        {field: 'begin_time', title: "开工日期",operate:"like",formatter: Table.api.formatter.datetime,type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'finish_time', title: "竣工日期",operate:"like",formatter: Table.api.formatter.datetime,type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
                        {field: 'check_time', title: "验收日期",operate:"like",formatter: Table.api.formatter.datetime,type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD"'},
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
                progress:function (value,row,index) {
                    if(value == '0'){
                        return "<label class='label bg-green'>未处理</label>";
                    }else if(value == '1'){
                        return "<label class='label bg-green'>已通知副站</label>";
                    }else  if(value == '2'){
                        return "<label class='label bg-green'>已通知主站</label>";
                    }else  if(value == '3'){
                        return "<label class='label bg-green'>主站同意</label>";
                    }
                }
            }
        }
    };
    return Controller;
});