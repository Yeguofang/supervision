<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:96:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\check\safety\detail.html";i:1543541642;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1543541642;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1543541642;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1543541642;}*/ ?>
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
    <tr>
        <td>建设公司:</td>
        <td>
            <?php echo $data['build_dept']; ?>
        </td>
    </tr>
    <tr>
        <td>设计公司:</td>
        <td>
            <?php echo $data['design_company']; ?>
        </td>
    </tr>
    <tr>
        <td>设计负责人:</td>
        <td>
            <?php echo $data['design_person']; ?>
        </td>
    </tr>
    <tr>
        <td>勘察公司:</td>
        <td>
            <?php echo $data['survey_company']; ?>
        </td>
    </tr>
    <tr>
        <td>勘察负责人:</td>
        <td>
            <?php echo $data['survey_person']; ?>
        </td>
    </tr>
    <tr>
        <td>施工公司:</td>
        <td>
            <?php echo $data['construction_company']; ?>
        </td>
    </tr>
    <tr>
        <td>施工负责人:</td>
        <td>
            <?php echo $data['construction_person']; ?>
        </td>
    </tr>
    <tr>
        <td>监理单位:</td>
        <td>
            <?php echo $data['supervision_company']; ?>
        </td>
    </tr>
    <tr>
        <td>监理负责人:</td>
        <td>
            <?php echo $data['supervision_person']; ?>
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