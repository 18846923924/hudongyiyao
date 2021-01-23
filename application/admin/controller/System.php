<?php


namespace app\admin\controller;


use app\exception\CommonException;
use app\exception\UserOff;
use app\Response;
use app\services\AdminUserService;
use app\services\AreaService;
use app\services\ConfigurationService;
use app\services\FileService;
use think\captcha\Captcha;
use think\Controller;
use think\Request;

class System extends Controller
{
    public function verify()
    {
        $config = [
            // 验证码字体大小
            'fontSize' => 100,
            // 验证码位数
            'length' => 4,
            'fontttf ' => '2.ttf',
            // 关闭验证码杂点
            'useNoise' => true,
            'useCurve' => false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    public function confEdit(Request $request)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $key = $request->post('key');
        $val = $request->post('val');
        ConfigurationService::getInstance()->updateConf($key, $val);
        return Response::wrapData(null);
    }

    public function confImgEdit()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $key = $this->request->post('key');
        $val = $this->request->post('val');
        $old_file = ConfigurationService::getInstance()->getConf($key);
        FileService::getInstance()->delPic($old_file);
        ConfigurationService::getInstance()->updateConf($key, $val);
        return Response::wrapData();
    }

    public function addConf()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $key = $this->request->post('key');
        $val = $this->request->post('val');
        $remark = $this->request->post('remark');
        ConfigurationService::getInstance()->addConf($key, $val, $remark);
        return Response::wrapData(null);
    }

    public function confDel()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $key = $this->request->post('key');
        ConfigurationService::getInstance()->confDel($key);
        return Response::wrapData(null);
    }

    public function miss()
    {
        if ($this->request->isAjax())
            throw new CommonException('错误的请求');
        echo 'FAIL';
    }

    public function province()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $data = AreaService::getInstance()->getProvince();
        return Response::wrapData($data);
    }

    public function city($p_code)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $data = AreaService::getInstance()->getCity($p_code);
        return Response::wrapData($data);
    }

    public function area($c_code)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $data = AreaService::getInstance()->getArea($c_code);
        return Response::wrapData($data);
    }

}