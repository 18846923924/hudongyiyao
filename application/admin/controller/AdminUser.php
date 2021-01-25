<?php
namespace app\admin\controller;

use app\exception\UserOff;
use app\Response;
use app\services\AdminUserService;
use app\services\SystemService;
use think\Request;

class AdminUser extends BaseController

{

    public function initPwd($au_id)
    {
        AdminUserService::getInstance()->initPwd($au_id);
        return Response::wrapData(null);
    }



    public function loginout()
    {
        AdminUserService::getInstance()->clearUserSession();
        return Response::wrapData(null);
    }

    public function editPwd()

    {
        $new_pwd = $this->request->post('new_pwd');
        $old_pwd = $this->request->post('old_pwd');
        AdminUserService::getInstance()->editPwd($this->admin_user['au_id'],$new_pwd,$old_pwd);
        return Response::wrapData(null);
    }



    public function editNickname()
    {
        $nickname = $this->request->post('nickname');
        AdminUserService::getInstance()->editNickname($nickname,$this->admin_user);
        return Response::wrapData(null);
    }



    public function navPermissionEdit()

    {
        $an_id = $this->request->post('an_id');
        $ag_id = $this->request->post('ag_id');
        SystemService::getInstance()->navPermissionEdit($an_id,$ag_id);
        return Response::wrapData(null);
    }



    public function subPermissionEdit()
    {
        $ans_id = $this->request->post('ans_id');
        $ag_id = $this->request->post('ag_id');
        SystemService::getInstance()->subPermissionEdit($ans_id,$ag_id);
        return Response::wrapData(null);
    }



    public function addGroup()
    {
        $name = $this->request->post('name');
        $ag_id = $this->request->post('ag_id',0);
        AdminUserService::getInstance()->addGroup($name,$ag_id);
        return Response::wrapData(null);
    }



    public function changeAdminStatus()
    {
        $au_id = $this->request->post('au_id');
        $status = $this->request->post('status');
        AdminUserService::getInstance()->changeAdminStatus($au_id,$status);
        return Response::wrapData(null);
    }



    public function adminUserAdd()
    {
        $ag_id = $this->request->post('ag_id');
        $name = $this->request->post('name');
        $pwd = $this->request->post('pwd');
        $nickname = $this->request->post('nickname');
        $prov = $this->request->post('prov');
        $user_id = AdminUserService::getInstance()->adminUserAdd($ag_id,$name,$pwd,$nickname,$prov);
        $data = [
            'au_id' => $user_id,
            'areacode' => $prov
        ];

        if($ag_id != 1){
            AdminUserService::getInstance()->adminAreaPermission($data);
        }

        return Response::wrapData(null);
    }



    public function adminUserUnderGroup($ag_id)
    {
        $users = AdminUserService::getInstance()->adminGroupUsers($ag_id);
        return Response::wrapData($users);
    }





    public function delGroup($ag_id)
    {
        AdminUserService::getInstance()->delGroup($ag_id);
        return Response::wrapData();
    }





}