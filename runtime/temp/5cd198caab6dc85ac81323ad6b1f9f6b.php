<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:82:"/var/www/html/supervision/public/../application/admin/view/quality/chief/deal.html";i:1545127438;s:68:"/var/www/html/supervision/application/admin/view/layout/default.html";i:1545127437;s:65:"/var/www/html/supervision/application/admin/view/common/meta.html";i:1545127435;s:67:"/var/www/html/supervision/application/admin/view/common/script.html";i:1545127435;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/supervision/public/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/supervision/public/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/supervision/public/assets/js/html5shiv.js"></script>
  <script src="/supervision/public/assets/js/respond.min.js"></script>
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
    <title>处理</title>
    <script src="/supervision/public/assets/js/jquery-3.2.1.js"></script>
</head>
<body>
<form class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <table class="table table-striped">
        <thead>
        <tr>
            <th style="width: 15%">标题</th>
            <th>内容</th>
        </tr>
        </thead>
        <tr id="code">
            <td>告知书编号:</td>
            <td>
                <input type="text" name="code" data-rule="required">
            </td>
        <tr>
            <td>下发告知书时间:</td>
            <td>
                <input type="text" data-rule="required" class="datetimepicker form-control" id="time"
                       data-date-format="YYYY-MM-DD" name="time"/>
            </td>
        </tr>
        <tr id="member">
            <td>选人员:</td>
            <td> <?php echo build_select('quality[]', $quality, null, ['class'=>'form-control selectpicker', 'multiple'=>'',
                'data-rule'=>'required']); ?>
            </td>
        </tr>
    </table>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
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
        <script src="/supervision/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/supervision/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>