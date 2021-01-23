<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2020/1/4 0004
 * Time: 17:21
 */

namespace app\exception;


class PwdSetting extends BaseException
{
    public function __construct($debug = '')
    {
        parent::__construct('请先设置交易密码', 999, $debug);
    }
}