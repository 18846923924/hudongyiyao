<?php
namespace app\admin\controller;
use app\exception\ParamErr;
use app\Response;
use think\Validate;
use think\Request;

use app\services\AlbumService;
use app\services\AdminUserService;
use app\services\WeblinkService;
use think\Controller;

class Weblink extends Controller{
    private $admin_user;

    protected function initialize()
    {
        if(!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
    }

    public function indexPage(){
        $name = $this->request->get('name','');
        $data = WeblinkService::getInstance()->getWeblinkList($name);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/weblink/link',[
            'data'=>$data,
            'name'=>$name,
            'albums'=>$albums,
        ]);
    }

    public function Weblinkadd(){
        $id = $this->request->post('id',0);
        $title = $this->request->post('title');
        $sort = $this->request->post('sort',0);
        $checkInfo = $this->request->post('checkinfo',1);
        $picurl = $this->request->post('picurl');
        $linkurl = $this->request->post('linkurl');

        $validate = new Validate([
            'title'=>'require',
        ]);
        $data = [
            'title'=>$title,
        ];
        if(!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        WeblinkService::getInstance()->addWeblink($id, $title, $sort, $checkInfo, $picurl, $linkurl);
        return Response::wrapData();
    }

    public function Weblinkstatus(){
        $id = $this->request->post('id');
        $status = $this->request->post('status');
        WeblinkService::getInstance()->WeblinkStatus($id,$status);
        return Response::wrapData();
    }

    public function sort(){
        $id = $this->request->post('id');
        $sort = $this->request->post('sort');
        WeblinkService::getInstance()->WeblinkSort($id,$sort);
        return Response::wrapData();
    }

    public function Weblinksort(){
        $id = $this->request->post('id');
        $sort = $this->request->post('sort');
        WeblinkService::getInstance()->WeblinkSort($id,$sort);
        return Response::wrapData();
    }

    public function Weblinkdel(){
        $id = $this->request->post('id',0);
        WeblinkService::getInstance()->WeblinkDel($id);
        return Response::wrapData();
    }


}