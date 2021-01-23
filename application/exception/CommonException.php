<?php


namespace app\exception;


class CommonException extends BaseException
{
    public function __construct($message, $debug = '')
    {
        parent::__construct($message, 1000, $debug);
    }
}