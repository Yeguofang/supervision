define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'safety/master/index',
                    // add_url: 'quality/master/add',
                    // edit_url: 'quality/master/edit',
                    // del_url: 'quality/master/del',
                    // multi_url: 'quality/master/multi',
                }
            });
            var buttons = [
                // {
                //     name     : 'detail',
                //     text     : '发起检查',
                //     icon     : 'fa fa-image',
                //     classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                //     url      : 'safety/master/qualitycheck',
                //  },
                {
                    name     : 'detail',
                    text     : '重大危险源',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'safety/danger/index',
                 },
                {
                    name     : 'select',
                    text     : '起重机械',
                    classname: 'btn btn-warning btn-xs btn-detail btn-dialog',
                    url      : 'safety/device/index',
                },
                {
                    name     : 'detail',
                    text     : '期限整改通知书',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'safety/term/index',
                },
                {
                    name     : 'detail',
                    text     : '施工安全监督抽查记录',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'safety/build/index',
                },
                {
                    name     : 'safety',
                    text     : '暂停施工整改通知书',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    extend   : 'target="_blank"',
                    url      : 'safety/suspend/index',
                },
                {
                    name     : 'safety',
                    text     : '停工整改通知书',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    extend   : 'target="_blank"',
                    url      : 'safety/stop/index',
                },
            ];

            
            var submitForm = function (ids, layero) {
                var options = table.bootstrapTable('getOptions');
                console.log(options);
                var columns = [];
                $.each(options.columns[0], function (i, j) {
                    if (j.field && !j.checkbox && j.visible && j.field != 'operate') {
                        columns.push(j.field);
                    }
                });
                var search = options.queryParams({});
                $("input[name=search]", layero).val(options.searchText);
                $("input[name=ids]", layero).val(ids);
                $("input[name=filter]", layero).val(search.filter);
                $("input[name=op]", layero).val(search.op);
                $("input[name=columns]", layero).val(columns.join(','));
                $("form", layero).submit();z
            };
            $(document).on("click", ".btn-export", function () {
                var ids  = Table.api.selectedids(table);
                var page = table.bootstrapTable('getData');
                var all  = table.bootstrapTable('getOptions').totalRows;
                console.log(ids, page, all);
                Layer.confirm("请选择导出的选项<form action='" + Fast.api.fixurl("quality/info/export") + "' method='post' target='_blank'><input type='hidden' name='ids' value='' /><input type='hidden' name='filter' ><input type='hidden' name='op'><input type='hidden' name='search'><input type='hidden' name='columns'></form>", {
                    title  : '导出数据',
                    btn    : ["选中项(" + ids.length + "条)", "本页(" + page.length + "条)", "全部(" + all + "条)"],
                    success: function (layero, index) {
                        $(".layui-layer-btn a", layero).addClass("layui-layer-btn0");
                    }
                    , yes: function (index, layero) {
                        if (ids.length == 0) {
                            alert('请先选择数据！');
                            return false;
                        }
                        submitForm(ids.join(","), layero);
                        return false;
                    }
                    ,
                    btn2: function (index, layero) {
                        var ids = [];
                        $.each(page, function (i, j) {
                            ids.push(j.id);
                        });
                        submitForm(ids.join(","), layero);
                        return false;
                    }
                    ,
                    btn3: function (index, layero) {
                        submitForm("all", layero);
                        return false;
                    }
                })
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
                        {field: 'licence_code', title: '监督编号', operate: "LIKE"},
                        {field: 'build_dept', title: '建设单位', operate: "LIKE"},
                        {field: 'project_name', title: '工程名称', operate: "LIKE"},
                        {field: 'address', title: '建设地址', operate:false},
                        // {field: 'a.nickname', title: '安监员', operate:false},
                        // {field: 's.nickname', title: '安监副站长', operate:false},
                        // {field: 'i.project_kind', title: '工程类别', formatter:Controller.api.formatter.kind,searchList: {'0':'市政建设','1': '房建'}},
                        // {field: 'i.situation', title: '工程概况', formatter:Controller.api.formatter.situation,searchList: {'0':'路基处理','1': '路面工程','2':'排水系统','3':'绿化照明','4':'标识标线','5':'完成','6':'竣工验收'}},
                        // {field: 'i.status', title: '工程状态', formatter:Controller.api.formatter.status,searchList: {'0':'未开工','1': '在建','2':'质量停工','3':'安全停工','4':'局停工','5':'自停工'}},
                        // {field: 'supervisor_progress', title: '安监进度', formatter:Controller.api.formatter.supervisor_progress,searchList: {'0':'未处理','1': '已申请中止施工并已通知副站','2':'已通知副站','3':'同意'}},
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
                },supervisor_progress:function(value,row,index){
                     if(value == '0'){
                        return "<label class='label bg-orange'>未开工</label>"
                    }else if(value == '1'){
                        return "<label class='label bg-green'>已申请中止施工<br/>并已通知副站</label>"
                    }else if(value == '2'){
                        return "<label class='label bg-red'>已通知副站</label>"
                    }else if(value == '3'){
                        return "<label class='label bg-red'>同意</label>"
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