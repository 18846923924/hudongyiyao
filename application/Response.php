<?php
/**
 * Created by PhpStorm.
 *
 * Phone: 15858132556
 * Date: 2018/10/17
 * Time: 9:39
 */

namespace app;


class Response
{
    /**包装API返回参数
     * @param $data
     * @return string
     */
    public static function wrapData($data = null)
    {
        if($data===null)
            return json_encode(['code'=>0]);
        return json_encode([
            'code'=>0,
            'data'=>$data
        ]);
    }

    /**包装API返回异常
     * @param $code
     * @param $info
     * @param string $debug
     * @return string
     */
    public static function wrapError($code,$info,$debug='')
    {
        return json_encode([
            'code'=>$code,
            'msg'=>$info,
            'debug'=>$debug
        ]);
    }
}