define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'quality/voucherapply/list',
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
                    name     : 'applyEdit',
                    text     : '同意修改',
                    title    : '同意修改',
                    classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                    icon     : 'fa fa-pencil',
                    url      : 'quality/voucherapply/statusEdit',
                    confirm  : '确认同意？',
                    visible  : function (row) {
                        if (row.edit_status == 1) {
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
                    url      : 'quality/voucherapply/statusDel',
                    confirm  : '确认同意？',
                    visible  : function (row) {
                        if(row.del_status==1){
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
                       
                        {field: 'id', title: __('序号'),operate: false, },
                        {field: 'licence_code', title: '监督编号', operate: "LIKE"},
                        {field: 'i.build_dept', title: __('建设单位')},
                        { field: 'i.project_name', title: __('工程名称') },
                        {field: 'situation', title: __('工程进度'),operate: false,formatter:Controller.api.formatter.situation},
                        {field: 'project_images', title: __('项目图片'),operate: false, formatter: Table.api.formatter.images},
                        {field: 'project_desc', title: __('检查说明')},
                        { field: 'push_time', title: __('上传时间'), operate: 'RANGE', addclass: 'datetimerange', formatter: Table.api.formatter.datetime },
                        {field: 'edit_status', title: '操作',operate: false,table: table, events: Table.api.events.operate,formatter: Table.api.formatter.operate, buttons: buttons},
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
            formatter: {
                situation:function(value,row,index){
                     if(row['kind'] == '0'){
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
                     } else if (row['kind'] == '1') {
                        if(value == '0'){
                            return "<label class='label bg-green'>基础阶段</label>"
                        }else if(value == '1'){
                             return "<label class='label bg-green'>主体阶段</label>  &nbsp;&nbsp;" + row['schedule'];
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