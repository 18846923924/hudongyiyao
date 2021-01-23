<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: 钟华滨
 * Phone：15858132556
 * Date: 2019/9/7 0007
 * Time: 10:34
 */

namespace app\admin\controller;


use app\exception\UserOff;
use app\Response;
use app\services\AdminUserService;
use app\services\BannerService;
use think\Controller;

class Banner extends Controller
{
    public function addBanner()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $b_id = $this->request->post('b_id', 0);
        $title = $this->request->post('title', 0);
        $retitle = $this->request->post('retitle', 0);
        $pic = $this->request->post('pic');
        $sort = $this->request->post('sort', 0);
        $url = $this->request->post('url','');
        $type = $this->request->post('type');
        BannerService::getInstance()->addBanner($b_id,$title,$pic,$sort,$url,$type,$retitle);
        return Response::wrapData();
    }

    public function delBanner()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $b_id = $this->request->post('b_id');
        $pic = $this->request->post('pic');
        BannerService::getInstance()->delBanner($b_id, $pic);
        return Response::wrapData();
    }

    public function bannerSort()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $b_id = $this->request->post('b_id');
        $sort = $this->request->post('sort');
        BannerService::getInstance()->bannerSort($b_id,$sort);
        return Response::wrapData();
    }

    public function bannerShow()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            throw new UserOff();
        $b_id = $this->request->post('b_id');
        $is_show = $this->request->post('is_show');
        BannerService::getInstance()->bannerShow($b_id,$is_show);
        return Response::wrapData();
    }



}