<?php


namespace app\exception;


class ParamErr extends BaseException
{
    public function __construct($debug = '')
    {
        parent::__construct('参数验证错误', 666, $debug);
    }
}