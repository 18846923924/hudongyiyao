<?php
namespace app\admin\controller;
use app\exception\ParamErr;
use app\Response;
use think\Validate;
use think\Request;

use app\services\AlbumService;
use app\services\AdminUserService;
use app\services\AboutService;
use think\Controller;

class About extends Controller{
    private $admin_user;

    protected function initialize()
    {
        if (!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
    }

    public function indexPage(){
        $data = AboutService::getInstance()->getAboutList();
        return view('admin/about/list',[
            'data'=>$data,
        ]);
    }

    public function addPage(){
        $a_id = $this->request->param('a_id',"");
        $data = AboutService::getInstance()->getAboutInfo($a_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/about/aboutadd',[
            'data'=>$data,
            'albums'=>$albums,
        ]);
    }

    public function editPage($a_id){
        $data = AboutService::getInstance()->getAboutInfo($a_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/about/aboutedit',[
            'data'=>$data,
            'albums'=>$albums,
        ]);
    }

    public function add(){
        $a_id = $this->request->param('a_id',"");
        $title = $this->request->param('title',"");
        $picurl = $this->request->param('picurl',"");
        $retitle = $this->request->param('retitle',"");
        $content = $this->request->param('content',"");
        AboutService::getInstance()->AboutAddDo($a_id,$title,$retitle,$picurl,$content);
        return Response::wrapData();
    }
}
