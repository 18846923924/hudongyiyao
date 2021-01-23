<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2020/9/2 0002
 * Time: 16:06
 */

namespace app\admin\controller;


use app\exception\ParamErr;
use app\Response;
use app\services\AdminUserService;
use app\services\TnCodeService;
use think\Controller;
use think\Validate;

class Login extends Controller
{
    public function loginPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if ($admin_user)
            return $this->redirect('/cms');
        return view('admin/login');
    }

    public function login()
    {
        $name = $this->request->post('account');
        $pwd = $this->request->post('pwd');
        $validate = new Validate([
            'account'=>'require',
            'pwd'=>'require|length:32',
        ]);
        $data = [
            'account'=>$name,
            'pwd'=>$pwd,
        ];
        if(!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        AdminUserService::getInstance()->login($name,$pwd);
        return Response::wrapData();
    }

    public function tncode()
    {
        TnCodeService::getInstance()->make();
    }

    public function tncodeCheck()
    {
        return  TnCodeService::getInstance()->check();
    }
}