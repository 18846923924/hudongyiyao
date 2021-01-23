<?php


namespace app\exception;


class UserOff extends BaseException
{
    public function __construct($debug = '')
    {
        parent::__construct('请前往登录', 888, $debug);
    }
}