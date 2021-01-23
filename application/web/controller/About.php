<?php
namespace app\web\controller;
use app\exception\ParamErr;
use app\Response;
use app\services\BannerService;
use app\services\HonorService;
use app\services\WebNavService;
use think\Validate;
use think\Request;
use app\services\AlbumService;
use app\services\AboutService;
use think\Controller;

class About extends Base
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function index(){
        $banner = BannerService::getInstance()->getOneBanner(2);
        $leader = AboutService::getInstance()->getAboutInfo(2);
        $intro = AboutService::getInstance()->getAboutInfo(3);
        $culture = AboutService::getInstance()->getAboutInfo(4);
        $honorli = HonorService::getInstance()->webHonorListAll();
        $controller =  $this->request->controller();
        $seo = WebNavService::getInstance()->getSeo($controller);
        if(!empty($seo['retitle']))
            $this->assign('lanmuname',$seo['retitle']);
        if(!empty($seo['keywords']))
            $this->assign('webkeyword',$seo['keywords']);
        if(!empty($seo['description']))
            $this->assign('webdescription',$seo['description']);
        return view('web/about/about',[
            'banner'=>$banner,
            'leader'=>$leader,
            'culture'=>$culture,
            'intro'=>$intro,
            'honorli'=>$honorli,
        ]);
    }

}