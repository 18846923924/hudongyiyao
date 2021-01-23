<?php
namespace app\web\controller;
use app\exception\ParamErr;
use app\Response;
use app\services\BannerService;
use think\Validate;
use think\Request;
use app\services\AlbumService;
use app\services\GoodsService;
use app\services\GoodsCatService;
use app\services\WebNavService;
use think\Controller;

class Industry extends Base
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function index(){
        $cid = $this->request->param('cid/d',"");
        $banner = BannerService::getInstance()->getOneBanner(3);
        $goodsCat = GoodsCatService::getInstance()->webGoodsCatListAll();
        $goodsli = GoodsService::getInstance()->webGoodsList($cid,6);

        $controller =  $this->request->controller();
        $seo = WebNavService::getInstance()->getSeo($controller);
        if(!empty($seo['retitle']))
            $this->assign('lanmuname',$seo['retitle']);
        if(!empty($seo['keywords']))
            $this->assign('webkeyword',$seo['keywords']);
        if(!empty($seo['description']))
            $this->assign('webdescription',$seo['description']);
        if(!empty($cid)){
            $tinfo = GoodsCatService::getInstance()->webGoodsCatInfo($cid);
            if(!empty($tinfo['retitle']))
                $this->assign('lanmuname',$tinfo['retitle']);
            if(!empty($tinfo['keywords']))
                $this->assign('webkeyword',$seo['keywords']);
            if(!empty($tinfo['description']))
                $this->assign('webdescription',$tinfo['description']);
            $this->assign("tinfo",$tinfo);
        }
        $page = $goodsli->render2();
        return view('web/industry/industry',[
            'banner'=>$banner,
            'goodsCat'=>$goodsCat,
            'goodsli'=>$goodsli,
            'page'=>$page,
        ]);
    }

    public function Detail(){
        $id = $this->request->param('id/d',"");
        $data = GoodsService::getInstance()->getGoodsInfo($id);
        $tjli = GoodsService::getInstance()->WebNewGoodsList($data['gc_id'],$id);

        $controller =  $this->request->controller();
        $seo = WebNavService::getInstance()->getSeo($controller);
        if(!empty($seo['retitle']))
            $this->assign('lanmuname',$seo['retitle']);
        if(!empty($seo['keywords']))
            $this->assign('webkeyword',$seo['keywords']);
        if(!empty($seo['description']))
            $this->assign('webdescription',$seo['description']);

        if(!empty($data['title']))
            $this->assign('lanmuname',$data['title']);
        if(!empty($data['keywords']))
            $this->assign('webkeyword',$data['keywords']);
        if(!empty($data['description']))
            $this->assign('webdescription',$data['description']);

        return view('web/industry/indetail',[
            'data'=>$data,
            'tjli'=>$tjli,
        ]);

    }

}