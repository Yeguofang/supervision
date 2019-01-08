<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:88:"/var/www/html/supervision/public/../application/admin/view/quality/assistant/detail.html";i:1545127438;s:68:"/var/www/html/supervision/application/admin/view/layout/default.html";i:1545127437;s:65:"/var/www/html/supervision/application/admin/view/common/meta.html";i:1545127435;s:67:"/var/www/html/supervision/application/admin/view/common/script.html";i:1545127435;}*/ ?>
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
        <td>开工时间:</td>
        <td>
            <?php if(!(empty($row['begin_time']) || (($row['begin_time'] instanceof \think\Collection || $row['begin_time'] instanceof \think\Paginator ) && $row['begin_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['begin_time']); endif; ?>
        </td>
    </tr>
    <tr>
        <td>竣工日期:</td>
        <td>
            <?php if(!(empty($row['finish_time']) || (($row['finish_time'] instanceof \think\Collection || $row['finish_time'] instanceof \think\Paginator ) && $row['finish_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['finish_time']); endif; ?>
        </td>
    </tr>
    <tr>
        <td>报建日期:</td>
        <td>
            <?php if(!(empty($row['push_time']) || (($row['push_time'] instanceof \think\Collection || $row['push_time'] instanceof \think\Paginator ) && $row['push_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['push_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>监督注册表审批时间:</td>
        <td>
            <?php if(!(empty($row['register_time']) || (($row['register_time'] instanceof \think\Collection || $row['register_time'] instanceof \think\Paginator ) && $row['register_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['register_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>施工许可审批时间:</td>
        <td>
            <?php if(!(empty($row['permit_time']) || (($row['permit_time'] instanceof \think\Collection || $row['permit_time'] instanceof \think\Paginator ) && $row['permit_time']->isEmpty()))): ?>
            <?php echo date('Y-m-d',$row['permit_time'] ); endif; ?>
        </td>
    </tr>
    <tr>
        <td>检测公司:</td>
        <td>
            <?php echo $info['check_company']; ?>
        </td>
    </tr>
    <tr>
        <td>检测负责人:</td>
        <td>
            <?php echo $info['check_person']; ?>
        </td>
    </tr>
    <tr>
        <td>施工负责人:</td>
        <td>
            <?php echo $licence['construction_person']; ?>
        </td>
    </tr>
    <tr>
        <td>监理负责人:</td>
        <td>
            <?php echo $licence['supervision_person']; ?>
        </td>
    </tr>
    <tr>
        <td>图审机构公司名:</td>
        <td>
            <?php echo $info['picture_company']; ?>
        </td>
    </tr>
    <tr>
        <td>图审机构联系人:</td>
        <td>
            <?php echo $info['picture_person']; ?>
        </td>
    </tr>
    <tr>
        <td>建设负责人:</td>
        <td>
            <?php echo $info['build_person']; ?>
        </td>
    </tr>
    <tr>
        <td>工程类别:</td>
        <td>
            <?php if($info['project_kind'] == '0'): ?>
            市政建设
            <?php elseif($info['project_kind'] == 1): ?>
            房建
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>节能:</td>
        <td>
            <?php echo $info['energy']; ?>
        </td>
    </tr>
    <tr>
        <td>道路工程延长米(米):</td>
        <td>
            <?php echo $info['extend']; ?>
        </td>
    </tr>
    <tr>
        <td>结构形式:</td>
        <td>
            <?php echo $info['structure']; ?>
        </td>
    </tr>
    <tr>
        <td>层数:</td>
        <td>
            地上：<?php echo $info['floor_up']; ?><br>
            地下：<?php echo $info['floor_down']; ?>
        </td>
    </tr>
    <tr>
        <td>工程进度:</td>
        <td>
            <?php echo $info['schedule']; ?>
        </td>
    </tr>
    <tr>
        <td>工程概况:</td>
        <td>
            <?php if($info['project_kind'] == 1): if($info['situation'] == '0'): ?>
              基础阶段
               <?php elseif($info['situation'] == 1): ?>
              主体阶段。类型：<?php if($info['extra_type'] == 0): ?>框架 <?php elseif($info['extra_type'] == 1): ?>砌砖    层数：<?php echo $info['extra_floor']; endif; elseif($info['situation'] == 2): ?>
              装饰阶段。类型：<?php if($info['status_extra'] == 0): ?>水电安装 <?php elseif($info['status_extra'] == 1): ?>普通装饰 <?php endif; elseif($info['situation'] == 3): ?>
              收尾
             <?php elseif($info['situation'] == 4): ?>
              完工
             <?php elseif($info['situation'] == 5): ?>
              竣工验收
              <?php endif; else: if($info['situation'] == '0'): ?>
            路基处理
            <?php elseif($info['situation'] == 1): ?>
            路面工程
            <?php elseif($info['situation'] == 2): ?>
            排水系统
            <?php elseif($info['situation'] == 3): ?>
            绿化照明
            <?php elseif($info['situation'] == 4): ?>
            标识标线
            <?php elseif($info['situation'] == 5): ?>
            完成
            <?php elseif($info['situation'] == 6): ?>
            竣工验收
            <?php endif; endif; ?>
        </td>
    </tr>
    <tr>
        <td>状态:</td>
        <td>
            <?php if($info['status'] == '0'): ?>
            未开工
            <?php elseif($info['status'] == 1): ?>
            在建
            <?php elseif($info['status'] == 2): ?>
            质量停工
            <?php elseif($info['status'] == 3): ?>
            安全停工
            <?php elseif($info['status'] == 4): ?>
            局停工
            <?php elseif($info['status'] == 5): ?>
            自停工
            <?php endif; ?>
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
        <script src="/supervision/public/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/supervision/public/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>