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

    //关于我们
    Route::post('about/add','admin/about/add');//关于我们文章处理
 

    /**-商品模块-**/
    Route::post('goods/cat/add', 'admin/goodsCat/goodsCatadd');//商品分类增改
    Route::post('goods/cat/del', 'admin/goodsCat/goodsCatdel');//商品分类删除
    Route::post('goods/cat/sort', 'admin/goodsCat/goodsCatsort');//商品分类排序
    Route::post('goods/cat/status', 'admin/goodsCat/goodsCatstatus');//商品分类状态切换

    /**-联系我们-**/
    Route::post('message/add', 'admin/message/messageEdit');//留言增改
    Route::post('message/status', 'admin/message/status');//留言
    Route::post('message/del', 'admin/message/del');//留言

    Route::post('goods/add', 'admin/goods/goodsAdd');//产品新增
    Route::post('goods/sort', 'admin/goods/goodsSort');//商品分类排序
    Route::post('goods/status', 'admin/goods/goodsStatus');//商品分类状态切换
    Route::post('goods/del', 'admin/goods/goodsDel');//商品分类删除

    /*-新闻模块-*/
    Route::post('news/cat/add', 'admin/newsCat/newsCatadd');//新闻分类增改
    Route::post('news/cat/del', 'admin/newsCat/newsCatdel');//新闻分类删除
    Route::post('news/cat/sort', 'admin/newsCat/newsCatsort');//新闻分类排序
    Route::post('news/cat/status', 'admin/newsCat/newsCatstatus');//新闻分类状态切换

    Route::post('news/add', 'admin/news/newsAdd');//新闻新增
    Route::post('news/sort', 'admin/news/newsSort');//新闻排序
    Route::post('news/status', 'admin/news/newsStatus');//新闻状态切换
    Route::post('news/del', 'admin/news/newsDel');//新闻删除

    /*-公司荣誉-*/
    Route::post('honor/add', 'admin/honor/honoradd');//商品分类增改
    Route::post('honor/del', 'admin/honor/honordel');//商品分类删除
    Route::post('honor/sort', 'admin/honor/honorsort');//商品分类排序
    Route::post('honor/status', 'admin/honor/honorstatus');//商品分类状态切换

    /*-友情链接-*/
    Route::post('link/add', 'admin/weblink/weblinkadd');//友情链接增改
    Route::post('link/del', 'admin/weblink/weblinkdel');//友情链接删除
    Route::post('link/sort', 'admin/weblink/weblinksort');//友情链接排序
    Route::post('link/status', 'admin/weblink/weblinkstatus');//友情链接状态切换

    Route::post('config/add','admin/config/Configadd');

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


    /*产品管理*/
    Route::get('goods/cat', 'admin/goodsCat/IndexPage');//产品分类
    Route::get('goods', 'admin/goods/indexPage');//产品列表
    Route::get('goods/add', 'admin/goods/goodsAddPage');//产品新增
    Route::get('goods/edit/:g_id', 'admin/goods/goodsEditPage');//产品新增

    //新闻管理
    Route::get('news/cat', 'admin/newsCat/IndexPage');//产品分类
    Route::get('news', 'admin/news/indexPage');//产品列表
    Route::get('news/add', 'admin/news/newsAddPage');//产品新增
    Route::get('news/edit/:n_id', 'admin/news/newsEditPage');//产品新增


    //关于我们
    Route::get('about','admin/about/indexPage');//关于我们
    Route::get('about/add','admin/about/addPage');//关于我们
    Route::get('about/edit/:a_id','admin/about/editPage');//关于我们
    Route::get('honor','admin/honor/indexPage');//关于我们

    //友情链接
    Route::get('link', 'admin/weblink/IndexPage');//友情链接

    //全局配置
    Route::get('config', 'admin/config/IndexPage');

    //导航管理
    Route::get('nav','admin/nav/indexPage');

    Route::get('message','admin/message/indexPage');
//    Route::get('nav/add','admin/nav/indexPage');
//    Route::get('nav/edit/n_id','admin/nav/indexPage');



});