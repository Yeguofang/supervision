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
                    text     : '发起检查',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/master/qualitycheck',
                 },
                {
                    name     : 'detail',
                    text     : '检查记录',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/vouchermaster/index',
                 },
                {
                    name     : 'select',
                    text     : '选择副站长',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/master/select',
                },
                {
                    name     : 'detail',
                    text     : '详细信息',
                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                    url      : 'quality/master/detail',
                },
                // {
                //     name     : 'quality',
                //     text     : '登记告知书',
                //     classname: 'btn btn-info btn-xs btn-detail  download',
                //     url      : 'quality/master/quality',
                //     extend   : 'target="_blank"',
                //     visible  : function (row) {
                //         //下发了告知书才显示
                //         if(row.quality_code!=null){
                //             return true;
                //         }
                //         return false;
                //     }
                // }
            ];
            var table = $("#table");

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
                    btn    : ["选中项(" + ids.length + "条)", "全部(" + all + "条)"],
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
            $(document).on("click", ".btn-check", function () {
                var ids  = Table.api.selectedids(table);
                var page = table.bootstrapTable('getData');
                var all  = table.bootstrapTable('getOptions').totalRows;
                console.log(ids, page, all);
                Layer.confirm("请选择导出的选项<form action='" + Fast.api.fixurl("quality/info/checkExport") + "' method='post' target='_blank'><input type='hidden' name='ids' value='' /><input type='hidden' name='filter' ><input type='hidden' name='op'><input type='hidden' name='search'><input type='hidden' name='columns'></form>", {
                    title  : '导出数据',
                    btn    : ["选中项(" + ids.length + "条)", "全部(" + all + "条)"],
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
                        {field: 'a.nickname', title: '质监员', operate:false},
                        {field: 'i.project_kind', title: '工程类别', formatter:Controller.api.formatter.kind,searchList: {'0':'市政建设','1': '房建'}},
                        {field: 'i.situation', title: '工程进度', formatter:Controller.api.formatter.situation,searchList: {'0':'路基处理','1': '路面工程','2':'排水系统','3':'绿化照明','4':'标识标线','5':'完成','6':'竣工验收','00':'基础阶段','11':'主体阶段','22':'装饰阶段','33':'收尾','44':'完工','55':'竣工验收'}},
                        {field: 'i.status', title: '工程状态', formatter:Controller.api.formatter.status,searchList: {'0':'未开工','1': '在建','2':'质量停工','3':'安全停工','4':'局停工','5':'自停工'}},
                        {field: 'quality_progress', title: '质监进度', formatter:Controller.api.formatter.quality_progress,searchList: {'0':'未处理','1': '已申请竣工并已通知副站','2':'已通知站长','3':'同意'}},
                        {field: 'i.energy', title: '节能', formatter:Controller.api.formatter.energy,searchList: {'0':'否','1': '是'}},
                        {field: 'permit_time', title: '施工许可审批时间', operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'register_time', title: '监督注册表审批时间', operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'project_type', title: '工程项目',operate: 'FIND_IN_SET',formatter:Controller.api.formatter.project_type,searchList: {'1':'地产','2': '宅','3':'保障性住房','4':'公共建筑','5':'工业建筑','6':'装修装饰','7':'建筑设备安装','8':'市政基础设施'} },
                        {field: 'l.construction_company', title: '施工单位', operate: "LIKE"},
                        {field: 'l.supervision_company', title: '监理单位',operate: "LIKE"},
                        {field: 'i.check_company', title: '检测单位',operate: "LIKE"},

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
                },
                energy:function (value,row,index) {
                    if(value == '0'){
                        return "<label class='label bg-red'>否</label>"
                    }else if(value == '1'){
                        return "<label class='label bg-green'>是</label>"
                    }
                },
                status:function (value,row,index) {
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
                    } else if (row['i.project_kind'] == '1') {
                        if(value == '0'){
                            return "<label class='label bg-green'>基础阶段</label>"
                        }else if(value == '1'){
                            return "<label class='label bg-green'>主体阶段</label> &nbsp;&nbsp;" + row['i.schedule'];
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
                    
                },
                project_type: function (value, row, index) {//项目工程显示处理
                  if(value[0] ==''){
                      return '-';
                    }else{  
                    var project_type = [
                        '','地产','宅','保障性住房','公共建筑','工业建筑','装修装饰','建筑设备安装','市政基础设施',
                    ];
                    let str = '';
                    value.forEach((v,i) => {
                        str += project_type[v]+',';
                    })
                    return ;
                  }
            }
            }
        }
    };
    return Controller;
});