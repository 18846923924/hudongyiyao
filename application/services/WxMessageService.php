<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2019/12/31 0031
 * Time: 14:07
 */

namespace app\services;


use app\exception\CommonException;
use app\model\User;
use ErrorCode;
use Prpcrypt;
use SHA1;
use think\facade\Log;
use XMLParse;

class WxMessageService
{
    private static $instance;
    private $conf;

    private function __construct()
    {
        $this->conf = config('djl.WeChat');
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self))
            self::$instance = new self();

        return self::$instance;
    }

    public function wxMess($post_data)
    {
        $db_u = new User();
        Log::info('微信消息：'.json_encode($post_data));
        $content = '你好';
        switch ($post_data['msgtype']) {
            case 'text':
                $word= $post_data['content'];
                $user = $db_u->field('phone')->where('pub_openid',$post_data['fromusername'])->find();
                if(!empty($user)) {
                    if($word == 1){
                        $content = randWord();
                    }
                    else
                        $content = '回复1获取新春祝福';
                }
                else{
                    $phone = urldecode($word);
                    if(preg_match("/^1[3456789]\d{9}$/", $phone)){
                        $user = $db_u->field('pub_openid,user_id')->where('phone',$phone)->find();
                        if(empty($user))
                            $content = '您的手机号尚未绑定小程序';
                        else{
                            if(!empty($user['pub_openid']))
                                $content = '该手机号已绑定小程序账户';
                            else{
                                $content = '恭喜您，绑定成功！回复1获取新春祝福';
                                UserService::getInstance()->updateUserInfo($user['user_id'],['pub_openid'=>$post_data['fromusername']]);
                            }
                        }
                    }else
                        $content = '请输入正确的手机号';
                }
                break;
            case 'event':
                if($post_data['event'] == 'subscribe') {
                    $user = $db_u->field('phone')->where('pub_openid',$post_data['fromusername'])->find();
                    if(empty($user))
                        $content = '欢迎关注董家乐美居服务号，回复手机号绑定小程序用户';
                    else
                        $content = '欢迎关注董家乐美居服务号，您当前绑定的小程序账号为'.$user['phone'].'。回复1获取新春祝福';
                }
                break;
        }
        return $this->generate($post_data['fromusername'],$post_data['tousername'],time(),'text',$content);
    }

    /**针对微信返回的xml参数，结构化为操作便利的数组
     * @param $xml
     * @return array
     */
    public function xmlStructureToArray($xml)
    {
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $xml, $arr);
        $data = [];
        foreach ($arr as $item) {
            if ($item['tag'] != 'XML')
                $data[strtolower($item['tag'])] = $item['value'];
        }
        unset($arr, $xml, $parser);
        return $data;
    }

    public function generate($openid, $from_id, $timestamp, $msg_type,$content)
    {
        $format = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        return sprintf($format, $openid, $from_id, $timestamp, $msg_type,$content);
    }


}