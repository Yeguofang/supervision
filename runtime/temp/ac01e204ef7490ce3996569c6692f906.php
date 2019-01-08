<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:94:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\quality\info\edit.html";i:1546831359;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
                                &nbsp;
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <script src="/assets/js/jquery-3.2.1.js"></script>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label for="begin_time" class="control-label col-xs-12 col-sm-2">开工时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="begin_time" data-date-format="YYYY-MM-DD" name="row[begin_time]" value="<?php echo $row['begin_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="finish_time" class="control-label col-xs-12 col-sm-2">竣工日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="finish_time" data-date-format="YYYY-MM-DD" name="row[finish_time]" value="<?php echo $row['finish_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="push_time" class="control-label col-xs-12 col-sm-2">报建日期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="push_time" data-date-format="YYYY-MM-DD" name="row[push_time]" value="<?php echo $row['push_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="register_time" class="control-label col-xs-12 col-sm-2">监督注册表审批时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="register_time" data-date-format="YYYY-MM-DD" name="row[register_time]" value="<?php echo $row['register_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="permit_time" class="control-label col-xs-12 col-sm-2">施工许可审批时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" id="permit_time" data-date-format="YYYY-MM-DD" name="row[permit_time]" value="<?php echo $row['permit_time']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="check_company" class="control-label col-xs-12 col-sm-2">检测公司:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="check_company" name="info[check_company]" value="<?php echo $info['check_company']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="check_person" class="control-label col-xs-12 col-sm-2">检测负责人:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="check_person" name="info[check_person]" value="<?php echo $info['check_person']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="picture_company" class="control-label col-xs-12 col-sm-2">图审机构公司名:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="picture_company" name="info[picture_company]" value="<?php echo $info['picture_company']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="picture_person" class="control-label col-xs-12 col-sm-2">图审机构联系人:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="picture_person" name="info[picture_person]" value="<?php echo $info['picture_person']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="build_person" class="control-label col-xs-12 col-sm-2">建设负责人:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="build_person" name="info[build_person]" value="<?php echo $info['build_person']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="project_kind" class="control-label col-xs-12 col-sm-2">工程类别:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="project_kind"  name="info[project_kind]" class="form-control">
                <option value="0" <?php if(in_array(($info['project_kind']), explode(',',"0"))): ?>selected<?php endif; ?>>市政建设</option>
                <option value="1"<?php if(in_array(($info['project_kind']), explode(',',"1"))): ?>selected<?php endif; ?>>房建</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="energy" class="control-label col-xs-12 col-sm-2">节能:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="energy" name="info[energy]" value="<?php echo $info['energy']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="extend" class="control-label col-xs-12 col-sm-2">道路工程延长米(米):</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="extend" name="info[extend]" value="<?php echo $info['extend']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="structure" class="control-label col-xs-12 col-sm-2">结构形式:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="structure" name="info[structure]" value="<?php echo $info['structure']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label  class="control-label col-xs-12 col-sm-2">层数（地上）:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control"  name="floor[]" value="<?php echo $info['floor_up']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label  class="control-label col-xs-12 col-sm-2">层数（地下）:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control"  name="floor[]" value="<?php echo $info['floor_down']; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <div  style="text-align: center;color: red">工程进度 (分子代表进度，分母代表总数 如:1/22)</div>
    </div>
    <div class="form-group">
        <label for="schedule" class="control-label col-xs-12 col-sm-2">工程进度:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="schedule" name="info[schedule]" value="<?php echo $info['schedule']; ?>"  />
        </div>
    </div>
    <div class="form-group">
        <label for="situation" class="control-label col-xs-12 col-sm-2">工程概况:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="situation" name="info[situation]" class="form-control">
            <?php if($info['project_kind'] == 1): ?>
            <option value="1"<?php if(in_array(($info['situation']), explode(',',"1"))): ?>selected<?php endif; ?>>主体阶段</option>
            <option value="2" <?php if(in_array(($info['situation']), explode(',',"2"))): ?>selected<?php endif; ?>>装饰阶段</option>
            <option value="3"<?php if(in_array(($info['situation']), explode(',',"3"))): ?>selected<?php endif; ?>>收尾</option>
            <option value="4" <?php if(in_array(($info['situation']), explode(',',"4"))): ?>selected<?php endif; ?>>完工</option>
            <option value="5"<?php if(in_array(($info['situation']), explode(',',"5"))): ?>selected<?php endif; ?>>竣工验收</option>
            <?php else: ?>
            <option value="0" <?php if(in_array(($info['situation']), explode(',',"0"))): ?>selected<?php endif; ?>>路基处理</option>
            <option value="1"<?php if(in_array(($info['situation']), explode(',',"1"))): ?>selected<?php endif; ?>>路面工程</option>
            <option value="2" <?php if(in_array(($info['situation']), explode(',',"2"))): ?>selected<?php endif; ?>>排水系统</option>
            <option value="3"<?php if(in_array(($info['situation']), explode(',',"3"))): ?>selected<?php endif; ?>>绿化照明</option>
            <option value="4" <?php if(in_array(($info['situation']), explode(',',"4"))): ?>selected<?php endif; ?>>标识标线</option>
            <option value="5"<?php if(in_array(($info['situation']), explode(',',"5"))): ?>selected<?php endif; ?>>完成</option>
            <option value="6"<?php if(in_array(($info['situation']), explode(',',"6"))): ?>selected<?php endif; ?>>竣工验收</option>
            <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class="control-label col-xs-12 col-sm-2">状态:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="status" data-rule="required" name="info[status]" class="form-control">
                <option value="0" <?php if(in_array(($info['status']), explode(',',"0"))): ?>selected<?php endif; ?>>未开工</option>
                <option value="1"<?php if(in_array(($info['status']), explode(',',"1"))): ?>selected<?php endif; ?>>在建</option>
                <option value="2" <?php if(in_array(($info['status']), explode(',',"2"))): ?>selected<?php endif; ?>>质量停工</option>
                <option value="3"<?php if(in_array(($info['status']), explode(',',"3"))): ?>selected<?php endif; ?>>安全停工</option>
                <option value="4" <?php if(in_array(($info['status']), explode(',',"4"))): ?>selected<?php endif; ?>>局停工</option>
                <option value="5"<?php if(in_array(($info['status']), explode(',',"5"))): ?>selected<?php endif; ?>>自停工</option>
            </select>
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
            var str = '<option value="1">主体阶段</option> <option value="2" >装饰阶段</option> <option value="3">收尾</option> <option value="4" >完工</option> <option value="5" >竣工验收</option>';
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