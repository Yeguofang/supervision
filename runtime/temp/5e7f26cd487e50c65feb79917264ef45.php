<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:99:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\quality\info\situation.html";i:1544083708;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1543541642;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1543541642;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1543541642;}*/ ?>
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
                                <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <?php if($info['situation'] == 1): ?>
    <div class="form-group">
        <label for="type" class="control-label col-xs-12 col-sm-2">主体阶段类型:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="type" name="info[type]" class="form-control">
                <option value="0" <?php if(in_array(($info['type']), explode(',',"0"))): ?>selected<?php endif; ?>>框架</option>
                <option value="1"<?php if(in_array(($info['type']), explode(',',"1"))): ?>selected<?php endif; ?>>砌砖</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="info" class="control-label col-xs-12 col-sm-2">层数:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="info" name="info[floor]" value="<?php echo $info['floor']; ?>" />
        </div>
    </div>
    <?php elseif($info['situation'] == 2): ?>
    <div class="form-group">
        <label for="type" class="control-label col-xs-12 col-sm-2">装修阶段类型:</label>
        <div class="col-xs-12 col-sm-8">
            <select  id="type" name="info[type]" class="form-control">
                <option value="0" <?php if(in_array(($info['type']), explode(',',"0"))): ?>selected<?php endif; ?>>水电安装</option>
                <option value="1"<?php if(in_array(($info['type']), explode(',',"1"))): ?>selected<?php endif; ?>>普通装修</option>
            </select>
        </div>
    </div>
    <?php endif; ?>
    <input type="hidden" name="info[situation]"value="<?php echo $info['situation']; ?>" />
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>
</body>
</html>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>