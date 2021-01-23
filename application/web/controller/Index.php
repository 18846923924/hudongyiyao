<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: 钟华滨
 * Phone：15858132556
 * Date: 2019/6/27 0027
 * Time: 10:31
 */

namespace app\web\controller;
use app\services\BannerService;
use app\services\GoodsCatService;
use app\services\GoodsService;
use app\services\NewsService;
use app\services\AboutService;
use app\services\WebNavService;
use think\Controller;

class Index extends Base
{

    protected function initialize()
    {
        parent::initialize();
    }

    public function Index(){
        $banner = BannerService::getInstance()->indexBanner(1,1);
        $info = AboutService::getInstance()->getAboutInfo(1);
        $news = NewsService::getInstance()->indexNewsLi(4);
        $catli = GoodsCatService::getInstance()->webGoodsCatListAll();
        $gdata = GoodsService::getInstance()->webIndexData($catli);
        $controller =  $this->request->controller();
        $seo = WebNavService::getInstance()->getSeo($controller);
        if(!empty($seo['retitle']))
            $this->assign('lanmuname',$seo['retitle']);
        if(!empty($seo['keywords']))
            $this->assign('webkeyword',$seo['keywords']);
        if(!empty($seo['description']))
            $this->assign('webdescription',$seo['description']);
        return view('web/index/index',[
            'banner'=>$banner,
            'info'=>$info,
            'news'=>$news,
            'catli'=>$catli,
            'gdata'=>$gdata,
        ]);
    }

}