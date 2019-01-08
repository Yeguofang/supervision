<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:104:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\quality\master\qualitycheck.html";i:1545961956;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
        <label class="control-label col-xs-12 col-sm-2">选择监督人员:</label>
        <div class="col-xs-12 col-sm-8">
            <?php if(is_array($quality) || $quality instanceof \think\Collection || $quality instanceof \think\Paginator): $i = 0; $__LIST__ = $quality;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$q): $mod = ($i % 2 );++$i;?>
                <input type="checkbox" name="qulity_name[]" value="<?php echo $q['name']; ?>" /><?php echo $q['name']; ?><br/>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-12 col-sm-2">检查任务:</label>
        <div class="col-xs-12 col-sm-8">
            <textarea name="task" style="width:300px;height:150px;"></textarea>
        </div>
       
    </div>
    <div class="form-group layer-footer">
            <label class="control-label col-xs-12 col-sm-2"></label>
            <div class="col-xs-12 col-sm-8">
                <button type="submit" class="btn btn-success btn-embossed "><?php echo __('OK'); ?></button>
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