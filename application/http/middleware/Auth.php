<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/11/12 0012
 * Time: 11:49
 */

namespace app\http\middleware;


class Auth
{
    public function handle($request,\Closure $next)
    {
        if(app('request')->header('auth-token')==false)
            die('you are welcome!');
        return $next($request);
    }
}