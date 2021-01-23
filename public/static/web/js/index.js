// 头部浮动
$(function () {
    //超过一定高度导航添加类名
    var nav = $("header"); //得到导航对象  
    var win = $(window); //得到窗口对象  
    var sc = $(document); //得到document文档对象。  
    win.scroll(function () {
        if (sc.scrollTop() >= 60) {
            nav.addClass("scope");
        } else {
            nav.removeClass("scope");
        }
    });
})


// 手机版导航弹出效果
$(function () {

    $('.phone_btn a').click(function () {
        $('.menu_box').addClass('into')
    })

    $('.menu_close').click(function () {
        $('.menu_box').removeClass('into')
    })

})



// 手机版导航展开效果
$(function () {
    // nav收缩展开
    $('.navMenu li a').on('click', function () {
        var parent = $(this).parent().parent();//获取当前页签的父级的父级
        var labeul = $(this).parent("li").find(">ul")
        if ($(this).parent().hasClass('open') == false) {
            //展开未展开
            parent.find('ul').slideUp(300);
            parent.find("li").removeClass("open")
            parent.find('li a').removeClass("active").find(".arrow").removeClass("open")
            $(this).parent("li").addClass("open").find(labeul).slideDown(300);
            $(this).addClass("active").find(".arrow").addClass("open")
        } else {
            $(this).parent("li").removeClass("open").find(labeul).slideUp(300);
            if ($(this).parent().find("ul").length > 0) {
                $(this).removeClass("active").find(".arrow").removeClass("open")
            } else {
                $(this).addClass("active")
            }
        }

    });
});


$(function () {
    jQuery(".nav").slide({
        type: "menu",// 效果类型，针对菜单/导航而引入的参数（默认slide）
        titCell: ".nLi", //鼠标触发对象
        targetCell: ".sub", //titCell里面包含的要显示/消失的对象
        effect: "slideDown", //targetCell下拉效果
        delayTime: 150, //效果时间
        triggerTime: 100, //鼠标延迟触发时间（默认150）
        defaultIndex:10,
        returnDefault: true //鼠标移走后返回默认状态，例如默认频道是“预告片”，鼠标移走后会返回“预告片”（默认false）
    });
})


$(function () {
    $('a[href*=#],area[href*=#]').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var $target = $(this.hash);
            $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
            if ($target.length) {
                var targetOffset = $target.offset().top;
                $('html,body').animate({ scrollTop: targetOffset }, 1000);
                return false;
            }
        }
    });
})

$(function () {
    $(".li_tel").hover(function () {
        $(this).addClass("current");
        $(this).children().stop().animate({
            marginLeft: -210
        }, 400)
    }, function () {
        $(this).removeClass("current");
        $(this).children().stop().animate({
            marginLeft: 0
        }, 200)
    });
    $(".s_side").click(function () {
        $("html,body").animate({
            scrollTop: 0
        }, 500);
    });

    var nav = $(".s_side"); //得到导航对象  
    var win = $(window); //得到窗口对象  
    var sc = $(document); //得到document文档对象。  
    win.scroll(function () {
        if (sc.scrollTop() >= 500) {
            nav.addClass("hade");
        } else {
            nav.removeClass("hade");
        }
    });

})
