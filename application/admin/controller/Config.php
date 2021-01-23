<?php
namespace app\admin\controller;
use app\exception\UserOff;
use app\exception\ParamErr;
use app\Response;
use think\Validate;
use think\Request;

use app\services\AlbumService;
use app\services\AdminUserService;
use app\services\ConfigService;
use think\Controller;
use think\DB;

class Config extends Controller{
    private $admin_user;

    protected function initialize()
    {
        if(!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
    }

    public function indexPage(){
        $data = ConfigService::getInstance()->webConfigListAll();
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/config/edit',[
            'data'=>$data,
            'albums'=>$albums,
        ]);
    }

    public function Configadd(){
        $data = input("post.");
        $update = 0;
        foreach($data as $k => $v) {
           $arr = array();
           $arr['varvalue'] = $v;
           $res = db::name("webconfig")->where("varname = '".$k."'")->update($arr);
           $update += $res;
        }
        return Response::wrapData();
    }

}