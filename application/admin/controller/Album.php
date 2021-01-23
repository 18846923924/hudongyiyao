<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2019/12/5 0005
 * Time: 17:00
 */

namespace app\admin\controller;


use app\exception\UserOff;
use app\Response;
use app\services\AdminUserService;
use app\services\AlbumService;
use think\Controller;

class Album extends Controller
{
    private $admin_user;

    protected function initialize()
    {
        if(!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
    }

    public function addAlbum()
    {
        $name = $this->request->post('name');
        $data = AlbumService::getInstance()->addAlbum($name);
        return Response::wrapData($data);
    }

    public function albumName()
    {
        $a_id = $this->request->post('a_id');
        $name = $this->request->post('name');
        AlbumService::getInstance()->albumName($a_id,$name);
        return Response::wrapData();
    }

    public function delAlbum($a_id)
    {
        AlbumService::getInstance()->delAlbum($a_id);
        return Response::wrapData();
    }

    public function delPic()
    {
        $a_id = $this->request->post('a_id');
        $ap_id = $this->request->post('ap_id');
        $pic = $this->request->post('pic');
        AlbumService::getInstance()->delPic($a_id,$ap_id,$pic);
        return Response::wrapData();
    }

    public function delPicBatch()
    {
        $ap_id = $this->request->post('ap_id');
        $a_id = $this->request->post('a_id');
        AlbumService::getInstance()->delPicBatch($ap_id,$a_id);
        return Response::wrapData();
    }

    public function albumPic($a_id)
    {
        $page = $this->request->get('page');
        $data = AlbumService::getInstance()->albumPicPage($a_id,$page);
        return Response::wrapData($data);
    }

    public function albumPicCount($a_id)
    {
        $data = AlbumService::getInstance()->albumPicCount($a_id);
        return Response::wrapData($data);
    }














}