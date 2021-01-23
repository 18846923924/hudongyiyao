<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2020/9/2 0002
 * Time: 16:07
 */


Route::miss('admin/system/miss');

Route::get('/', function () {
    return redirect('/cms');
});
Route::group('/cms/', function () {
    Route::get('login', 'admin/login/loginPage');//登录
    Route::get('', 'admin/page/commonPage');//外页
    Route::get('index', 'admin/page/indexPage');//主页
    Route::get('none', 'admin/page/nonePage');//没有权限
    Route::get('pwd/edit', 'admin/page/pwdEditPage');//
    Route::get('banner/position', 'admin/page/bannerPositionPage');// 轮播图位
    Route::get('banner', 'admin/page/bannerPage');// 轮播图
    Route::get('banner/add/:b_id', 'admin/page/bannerAddPage');// 轮播图新增
    Route::get('feedback', 'admin/page/feedbackPage');//意见反馈

    Route::get('article/cat', 'admin/page/articleCatPage');// 轮播图位
    Route::get('article', 'admin/page/articlePage');// 轮播图
    Route::get('article/add/:a_id', 'admin/page/articleAddPage');// 轮播图新增


    Route::get('staff', 'admin/page/staffPage');//人事管理
    Route::get('group', 'admin/page/groupPage');//人事管理
    Route::get('permission/:ag_id', 'admin/page/permissionPage');//权限管理

    // 相册
    Route::get('album','admin/page/albumListPage');//
    Route::get('album/pic/:a_id','admin/page/albumPicPage');//

    // 会员中心
    Route::get('user','admin/page/userPage');//会员列表
    Route::get('user/add','admin/page/userAddPage');//会员列表
    Route::get('user/detail/:user_id','admin/page/userDetailPage');//会员列表
    Route::get('user/health','admin/page/healthPage');//健康档案
    Route::get('user/health/detail/:uh_id','admin/page/healthDetailPage');//健康档案详情
    Route::get('user/pay','admin/page/payLogsPage');//支付流水
    Route::get('account/log','admin/page/accountLogsPage');//账户日志
    Route::get('message/health','admin/page/messageHealthPage');//健康咨询
    Route::get('message/health/detail/:mh_id','admin/page/messageHealthDetailPage');//健康咨询详情
    Route::get('reg','admin/page/regPage');//挂号列表
    Route::get('reg/detail/:ur_id','admin/page/regDetailPage');//挂号详情

    Route::get('badge','admin/page/badgePage');// 徽章管理
    Route::get('conf/health','admin/page/confHealthPage');// 健康档案类型
    Route::get('conf/goods','admin/page/confGoodsPage');// 跑腿商品类型
    Route::get('conf/feedback','admin/page/confFeedbackPage');// 反馈标签
    Route::get('conf/rate','admin/page/confRatePage');// 抽成配置
    Route::get('conf/consult','admin/page/confConsultPage');// 健康咨询配置
    Route::get('apple', 'admin/page/applePage');//版本管理
    Route::get('apk', 'admin/page/apkPage');//版本管理
    Route::get('apk/add', 'admin/page/apkAddPage');//版本新增
    Route::get('apk/detail/:version_code', 'admin/page/apkDetailPage');//版本详情


    // 审核
    Route::get('audit/activity','admin/page/activityAuditPage');//活动审核
    Route::get('audit/activity/detail/:a_id','admin/page/activityAuditDetailPage');//活动审核详情
    Route::get('audit/doctor','admin/page/doctorAuditPage');//医生注册
    Route::get('audit/doctor/detail/:urd_id','admin/page/doctorAuditDetailPage');//医生注册详情
    Route::get('audit/nurse','admin/page/nurseAuditPage');//陪诊注册
    Route::get('audit/nurse/detail/:urn_id','admin/page/nurseAuditDetailPage');//陪诊注册详情
    Route::get('audit/ride','admin/page/rideAuditPage');//骑手注册
    Route::get('audit/ride/detail/:urr_id','admin/page/rideAuditDetailPage');//骑手注册详情
    Route::get('audit/shop','admin/page/shopAuditPage');//商家注册
    Route::get('audit/shop/detail/:urs_id','admin/page/shopAuditDetailPage');//商家注册详情

    // 商家中心
    Route::get('shop','admin/page/shopPage');// 门店详情
    Route::get('shop/edit','admin/page/shopEditPage');// 门店修改
    Route::get('shop/mine','admin/page/shopMinePage');// 门店详情
    Route::get('shop/detail/:s_id','admin/page/shopDetailPage');// 门店详情
    Route::get('shop/rule','admin/page/shopRulePage');// 门店优惠
    Route::get('shop/cat','admin/page/shopCatPage');// 商品分类
    Route::get('shop/goods','admin/page/shopGoodsPage');// 商品列表
    Route::get('shop/shop/goods','admin/page/shopShopGoodsPage');// 商品列表
    Route::get('shop/goods/add/:sg_id','admin/page/shopGoodsAddPage');// 商品列表
    Route::get('flea','admin/page/fleaPage');//跳蚤市场
    Route::get('flea/add','admin/page/fleaAddPage');//跳蚤市场
    Route::get('flea/edit/:f_id','admin/page/fleaEditPage');//跳蚤市场

    // 订单
    Route::get('order/shop','admin/page/orderShopPage');// 健康商城订单
    Route::get('order/shop/detail/:os_id','admin/page/orderShopDetailPage');// 健康商城订单详情
    Route::get('order/nurse','admin/page/orderNursePage');// 陪诊订单
    Route::get('order/nurse/detail/:on_id','admin/page/orderNurseDetailPage');// 陪诊订单详情
    Route::get('order/ride','admin/page/rideOrderListPage');// 陪诊订单详情
    Route::get('order/ride/detail/:or_id','admin/page/rideOrderDetailPage');// 陪诊订单详情
    Route::get('order/flea','admin/page/fleaOrderListPage');// 跑腿订单
    Route::get('order/flea/detail/:of_id','admin/page/fleaOrderDetailPage');// 跑腿订单详情
    Route::get('order/package','admin/page/packageOrderListPage');// 体检套餐订单
    Route::get('order/package/detail/:ohp_id','admin/page/packageOrderDetailPage');// 体检套餐订单详情
    Route::get('order/help','admin/page/helpOrderListPage');// 互助订单
    Route::get('order/help/detail/:oh_id','admin/page/helpOrderDetailPage');// 互助订单详情
    Route::get('order/activity','admin/page/activityOrderListPage');// 休闲活动订单
    Route::get('order/activity/detail/:oa_id','admin/page/activityOrderDetailPage');// 休闲活动订单详情
    Route::get('order/tour','admin/page/tourOrderListPage');// 休闲活动订单
    Route::get('order/tour/detail/:ot_id','admin/page/tourOrderDetailPage');// 休闲活动订单详情


    Route::get('order/course','admin/page/courseOrderPage');// 课程订单
    // 课程
    Route::get('course/cat','admin/page/courseCatPage');//课程分类
    Route::get('course','admin/page/coursePage');//课程
    Route::get('course/add/:c_id','admin/page/courseAddPage');//课程新增
    Route::get('course/item/:c_id','admin/page/courseItemPage');//课程章节
    Route::get('course/item/add/:c_id/:ci_id','admin/page/courseItemAddPage');//课程章节新增

    // 休闲活动
    Route::get('activity','admin/page/activityPage');//列表
    Route::get('activity/add','admin/page/activityAddPage');//新增
    Route::get('activity/edit/:a_id','admin/page/activityEditPage');//修改
    Route::get('activity/detail/:a_id','admin/page/activityDetailPage');//详情

    Route::get('help/cat','admin/page/helpCatPage');// 互帮互助分类

    // 跑腿加价模板
    Route::get('ride/template','admin/page/rideTemplatePage');// 跑腿模板设置
    Route::get('ride/template/detail/:tr_id','admin/page/rideTemplateDetailPage');// 跑腿模板设置
    Route::get('ride/region','admin/page/rideRegionPage');// 跑腿区域设置

    /** 相亲广场 **/
    Route::get('user/meet','admin/UserMeet/meetLi');//相亲广场
    Route::get('meet/edit/:um_id','admin/UserMeet/meetEdit');// 个人信息详情
    /** 菩提之旅 **/
    Route::get('tour/cat','admin/tour/catList');//旅游分类列表
    Route::get('tour/group','admin/tour/groupList');//旅游团列表
    Route::get('tour','admin/tour/tourList');//旅游列表
    Route::get('tour/add/:t_id','admin/tour/tourAddPage');//旅游新增
    Route::get('tour/content/:t_id','admin/tour/tourContentList');//旅游详情列表
    Route::get('tour/content/add/:tc_id/:t_id','admin/tour/tourContentAddPage');//旅游详情新增
    Route::get('tour/article','admin/tour/adminTourArticle');//菩提之旅软文列表
    Route::get('tour/article/add','admin/tour/adminTourArticlePage');//菩提之旅软文添加页
    Route::get('tour/article/detail/:ta_id', 'admin/tour/adminTourArticleDetailPage');//菩提之旅软文详情
    Route::get('package','admin/HospitalPackage/adminPackagePage');//体检套餐列表页
    Route::get('package/add/:hp_id','admin/HospitalPackage/adminPackageAddPage');//体检套餐添加、修改页
    Route::get('package/content/:hp_id','admin/HospitalPackage/adminContentPage');// 套餐详情内容
    Route::get('package/content/add/:hc_id/:hp_id','admin/HospitalPackage/adminContentAddPage');// 套餐详情内容新增
    Route::get('hospital','admin/HospitalPackage/adminHospitalPage');// 医院列表
    Route::get('hospital/add/:h_id','admin/HospitalPackage/adminHospitalAddPage');// 医院新址
    Route::get('hospital/office/:h_id','admin/HospitalPackage/hospitalOfficePage');// 医院科室


});