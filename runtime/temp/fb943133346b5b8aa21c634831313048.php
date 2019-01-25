<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:95:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\safety\device\edit.html";i:1547714091;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
        <label for="check_company" class="control-label col-xs-12 col-sm-2">监督编号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" name="row[supervision_number]"  value="<?php echo $device['supervision_number']; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label for="check_company" class="control-label col-xs-12 col-sm-2">工程名称:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" name="row[project_name]" readonly value="<?php echo $device['project_name']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="check_company" class="control-label col-xs-12 col-sm-2">起重机械类别:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" name="row[type]"  value="<?php echo $device['type']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="check_company" class="control-label col-xs-12 col-sm-2">设备备案号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" name="row[device_record]" value="<?php echo $device['device_record']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="check_company" class="control-label col-xs-12 col-sm-2">安装单位:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" name="row[install_unit]"  value="<?php echo $device['install_unit']; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label for="register_time" class="control-label col-xs-12 col-sm-2">安装告知时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" data-date-format="YYYY-MM-DD" name="row[install_time]" value="<?php echo $device['install_time']; ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="register_time" class="control-label col-xs-12 col-sm-2">检测时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" data-date-format="YYYY-MM-DD" name="row[test_time]"  value="<?php echo $device['test_time']; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label for="register_time" class="control-label col-xs-12 col-sm-2">检测到期时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" data-date-format="YYYY-MM-DD" name="row[test_end_time]"  value="<?php echo $device['test_end_time']; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label for="register_time" class="control-label col-xs-12 col-sm-2">办理使用登记牌时间:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form-control" data-date-format="YYYY-MM-DD" name="row[handle_time]" value="<?php echo $device['handle_time']; ?>" />
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

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>