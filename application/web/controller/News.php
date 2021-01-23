<?php
namespace app\web\controller;
use app\Response;
use app\services\WebNavService;
use app\services\BannerService;
use app\services\NewsCatService;
use app\services\NewsService;
use app\services\EnquiryService;
use think\Controller;

class News extends Base
{

    public function NewsCatList(){
        $data = NewsCatService::getInstance()->webNewsCatListAll();
        return Response::wrapData($data);
    }
    //产品列表
    protected function initialize()
    {
        parent::initialize();
    }

    public function index(){
        $cid = $this->request->param('cid/d',"");
        $banner = BannerService::getInstance()->getOneBanner(4);
        $NewsCat = NewsCatService::getInstance()->webNewsCatListAll();
        $Newsli = NewsService::getInstance()->webNewsList($cid,10);
        $tjli = NewsService::getInstance()->WebNewsListSort($cid);
        $controller =  $this->request->controller();
        $seo = WebNavService::getInstance()->getSeo($controller);
        if(!empty($seo['retitle']))
            $this->assign('lanmuname',$seo['retitle']);
        if(!empty($seo['keywords']))
            $this->assign('webkeyword',$seo['keywords']);
        if(!empty($seo['description']))
            $this->assign('webdescription',$seo['description']);
        if(!empty($cid)){
            $tinfo = NewsCatService::getInstance()->webNewsCatInfo($cid);
            if(!empty($tinfo['retitle']))
                $this->assign('lanmuname',$tinfo['retitle']);
            if(!empty($tinfo['keywords']))
                $this->assign('webkeyword',$seo['keywords']);
            if(!empty($tinfo['description']))
                $this->assign('webdescription',$tinfo['description']);
            $this->assign("tinfo",$tinfo);
        }
        $page = $Newsli->render2();
        return view('web/news/news',[
            'banner'=>$banner,
            'NewsCat'=>$NewsCat,
            'Newsli'=>$Newsli,
            'tjli'=>$tjli,
            'page'=>$page,
        ]);
    }

    public function Detail(){
        $id = $this->request->param('id/d',"");
        $data = NewsService::getInstance()->getNewsInfo($id);
        $tjli = NewsService::getInstance()->WebNewNewsList($data['nc_id'],$id);
        $controller =  $this->request->controller();
        $seo = WebNavService::getInstance()->getSeo($controller);
        if(!empty($seo['retitle']))
            $this->assign('lanmuname',$seo['retitle']);
        if(!empty($seo['keywords']))
            $this->assign('webkeyword',$seo['keywords']);
        if(!empty($seo['description']))
            $this->assign('webdescription',$seo['description']);

        if(!empty($data['retitle']))
            $this->assign('lanmuname',$data['title']);
        if(!empty($data['keywords']))
            $this->assign('webkeyword',$data['keywords']);
        if(!empty($data['description']))
            $this->assign('webdescription',$data['description']);

        return view('web/news/detail',[
            'data'=>$data,
            'tjli'=>$tjli,
        ]);

    }
  

}