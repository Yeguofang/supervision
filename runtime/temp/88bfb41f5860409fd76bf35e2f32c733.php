<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:97:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\safety\system\notice.html";i:1547106025;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
        <?php if($all['terms'] != null): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <td colspan="3">
                    <h2>期限整改</h2>
                </td>
            </tr>
            <tr>
                <th>项目名称</th>
                <th>到期日期</th>
                <th>情况</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($all['terms']) || $all['terms'] instanceof \think\Collection || $all['terms'] instanceof \think\Paginator): $i = 0; $__LIST__ = $all['terms'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $t['project_name']; ?></td>
                <td><?php echo $t['expire_time']; ?></td>
                <td><b style="color:red">
                    <?php if($t['day'] < 0): ?>
                        已过期
                   <?php else: ?>
                        还有 <?php echo $t['day']; ?> 天到期
                  <?php endif; ?>
                </b></td>
                <td><a href="javascript:void(0)" onclick="term(<?php echo $t['id']; ?>)"><b>处理</b></a></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    <?php else: endif; if($all['suspends'] != null): ?>
    <table class="table table-striped">
            <thead>
                <tr>
                    <td colspan="3">
                        <h2>暂停施工整改</h2>
                    </td>
                </tr>
                <tr>
                    <th>项目名称</th>
                    <th>到期日期</th>
                    <th>情况</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($all['suspends']) || $all['suspends'] instanceof \think\Collection || $all['suspends'] instanceof \think\Paginator): $i = 0; $__LIST__ = $all['suspends'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $t['project_name']; ?></td>
                    <td><?php echo $t['expire_time']; ?></td>
                    <td><b style="color:red">
                        <?php if($t['day'] < 0): ?>
                            已过期
                       <?php else: ?>
                            还有 <?php echo $t['day']; ?> 天到期
                      <?php endif; ?>
                    </b></td>
                    <td><a href="javascript:void(0)" onclick="term(<?php echo $t['id']; ?>)"><b>处理</b></a></td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        <?php else: endif; if($all['stops'] != null): ?>
        <table class="table table-striped">
                <thead>
                    <tr>
                        <td colspan="3">
                            <h2>停工整改</h2>
                        </td>
                    </tr>
                    <tr>
                        <th>项目名称</th>
                        <th>到期日期</th>
                        <th>情况</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($all['stops']) || $all['stops'] instanceof \think\Collection || $all['stops'] instanceof \think\Paginator): $i = 0; $__LIST__ = $all['stops'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo $t['project_name']; ?></td>
                        <td><?php echo $t['expire_time']; ?></td>
                        <td><b style="color:red">
                            <?php if($t['day'] < 0): ?>
                                已过期
                           <?php else: ?>
                                还有 <?php echo $t['day']; ?> 天到期
                          <?php endif; ?>
                        </b></td>
                        <td><a href="javascript:void(0)" onclick="term(<?php echo $t['id']; ?>)"><b>处理</b></a></td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
            <?php else: endif; if($all['builds'] != null): ?>
            <table class="table table-striped">
                    <thead>
                        <tr>
                            <td colspan="3">
                                <h2>施工安全抽查</h2>
                            </td>
                        </tr>
                        <tr>
                            <th>项目名称</th>
                            <th>到期日期</th>
                            <th>情况</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($all['builds']) || $all['builds'] instanceof \think\Collection || $all['builds'] instanceof \think\Paginator): $i = 0; $__LIST__ = $all['builds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $t['project_name']; ?></td>
                            <td><?php echo $t['expire_time']; ?></td>
                            <td><b style="color:red">
                                <?php if($t['day'] < 0): ?>
                                    已过期
                               <?php else: ?>
                                    还有 <?php echo $t['day']; ?> 天到期
                              <?php endif; ?>
                            </b></td>
                            <td><a href="javascript:void(0)" onclick="term(<?php echo $t['id']; ?>)"><b>处理</b></a></td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <?php else: endif; if($all['devices'] != null): ?>
            <table class="table table-striped">
                    <thead>
                        <tr>
                            <td colspan="3">
                                <h2>起重机械</h2>
                            </td>
                        </tr>
                        <tr>
                            <th>项目名称</th>
                            <th>检测日期</th>
                            <th>检测到期时间</th>
                            <th>情况</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(is_array($all['devices']) || $all['devices'] instanceof \think\Collection || $all['devices'] instanceof \think\Paginator): $i = 0; $__LIST__ = $all['devices'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$t): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td><?php echo $t['project_name']; ?></td>
                            <td><?php echo $t['test_time']; ?></td>
                            <td><?php echo $t['test_end_time']; ?></td>
                            <td><b style="color:red">
                                <?php if($t['day'] < 0): ?>
                                     已过期
                                <?php else: ?>
                                     还有 <?php echo $t['day']; ?> 天到期
                               <?php endif; ?>
                            </b></td>
                            <td><a href="javascript:void(0)" onclick="device(<?php echo $t['id']; ?>)"><b>处理</b></a></td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <?php else: endif; ?>
                
</body>
<script type="text/javascript">
    function term(ids) {
        if (confirm("确定已处理？")) {
            $.ajax({
                type: "post",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                url: "<?php echo url('/admin/safety/system/status'); ?>?ids=" + ids,
                success: function (data) {
                    console.log(data);
                    if (data == 1) {
                        location.replace(location);
                    } 
                }
            });
        }
    }
    function device(ids) {
        if (confirm("确定已处理？")) {
            $.ajax({
                type: "post",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                url: "<?php echo url('/admin/safety/device/status'); ?>?ids=" + ids,
                success: function (data) {
                    if (data == 1) {
                        location.reload(location);
                    } 
                }
            });
        }
    }
</script>

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