<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:93:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\safety\info\edit.html";i:1545637548;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545637553;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !$config['fastadmin']['multiplenav']): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <script src="/assets/js/jquery-3.2.1.js"></script>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label for="down_time" class="control-label col-xs-12 col-sm-2">开工交底时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="down_time" data-date-format="YYYY-MM-DD" name="info[down_time]" value="<?php echo $info['down_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="stop_time" class="control-label col-xs-12 col-sm-2">中止施工日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="stop_time" data-date-format="YYYY-MM-DD" name="info[stop_time]" value="<?php echo $info['stop_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="assess_time" class="control-label col-xs-12 col-sm-2">评定办结日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="assess_time" data-date-format="YYYY-MM-DD" name="info[assess_time]" value="<?php echo $info['assess_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="argument_time" class="control-label col-xs-12 col-sm-2">论证日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="argument_time" data-date-format="YYYY-MM-DD" name="info[argument_time]" value="<?php echo $info['argument_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="supervise_time" class="control-label col-xs-12 col-sm-2">报监时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="supervise_time" data-date-format="YYYY-MM-DD" name="row[supervise_time]" value="<?php echo $row['supervise_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="reply_time" class="control-label col-xs-12 col-sm-2">回复日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="reply_time" data-date-format="YYYY-MM-DD" name="info[reply_time]" value="<?php echo $info['reply_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="inspect_time" class="control-label col-xs-12 col-sm-2">检查日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="inspect_time" data-date-format="YYYY-MM-DD" name="info[inspect_time]" value="<?php echo $info['inspect_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="check_time" class="control-label col-xs-12 col-sm-2">验收时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="check_time" data-date-format="YYYY-MM-DD" name="row[check_time]" value="<?php echo $row['check_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="end_time" class="control-label col-xs-12 col-sm-2">完工日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="end_time" data-date-format="YYYY-MM-DD" name="row[end_time]" value="<?php echo $row['end_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="safety_code" class="control-label col-xs-12 col-sm-2">安全监督编号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="safety_code" name="info[safety_code]" value="<?php echo $info['safety_code']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="assess" class="control-label col-xs-12 col-sm-2">工程评定结论:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea rows="3" cols="20" class="form-control" id="assess" name="info[assess]"><?php echo $info['assess']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="danger" class="control-label col-xs-12 col-sm-2">危险性较大的分部分项工程范围:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea rows="3" cols="20" class="form-control" id="danger" name="info[danger]"><?php echo $info['danger']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="scale_danger" class="control-label col-xs-12 col-sm-2">超过一定规模的危险性较大的分部分项工程范围:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea rows="3" cols="20" class="form-control" id="scale_danger" name="info[scale_danger]"><?php echo $info['scale_danger']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="measure" class="control-label col-xs-12 col-sm-2">整改措施:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="measure"  name="info[measure]" class="form-control">
                <option value="">无</option>
                <option value="0" <?php if(in_array(($info['measure']), explode(',',"0"))): ?>selected<?php endif; ?>>抽查</option>
                <option value="1"<?php if(in_array(($info['measure']), explode(',',"1"))): ?>selected<?php endif; ?>>整改</option>
                <option value="2" <?php if(in_array(($info['measure']), explode(',',"2"))): ?>selected<?php endif; ?>>暂停</option>
                <option value="3"<?php if(in_array(($info['measure']), explode(',',"3"))): ?>selected<?php endif; ?>>约谈</option>
                <option value="4" <?php if(in_array(($info['measure']), explode(',',"4"))): ?>selected<?php endif; ?>>扣分</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="content" class="control-label col-xs-12 col-sm-2">整改内容:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea rows="3" cols="20" class="form-control" id="content" name="info[content]"><?php echo $info['content']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div  style="text-align: center;color: red">整改期限 例：2018/11/27 - 2018/12/1</div>
    </div>
    <div class="form-group">
        <label for="deadline" class="control-label col-xs-12 col-sm-2">整改期限:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="deadline" name="info[deadline]" value="<?php echo $info['deadline']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="document_code" class="control-label col-xs-12 col-sm-2">文书编号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="document_code" name="info[document_code]" value="<?php echo $info['document_code']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="remark" class="control-label col-xs-12 col-sm-2">备注:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea rows="3" cols="20" class="form-control" id="remark" name="info[remark]"><?php echo $info['remark']; ?></textarea>
        </div>
    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>
<script>
    $("#project_kind").change(function(){
        if($("#project_kind").val()==1){
            var str = '<option value="0" >基础阶段</option> <option value="1">主体阶段</option> <option value="2" >装饰阶段</option> <option value="3">收尾</option> <option value="4" >完工</option> <option value="5" >竣工验收</option>';
        }else {
            var str = ' <option value="0" >路基处理</option>  <option value="1">路面工程</option> <option value="2" >排水系统</option> <option value="3">绿化照明</option><option value="4" }>标识标线</option> <option value="5">完成</option> <option value="6">竣工验收</option>';
        }
        $("#situation").empty();
        $("#situation").append(str);
    });
</script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>