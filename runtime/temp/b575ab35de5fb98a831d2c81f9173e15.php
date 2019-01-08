<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:109:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\administration\project\checkinfo.html";i:1546399454;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1545909786;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1545637557;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1545637557;}*/ ?>
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
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <script src="/assets/js/jquery-3.2.1.js"></script>
    <link rel="stylesheet" href="/assets/css/viewer/viewer.min.css">
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=DcxfH5QHZDdQguhhQrOQFBbTZq7m3Mod"></script>

    <title>地图展示</title>
</head>
<style>
ul li{
list-style:none;
float: left;
margin-left: 10px;
}
</style>

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
                <td>工程进度:</td>
                <td>
                    <?php echo $schedule; ?>
                </td>
            </tr>
            <tr>
                <td>检查说明:</td>
                <td>
                    <?php echo $project['project_desc']; ?>
                </td>
            </tr>
            <tr>
                <td>图片:</td>
                <td>
                    
                    <ul id="dowebok">
                            <?php $__FOR_START_31732__=0;$__FOR_END_31732__=$count;for($i=$__FOR_START_31732__;$i < $__FOR_END_31732__;$i+=1){ ?>
                            <li><img data-original="<?php echo $image[$i]; ?>" src="<?php echo $image[$i]; ?>" alt="点击可放大，缩小" width="100px" height="150px"></li>
                            <?php } ?>
                        </ul>
                </td>
            </tr>
            <tr>
                <td>位置:</td>
                <td>
                    <input type="text" id="w" disabled value="<?php echo $w; ?>" />
                    <input type="text" id="h" disabled value="<?php echo $h; ?>" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div id="allmap" class="form-control" style="height:400px;width: 500px;padding-left: 20%;"></div>
                </td>
            </tr>
        </tbody>
    </table>
   
    <script src="/assets/js/backend/viewer/jquery.min.js"></script>
    <script src="/assets/js/backend/viewer/viewer-jquery.min.js"></script>
    <script>
    $(function() {
        $('#dowebok').viewer({
            url: 'data-original',
        });
    });
    </script>
</body>

</html>
<script type="text/javascript">
    // 百度地图API功能
    var w = document.getElementById("w");
    var h = document.getElementById("h");
    // 百度地图API功能
    var map = new BMap.Map("allmap"); // 创建Map实例
    //添加地图类型控件
    var point = new BMap.Point(h.value, w.value);
    map.centerAndZoom(point, 15);
    var marker = new BMap.Marker(point); // 创建标注
    map.addOverlay(marker);
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