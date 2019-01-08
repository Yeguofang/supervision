<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:103:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\quality\rectifylist\detail.html";i:1546487201;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
                                <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>详情</title>
</head>

<body>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>标题</th>
                <th style="width: 85%">内容</th>
            </tr>
        </thead>
        <tbody>
            </tr>
            <tr>
                <td>编号:</td>
                <td>
                    <?php echo $data['number']; ?>
                </td>
            </tr>
            <tr>
                <td>说明:</td>
                <td>
                        <?php echo $data['desc']; ?>
                </td>
            </tr>
            <tr>
                <td>下发时间:</td>
                <td>
                    <?php echo $data['time']; ?>
                </td>
            </tr>
            <tr>
                <td>工程地点:</td>
                <td>
                    <img src="<?php echo $data['images']; ?>" />
                </td>
            </tr>
           
           
        </tbody>
    </table>
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