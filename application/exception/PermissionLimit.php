<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/6/22 0022
 * Time: 15:41
 */

namespace app\exception;


class PermissionLimit extends BaseException
{
    public function __construct($debug = '')
    {
        parent::__construct('权限不足', 1001, $debug);
    }
}