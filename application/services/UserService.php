<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/6/25 0025
 * Time: 08:25
 */

namespace app\services;


use app\exception\BindAli;
use app\exception\CommonException;
use app\exception\PwdSetting;
use app\exception\UserOff;
use app\model\Shop;
use app\model\User;
use app\model\UserAccount;
use app\model\UserAddress;
use app\model\UserBalanceLog;
use app\model\UserCreditsLog;
use app\model\UserRecharge;
use think\Db;
use think\helper\Hash;

class UserService
{
    private static $instance;

    const CACHE_TAGS = 'user';
    // 一旦使用不可更改，生成用户邀请码
    private $encrypt = '6YDBNVLO5FUGQ1IAK3HJPCSW7MX2ET49RZ8';
    private $login_valid = 864000;// 规定时间内没有活动，需重新登录

    private function __construct()
    {
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

    public function webGetUserInfo($user_id){
        $db_u = new User();
        $data = $db_u->alias("u")
            ->join("personnel p","p.user_id=u.user_id")
            ->field("p.name,p.jobnumber,p.department,p.position,u.avatar")
            ->where("u.user_id",$user_id)
            ->find();
        return $data;

    }

    public function userList($phone)
    {
        $db_u = new User();
        $query = $db_u
          ->field('user_id,mobile,nickname,avatar');
        if ($phone)
            $query->where('mobile', $phone);
        $users = $query->paginate(10);
        $users->appends('phone', $phone);
        return $users;
    }

    public function login($phone, $pwd)
    {
        $db_u = new User();
        $user = $db_u->where('mobile', $phone)->field('pwd,user_id,status')->find();
        if (empty($user))
            throw new CommonException('用户名或密码错误');
        if (!Hash::check($pwd, $user['pwd']))
            throw new CommonException('用户名或密码错误');
        if ($user['status'] == 0)
            throw new CommonException('用户已冻结');
        $this->updateUserInfo($user['user_id'], ['last_login_at' => time()]);
        $token = $this->setMyToken($user['user_id']);
        return $token;
    }

    public function loginOut()
    {
        $token = app('request')->header('auth-token');
        if (app('cache')->tag(self::CACHE_TAGS)->has($token)) {
            $user_id = app('cache')->tag(self::CACHE_TAGS)->pull($token);
        }
    }



    public function getUserIdFromToken($mute = false)
    {
        $token = app('request')->header('auth-token');
        if (app('cache')->tag(self::CACHE_TAGS)->has($token)) {
            $user_id = app('cache')->tag(self::CACHE_TAGS)->get($token);
            UserService::getInstance()->bindCheckStatus($user_id);
            return $user_id;
        }
        if (!$mute)
            throw new UserOff();
        return 0;
    }
    public function getUserIdFromToken2($mute = false)
    {
        $token = app('request')->header('auth-token');
        if (app('cache')->tag(self::CACHE_TAGS)->has($token)) {
            $user_id = app('cache')->tag(self::CACHE_TAGS)->get($token);
//            UserService::getInstance()->bindCheckStatus($user_id);
            return $user_id;
        }
        if (!$mute)
            throw new UserOff();
        return 0;
    }


    public function setMyToken($user_id)
    {
        $token = str_random(18);
        app('cache')->tag(self::CACHE_TAGS)->set($token, $user_id, $this->login_valid);
        return $token;
    }

    public function getUserInfoById($user_id)
    {
        $key = 'user_info_' . $user_id;
        if (app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $db_u = new User();
        $user = $db_u->find($user_id);
        if (empty($user))
            return [];
        app('cache')->tag(self::CACHE_TAGS)->set($key, $user, 3600);
        return $user;
    }

    public function updateUserInfo($user_id, array $update)
    {
        $db_u = new User();
        $db_u->where('user_id', $user_id)->update($update);
        unset($db_u);
        $this->flushUser($user_id);
    }

    public function updatePersonnel($update,$phone){
//        echo $phone."<br>";
//        echo $user_id;exit;
        Db::name("personnel")->where("mobile",$phone)->update($update);

    }

    public function bindCheckStatus($user_id){
        $db_u = new User();
        $user = $db_u->where('user_id', $user_id)->field('mobile,ps_id')->find();
        if (empty($user)){
            throw new CommonException('登陆状态异常');
        }
        if(empty($user["mobile"]||$user["ps_id"] == 0)){
            throw new CommonException('未授权绑定手机号');
        }
        $personnel = Db::name("personnel")->where("user_id",$user_id)->find();
        if(empty($personnel)){
            throw new CommonException('未授权绑定手机号');
        }
        return $user;
    }

    public function flushUser($user_id)
    {
        $key = 'user_info_' . $user_id;
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
    }

    public function flush()
    {
        app('cache')->tag(self::CACHE_TAGS)->clear();
    }

    /**
     * 获取用户邀请码
     * @param $num
     * @return string
     */
    public function encodeUserId($num)
    {
        $source_string = $this->encrypt;
        $code = '';
        while ($num > 0) {
            $mod = $num % 35;
            $num = ($num - $mod) / 35;
            $code = $source_string[$mod] . $code;
        }
        if (empty($code[3]))
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        return $code;
    }

    /**
     * 根据邀请码获取用户id
     * @param $code
     * @return float|int
     */
    public function decodeUserId($code)
    {
        $source_string = $this->encrypt;
        if (strrpos($code, '0') !== false)
            $code = substr($code, strrpos($code, '0') + 1);
        $len = strlen($code);
        $code = strrev($code);
        $num = 0;
        for ($i = 0; $i < $len; $i++) {
            $num += strpos($source_string, $code[$i]) * pow(35, $i);
        }
        return $num;
    }





}