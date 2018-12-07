<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:104:"D:\wamp64\www\Work\supervision_backend\public/../application/admin\view\administration\project\edit.html";i:1544152755;s:81:"D:\wamp64\www\Work\supervision_backend\application\admin\view\layout\default.html";i:1543541642;s:78:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\meta.html";i:1543541642;s:80:"D:\wamp64\www\Work\supervision_backend\application\admin\view\common\script.html";i:1543541642;}*/ ?>
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
                                <script src="/assets/js/jquery-3.2.1.js"></script>
<form id="add-form" class="form-horizontal" role="form" data-toggle="validator" method="POST" action="">
    <div class="form-group">
        <label for="build_dept" class="control-label col-xs-12 col-sm-2">建设单位:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text"  list="depts" class="form-control" id="build_dept" name="row[build_dept]" value="<?php echo $row['build_dept']; ?>" data-rule="required" />
            <datalist id="depts">
                <?php if(!(empty($five['build_dept']) || (($five['build_dept'] instanceof \think\Collection || $five['build_dept'] instanceof \think\Paginator ) && $five['build_dept']->isEmpty()))): if(is_array($five['build_dept']) || $five['build_dept'] instanceof \think\Collection || $five['build_dept'] instanceof \think\Paginator): if( count($five['build_dept'])==0 ) : echo "" ;else: foreach($five['build_dept'] as $key=>$vo): ?>
                    <option ><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="project_name" class="control-label col-xs-12 col-sm-2">工程名称:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="project_name" name="row[project_name]" value="<?php echo $row['project_name']; ?>"data-rule="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="address" class="control-label col-xs-12 col-sm-2">建设地址:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="address" name="row[address]" value="<?php echo $row['address']; ?>"  data-rule="required"/>
        </div>
    </div>
    <div class="form-group">
        <label for="c-avatar" class="control-label col-xs-12 col-sm-2">许可证二维码:</label>
        <div class="col-xs-12 col-sm-8">
            <div class="input-group">
                <input id="c-avatar"class="form-control" size="50" name="licence[qr_code]" type="text" value="<?php echo $licence['qr_code']; ?>" readonly="readonly">
                <div class="input-group-addon no-border no-padding">
                    <span><button type="button" id="plupload-avatar" class="btn btn-danger plupload" data-input-id="c-avatar" data-mimetype="image/gif,image/jpeg,image/png,image/jpg,image/bmp" data-multiple="false"   data-preview-id="p-avatar"><i class="fa fa-upload"></i> 上传</button></span>
                </div>
                <span class="msg-box n-right" for="c-avatar"></span>
            </div>
            <ul class="row list-inline plupload-preview" id="p-avatar"></ul>
        </div>
        <i style="color:red;font-size: 24px;">*</i>
    </div>
    <div class="form-group">
        <label for="licence_code" class="control-label col-xs-12 col-sm-2">编号:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="licence_code" name="licence[licence_code]" value="<?php echo $licence['licence_code']; ?>"  />
        </div>
        <i style="color:red;font-size: 24px;">*</i>
    </div>
    <div class="form-group">
        <label for="area" class="control-label col-xs-12 col-sm-2">建设规模:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="area" name="licence[area]" value="<?php echo $licence['area']; ?>"  />
        </div>
        <label class="col-xs-12 col-sm-2" style="margin-top: 8px;margin-left: -25px;">（平方米）</label>
    </div>
    <div class="form-group">
        <label for="cost" class="control-label col-xs-12 col-sm-2">合同价格:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="form-control" id="cost" name="licence[cost]" value="<?php echo $licence['area']; ?>"  />
        </div>
        <label class="col-xs-12 col-sm-2" style="margin-top: 8px;margin-left: -18px;">(万元)</label>
    </div>
    <div class="form-group">
        <label for="survey_company" class="control-label col-xs-12 col-sm-2">勘察单位:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" list="survey_companys" class="form-control" id="survey_company" name="licence[survey_company]" value="<?php echo $licence['survey_company']; ?>" />
            <datalist id="survey_companys">
                <?php if(!(empty($five['survey_company']) || (($five['survey_company'] instanceof \think\Collection || $five['survey_company'] instanceof \think\Paginator ) && $five['survey_company']->isEmpty()))): if(is_array($five['survey_company']) || $five['survey_company'] instanceof \think\Collection || $five['survey_company'] instanceof \think\Paginator): if( count($five['survey_company'])==0 ) : echo "" ;else: foreach($five['survey_company'] as $key=>$vo): ?>
                        <option><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="design_company" class="control-label col-xs-12 col-sm-2">设计单位:</label>
        <div class="col-xs-12 col-sm-8">
            <input  type="text" lsit="design_companys" class="form-control" id="design_company" name="licence[design_company]" value="<?php echo $licence['design_company']; ?>" />
            <datalist id="design_companys">
                    <?php if(!(empty($five['design_company']) || (($five['design_company'] instanceof \think\Collection || $five['design_company'] instanceof \think\Paginator ) && $five['design_company']->isEmpty()))): if(is_array($five['design_company']) || $five['design_company'] instanceof \think\Collection || $five['design_company'] instanceof \think\Paginator): if( count($five['design_company'])==0 ) : echo "" ;else: foreach($five['design_company'] as $key=>$vo): ?>
                            <option><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
             </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="construction_company" class="control-label col-xs-12 col-sm-2">施工单位:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" list="construction_companys"  class="form-control" id="construction_company" name="licence[construction_company]" value="<?php echo $licence['construction_company']; ?>"  />
            <datalist id="construction_companys">
                    <?php if(!(empty($five['construction_company']) || (($five['construction_company'] instanceof \think\Collection || $five['construction_company'] instanceof \think\Paginator ) && $five['construction_company']->isEmpty()))): if(is_array($five['construction_company']) || $five['construction_company'] instanceof \think\Collection || $five['construction_company'] instanceof \think\Paginator): if( count($five['construction_company'])==0 ) : echo "" ;else: foreach($five['construction_company'] as $key=>$vo): ?>
                            <option><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
             </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="supervision_company" class="control-label col-xs-12 col-sm-2">监理单位:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" list="supervision_companys"  class="form-control" id="supervision_company" name="licence[supervision_company]" value="<?php echo $licence['supervision_company']; ?>"  />
            <datalist id="supervision_companys">
                    <?php if(!(empty($five['supervision_company']) || (($five['supervision_company'] instanceof \think\Collection || $five['supervision_company'] instanceof \think\Paginator ) && $five['supervision_company']->isEmpty()))): if(is_array($five['supervision_company']) || $five['supervision_company'] instanceof \think\Collection || $five['supervision_company'] instanceof \think\Paginator): if( count($five['supervision_company'])==0 ) : echo "" ;else: foreach($five['supervision_company'] as $key=>$vo): ?>
                            <option><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
             </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="survey_person" class="control-label col-xs-12 col-sm-2">勘察项目负责人:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" list="survey_persons" class="form-control" id="survey_person" name="licence[survey_person]" value="<?php echo $licence['survey_person']; ?>"/>
            <datalist id="survey_persons">
                    <?php if(!(empty($five['survey_person']) || (($five['survey_person'] instanceof \think\Collection || $five['survey_person'] instanceof \think\Paginator ) && $five['survey_person']->isEmpty()))): if(is_array($five['survey_person']) || $five['survey_person'] instanceof \think\Collection || $five['survey_person'] instanceof \think\Paginator): if( count($five['survey_person'])==0 ) : echo "" ;else: foreach($five['survey_person'] as $key=>$vo): ?>
                            <option><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
             </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="design_person" class="control-label col-xs-12 col-sm-2">设计项目负责人:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" list="design_persons" class="form-control" id="design_person" name="licence[design_person]" value="<?php echo $licence['design_person']; ?>"  />
            <datalist id="design_persons">
                    <?php if(!(empty($five['design_person']) || (($five['design_person'] instanceof \think\Collection || $five['design_person'] instanceof \think\Paginator ) && $five['design_person']->isEmpty()))): if(is_array($five['design_person']) || $five['design_person'] instanceof \think\Collection || $five['design_person'] instanceof \think\Paginator): if( count($five['design_person'])==0 ) : echo "" ;else: foreach($five['design_person'] as $key=>$vo): ?>
                            <option><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
             </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="construction_person" class="control-label col-xs-12 col-sm-2">施工项目负责人:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" list="construction_persons" class="form-control" id="construction_person" name="licence[construction_person]" value="<?php echo $licence['construction_person']; ?>"  />
            <datalist id="construction_persons">
                    <?php if(!(empty($five['construction_person']) || (($five['construction_person'] instanceof \think\Collection || $five['construction_person'] instanceof \think\Paginator ) && $five['construction_person']->isEmpty()))): if(is_array($five['construction_person']) || $five['construction_person'] instanceof \think\Collection || $five['construction_person'] instanceof \think\Paginator): if( count($five['construction_person'])==0 ) : echo "" ;else: foreach($five['construction_person'] as $key=>$vo): ?>
                            <option><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
             </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="supervision_person" class="control-label col-xs-12 col-sm-2">总监理工程师:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" list="supervision_persons" class="form-control man" id="supervision_person" name="licence[supervision_person]" value="<?php echo $licence['supervision_person']; ?>"  />
            <datalist id="supervision_persons">
                    <?php if(!(empty($five['supervision_person']) || (($five['supervision_person'] instanceof \think\Collection || $five['supervision_person'] instanceof \think\Paginator ) && $five['supervision_person']->isEmpty()))): if(is_array($five['supervision_person']) || $five['supervision_person'] instanceof \think\Collection || $five['supervision_person'] instanceof \think\Paginator): if( count($five['supervision_person'])==0 ) : echo "" ;else: foreach($five['supervision_person'] as $key=>$vo): ?>
                            <option><?php echo $vo['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
             </datalist>
        </div>
    </div>
    <div class="form-group">
        <label for="begin_time" class="control-label col-xs-12 col-sm-2">合同工期:</label>
        <div class="col-xs-12 col-sm-8">
            <input type="text" class="datetimepicker form" id="begin_time" data-date-format="YYYY-MM-DD" name="licence[begin_time]" value="<?php echo $licence['begin_time']; ?>"  />
            ——
            <input type="text" class="datetimepicker form" id="end_time" data-date-format="YYYY-MM-DD" name="licence[end_time]" value="<?php echo $licence['end_time']; ?>"  />
        </div>
    </div>
    
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
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