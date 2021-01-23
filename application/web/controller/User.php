<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/11/11 0011
 * Time: 14:57
 */

namespace app\web\controller;


use app\exception\ParamErr;
use app\Response;
use app\services\UserService;
use app\services\WeChatService;
use think\Controller;
use think\Validate;

class User extends Controller
{
    public function login()
    {
        $code = $this->request->post('code');
        $parent_id = $this->request->post('parent_id',0);
        $encrypted_data = $this->request->post('encryptedData');
        $iv = $this->request->post('iv');
        $validate = new Validate([
            'parent_id'=>'egt:0|integer',
            'code'=>'require',
        ]);
        $data = [
            'parent_id'=>$parent_id,
            'code'=>$code,
        ];
        if(!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        $data = WeChatService::getInstance()->login($code,$encrypted_data,$iv);
        return Response::wrapData($data);
    }

    public function bindPhone()
    {
        $user_id = UserService::getInstance()->getUserIdFromToken2();
        $code = $this->request->post('code');
        $encrypted_data = $this->request->post('encryptedData');
        $iv = $this->request->post('iv');
        $validate = new Validate([
            'code'=>'require',
            'encryptedData'=>'require',
            'iv'=>'require',
        ]);
        $data = [
            'code'=>$code,
            'encryptedData'=>$encrypted_data,
            'iv'=>$iv,
        ];
        if(!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        WeChatService::getInstance()->bindPhone($user_id, $code, $encrypted_data, $iv);
        return Response::wrapData();
    }

    public function bindCheck(){
        $user_id = UserService::getInstance()->getUserIdFromToken();
        UserService::getInstance()->bindCheckStatus($user_id);
        return Response::wrapData();
    }


    public function loginOut()
    {
        UserService::getInstance()->loginOut();
        return Response::wrapData();
    }


    public function Userinfo(){
        $user_id = UserService::getInstance()->getUserIdFromToken();
        $data = UserService::getInstance()->webGetUserInfo($user_id);
        return Response::wrapData($data);

    }


}