<?php /*a:3:{s:56:"D:\xiangmu\huadongyiyao\template\admin\system\staff.html";i:1611544734;s:48:"D:\xiangmu\huadongyiyao\template\admin\head.html";i:1611546374;s:50:"D:\xiangmu\huadongyiyao\template\admin\footer.html";i:1587717024;}*/ ?>
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
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>人事管理</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <form class="layui-form" action="">
                            <div class="layui-form-item">
                                <div class="layui-input-inline">
                                    <input type="text" name="name" value="<?php echo htmlentities($name); ?>" placeholder="请输入账户名" class="layui-input">
                                </div>
                                <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">搜索</button>
                                <a class="layui-btn layui-btn-normal" onclick="addUser()">新增用户</a>
                            </div>
                        </form>

                        <table class="layui-table">
                            <thead>
                            <tr>
                                <td>用户名</td>
                                <td>昵称</td>
                                <td>分组</td>
                                <td>状态</td>
                                <td>省份</td>
                                <td>操作</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($staffs) || $staffs instanceof \think\Collection || $staffs instanceof \think\Paginator): $i = 0; $__LIST__ = $staffs;if( count($__LIST__)==0 ) : echo "<tr><td colspan=5>暂无数据</td></tr>" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td><?php echo htmlentities($vo['name']); ?></td>
                                <td><?php echo htmlentities($vo['nickname']); ?></td>
                                <td><?php echo htmlentities($vo['group_name']); ?></td>
                                <td>
                                    <?php if($vo['status'] == '1'): ?>
                                    <button class="layui-btn layui-btn-xs" onclick="changeStatus('<?php echo htmlentities($vo['au_id']); ?>',0,'锁定后用户将不可登录')">正常</button>
                                    <?php else: ?>
                                    <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="changeStatus('<?php echo htmlentities($vo['au_id']); ?>',1,'解锁后用户可恢复登录')">锁定</button>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($vo['ag_id'] == 1): ?>
                                        超级管理员
                                    <?php else: ?>
                                        <?php echo htmlentities(getProvName($vo['province'])); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="layui-btn layui-btn-xs" onclick="initPwd('<?php echo htmlentities($vo['au_id']); ?>')">密码重置</button>
                                </td>
                            </tr>
                            <?php endforeach; endif; else: echo "<tr><td colspan=5>暂无数据</td></tr>" ;endif; ?>
                            </tbody>
                        </table>
                        <?php echo $staffs; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="user_add_modal" hidden>
    <br>
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">分组</label>
            <div class="layui-input-inline">
                <select name="ag_id" lay-filter="ag_id" lay-verify="required" lay-reqtext="请选择分组" >
                    <option value="">请选择分组</option>
                    <?php if(is_array($groups) || $groups instanceof \think\Collection || $groups instanceof \think\Paginator): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo htmlentities($vo['ag_id']); ?>"><?php echo htmlentities($vo['name']); ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">省份</label>
            <div class="layui-input-inline">
                <select name="prov" lay-filter="prov" lay-verify="required" lay-reqtext="请选择省份" lay-search>
                    <option value="">请选择省份</option>
                    <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo htmlentities($vo['p_code']); ?>"><?php echo htmlentities($vo['name']); ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">账户</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" lay-reqtext="请输入用户名" placeholder="请输入" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline">
                <input type="text" name="pwd" id="pwd" lay-verify="required" lay-reqtext="请输入用户密码" placeholder="请输入" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <i class="layui-icon" onclick="randomPwd()">&#xe669;</i>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="nickname" lay-verify="required" lay-reqtext="请输入昵称" placeholder="请输入" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="add_user">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
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
<script>
    layui.use(['form','layer'], function() {
        var form = layui.form
        form.on('submit(add_user)',function (data) {
            var _data = data.field
            _data.pwd = hex_md5(_data.pwd)
            $.ajax({
                type : "post",
                url : adminOrigin() + "staff/add",
                data : _data,
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:500
                        },function () {
                            location.reload()
                        })
                    }
                    else
                        layer.msg(res.msg)
                },
                error : function () {
                    layer.msg('网络异常')
                }
            })
            return false
        })
    })

    function changeStatus(au_id,status,title) {
        layer.confirm(title,function () {
            $.ajax({
                type : "post",
                url : adminOrigin() + "staff/status",
                data : {
                    au_id:au_id,
                    status:status
                },
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:500
                        },function () {
                            location.reload()
                        })
                    }
                    else
                        layer.msg(res.msg)
                },
                error : function () {
                    layer.msg('网络异常')
                }
            })
        })
    }

    function initPwd(au_id) {
        layer.confirm('密码将重置为123456，请尽快修改密码',function () {
            $.ajax({
                type : "post",
                url : adminOrigin() + "init/pwd/"+au_id,
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:500
                        })
                    }
                    else
                        layer.msg(res.msg)
                },
                error : function () {
                    layer.msg('网络异常')
                }
            })
        })
    }

    function addUser() {
        layer.open({
            type: 1,
            offset:'t',
            title:'新增用户',
            content:$('.user_add_modal'),
            shadeClose:true,
            area:['450px','400px'],
            shade: [0.8, '#393D49'],
        })
    }

    function randomPwd() {
        $('#pwd').val(randomStr(6))
    }
</script>
</body>
</html>