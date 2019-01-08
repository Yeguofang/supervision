<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"D:\wamp64\www\Work\supervision_backend\public/../application/index\view\index\index.html";i:1545637566;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>高要区建设工程质量安全监督管理系统</title>

    <!-- Bootstrap Core CSS -->
    <link href="//cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/index.css" rel="stylesheet">

    <!-- Plugin CSS -->
    <link href="//cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdn.jsdelivr.net/npm/simple-line-icons@2.4.1/css/simple-line-icons.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>

<body id="page-top">

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-menu">
                <span class="sr-only">Toggle navigation</span><i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top"></a>
        </div>


        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="header-content">
                    <div class="header-content-inner">
                        <h2>高要区建设工程质量安全监督管理系统</h2>
                        <a href="<?php echo url('admin/index/login'); ?>" class="btn btn-warning btn-xl page-scroll"><?php echo __('Go to Dashboard'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- jQuery -->
<script src="//cdn.jsdelivr.net/npm/jquery@2.1.4/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="//cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>

<!-- Plugin JavaScript -->
<script src="//cdn.jsdelivr.net/npm/jquery.easing@1.4.1/jquery.easing.min.js"></script>

<script>
    $(function () {
        $(window).on("scroll", function () {
            $("#mainNav").toggleClass("affix", $(window).height() - $(window).scrollTop() <= 50);
        });


    });
</script>


</body>

</html>