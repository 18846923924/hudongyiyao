<?php /*a:3:{s:49:"D:\xiangmu\huadongyiyao\template\admin\index.html";i:1611395438;s:48:"D:\xiangmu\huadongyiyao\template\admin\head.html";i:1611546374;s:50:"D:\xiangmu\huadongyiyao\template\admin\footer.html";i:1587717024;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--360浏览器优先以webkit内核解析-->
    <title>杭州帷拓科技有限公司管理后台</title>
    <link rel="shortcut icon" href="/public/static/hplus_ui/favicon.ico">
    <link href="/public/static/hplus_ui/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/public/static/hplus_ui/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="/public/static/hplus_ui/css/animate.min.css" rel="stylesheet">
    <link href="/public/static/hplus_ui/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <link href="/public/static/hplus_ui/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/public/static/layui/css/layui.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/static/css/commom.css" media="all">
    <link rel="stylesheet" href="/public/static/css/page.css?v1" media="all">
    <link href="//at.alicdn.com/t/font_2345205_qkqe1wys9ll.css" rel="stylesheet">
    
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content">
    <!-- 上方tab -->
    <div class="row" >
        <div class="col-sm-4" style="display: none;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>会员</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><label></label></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>产品数量</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><label><?php echo htmlentities($goods_count); ?></label></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>文章数量</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><label><?php echo htmlentities($news_count); ?></label></h1>
                </div>
            </div>
        </div>
    </div>

    <!-- 中间折线 -->
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <i class="fa fa-cogs"></i> 系统信息
                    </div>
                    <div class="panel-body">
                        <p><i class="fa fa-sitemap"></i> 框架版本：ThinkPHP<?php echo htmlentities($sys_info['think_v']); ?>
                        </p>
                        <p><i class="fa fa-windows"></i> 服务环境：<?php echo htmlentities($sys_info['web_server']); ?>
                        </p>
                        <p><i class="fa fa-futbol-o"></i> 服务器操作系统：<?php echo htmlentities($sys_info['os']); ?>
                        </p>
                        <p><i class="fa fa-tag"></i> 服务器域名/IP：<?php echo htmlentities($sys_info['domain']); ?> [ <?php echo htmlentities($sys_info['ip']); ?> ]
                        </p>
                        <p><i class="fa fa-credit-card"></i> PHP 版本：<?php echo htmlentities($sys_info['phpv']); ?>
                        </p>
                        <p><i class="fa fa-warning"></i> 上传附件限制：<?php echo htmlentities($sys_info['fileupload']); ?>
                        </p>
                        <p><i class="fa fa-unlock"></i> 最大占用内存：<?php echo htmlentities($sys_info['memory_limit']); ?>
                        </p>
                        <p><i class="fa fa-times-circle"></i> 最大执行时间：<?php echo htmlentities($sys_info['max_ex_time']); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <i class="fa fa-cogs"></i> 服务信息
                    </div>
                    <div class="panel-body">
                        <p><i class="fa fa-send-o"></i> 官网<a href="http://www.zjteam.com" target="_blank">&nbsp;&nbsp;&nbsp;&nbsp;http://www.zjteam.com</a>
                        </p>
                        <p><i class="fa fa-qq"></i> QQ<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=1054219137&amp;site=qq&amp;menu=yes" target="_blank">&nbsp;&nbsp;&nbsp;&nbsp;1054219137</a>
                        </p>
                        <p><i class="fa fa-weixin"></i> 微信：<a href="javascript:;">&nbsp;&nbsp;&nbsp;&nbsp;17853391882</a>
                        </p>
                        <p><i class="fa fa-id-card"></i> 邮箱<a href="javascript:;" class="邮箱">&nbsp;&nbsp;&nbsp;&nbsp;zhb1002462117@163.com / 钟华滨</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <i class="fa fa-window-restore"></i> 注意
                    </div>
                    <div class="panel-body">
                        <p>
                        <h4>1.定时修改密码且密码尽量复杂</h4>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="/public/static/hplus_ui/js/jquery.min.js?v=2.1.4"></script>
<script src="/public/static/hplus_ui/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/public/static/hplus_ui/js/plugins/layer/layer.min.js"></script>
<script type="text/javascript" src="/public/static/layui/layui.js"></script>
<script src="/public/static/hplus_ui/js/content.min.js"></script>
<script src="/public/static/hplus_ui/js/plugins/toastr/toastr.min.js"></script>
<script src="/public/static/js/common_zhb.js"></script>
<script type="text/javascript" src="/public/static/js/md5.js"></script>
<script type="text/javascript" src="/public/static/uedit/ueditor.config.js"></script>
<script type="text/javascript" src="/public/static/uedit/ueditor.all.min.js"></script>
</body>
</html>
