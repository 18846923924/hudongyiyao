<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/6/25 0025
 * Time: 09:12
 */

namespace app\web\controller;


use app\exception\ParamErr;
use app\exception\VerifyCodeErr;
use app\Response;
use app\services\AreaService;
use app\services\ConfigurationService;
use app\services\SystemService;
use app\services\TempCacheService;
use app\services\UserService;
use think\captcha\Captcha;
use think\Controller;
use think\Request;
use think\Validate;

class System extends Controller
{
    public function verify()
    {
        $config = [
            // 验证码字体大小
            'fontSize' => 30,
            // 验证码位数
            'length' => 4,
            // 关闭验证码杂点
            'useNoise' => true,
            'useCurve' => false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    public function sendMessCode(Request $request)
    {
        $phone = $request->post('phone');
        $type = $request->post('type');
        $validate = new Validate([
            'phone' => 'require|mobile',
            'type' => 'require|egt:0|elt:2',
        ]);
        $data = [
            'phone' => $phone,
            'type' => $type,
        ];
        if (!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        SystemService::getInstance()->sendMessCode($phone, $type);
        return Response::wrapData(null);
    }

    public function sendMessCodeVerify(Request $request)
    {
        $phone = $request->post('phone');
        $type = $request->post('type');
        $code = $request->post('code');
        $validate = new Validate([
            'phone' => 'require|mobile',
            'type' => 'require|egt:0|elt:2',
        ]);
        $data = [
            'phone' => $phone,
            'type' => $type,
        ];
        if (!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        $captcha = new Captcha();
        if(!$captcha->check($code))
            throw new VerifyCodeErr($validate->getError());
        SystemService::getInstance()->sendMessCode($phone, $type);
        return Response::wrapData(null);
    }



    public function phoneCodeCheck()
    {
        $phone = $this->request->post('phone');
        $code = $this->request->post('code');
        $validate = new Validate([
            'phone' => 'require|mobile',
            'code' => 'require|length:4',
        ]);
        $data = [
            'phone' => $phone,
            'code' => $code,
        ];
        if (!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        SystemService::getInstance()->checkPhoneCode($phone, $code);
        return Response::wrapData(null);
    }

    public function province()
    {
        $data = AreaService::getInstance()->getProvince();
        return Response::wrapData($data);
    }

    public function city()
    {
        $p_code = $this->request->post('p_code');
        $validate = new Validate([
            'p_code' => 'require|length:6',
        ]);
        $data = [
            'p_code' => $p_code
        ];
        if (!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        $data = AreaService::getInstance()->getCity($p_code);
        return Response::wrapData($data);
    }

    public function area()
    {
        $c_code = $this->request->post('c_code');
        $validate = new Validate([
            'c_code' => 'require|length:6',
        ]);
        $data = [
            'c_code' => $c_code
        ];
        if (!$validate->batch()->check($data))
            throw new ParamErr($validate->getError());
        $data = AreaService::getInstance()->getArea($c_code);
        return Response::wrapData($data);
    }

    public function topSearch()
    {
        $data = ConfigurationService::getInstance()->getConfLike('search_');
        return Response::wrapData($data);
    }

    public function conf()
    {
        $key = $this->request->post('key');
        $data = ConfigurationService::getInstance()->getConf($key);
        return Response::wrapData($data);
    }

    public function payToken()
    {
        $user_id = UserService::getInstance()->getUserIdFromToken();
        $data = str_random(16);
        TempCacheService::getInstance()->set($data,300,'order_pay-'.$user_id);
        return Response::wrapData($data);
    }

}