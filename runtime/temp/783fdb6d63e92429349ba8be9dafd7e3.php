<?php /*a:2:{s:50:"D:\xiangmu\huadongyiyao\template\admin\common.html";i:1610508836;s:48:"D:\xiangmu\huadongyiyao\template\admin\head.html";i:1611388449;}*/ ?>
<!DOCTYPE html>
<html>
<!-- Mirrored from www.zi-han.net/theme/hplus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:16:41 GMT -->
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
    <link href="//at.alicdn.com/t/font_2345205_md0dljx1fp.css" rel="stylesheet">
    
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element" style="text-align: center">
                        <span><img alt="image" class="img-circle" src="/public/static/img/head.jpg" width="60px"/></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                 <span class="block m-t-xs"><strong class="font-bold"><?php echo htmlentities($name); ?></strong></span>
                                 <span class="text-muted text-xs block"><?php echo htmlentities($name); ?><b class="caret"></b></span>
                                </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li class="divider"><?php echo htmlentities($name); ?></li>
                            <li><a onclick="loginOut()">安全退出</a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">
                    </div>
                </li>
                <?php if(is_array($navs) || $navs instanceof \think\Collection || $navs instanceof \think\Paginator): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li>
                    <a href="#">
                        <i class="iconfont <?php echo htmlentities($vo['icon']); ?>"></i>
                        <span class="nav-label"><?php echo htmlentities($vo['name']); ?></span>
                        <span class="fa arrow"></span>
                    </a>
                    <?php if($vo['an_id'] == 3): ?>
                    <ul class="nav nav-second-level">
                        <?php if(is_array($vo['subs']) || $vo['subs'] instanceof \think\Collection || $vo['subs'] instanceof \think\Paginator): $k = 0; $__LIST__ = $vo['subs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                        
                        <li>
                            <a class="J_menuItem" href="<?php echo htmlentities($v['url']); ?>" data-index="0"><i class="iconfont <?php echo htmlentities($v['icon']); ?>"></i><?php echo htmlentities($v['name']); ?></a>
                        </li>
                      
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <?php else: ?>
                    <ul class="nav nav-second-level">
                        <?php if(is_array($vo['subs']) || $vo['subs'] instanceof \think\Collection || $vo['subs'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['subs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                        <li>
                            <a class="J_menuItem" href="<?php echo htmlentities($v['url']); ?>" data-index="0"><i class="iconfont <?php echo htmlentities($v['icon']); ?>"></i><?php echo htmlentities($v['name']); ?></a>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown hidden-xs">
                        <a class="right-sidebar-toggle" aria-expanded="false">
<!--                            <i class="fa fa-tasks"></i> 主题-->
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row content-tabs">
            <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
            </button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="/cms/index">首页</a>

                </div>
            </nav>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
            </button>
            <div class="btn-group roll-nav roll-right">
                <button class="dropdown J_tabClose" data-toggle="dropdown">更多操作<span class="caret"></span>

                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabBack"><a>后退</a>
                    </li>
                    <li class="J_tabFresh"><a>刷新</a>
                    </li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                    </li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
            <a class="roll-nav roll-right J_tabExit" onclick="loginOut()"><i class="fa fa fa-sign-out"></i> 退出</a>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="/cms/index" frameborder="0" data-id="/cms/index" seamless></iframe>
        </div>
        <div class="footer">
            <div style="text-align: center">
                Copyright&nbsp; 2003-2020&nbsp; WEETOP &nbsp;all &nbsp;rights&nbsp; reserved&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.zjteam.com/" target="_blank">杭州帷拓科技有限公司版权所有</a>
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
    <!--右侧边栏开始-->
    <!--右侧边栏结束-->
</div>
<script src="/public/static/hplus_ui/js/jquery.min.js?v=2.1.4"></script>
<script src="/public/static/hplus_ui/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/public/static/hplus_ui/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/public/static/hplus_ui/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/public/static/hplus_ui/js/plugins/layer/layer.min.js"></script>
<script src="/public/static/hplus_ui/js/hplus.min.js?v=4.1.0"></script>
<script type="text/javascript" src="/public/static/hplus_ui/js/contabs.min.js"></script>
<script src="/public/static/hplus_ui/js/plugins/pace/pace.min.js"></script>
<script src="/public/static/js/common_zhb.js"></script>
<script src="/public/static/hplus_ui/js/contabs.js"></script>
<script type="text/javascript" src="/public/static/layui/layui.js"></script>
<script type="text/javascript">
    function loginOut() {
        $.ajax({
            type : "post",
            url : adminOrigin() + "login/out",
            success : function(res) {
                res = JSON.parse(res)
                if(res.code==0){
                    location.reload()
                }
                else
                    toast('warning',res.msg)
            },
            error : function () {
                toast('error','网络异常')
            }
        })
    }
</script>
</body>
</html>
