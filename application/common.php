<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Db;

function str_random($length = 16)
{
    $string = '';
    while (($len = strlen($string)) < $length) {
        $size = $length - $len;
        $bytes = random_bytes($size);
        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }
    return $string;
}

/**
 * 生成支付订单号 24位
 * @param bool $shop_num 大于1时为多商铺同时下单
 * @param string $prefix 不为空时,使用此头
 * @return string
 * @throws Exception
 */
function generatePayOrderNo($shop_num)
{
    $header = 's_';// 单一店铺下单
    if ($shop_num > 1)
        $header = 'm_';// 多店铺下单
    return $header . date('YmdHis') . random_int(1000000, 9999999) . $shop_num;
}

/**
 * @param bool $is_recharge
 * @param bool $is_purchase
 * @return string
 * @throws Exception
 */
function generateRechargeOrderNo($is_recharge = false)
{
    $header = 'vip_';// 购买vip
    if ($is_recharge)
        $header = 're_';// 充值
    return $header . date('YmdHis') . random_int(1000000, 9999999);
}

/**
 * 解析订单号
 * @param $order_no
 * @return bool|int 1单一店铺下单 2多店铺下单
 */
function analysePayOrderNo($order_no)
{
    $re = explode('_', $order_no);
    if ($re[0] == 're')
        return 1;
    if ($re[0] == 'vip')
        return 2;
    return false;

}

function generateOrderNo()
{
    return date('YmdHis') . random_int(1000000, 9999999);
}


//type  0:加密  1:解密
function encryption($value, $type = 0)
{
    $key = config('cookie.encryption_key');
    if ($type == 0)
        return str_replace('=', '', base64_encode($value ^ $key));
    return base64_decode($value) ^ $key;
}

function substrstr($str, $length)
{
    $misc = '';
    if (mb_strlen($str) > $length)
        $misc = '...';
    return mb_substr($str, 0, $length) . $misc;
}

function parseTime($time, $precision = 3)
{
    if (!$time)
        return '';
    $format = 'Y-m-d';
    switch ($precision) {
        case 1:
            $format = 'Y-m-d';
            break;
        case 2:
            $format = 'Y-m-d H';
            break;
        case 3:
            $format = 'Y-m-d H:i';
            break;
        case 4:
            $format = 'Y-m-d H:i:s';
            break;
    }
    return date($format, $time);
}


function userTextDecode($str)
{
    $text = json_encode($str); //
    $text = preg_replace_callback('/\\\\\\\\/i', function ($str) {
        return '\\';
    }, $text); //将两条斜杠变成一条，其他不动
    return json_decode($text);
}


function parseBoo($boo)
{
    return $boo ? '是' : '否';
}


function sexToName($sex)
{
    switch ($sex) {
        case 0:
            return '保密';
            break;
        case 1:
            return '男';
            break;
        case 2:
            return '女';
            break;
    }
}

function getCatname($gc_id){
    $res = Db::name("goodsCat")->where(["gc_id"=>$gc_id])->value("title");
    if($res){
        return $res;
    }else{
        return "暂未查出所属分类";
    }
}

function bannerType($type) {
    $res = Db::name("bannertype")->where(["bt_id"=>$type])->value("title");
    if($res){
        return $res;
    }else{
        return "暂未查出所属分类";
    }
}

function object_array($array){
    if(is_object($array)){
        $array = (array)$array;
    }
    if(is_array($array)){
        foreach($array as $key=>$value){
            $array[$key] = object_array($value);
        }
    }
    return $array;
}


function webconfig($varname){

    $config = Db::name('webconfig')->where('varname="'.$varname.'"')->find();
    return $config['varvalue'];
}