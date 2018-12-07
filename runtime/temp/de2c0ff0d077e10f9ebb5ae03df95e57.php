<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:97:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\safety\master\detail.html";i:1543541642;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1543541642;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1543541642;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1543541642;}*/ ?>
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
        <td>报监日期:</td>
        <td>
            <?php if(!(empty($row['supervise_time']) || (($row['supervise_time'] instanceof \think\Collection || $row['supervise_time'] instanceof \think\Paginator ) && $row['supervise_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['supervise_time']); endif; ?>
        </td>
    </tr>
    <tr>
        <td>监督告知书日期:</td>
        <td>
            <?php if(!(empty($row['supervisor_time']) || (($row['supervisor_time'] instanceof \think\Collection || $row['supervisor_time'] instanceof \think\Paginator ) && $row['supervisor_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['supervisor_time']); endif; ?>
        </td>
    </tr>
    <tr>
        <td>开工交底日期:</td>
        <td>
            <?php if(!(empty($info['down_time']) || (($info['down_time'] instanceof \think\Collection || $info['down_time'] instanceof \think\Paginator ) && $info['down_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$info['down_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>中止施工日期:</td>
        <td>
            <?php if(!(empty($info['stop_time']) || (($info['stop_time'] instanceof \think\Collection || $info['stop_time'] instanceof \think\Paginator ) && $info['stop_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$info['stop_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>评定办结日期:</td>
        <td>
            <?php if(!(empty($info['assess_time']) || (($info['assess_time'] instanceof \think\Collection || $info['assess_time'] instanceof \think\Paginator ) && $info['assess_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$info['assess_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>论证日期:</td>
        <td>
            <?php if(!(empty($info['argument_time']) || (($info['argument_time'] instanceof \think\Collection || $info['argument_time'] instanceof \think\Paginator ) && $info['argument_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$info['argument_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>验收日期:</td>
        <td>
            <?php if(!(empty($row['check_time']) || (($row['check_time'] instanceof \think\Collection || $row['check_time'] instanceof \think\Paginator ) && $row['check_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['check_time']); endif; ?>
        </td>
    </tr>
    <tr>
        <td>完工日期:</td>
        <td>
            <?php if(!(empty($info['end_time']) || (($info['end_time'] instanceof \think\Collection || $info['end_time'] instanceof \think\Paginator ) && $info['end_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$info['end_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>检查日期:</td>
        <td>
            <?php if(!(empty($info['inspect_time']) || (($info['inspect_time'] instanceof \think\Collection || $info['inspect_time'] instanceof \think\Paginator ) && $info['inspect_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$info['inspect_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>回复日期:</td>
        <td>
            <?php if(!(empty($info['reply_time']) || (($info['reply_time'] instanceof \think\Collection || $info['reply_time'] instanceof \think\Paginator ) && $info['reply_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$info['reply_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>安全监督编号:</td>
        <td>
            <?php echo $info['safety_code']; ?>
        </td>
    </tr>
    <tr>
        <td>建设单位:</td>
        <td>
            <?php echo $row['build_dept']; ?>
        </td>
    </tr>
    <tr>
        <td>监理单位:</td>
        <td>
            <?php echo $licence['supervision_company']; ?>
        </td>
    </tr>
    <tr>
        <td>施工单位:</td>
        <td>
            <?php echo $licence['construction_company']; ?>
        </td>
    </tr>
    <tr>
        <td>工程名称:</td>
        <td>
            <?php echo $row['project_name']; ?>
        </td>
    </tr>
    <tr>
        <td>地点:</td>
        <td>
            <?php echo $row['address']; ?>
        </td>
    </tr>
    <tr>
        <td>面积（平方米）:</td>
        <td>
            <?php echo $licence['area']; ?>
        </td>
    </tr>
    <tr>
        <td>道路工程延长米(米):</td>
        <td>
            <?php echo $quality['extend']; ?>
        </td>
    </tr>
    <tr>
        <td>造价（万元）:</td>
        <td>
            <?php echo $licence['cost']; ?>
        </td>
    </tr>
    <tr>
        <td>层数:</td>
        <td>
            地上：<?php echo $quality['floor_up']; ?><br>
            地下：<?php echo $quality['floor_down']; ?>
        </td>
    </tr>
    <tr>
        <td>施工许可证号:</td>
        <td>
            <?php echo $licence['licence_code']; ?>
        </td>
    </tr>
    <tr>
        <td>工程进度:</td>
        <td>
            <?php echo $quality['schedule']; ?>
        </td>
    </tr>
    <tr>
        <td>工程评定结论:</td>
        <td>
            <?php echo $info['assess']; ?>
        </td>
    </tr>
    <tr>
        <td>超过一定规模的危险性较大的分部分项工程范围:</td>
        <td>
            <?php echo $info['danger']; ?>
        </td>
    </tr>
    <tr>
        <td>整改措施:</td>
        <td>
            <?php if($info['measure'] == '0'): ?>
            抽查
            <?php elseif($info['measure'] == 1): ?>
            整改
            <?php elseif($info['measure'] == 2): ?>
            暂停
            <?php elseif($info['measure'] == 3): ?>
            约谈
            <?php elseif($info['measure'] == 4): ?>
            扣分
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>整改内容:</td>
        <td>
            <?php echo $info['content']; ?>
        </td>
    </tr>
    <tr>
        <td>整改期限:</td>
        <td>
            <?php echo $info['deadline']; ?>
        </td>
    </tr>
    <tr>
        <td>文书编号:</td>
        <td>
            <?php echo $info['document_code']; ?>
        </td>
    </tr>
    <tr>
        <td>备注:</td>
        <td>
            <?php echo $info['remark']; ?>
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