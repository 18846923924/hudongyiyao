<?php


namespace app\services;


use app\exception\CommonException;
use app\model\AdminNavPermission;
use app\model\AdminNavSubPermission;
use app\model\Agent;
use app\model\Diamond;
use app\model\Feedback;
use app\model\GoodsCat;
use app\model\Help;
use app\model\Video;
use think\Db;

class SystemService
{
    private static $instance;

    const CACHE_TAGS = 'system';

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

    public function groupPermission()
    {
        $groups = AdminUserService::getInstance()->adminGroup();
        foreach ($groups as &$group) {
            $navs = NavService::getInstance()->navsUnderGroup($group['ag_id']);
            $group['navs'] = $navs;
            $group['row'] = count($navs);
        }
        return $groups;
    }

    public function navPermissionEdit($an_id, $ag_id)
    {
        $db_anp = new AdminNavPermission();
        $data = $db_anp->where(['an_id' => $an_id, 'ag_id' => $ag_id])->field('anp_id')->find();
        if (empty($data))
            $db_anp->insert([
                'an_id' => $an_id,
                'ag_id' => $ag_id
            ]);
        else
            $db_anp->where('anp_id', $data['anp_id'])->delete();
        NavService::getInstance()->flushNav($ag_id);
    }

    public function subPermissionEdit($ans_id, $ag_id)
    {
        $db_ansp = new AdminNavSubPermission();
        $data = $db_ansp->where(['ans_id' => $ans_id, 'ag_id' => $ag_id])->field('ansp_id')->find();
        if (empty($data))
            $db_ansp->insert([
                'ans_id' => $ans_id,
                'ag_id' => $ag_id
            ]);
        else
            $db_ansp->where('ansp_id', $data['ansp_id'])->delete();
        NavService::getInstance()->flushNav($ag_id);
    }

    public function checkOperation($ag_id , $path = '')
    {
        if($path == '')
            $path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $res = Db::name('admin_nav_sub')
            ->alias('ans')
            ->leftJoin('admin_nav_sub_permission ansp','ans.ans_id=ansp.ans_id')
            ->where('ans.url',$path)
            ->where('ansp.ag_id',$ag_id)
            ->field('ans.ans_id')->find();
        if(empty($res))
            return false;
        return true;

    }

    /**
     * @param $phone
     * @param $type 1 手机号不存在时抛异常 0手机号存在时抛异常
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sendMessCode($phone, $type)
    {
        $user_id = UserService::getInstance()->checkUserExist($phone);
        if (!$user_id && $type == 1)
            throw new CommonException('手机号不存在');
        if ($user_id && $type == 0)
            throw new CommonException('手机号已存在');
        $code = rand(1000,9999);
//        $code = 1111;
        MessService::getInstance()->sendMess($phone,$code);
        TempCacheService::getInstance()->set($code, 900, $phone);
    }

    public function checkPhoneCode($phone, $code)
    {
        $code_cache = TempCacheService::getInstance()->get($phone);
        if ($code != $code_cache)
            throw new CommonException('手机验证码不正确');
    }

    public function checkEmailCode($email,$code)
    {
        $old_code = TempCacheService::getInstance()->get('email-'.$email);
        if($old_code!=$code)
            throw new CommonException('验证码输入错误');
    }

    public function imageUrl($content)
    {
        $url = HOST;
        $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
        $content = preg_replace($pregRule, '<img src="' . $url . '${1}" style="max-width:100%">', $content);
        return $content;
    }


}