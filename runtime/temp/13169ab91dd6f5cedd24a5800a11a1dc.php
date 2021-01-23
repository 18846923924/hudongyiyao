<?php /*a:4:{s:55:"D:\xiangmu\huadongyiyao\template\admin\config\edit.html";i:1610613239;s:48:"D:\xiangmu\huadongyiyao\template\admin\head.html";i:1610524797;s:50:"D:\xiangmu\huadongyiyao\template\admin\footer.html";i:1587717024;s:54:"D:\xiangmu\huadongyiyao\template\admin\album\open.html";i:1599097920;}*/ ?>
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
    <link href="//at.alicdn.com/t/font_2324500_tdqc9rx2g5.css" rel="stylesheet">
    
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>网站配置</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <form class="layui-form" action="">
                            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;if($v['varname'] == 'cfg_logo'): ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label"><?php echo htmlentities($v['varinfo']); ?>：</label>
                                <div class="layui-input-block">
                                    <button type="button" class="layui-btn layui-btn-normal" onclick="chooseImg('<?php echo htmlentities($v['varname']); ?>',1)"><i
                                            class="layui-icon"></i>选择图片
                                    </button>
                                    <span class="pic_span_<?php echo htmlentities($v['varname']); ?>">
                                        <span><img oncontextmenu="rightBtn(this)" ondblclick="delThisPic(this)" class="layui-upload-img" src="/public/uploads/<?php echo htmlentities($v['varvalue']); ?>" height="100px">
                                        <input style="display: none;" type="text" name="<?php echo htmlentities($v['varname']); ?>"  value="<?php echo htmlentities($v['varvalue']); ?>" class="layui-input"></span>
                                    </span>
                                </div>
                                <label class="layui-form-label"></label>
                            </div>
                            <?php elseif($v['varname'] == 'cfg_mlogo2'): ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label"><?php echo htmlentities($v['varinfo']); ?>：</label>
                                <div class="layui-input-block">
                                    <button type="button" class="layui-btn layui-btn-normal" onclick="chooseImg('<?php echo htmlentities($v['varname']); ?>',1)"><i
                                            class="layui-icon"></i>选择图片
                                    </button>
                                    <span class="pic_span_<?php echo htmlentities($v['varname']); ?>">
                                        <span><img oncontextmenu="rightBtn(this)" ondblclick="delThisPic(this)" class="layui-upload-img" src="/public/uploads/<?php echo htmlentities($v['varvalue']); ?>" height="100px">
                                        <input style="display: none;" type="text" name="<?php echo htmlentities($v['varname']); ?>"  value="<?php echo htmlentities($v['varvalue']); ?>" class="layui-input"></span>
                                    </span>
                                </div>
                                <label class="layui-form-label"></label>
                            </div>
                            <?php elseif($v['varname'] == 'cfg_mlogo'): ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label"><?php echo htmlentities($v['varinfo']); ?>：</label>
                                <div class="layui-input-block">
                                    <button type="button" class="layui-btn layui-btn-normal" onclick="chooseImg('<?php echo htmlentities($v['varname']); ?>',1)"><i
                                            class="layui-icon"></i>选择图片
                                    </button>
                                    <span class="pic_span_<?php echo htmlentities($v['varname']); ?>">
                                        <span><img oncontextmenu="rightBtn(this)" ondblclick="delThisPic(this)" class="layui-upload-img" src="/public/uploads/<?php echo htmlentities($v['varvalue']); ?>" height="100px">
                                        <input style="display: none;" type="text" name="<?php echo htmlentities($v['varname']); ?>"  value="<?php echo htmlentities($v['varvalue']); ?>" class="layui-input"></span>
                                    </span>
                                </div>
                                <label class="layui-form-label"></label>
                            </div>
                            <?php else: ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label"><?php echo htmlentities($v['varinfo']); ?>：</label>
                                <div class="layui-input-block">
                                    <input type="text" name="<?php echo htmlentities($v['varname']); ?>" value="<?php echo htmlentities($v['varvalue']); ?>" lay-verify="required" lay-reqtext="请输入<?php echo htmlentities($v['varinfo']); ?>" class="layui-input" maxlength="250">
                                </div>
                            </div>

                            <?php endif; ?>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            
                          
                         <!--    <div class="layui-form-item" style="display: none;">
                                <label class="layui-form-label">展    示：</label>
                                <div class="layui-input-block">
                                    <select name="">
                                        <option value="1">是</option>
                                        <option value="0">否</option>
                                    </select>
                                </div>
                            </div> -->

                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="add_goods">立即提交</button>
                                </div>
                            </div>
                        </form>
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
<!--图库-->
<div class="img_modal" hidden>
    <div>已选图片：<span class="temp_modal"></span></div>
    <form class="layui-form" action="" lay-filter="album">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <select name="a_id" lay-filter="a_id" id="a_select" lay-search="">
                    <option value="0">请选择相册</option>
                    <?php if(is_array($albums) || $albums instanceof \think\Collection || $albums instanceof \think\Paginator): $i = 0; $__LIST__ = $albums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo htmlentities($vo['a_id']); ?>"><?php echo htmlentities($vo['name']); ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="name" id="name" placeholder="请输入相册名称" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <a class="layui-btn" onclick="addAlbum()">新增相册</a>
                <a class="layui-btn" style="display: none" id="album_pic">上传</a>
            </div>
        </div>
    </form>
    <div class="img-tab">
    </div>
    <div id="page_modal"></div>
</div>

<input type="number" id="a_id" hidden>
<input type="number" id="multiple" hidden>
<input type="text" id="type" hidden>
<input type="text" id="input_name" hidden>
<!--右键菜单-->
<div id="right_box">
    <a href="javascript:delPic();" >删除</a>
    <a href="javascript:moveOne();" >向前移动</a>
    <a href="javascript:moveAfter();" >向后移动</a>
</div>
<div class="album_add_modal" hidden>
    <div style="padding: 15px;">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit="" lay-filter="add_album">立即提交</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var obj
    var form
    var pageFun = function(){}
    layui.use(['form','layer','upload','laypage'], function() {
        form = layui.form
            ,upload = layui.upload
            ,laypage = layui.laypage
        var album = upload.render({
            elem: '#album_pic'
            ,url: '/cms/api/album/upload'
            ,multiple:true
            ,data: {
                a_id: $('#a_id').val()
            }
            ,done: function (res) {
                //如果上传失败
                if (res.code > 0) {
                    layer.msg(res.msg, {offset: 't'});
                    return false
                }
                layer.msg('上传成功', {
                    offset: 't'
                });
            },allDone: function(obj){ //当文件全部被提交后，才触发
                getAlbumPic($('#a_id').val())
            }
        });
        pageFun = function (count,a_id){
            laypage.render({
                elem: 'page_modal'
                ,count: count
                ,limit:8
                ,layout: ['count', 'prev', 'page', 'next', 'refresh', 'skip']
                ,jump: function(obj, first){
                    let page = obj.curr
                    $.ajax({
                        type : "get",
                        url : adminOrigin() + "album/pic/list/"+a_id,
                        data : {
                            page:page
                        },
                        success : function(res) {
                            res = JSON.parse(res)
                            if(res.code==0){
                                if(res.data.length >0 ){
                                    var str = ''
                                    for (var i=0;i<res.data.length;i++){
                                        str += '<div class="img-item" onclick="chooseAlbumPic(\''+res.data[i].pic+'\',\''+res.data[i].ap_id+'\')">'
                                        str += '<div class="img-div">'
                                        str += '<div>'
                                        str += '<img src="/public/uploads/'+res.data[i].pic+'" alt="">'
                                        str += '</div>'
                                        str += '</div>'
                                        str += '<div class="img-text">'
                                        str += '<p>'+res.data[i].pic_name+'</p>'
                                        str += '<span>'+res.data[i].width+'*'+res.data[i].height+'</span>'
                                        str += '</div>'
                                        str += '</div>'
                                    }
                                    $('.img-tab').html(str)
                                }else
                                    $('.img-tab').html('')
                            }
                            else
                                layer.msg(res.msg,{offset:'t'})
                        },
                        error : function () {
                            layer.msg('网络异常',{offset:'t'})
                        }
                    })
                }
            });
        }
        form.on('select(a_id)',function (data) {
            var a_id = data.value
            $('#a_id').val(a_id)
            album.reload({
                data:{
                    a_id:a_id
                }
            })
            if(a_id>0)
                $('#album_pic').show()
            else
                $('#album_pic').hide()
            getAlbumPic(a_id)
            return false
        })
    })

    var img_index;
    function chooseImg(type,multiple = 0,input_name='') {
        $('#type').val(type)
        $('#input_name').val(input_name)
        $('#multiple').val(multiple)
        img_index = layer.open({
            type: 1,
            offset: 't',
            title: '选择图片',
            content: $('.img_modal'),
            shadeClose: true,
            area: ['100%', '100%'],
            shade: [0.8, '#393D49'],
            btn:'确定',
            success:function () {
                $('#type').val(type)
                $('.temp_modal').html($('.pic_span_'+type).html())
            },
            cancel:function () {
                $('.temp_modal').html('')
            },
            yes:function () {
                let html = $('.temp_modal').html()
                $('.pic_span_'+type).html(html)
                $('.temp_modal').html('')
                layer.close(img_index)
            }
        })
    }

    function getAlbumPic(a_id) {
        $.ajax({
            type : "get",
            url : adminOrigin() + "album/pic/count/"+a_id,
            success : function(res) {
                res = JSON.parse(res)
                if(res.code==0){
                    pageFun(res.data,a_id)
                }
                else
                    layer.msg(res.msg,{offset:'t'})
            },
            error : function () {
                layer.msg('网络异常',{offset:'t'})
            }
        })
    }

    function chooseAlbumPic(pic,ap_id) {
        var type = $('#type').val()
        var input_name = $('#input_name').val()
        var multiple = $('#multiple').val()
        var str = ''
        console.log(input_name)
        if(input_name != '')
            type = input_name
        if(multiple == 1) {
            // 单图
            str += '<span><img oncontextmenu="rightBtn(this)" ondblclick="delThisPic(this)" class="layui-upload-img" src="/public/uploads/'+pic+'" height="60px">'
            str += '<input style="display: none;" type="text" name="'+type+'"  value="'+pic+'" class="layui-input edit_td_pic_input"></span>'
            $('.temp_modal').html(str)
        }else {
            // 多图
            str += '<span><img oncontextmenu="rightBtn(this)" ondblclick="delThisPic(this)" class="layui-upload-img" src="/public/uploads/'+pic+'" height="60px">'
            str += '<input style="display: none;" type="text" name="'+type+'[]"  value="'+ap_id+'" class="layui-input"></span>'
            $('.temp_modal').append(str)
        }
    }

    function addAlbum() {
        var name = $('#name').val()
        if(name == '')
            return false
        var load_index = layer.load(1, {
            shade: [0.5,'#000'] //0.1透明度的背景
        });
        $.ajax({
            type : "post",
            url : adminOrigin() + "album/add",
            data : {
                name:name
            },
            success : function(res) {
                res = JSON.parse(res)
                if(res.code==0){
                    layer.msg('操作成功',{
                        time:500,
                    },function () {
                        var  str = '<option value="'+res.data+'">'+name+'</option>'
                        $('#a_select').append(str)
                        form.render('select','album')
                    })
                }
                else
                    layer.msg(res.msg)
                layer.close(load_index)
            },
            error : function () {
                layer.msg('网络异常')
                layer.close(load_index)
            }
        })

    }
    // 右键操作
    function rightBtn(e) {
        obj = e
        var mouse_x = event.clientX
        var mouse_y = event.clientY
        var w=document.documentElement.clientWidth||document.body.clientWidth;
        var h=document.documentElement.clientHeight||document.body.clientHeight;
        if(w - mouse_x > 100){
            $('#right_box').css('left',mouse_x+'px')
        }else{
            $('#right_box').css({
                'left':'auto',
                'right':w - mouse_x + 'px'
            })
        }
        if(h - mouse_y > 100){
            $('#right_box').css('top',mouse_y+'px')
        }else{
            $('#right_box').css({
                'top':'auto',
                'bottom':h - mouse_y + 'px'
            })
        }
        $('#right_box').show()
    }
    //
    document.oncontextmenu = function(){
        return false;
    }
    window.onclick=function(e){
        $('#right_box').hide()
    }
    function delThisPic(obj){
        $(obj).parent().remove()
    }
    function delPic() {
        $(obj).parent().remove()
    }
    function moveOne() {
        if($(obj).parent().prev().length == 0)
            return false
        $(obj).parent().prev().before($(obj).parent()[0].outerHTML)
        $(obj).parent().remove()
    }
    function moveAfter() {
        if($(obj).parent().next().length == 0)
            return false
        $(obj).parent().next().after($(obj).parent()[0].outerHTML)
        $(obj).parent().remove()
    }
</script>
<script>
    layui.use(['form','layer'], function() {
        var form = layui.form
        form.on('submit(add_goods)',function (data) {
            var _data = data.field
            var load_index = layer.load(1, {
                shade: [0.5,'#000'] //0.1透明度的背景
            });
            $.ajax({
                type : "post",
                url : adminOrigin() + "config/add",
                data : _data,
                success : function(res) {
                    res = JSON.parse(res)
                    if(res.code==0){
                        layer.msg('操作成功',{
                            time:500,
                        },function () {
                            window.location.href = '/cms/config'
                        })
                    }
                    else
                        layer.msg(res.msg)
                    layer.close(load_index)
                },
                error : function () {
                    layer.msg('网络异常')
                    layer.close(load_index)
                }
            })
            return false
        })
    })
    var ue = UE.getEditor('editor',{
    textarea:'content',
    zIndex:1
    });
</script>
</body>
</html>
