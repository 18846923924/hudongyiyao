function webOrigin() {
    return window.location.origin + '/'
}

function getQueryParam(name){
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(decodeURI(r[2]))
    }
    return '';
}

// 日期转时间戳
function dateToTimestamp(time) {
    if(!time)
        return 0
    var date = new Date(time.replace(/-/g,'/'));
    return parseInt(date.getTime()/1000)
}


function nowTimeStamp() {
    return Math.round(new Date().getTime()/1000)
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