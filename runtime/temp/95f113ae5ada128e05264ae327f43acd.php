<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:100:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\quality\assistant\index.html";i:1546348221;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
<div class="message" style="display:none">
    <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 25%">主责检查工程名称</th>
                <th>发起人</th>
                <th>发起时间</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <?php if(is_array($project) || $project instanceof \think\Collection || $project instanceof \think\Paginator): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$i): $mod = ($i % 2 );++$i;?>
                    <?php echo $i['project_name']; ?><br />
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </td>
                <td>
                    <?php if(is_array($name) || $name instanceof \think\Collection || $name instanceof \think\Paginator): $i = 0; $__LIST__ = $name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$i): $mod = ($i % 2 );++$i;?>
                    <?php echo $i['nickname']; ?><br />
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </td>
                <td>
                    <?php if(is_array($time) || $time instanceof \think\Collection || $time instanceof \think\Paginator): $i = 0; $__LIST__ = $time;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$i): $mod = ($i % 2 );++$i;?>
                    <?php echo $i; ?><br />
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
    
</div>
<div class="panel panel-default panel-intro">
    <div id="toolbar" class="toolbar" >
        <?php echo build_toolbar('refresh'); ?>
        <a href="javascript:;" class="btn btn-success btn-export " title="<?php echo __('Export'); ?>" id="btn-export-file"><i class="fa fa-download"></i>导出项目信息</a>
        <a href="javascript:;" class="btn btn-success btn-check " title="<?php echo __('Export'); ?>" id="btn-check-file"><i class="fa fa-download"></i>导出质量工程检查信息</a>
        <?php if($count == 0): else: ?>
        <a class="btn bg-orange " id="msg" title="点击查看，隐藏消息"></i>你有<b> <?php echo $count; ?> </b>条消息！</a>
        <?php endif; ?>
    </div>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <table id="table" class="table table-striped table-bordered table-hover"
                           width="100%">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function (e) {
        $("#msg").click(function (e) {
            $(".message").toggle();
        });
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