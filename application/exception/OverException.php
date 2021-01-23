<?php
/**
 * Description:接管异常
 * Created by PhpStorm.
 *
 * Date: 2019/3/15
 * Time: 16:42
 */

namespace app\exception;


use app\Response;
use Exception;
use think\exception\Handle;

class OverException extends Handle
{
    public function render(Exception $e)
    {
        if($e instanceof BaseException) {
            echo Response::wrapError($e->getCode(), $e->getMessage(), $e->getDebug());
            exit;
        }else
            return parent::render($e);
    }
}