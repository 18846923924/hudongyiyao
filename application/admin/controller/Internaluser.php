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

class Internaluser extends Controller{
    private $admin_user;

    protected function initialize()
    {
        if (!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
    }

    public function indexPage(){
        $name = $this->request->get('name','');
        $prov = $this->request->get('prov','');
        $areaCode = AdminUserService::getInstance()->getAdminCode();
        $province = AreaService::getInstance()->getAdminProvince($areaCode);
//        var_dump($province);exit;
    	$data = InternalUserService::getInstance()->adminGetIuli($areaCode,$name,$prov);
        return view('admin/internaluser/index',[
            'data'=>$data,
            'name'=>$name,
            'prov'=>$prov,
            'province'=>$province,
        ]);
    }

    public function addPage(){
        $areaCode = AdminUserService::getInstance()->getAdminCode();
        $province = AreaService::getInstance()->getAdminProvince($areaCode);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/internaluser/add',[
            "prov" => $province,
            "albums" => $albums,
        ]);
    }

    public function editPage($user_id){
        $au_id = AdminUserService::getInstance()->getAdminUserID();
        $userinfo = AdminUserService::getInstance()->getAdminUserById($au_id);
        if($userinfo["ag_id"]==1){
            $province = AreaService::getInstance()->getProvince();
        }else{
            $areaCode = AdminUserService::getInstance()->getAdminCode();
            $province = AreaService::getInstance()->getAdminProvince($areaCode);
        }
        $data = InternalUserService::getInstance()->getUerInfoByID($user_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/internaluser/edit',[
            "prov" => $province,
            "albums" => $albums,
            "data" => $data,
        ]);
    }


    public function addIuInfo(){
        $user_id = $this->request->post('user_id',0);
        $username = $this->request->post('username');
        if($username&&empty($user_id)){
            $data = InternalUserService::getInstance()->getUerInfo($username);
            if(!empty($data)){
                throw new CommonException('该账号已存在');
            }
        }
        $pwd = $this->request->post('pwd');
        $avatar = $this->request->post('avatar');
        $mobile = $this->request->post('mobile');
        $nickname = $this->request->post('nickname');
        $name = $this->request->post('name');
        $idnum = $this->request->post('idnum');
        $province = $this->request->post('province');
        $checkinfo = $this->request->post('checkinfo',1);
        InternalUserService::getInstance()->adminGetIuAdd($user_id,$username,$pwd,$avatar,$mobile,$nickname,$name,$idnum,$province,$checkinfo);
        return Response::wrapData();
    }


    public function status(){
        $user_id = $this->request->post('user_id',0);
        $status = $this->request->post('status');
        InternalUserService::getInstance()->statusSet($user_id,$status);
        return Response::wrapData();
    }


    public function del(){
        $user_id = $this->request->post('user_id',0);
        InternalUserService::getInstance()->InternalDel($user_id);
        return Response::wrapData();
    }


}







?>