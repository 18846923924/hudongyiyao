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

    // 相册
    Route::post('album/add', 'admin/album/addAlbum');//
    Route::post('album/name', 'admin/album/albumName');//
    Route::post('album/del/:a_id', 'admin/album/delAlbum');//
    Route::post('album/pic/del', 'admin/album/delPic');//
    Route::post('album/pic/del/batch', 'admin/album/delPicBatch');//
    Route::get('album/pic/list/:a_id', 'admin/album/albumPic');//
    Route::get('album/pic/count/:a_id', 'admin/album/albumPicCount');//

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

    Route::post('banner/add', 'admin/banner/addBanner');//
    Route::post('banner/del', 'admin/banner/delBanner');//
    Route::post('banner/sort', 'admin/banner/bannerSort');//
    Route::post('banner/show', 'admin/banner/bannerShow');//

    Route::post('config/add','admin/config/Configadd');


    Route::post('internaluser/add','admin/internaluser/addIuInfo');
    Route::post('internaluser/status','admin/internaluser/status');
    Route::post('internaluser/del','admin/internaluser/del');



    /*-导航模块-*/
    Route::post('nav/add', 'admin/nav/navadd');//导航增改
    Route::post('nav/del', 'admin/nav/navdel');//导航删除
    Route::post('nav/sort', 'admin/nav/navsort');//导航排序
    Route::post('nav/status', 'admin/nav/navstatus');//导航状态切换
});


Route::get('/miss', 'admin/system/miss');

 Route::get('/', function () {
     return redirect('/cms');
 });

Route::group('/cms/', function () {
    Route::get('', 'admin/page/commonPage');//外页
    Route::get('index', 'admin/page/indexPage');//主页
    Route::get('none', 'admin/page/nonePage');//主页
    Route::get('login', 'admin/page/loginPage');//登录
    Route::get('banner', 'admin/page/bannerPage');// 轮播图
    Route::get('banner/add/:b_id', 'admin/page/bannerAddPage');// 轮播图新增
    Route::get('staff', 'admin/page/staffPage');//人事管理
    Route::get('permission', 'admin/page/permissionPage');//权限管理

    // 相册
    Route::get('album','admin/page/albumListPage');//
    Route::get('album/pic/:a_id','admin/page/albumPicPage');//
    Route::get('album/pic/list/:a_id','admin/album/albumPic');//
    Route::get('album/pic/count/:a_id','admin/album/albumPicCount');//

    //内部账号（业务员）
    Route::get('internaluser','admin/internaluser/indexPage');
    Route::get('internaluser/add','admin/internaluser/addPage');
    Route::get('internaluser/edit/:user_id','admin/internaluser/editPage');


    //医院管理
    Route::get('hopsital','admin/hopsital/indexPage');

    //全局配置
    Route::get('config', 'admin/config/IndexPage');






});