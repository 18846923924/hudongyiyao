<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2019/11/15 0015
 * Time: 17:19
 */
Route::group('/', function(){
    Route::post('wx/roll/back','web/pay/weixinRollback');
    Route::post('wx/refund/listener','web/pay/weixinRefundListener');
    Route::post('ali/roll/back','web/pay/alipayPayListener');
});