<?php
namespace app\admin\controller;
use app\exception\ParamErr;
use app\Response;
use app\services\AreaService;
use think\Validate;
use think\Request;
use app\exception\CommonException;
use app\services\AlbumService;
use app\services\AdminUserService;
use app\services\InternalUserService;
use think\Controller;


class Hospital extends Controller{
    private $admin_user;

    protected function initialize()
    {
        if (!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
    }

    public function indexPage(){
        $areaCode = AdminUserService::getInstance()->getAdminCode();
        $province = AreaService::getInstance()->getAdminProvince($areaCode);
        $IuList= InternalUserService::getInstance()->adminGetIuli($areaCode,"","");
        $data = HospitalService::getInstance()->
        return view('admin/hospital/index',[
            "prov" => $province,
            "iulist" => $IuList,
        ]);
    }
}