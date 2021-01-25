<?php /*a:3:{s:61:"D:\xiangmu\huadongyiyao\template\admin\system\permission.html";i:1590998903;s:48:"D:\xiangmu\huadongyiyao\template\admin\head.html";i:1611546374;s:50:"D:\xiangmu\huadongyiyao\template\admin\footer.html";i:1587717024;}*/ ?>
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
                    <h5>权限管理</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <button class="layui-btn" onclick="addGroup()">新增分组</button>
                        <div class="layui-form">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="150">
                                    <col width="150">
                                    <col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>角色组</th>
                                    <th>导航</th>
                                    <th>二级导航</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($groups) || $groups instanceof \think\Collection || $groups instanceof \think\Paginator): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <tr>
                                    <td rowspan="<?php echo htmlentities($vo['row']+1); ?>"><?php echo htmlentities($vo['name']); ?>
<!--                                        <button class="layui-btn layui-btn-xs layui-btn-danger" onclick="delGroup('<?php echo htmlentities($vo['ag_id']); ?>')">删除</button>-->
                                    </td>
                                </tr>
                                <?php if(is_array($vo['navs']) || $vo['navs'] instanceof \think\Collection || $vo['navs'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['navs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                <tr>
                                    <td>
                                        <?php if($v['is_checked'] == '1'): ?>
                                        <input type="checkbox" name="nav" title="<?php echo htmlentities($v['name']); ?>" value="<?php echo htmlentities($v['an_id']); ?>" data-id="<?php echo htmlentities($vo['ag_id']); ?>" lay-skin="primary" lay-filter="nav" checked>
                                        <?php else: ?>
                                        <input type="checkbox" name="nav" title="<?php echo htmlentities($v['name']); ?>" value="<?php echo htmlentities($v['an_id']); ?>" data-id="<?php echo htmlentities($vo['ag_id']); ?>" lay-skin="primary" lay-filter="nav">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(is_array($v['subs']) || $v['subs'] instanceof \think\Collection || $v['subs'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['subs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;if($v1['is_checked'] == '1'): ?>
                                        <input type="checkbox" name="sub" title="<?php echo htmlentities($v1['name']); ?>" value="<?php echo htmlentities($v1['ans_id']); ?>" data-id="<?php echo htmlentities($vo['ag_id']); ?>" lay-skin="primary" lay-filter="sub" checked>
                                        <?php else: ?>
                                        <input type="checkbox" name="sub" title="<?php echo htmlentities($v1['name']); ?>" value="<?php echo htmlentities($v1['ans_id']); ?>" data-id="<?php echo htmlentities($vo['ag_id']); ?>" lay-skin="primary" lay-filter="sub">
                                        <?php endif; ?>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="add_group_modal" hidden>
    <br>
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">分组名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" lay-reqtext="请输入分组名称"  placeholder="请输入分组名称" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-submit="" lay-filter="add_group">立即提交</button>
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
    layui.use(['form', 'util', 'layer'], function () {
        var form = layui.form
            , layer = layui.layer
            , util = layui.util
        form.on('submit(add_group)',function (data) {
            var _data = data.field
            $.ajax({
                type : "post",
                url : adminOrigin() + "group/add",
                data : _data,
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:500,
                            offset:'t'
                        },function () {
                            location.reload()
                        })
                    }
                    else
                        layer.msg(res.msg,{offset:'t'})
                },
                error : function () {
                    layer.msg('网络异常',{offset:'t'})
                }
            })
            return false
        })
        form.on('checkbox(nav)', function(data){
            // console.log(data.elem); //得到checkbox原始DOM对象
            // console.log(data.othis); //得到美化后的DOM对象
            let ag_id=data.elem.dataset.id
            let an_id=data.value
            $.ajax({
                type : "post",
                url : adminOrigin() + "nav/permission/edit",
                data : {
                    ag_id:ag_id,
                    an_id:an_id
                },
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:300
                        })
                    }
                    else
                        layer.msg(res.msg)
                },
                error : function () {
                    layer.msg('网络异常')
                }
            })
        });
        form.on('checkbox(sub)', function(data){
            let ag_id=data.elem.dataset.id
            let ans_id=data.value
            $.ajax({
                type : "post",
                url : adminOrigin() + "sub/permission/edit",
                data : {
                    ag_id:ag_id,
                    ans_id:ans_id
                },
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:300
                        })
                    }
                    else
                        layer.msg(res.msg)
                },
                error : function () {
                    layer.msg('网络异常')
                }
            })
        });
    })

    function delGroup(ag_id) {
        layer.confirm('确定删除此分组？',{offset:'t'},function () {
            $.ajax({
                type : "post",
                url : adminOrigin() + "group/del/"+ag_id,
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:500,
                            offset:'t'
                        },function () {
                            location.reload()
                        })
                    }
                    else
                        layer.msg(res.msg,{offset:'t'})
                },
                error : function () {
                    layer.msg('网络异常',{offset:'t'})
                }
            })
        })
    }
    function addGroup() {
        layer.open({
            type: 1,
            offset:'t',
            title:'新增分组',
            content:$('.add_group_modal'),
            shadeClose:true,
            area:['50%','50%'],
            shade: [0.8, '#393D49'],
        })
    }
</script>
</body>
</html>