<?php
namespace app\web\controller;
use app\exception\ParamErr;
use app\Response;
use app\services\WebNavService;
use app\services\BannerService;
use app\services\MessageService;
use think\Controller;
use think\Validate;

class Contact extends Base
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
        $banner = BannerService::getInstance()->getOneBanner(5);
      

        $controller =  $this->request->controller();
        $seo = WebNavService::getInstance()->getSeo($controller);
        if(!empty($seo['retitle']))
            $this->assign('lanmuname',$seo['retitle']);
        if(!empty($seo['keywords']))
            $this->assign('webkeyword',$seo['keywords']);
        if(!empty($seo['description']))
            $this->assign('webdescription',$seo['description']);
       
        return view('web/contact/contact',[
            'banner'=>$banner,
        ]);
    }

    public function messageAdd(){
        $name = $this->request->post("name/s","");
        $tel = $this->request->post("tel/s","");
        $address = $this->request->post("address/s","");
        $email = $this->request->post("email/s","");
        $content = $this->request->post("content/s","");
        $validate = new Validate([
            'name|姓名'=>'require',
            'tel|电话'=>'require',
            'address|地址'=>'require',
            'email|邮箱'=>'require',
        ]);
        $data = [
            'name'=>$name,
            'tel'=>$tel,
            'address'=>$address,
            'email'=>$email,
            'content'=>$content,
            'posttime'=>time()
        ];

        if(!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        MessageService::getInstance()->Add("",$data);
        return Response::wrapData();
    }

  

}