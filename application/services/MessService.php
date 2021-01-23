<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/7/2 0002
 * Time: 18:14
 */

namespace app\services;


use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use app\exception\CommonException;

class MessService
{
    private static $instance;

    static $acsClient = null;

    private $conf;
    private function __construct(){
        $this->conf = config('xss.Alisms');
    }

    private function __clone(){

    }

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }

    public function sendMess($phone,$code)
    {
        require_once './extend/alisms/vendor/autoload.php';
        Config::load(); //加载区域结点配置
        $templateParam = ['code'=>$code];
        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";
        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $this->conf['accessKeyId'], $this->conf['accessKeySecret']);
        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        // 初始化AcsClient用于发起请求
        $acsClient = new DefaultAcsClient($profile);
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phone);
        // 必填，设置签名名称
        $request->setSignName($this->conf['signName']);
        // 必填，设置模板CODE
        $request->setTemplateCode($this->conf['templateCode']);
        // 可选，设置模板参数
        if ($templateParam)
            $request->setTemplateParam(json_encode($templateParam));
        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        //返回请求结果
        $result = json_decode(json_encode($acsResponse), true);
        // 具体返回值参考文档：https://help.aliyun.com/document_detail/55451.html?spm=a2c4g.11186623.6.563.YSe8FK
        if($result['Code'] != "OK")
            throw new CommonException('短信发送失败',json_encode($result));
    }

}