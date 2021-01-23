<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/7/2 0002
 * Time: 09:22
 */

namespace app\web\controller;


use app\exception\UserOff;
use app\Response;
use app\services\FileService;
use app\services\UserService;
use think\Controller;

class Upload extends Controller
{
    public function upload()
    {
        $user_id = UserService::getInstance()->getUserIdFromToken();
        if (!$user_id)
            throw new UserOff();
        $data = FileService::getInstance()->upload();
        return Response::wrapData($data);
    }

    public function delFile()
    {
        $user_id = UserService::getInstance()->getUserIdFromToken();
        if (!$user_id)
            throw new UserOff();
        $file = $this->request->post('file');
        FileService::getInstance()->delPic($file);
        return Response::wrapData(null);
    }
}