// 重新加载表单
function renderForm(){
    layui.use('form', function(){
        var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
        form.render();
    });
}

//加载省
function initProvince(obj){
    $.ajax({
        type : "get",
        url : "/province",
        success : function(res) {
            if (res.data!= null && res.data.length > 0) {
                var str = "";
                for (var i = 0; i < res.data.length; i++) {
                    str += '<option value="'+res.data[i].code+'">'+res.data[i].name+'</option>';
                }
                $(obj).append(str);
                renderForm()
            }else{
                layer.msg(res.msg)
            }
        }
    });
}

//选择省份弹出市区
function getCity(code,second,third) {
    var id = code;
    $.ajax({
        type : "get",
        url : '/city',
        data:{
            'p_code':id
        },
        success : function(res) {
            if (res.data != null && res.data.length > 0) {
                var str = "<option value=''>请选择市</option>";
                for (var i = 0; i < res.data.length; i++) {
                    str += '<option value="'+res.data[i].code+'">'+res.data[i].name+'</option>';
                }
                $(second).html(str);
                $(third).html('<option value="-1" selected="selected">请选择区</option>');
                renderForm()
            }
        }
    });
};

//选择省份弹出市区
function getArea(code,second) {
    var id = code;
    $.ajax({
        type : "get",
        url : '/area',
        data:{
            'c_code':id
        },
        success : function(res) {
            if (res.data != null && res.data.length > 0) {
                var str = "";
                var str = "<option value=''>请选择区</option>";
                for (var i = 0; i < res.data.length; i++) {
                    str += '<option value="'+res.data[i].code+'">'+res.data[i].name+'</option>';
                }
                $(second).html(str);
                renderForm()

            }
        }
    });
};