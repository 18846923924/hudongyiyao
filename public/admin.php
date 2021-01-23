<?php
/**
 * Description: 管理后台接口
 * Created by PhpStorm.
 *
 * Phone: 15858132556
 * Date: 2019/4/12
 * Time: 8:45
 */
// 管理后台api
Route::get('test', 'admin/test/test');
Route::group('/cms/api/', function () {
	Route::get('tncode','admin/login/tncode');//获取滑动验证码
    Route::get('tncode/check','admin/login/tncodeCheck');//获取滑动验证码
    Route::get('verify', 'admin/system/verify');
    Route::post('login', 'admin/login/login');
    Route::post('login/out', 'admin/adminUser/loginout');
    Route::post('edit/pwd', 'admin/adminUser/editPwd');
    Route::post('edit/nickname', 'admin/adminUser/editNickname');

    // 文件处理
    Route::post('upload', 'admin/upload/upload');
    Route::post('album/upload', 'admin/upload/albumPicUpload');
    Route::post('big/upload', 'admin/upload/bigUpload');
    Route::post('del/file', 'admin/upload/delFile');

    // 管理员
    Route::post('nav/permission/edit', 'admin/adminUser/navPermissionEdit');
    Route::post('sub/permission/edit', 'admin/adminUser/subPermissionEdit');
    Route::post('group/add', 'admin/adminUser/addGroup');
    Route::post('group/del/:ag_id', 'admin/adminUser/delGroup');
    Route::post('change/admin/status', 'admin/adminUser/changeAdminStatus');
    Route::post('staff/add', 'admin/adminUser/adminUserAdd');
    Route::post('staff/status', 'admin/adminUser/changeAdminStatus');
    Route::post('init/pwd/:au_id', 'admin/adminUser/initPwd');


    Route::post('conf/edit', 'admin/system/confEdit');// 修改配置项
    Route::post('conf/add', 'admin/system/addConf');// 修改配置项
    Route::post('conf/del', 'admin/system/confDel');// 修改配置项
    Route::get('province', 'admin/system/province');
    Route::get('city/:p_code', 'admin/system/city');
    Route::get('area/:c_code', 'admin/system/area');
    Route::post('apk/add', 'admin/system/addApk');
    Route::post('apple/add', 'admin/system/addApple');
    Route::post('feedback/del', 'admin/system/feedbackDel');

    Route::post('banner/position/add', 'admin/banner/addBannerPosition');//
    Route::post('banner/add', 'admin/banner/addBanner');//
    Route::post('banner/del', 'admin/banner/delBanner');//
    Route::post('banner/sort', 'admin/banner/bannerSort');//
    Route::post('banner/status', 'admin/banner/bannerStatus');//

    Route::post('article/cat/add', 'admin/article/addArticleCat');//
    Route::post('article/add', 'admin/article/addArticle');//
    Route::post('article/del', 'admin/article/delArticle');//
    Route::post('article/sort', 'admin/article/articleSort');//

    // 相册
    Route::post('album/add','admin/album/addAlbum');//
    Route::post('album/name','admin/album/albumName');//
    Route::post('album/del/:a_id','admin/album/delAlbum');//
    Route::post('album/pic/del','admin/album/delPic');//
    Route::post('album/pic/del/batch','admin/album/delPicBatch');//
    Route::get('album/pic/list/:a_id','admin/album/albumPic');//
    Route::get('album/pic/count/:a_id','admin/album/albumPicCount');//

    // 用户
    Route::post('audit/ride','admin/user/dealRideRegister');// 骑手注册审核
    Route::post('audit/shop','admin/user/dealShopRegister');// 商家注册审核
    Route::post('audit/nurse','admin/user/dealNurseRegister');// 陪诊注册审核
    Route::post('audit/doctor','admin/user/dealDoctorRegister');// 医生注册审核
    Route::post('user/add','admin/user/addUser');// 新增用户
    Route::post('reg/deal','admin/user/regDeal');// 挂号申请处理

    // 徽章-俱乐部-会员标签
    Route::post('badge/add','admin/badge/addBadge');// 新增徽章
    Route::post('badge/del','admin/badge/delBadge');// 删除徽章
    Route::post('user/badge/add','admin/badge/addUserBadge');// 新增用户徽章
    Route::post('user/badge/del','admin/badge/delUserBadge');// 删除用户徽章

    // 休闲活动
    Route::post('activity/add','admin/activity/addActivity');//新增活动
    Route::post('activity/deal','admin/activity/activityDeal');//审核活动
    Route::post('activity/del','admin/activity/activityDel');//删除活动
    Route::post('activity/edit','admin/activity/activityEdit');//活动编辑
    Route::post('activity/fine','admin/activity/activityFine');//活动设置精选

    // 商家中心
    Route::post('shop/edit','admin/shop/editShop');// 修改店铺
    Route::post('shop/lock','admin/shop/lockShop');// 锁定店铺
    Route::post('shop/rule/add','admin/shop/addShopRule');// 新增店铺优惠
    Route::post('shop/rule/del','admin/shop/delShopRule');// 删除店铺优惠
    Route::post('goods/cat/add','admin/goods/addGoodsCat');//新增商品分类
    Route::post('goods/cat/del','admin/goods/delGoodsCat');//删除商品分类
    Route::post('goods/cat/sort','admin/goods/goodsCatSort');//商品分类排序
    Route::post('goods/add','admin/goods/addGoods');//新增商品
    Route::post('goods/del','admin/goods/delGoods');//删除商品
    Route::post('goods/show','admin/goods/goodsShowIndex');//商品是否显示在首页
    // 跳蚤市场
    Route::post('flea/add','admin/flea/addFlea');//新增跳蚤商品
    Route::post('flea/edit','admin/flea/editFlea');//修改跳蚤商品
    Route::post('flea/del','admin/flea/delFlea');//删除跳蚤商品

    // 订单
    Route::post('order/shop/evaluate/reply','admin/order/orderShopEvaluateReply');//商家订单评价回复
    Route::post('order/shop/send','admin/order/orderShopSend');//商家订单发货
    Route::post('order/shop/after/deal','admin/order/orderShopAfterDeal');//商家订单售后处理

    Route::post('order/flea/send','admin/order/orderFleaSend');// 跳蚤订单发货
    Route::post('order/package/verify','admin/order/orderPackageVerify');// 体检订单核销
    Route::post('order/tour/verify','admin/order/orderTourVerify');// 旅游订单核销
    Route::post('order/tour/refund','admin/order/orderTourRefund');// 旅游订单退款


    Route::post('order/nurse/quote','admin/order/orderNurseQuote');// 陪诊订单报价


    // 课程
    Route::post('course/cat/add','admin/course/addCat');//新增分类
    Route::post('course/cat/del','admin/course/catDel');//删除分类
    Route::post('course/cat/sort','admin/course/catSort');//分类排序
    Route::post('course/add','admin/course/addCourse');//新增课程
    Route::post('course/del','admin/course/delCourse');//删除课程
    Route::post('course/sort','admin/course/courseSort');//课程排序
    Route::post('course/item/del','admin/course/courseItemDel');//课程章节删除
    Route::post('course/item/add','admin/course/courseItemAdd');//课程章节新增

    // 互帮互助
    Route::post('help/cat/add','admin/help/addCat');//
    Route::post('help/cat/del','admin/help/delCat');//

    // 跑腿加价模板
    Route::post('template/ride/add','admin/template/addRideTemp');// 新增跑腿模板
    Route::post('template/ride/del','admin/template/delRideTemp');// 删除模板
    Route::post('template/ride/distance/add','admin/template/addRideTempDistance');// 新增模板距离加价规则
    Route::post('template/ride/distance/del','admin/template/delRideTempDistance');// 删除距离加价规则
    Route::post('template/ride/weight/add','admin/template/addRideTempWeight');// 新增重力加价规则
    Route::post('template/ride/weight/del','admin/template/delRideTempWeight');//删除重量加价规则
    Route::post('template/ride/time/add','admin/template/addRideTempTime');// 新增时间段加价规则
    Route::post('template/ride/time/del','admin/template/delRideTempTime');// 删除时间段加价规则
    Route::post('template/ride/region/add','admin/template/addRideRegion');//新增跑腿区域
    Route::post('template/ride/region/del','admin/template/delRideRegion');//删除跑腿区域
    Route::post('template/ride/region/edit','admin/template/rideRegionChangeTemp');//修改区域跑腿规则

    // 消息管理
    Route::post('message/health/reply','admin/message/messageHealthReply');// 健康咨询回复



    /** 相亲广场 **/
    Route::post('meet/state', 'admin/UserMeet/state');//相亲广场个人信息锁定
    /** 菩提之旅 **/
    //旅游分类
    Route::post('tour/cat/add', 'admin/tour/catAdd');//添加
    Route::post('tour/cat/del', 'admin/tour/catDel');//删除
    Route::post('tour/cat/sort', 'admin/tour/catSort');//排序
    //旅游团
    Route::post('tour/group/add', 'admin/tour/groupAdd');//添加
    Route::post('tour/group/del', 'admin/tour/groupDel');//删除
    //旅游
    Route::post('tour/add', 'admin/tour/tourAdd');//添加
    Route::post('tour/del', 'admin/tour/tourDel');//删除
    //旅游详情
    Route::post('tour/content/add', 'admin/tour/AddTourContent');//添加、修改
    Route::post('tour/content/del', 'admin/tour/TourContentDel');//删除
    //菩提之旅软文
    Route::post('tour/article/add', 'admin/tour/AddTourArticle');//添加、修改
    Route::post('tour/article/state', 'admin/tour/TourArticleState');//状态管理
    Route::post('tour/article/del', 'admin/tour/TourArticleDel');//删除
    Route::post('package/add', 'admin/HospitalPackage/packageAdd');//体检套餐添加
    Route::post('package/del', 'admin/HospitalPackage/packageDel');//体检套餐删除
    Route::post('hospital/add', 'admin/HospitalPackage/hospitalAdd');//体检套餐删除
    Route::post('hospital/del', 'admin/HospitalPackage/hospitalDel');//体检套餐删除
    Route::post('hospital/office/add', 'admin/HospitalPackage/hospitalOfficeAdd');//
    Route::post('hospital/office/del', 'admin/HospitalPackage/hospitalOfficeDel');//
    Route::post('hospital/office/doctor/add', 'admin/HospitalPackage/hospitalOfficeDoctorAdd');//
    Route::post('hospital/office/doctor/del', 'admin/HospitalPackage/hospitalOfficeDoctorDel');//
    Route::post('package/content/add', 'admin/HospitalPackage/contentAdd');//体检套餐删除
    Route::post('package/content/del', 'admin/HospitalPackage/contentDel');//体检套餐删除

});

