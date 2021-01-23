<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: 钟华滨
 * Date: 2019/2/19
 * Time: 15:29
 */

namespace app\services;


use app\exception\CommonException;
use app\exception\SystemErr;
use app\exception\UserNotExist;
use app\model\Machine;
use app\model\User;
use think\facade\Log;
use think\Db;

class WeChatService
{
    private static $instance;
    private $conf;

    const template_url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send';

    const CACHE_TAGS = 'wx';

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

    /**
     * 登录
     * @param $code
     * @param $parent_id
     * @param $encrypted_data
     * @param $iv
     * @return array
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($code, $encrypted_data, $iv)
    {
        $data = $this->getUnionData($code);
//        return $data;
        $db_u = new User();
        $info = $this->userInfoDecode($encrypted_data, $iv, $data['session_key']);
        $info = json_decode($info);
        $wx_info = object_array($info);
        $nickname = urlencode($wx_info['nickName']);
        $avatar = $wx_info['avatarUrl'];
        $user = $db_u->where('openid', $data['openid'])->field('user_id')->find();
        $time = time();
        // 不是会员,新用户注册
        if (empty($user)) {
            $data = [
                'openid' => $data['openid'],
                'mobile' => '',
                'nickname' => $wx_info['nickName'],
                'avatar' => $wx_info['avatarUrl'],
                'province' => $wx_info['province'],
                'city' => $wx_info['city'],
                // 'name' => '',
                'register_at' => $time
            ];
            $user_id = $db_u->insertGetId($data);

        } else {
            $user_id = $user['user_id'];
            $update = [
                'user_id' => $user_id,
                'nickname' => $wx_info['nickName'],
                'avatar' => $avatar,
            ];
            $db_u->update($update);
        }
        return [
            'token' => UserService::getInstance()->setMyToken($user_id),
        ];
    }

    public function bindPhone($user_id, $code, $encrypted_data, $iv)
    {
//        $user = UserService::getInstance()->getUserInfoById($user_id);
//        if(!empty($user['mobile']))
//            throw new CommonException('你已绑定手机号，请勿重复操作');
        $data = $this->getUnionData($code);
        Log::info('绑定手机返回.'.json_encode($data));
        $db_u = new User();
        // 不是会员,新用户注册
        $info = $this->userInfoDecode($encrypted_data, $iv, $data['session_key']);
        $info = json_decode($info);
        $wx_info = object_array($info);
        $phone = $wx_info['phoneNumber'];
//        var_dump($wx_info);exit;
        $bindstatus =  Db::name("personnel")->where('mobile', $phone)->field('ps_id,user_id')->find();
        if (!empty($bindstatus)&&$bindstatus["user_id"] != 0)
            throw new CommonException('该手机号已绑定过，请换个手机再试');
        if(empty($bindstatus)){
            throw new CommonException('该手机号未在后台登记，请换个手机再试');
        }
        $user = $db_u->where('mobile', $phone)->field('user_id')->find();
        if (!empty($user))
            throw new CommonException('该手机号已绑定过，请换个手机再试');

        UserService::getInstance()->updatePersonnel(['user_id'=>$user_id],$phone);
        UserService::getInstance()->updateUserInfo($user_id,['mobile'=>$phone,"ps_id"=>$bindstatus['ps_id']]);
    }

    /**
     * 获取小程序用户的 open_id & token | union_id
     * @param $code string 前端提供的
     * @return bool|string
     * @throws CommonException
     */
    public function getUnionData($code)
    {
        $conf = $this->conf;
        $api = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $conf['AppId'] . '&secret=' . $conf['AppSecret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $re = file_get_contents($api);
        if ($re)
            return json_decode($re, 1);
        else
            throw new CommonException('occur error while acquire union_id!');
    }

    /**
     * 生成邀请二维码
     * @param $page
     * @param $user_id
     * @return array
     * @throws CommonException
     */
    public function qrCode($page, $user_id)
    {
        $user = UserService::getInstance()->getUserInfoById($user_id);
        if($user['vip_level'] == 0 && $user['s_id'] == 0)
            throw new CommonException('非会员暂无此权限');
        $file_dir = dirname(dirname(dirname(__FILE__))) . '/public/uploads/qrcode';
        if (!file_exists($file_dir)) {
            mkdir($file_dir, 0777, true);
        }
        $file = $file_dir . '/qr_' . $user_id . '.jpg';
        if (file_exists($file)) {
            $file = '/uploads/qrcode' . '/qr_' . $user_id . '.jpg';
            return ['qrcode' => $file];
        }
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
        $data = array();
        $data['page'] = $page;
        $data['scene'] = $user_id;
        $result = $this->get_img_array($url, $data, 'json');
        if (!file_exists($file)) {
            file_put_contents($file_dir . '/qr_' . $user_id . '.jpg', $result);
        }
        $file = '/uploads/qrcode' . '/qr_' . $user_id . '.jpg';
        return ['qrcode' => $file];
    }

    public function get_img_array($url, $post_data, $type = "plain")
    {
        if ($type == 'json') {
            $headers = array("Content-type: application/json;charset=UTF-8", "Accept: application/json", "Cache-Control: no-cache", "Pragma: no-cache");
            $post_data = json_encode($post_data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $out = curl_exec($ch);
        curl_close($ch);
        return $out;
    }

    /**
     * 解密用户信息
     * @param $encrypted_data
     * @param $iv
     * @param $session_key
     * @return mixed
     * @throws CommonException
     */
    private function userInfoDecode($encrypted_data, $iv, $session_key)
    {
        $data = base64_decode($encrypted_data);
        $iv = base64_decode($iv);
        $key = base64_decode($session_key);

        $re = openssl_decrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($re == false)
            throw new CommonException('userinfo decryption failed!');

        return $re;
    }


    public function getAccessToken()
    {
        $key = 'access_token';
        if (app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $data = [
            'grant_type' => 'client_credential',
            'appid' => $this->conf['AppId'],
            'secret' => $this->conf['AppSecret'],
        ];
        $res = CurlService::getInstance()->get('https://api.weixin.qq.com/cgi-bin/token', $data);
        if ($res) {
            $res = json_decode($res, 1);
            if (isset($res['access_token'])) {
                $token = $res['access_token'];
                app('cache')->tag(self::CACHE_TAGS)->set($key, $token, 7200);
                return $token;
            } else
                throw new CommonException($res['errmsg']);
        }
        throw new CommonException('获取token失败');
    }

    public function getPubAccessToken()
    {
        $key = 'pub_access_token';
        if (app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $data = [
            'grant_type' => 'client_credential',
            'appid' => $this->conf['PubAppId'],
            'secret' => $this->conf['PubAppSecret'],
        ];
        $res = CurlService::getInstance()->get('https://api.weixin.qq.com/cgi-bin/token', $data);
        if ($res) {
            $res = json_decode($res, 1);
            if (isset($res['access_token'])) {
                $token = $res['access_token'];
                app('cache')->tag(self::CACHE_TAGS)->set($key, $token, 7200);
                return $token;
            } else
                throw new CommonException($res['errmsg']);
        }
        throw new CommonException('获取token失败');
    }

    public function sendWxMess($openid,$project_no,$title,$name)
    {
        if(empty($openid))
            return ;
        $token = $this->getPubAccessToken();
        $data = [
            'touser' => $openid,
            'template_id' => '5GPjbTPMPOJ_Z47f0GvkiCQ2Zt1Mdk4vAO7pAeo9-oU',
//            'miniprogram'=>[
//                'appid'=>$this->conf['AppId'],
//                "pagepath"=>"index?foo=bar"
//            ],
            'data' => [
                'first' => [
                    'value' => '新的待办事项',
                ],
                "keyword1" => [
                    "value" => $project_no
                ],
                "keyword2" => [
                    "value" => $title,
                ],
                "keyword3" => [
                    "value" => $name,
                ],
                "remark" => [
                    "value" => '请尽快处理相关事宜',
                ]
            ]
        ];
        $res = CurlService::getInstance()->post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token, json_encode($data));
        $res = json_decode($res, 1);
        return $res;
    }


}