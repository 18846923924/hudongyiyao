function adminOrigin() {
    return window.location.origin + '/cms/api/'
}

function delHTML(str,all = false) {
    if (!str) return '';
    var str=str.replace(/<\/?[^>]*>/gim,"");//去掉所有的html标记
    var result=str.replace(/(^\s+)|(\s+$)/g,"");//去掉前后空格
    if (all) return  result.replace(/\s/g,"");//去除文章中间空格
    return result;
}

function cMobile(phone) {
    if(phone == ''){
        return false
    }else{
        return !(phone.search(/^1(3|4|5|6|7|8|9)\d{9}$/) == -1)
    }
}

function cMail(email_address) {
    var regex = /^([0-9A-Za-z\-_\.]+)@([0-9a-z]+\.[a-z]{2,3}(\.[a-z]{2})?)$/g;
    return regex.test( email_address )
}

function cUrl(url) {
    var reg=/^((ht|f)tps?):\/\/([\w-]+(\.[\w-]+)*\/?)+(\?([\w\-\.,@?^=%&:\/~\+#]*)+)?$/;
    return reg.test(url)
}

function randomStr(num) {
    var str = "",
        arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    for(var i=0; i<num; i++){
        pos = Math.round(Math.random() * (arr.length-1));
        str += arr[pos];
    }
    return str;
}

function limitContentLength(obj,line,showId) {
    var content = $(obj).val()
    var num = content.length
    if(num>line)
    {
        content = content.substring(0,line)
        $(obj).val(content)
        return false
    }
    $(showId).html(num)
}

function nowTimeStamp() {
    return Math.round(new Date().getTime()/1000)
}


// 获取编辑器内容
function getEditText() {
    return ue.getContent();
}

// 获取纯文本
function getEditContentTxt() {
    return ue.getContentTxt();
}

function boo(boo) {
    return boo?'<span style="color:green">是</span>':'<span style="color:red">否</span>'
}

// 时间戳转时间日期时间
function timestampToDatetime(timestamp){
    if(!timestamp)
        return ''
    var timestamp = new Date(timestamp * 1000);
    var datetime = timestamp.toLocaleDateString().replace(/\//g, "-") + " " + timestamp.toTimeString().substr(0, 8)
    return datetime
}

// 时间戳转日期
function timestampToDate(timestamp){
    if(!timestamp)
        return ''
    var timestamp = new Date(timestamp * 1000);
    var datetime = timestamp.toLocaleDateString().replace(/\//g, "-")
    return datetime
}

// 去除br
function replaceBr(str) {
    return str.replace(/<br>/g,'')
}

// 日期转时间戳
function dateToTimestamp(time) {
    if(!time)
        return 0
    var date = new Date(time.replace(/-/g,'/'));
    return parseInt(date.getTime()/1000)
}

// 上传图片
function uploadImg(id='') {
    layui.use(['upload','layer'], function() {
        var upload = layui.upload,
            layer = layui.layer
        let pic = upload.render({
            elem: '#pic'+id
            ,url: '/cms/api/upload'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#pic_img'+id).attr('src', result); //图片链接（base64）
                    $('#pic_img'+id).width("60px");
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code > 0){
                    return layer.msg(res.msg);
                }
                layer.msg('上传成功');
                //上传成功
                $('#pic_save_name'+id).val(res.data.save_name)
                $('#pic_name'+id).val(res.data.name)
                $('#pic_text'+id).html('')
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#pic_text'+id);
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    pic.upload();
                });
            }
        });
    })
}

// 获取url参数
function getQueryParam(name){
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(decodeURI(r[2]))
    }
    return '';
}

function bigUpload() {
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: false,
        // swf文件路径
        swf: '__static__/Uploader.swf',
        // 文件接收服务端。
        server: adminOrigin()+"big/upload",
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#picker',

        chunked: true,//开启分片上传
        chunkSize:2097152,// 限制分片上传大小
        threads: 1,//上传并发数
        fileNumLimit:1,
        method:'POST',
        // accept: {
        //     title: 'Files',
        //     extensions: 'pdf,doc,docx,xls,xlsx,ppt,pptx',
        //     mimeTypes: 'application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf'
        // }
    });
    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        uploader.upload();
        $('#progress').show()
        $('#picker').hide()
        // webuploader事件.当选择文件后，文件被加载到文件队列中，触发该事件。等效于 uploader.onFileueued = function(file){...} ，类似js的事件定义。
    });
    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        setPercent(percentage)
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file,res ) {
        msg('上传成功')
        $('#file_area').show()
        $('#file_name').html(res.data.file_name)
        $('#file_name').next().val(res.data.file_name)
        $('#file_name').next().next().val(res.data.file_path)
    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        msg('文件上传失败，请稍后再试')
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( 'uploadComplete', function( file ) {
        $('#progress').hide()

    });
}

function removeThisFile(obj) {
    layer.confirm('确定删除',function () {
        $.ajax({
            type : "post",
            url : adminOrigin() + "del/file",
            data : {
                file:$(obj).prev().val()
            },
            success : function(res) {
                res = JSON.parse(res)
                if(res.code==0){
                    layer.msg('操作成功',{
                        time:500
                    },function () {
                        $('#file_name').html('')
                        $(obj).prev().val('')
                        $(obj).prev().prev().val('')
                        $('#file_area').hide()
                        setPercent(0)
                        $('#picker').show().html('选择文件')
                        bigUpload()
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

function searchByStationName() {
    // 百度地图API功能
    map.clearOverlays();//清空原来的标注
    var keyword = $('#address').val();
    localSearch.setSearchCompleteCallback(function (searchResult) {
        var poi = searchResult.getPoi(0);
        $('#lat').val(poi.point.lat)
        $('#lng').val(poi.point.lng)
        map.centerAndZoom(poi.point, 13);
        var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
        map.addOverlay(marker);
        var content = $('#address').val() + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
        var infoWindow = new BMap.InfoWindow("<p style='font-size:14px;'>" + content + "</p>");
        marker.addEventListener("click", function () { this.openInfoWindow(infoWindow); });
    });
    localSearch.search(keyword);
}

function highChart(title,y_name,x_name,_data,categories,container_id = 'container') {
    chart  = Highcharts.chart(container_id, {
        title: {
            text: title
        },
        subtitle: {
            text: '来源： <a href="https://www.zjteam.com/" target="_blank">帷拓科技</a>'
        },
        credits:{
            enabled: false
        },
        lang:{
            printChart: '打印图表',
            downloadJPEG: '导出 JPEG',
            downloadPDF: '导出 PDF',
            downloadPNG: '导出 PNG',
            downloadSVG: '导出 SVG',
            downloadCSV: '导出 CSV',
            downloadXLS: '导出 XLS',
            viewFullscreen: '全屏',
            loading: '加载中...',
            noData: '没有可显示的数据',
            viewData: '显示表格',
            openInCloud: '在新页面中查看'
        },
        yAxis: {
            title: {
                text: y_name
            }
        },
        xAxis: {
            title: {
                text: x_name
            },
            labels: {
                step: 1,
            },
            categories: categories
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'top'
        },
        series:_data,
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: true
                },
            }
        },
    });
    return chart
}

// success,info,error,warning
function toast(type,msg,title='提示信息') {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        onclick: null,
    };
    $("#toastrOptions").text("Command: toastr[" + type + ']("' + msg + (title ? '", "' + title : "") + '")\n\ntoastr.options = ' + JSON.stringify(toastr.options, null, 2));
    toastr[type](msg, title);
}

