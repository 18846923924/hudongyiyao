<?php
/**
 * Description: app接口
 * Created by PhpStorm.
 * Date: 2019/4/12
 * Time: 8:45
 */
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Headers:x-requested-with, content-type');
//Route::group('/', function(){
//    Route::get('','web/index/index');//首页接口
//    Route::get('index','web/index/index');//首页接口
//    Route::get('about','web/about/index');//关于我们
//    Route::get('industry','web/industry/index');//产业介绍
//    Route::get('InDetail','web/industry/Detail');//产业详情
//    Route::get('news','web/news/index');//新闻列表
//    Route::get('Detail','web/news/Detail');//新闻详情
//    Route::get('contact','web/contact/index');//联系我们
//    Route::get('message','web/contact/messageAdd');//联系我们
//    Route::post('message','web/contact/messageAdd');//联系我们
//});