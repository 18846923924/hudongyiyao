<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 * Date: 2019/3/15
 * Time: 16:45
 */

namespace app\exception;


use think\Exception;

class BaseException extends Exception
{
    //调试过程中输出的调试信息
    private $debug;

    public function __construct($message,$code,$debug='')
    {
        $this->debug = $debug;
        parent::__construct($message, $code, null);
    }

    public function getDebug()
    {
        return $this->debug;
    }
}