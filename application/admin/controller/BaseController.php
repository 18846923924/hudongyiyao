<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2020/9/2 0002
 * Time: 16:08
 */

namespace app\admin\controller;


use app\exception\UserOff;
use app\services\AdminUserService;
use think\Controller;

class BaseController extends Controller
{
    protected $admin_user;
    protected function initialize()
    {
        if (!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus()) {
            if($this->request->isAjax())
                throw new UserOff();
            else
                return $this->redirect('/cms/login');
        }
    }
}