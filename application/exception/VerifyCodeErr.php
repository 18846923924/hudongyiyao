<?php


namespace app\exception;


class VerifyCodeErr extends BaseException
{
    public function __construct($debug = '')
    {
        parent::__construct('图形验证码错误', 1002, $debug);
    }
}