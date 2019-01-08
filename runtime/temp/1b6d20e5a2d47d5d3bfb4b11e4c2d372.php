<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:92:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\dept\safety\add.html";i:1545637558;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
                                <form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label for="worker_code" class="control-label col-xs-12 col-sm-2">工号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="worker_code" name="row[worker_code]" value="" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="nickname" class="control-label col-xs-12 col-sm-2">姓名:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="nickname" name="row[nickname]" value="" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="mobile" class="control-label col-xs-12 col-sm-2">联系方式:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="mobile" name="row[mobile]" value="" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="supervisor_card" class="control-label col-xs-12 col-sm-2">监督员证:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="supervisor_card" name="row[supervisor_card]" value="" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="admin_code" class="control-label col-xs-12 col-sm-2">编号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="admin_code" name="row[admin_code]" value="" data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="is_law" class="control-label col-xs-12 col-sm-2">是否有执法员证:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="is_law" data-rule="required" name="row[is_law]" class="form-control">
                <option value="0" selected>没有</option>
                <option value="1">有</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="control-label col-xs-12 col-sm-2">登录账号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="username" name="row[username]" value="" data-rule="required;username" />
        </div>
    </div>
    <div class="form-group">
        <label for="admin_level" class="control-label col-xs-12 col-sm-2">权限:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="admin_level" data-rule="required" name="level" class="form-control">
                <?php if($admin['id']==1): ?>
                <option value="0" selected>安监站长</option>
                <option value="1">安监副站长</option>
                <option value="2">安监员</option>
                <option value="3">安监资料录入员</option>
                <?php endif; if($level==14): ?>
                <option value="0" selected>副站长</option>
                <option value="2">安监员</option>
                <option value="3">安监资料录入员</option>
                <?php elseif($level==15): ?>
                <option value="2" selected>安监员</option>
                <option value="3">安监资料录入员</option>
                <?php endif; ?>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>