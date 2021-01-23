<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2020/1/5 0005
 * Time: 14:59
 */

namespace app\exception;


class BindAli extends BaseException
{
    public function __construct($debug = '')
    {
        parent::__construct('请先绑定支付宝账户', 777, $debug);
    }
}