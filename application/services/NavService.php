<?php


namespace app\services;


use app\model\AdminNav;
use app\model\AdminNavPermission;
use app\model\AdminNavSub;
use app\model\AdminNavSubPermission;
use think\Db;

class NavService
{
    private static $instance;

    const CACHE_TAGS = 'nav';

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * 一级导航权限
     * @param $ag_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function groupNavs($ag_id)
    {
        $navs = Db::table('admin_nav_permission')
            ->alias('anp')
            ->leftJoin('admin_nav an','anp.an_id=an.an_id')
            ->field('an.an_id,an.name,an.icon')
            ->where('anp.ag_id',$ag_id)
            ->order('sort')
            ->select();
        return $navs;
    }

    /**
     * 二级导航权限
     * @param $ag_id
     * @param $an_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function groupSubs($ag_id,$an_id)
    {
        $subs = Db::table('admin_nav_sub_permission')
            ->alias('ansp')
            ->leftJoin('admin_nav_sub ans','ansp.ans_id=ans.ans_id')
            ->field('ans.ans_id,ans.name,ans.url,ans.icon')
            ->where(['ansp.ag_id'=>$ag_id,'ans.an_id'=>$an_id])
            ->order('sort')
            ->select();
        return $subs;
    }

    /**
     * 导航权限数据包装并缓存
     * @param $ag_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function wrapNavSubData($ag_id)
    {
        $key = 'nsd-'.$ag_id;
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $navs = $this->groupNavs($ag_id);
        foreach ($navs as &$nav)
            $nav['subs'] = $this->groupSubs($ag_id,$nav['an_id']);
        app('cache')->tag(self::CACHE_TAGS)->set($key,$navs,3600);
        return $navs;
    }

    /**
     * 清除用户组导航缓存
     * @param $ag_id
     */
    public function flushNav($ag_id)
    {
        $key = 'nsd-'.$ag_id;
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
    }

    /**
     * 检验用户组是否有一级导航权限
     * @param $an_id
     * @param $ag_id
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkNavPermission($an_id,$ag_id)
    {
        $db_anp = new AdminNavPermission();
        $data = $db_anp->where(['an_id'=>$an_id,'ag_id'=>$ag_id])->field('anp_id')->find();
        if(empty($data))
            return 0;
        return 1;
    }

    /**
     * 检验用户组是否有二级导航权限
     * @param $ans_id
     * @param $ag_id
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkSubPermission($ans_id,$ag_id)
    {
        $db_ansp = new AdminNavSubPermission();
        $data = $db_ansp->where(['ans_id'=>$ans_id,'ag_id'=>$ag_id])->field('ansp_id')->find();
        if(empty($data))
            return 0;
        return 1;
    }

    /**
     * 所有一级导航
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function navs()
    {
        $key = 'navs';
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $db_an = new AdminNav();
        $navs = $db_an->order('sort')->select();
        if(!empty($navs))
            app('cache')->tag(self::CACHE_TAGS)->set($key,$navs,3600);
        return $navs;
    }

    /**
     * 所有二级导航
     * @param $an_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function subs($an_id)
    {
        $key = 'subs-'.$an_id;
//        app('cache')->tag(self::CACHE_TAGS)->rm($key);
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $db_ans = new AdminNavSub();
        $subs = $db_ans->order('sort')->where('an_id',$an_id)->select();
        if(!empty($subs))
            app('cache')->tag(self::CACHE_TAGS)->set($key,$subs,3600);
        return $subs;
    }

    /**
     * 清理一级导航缓存，添加删除缓存时使用
     */
    public function flushNavs()
    {
        $key = 'navs';
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
    }

    /**
     * 清理二级导航缓存
     * @param $an_id
     */
    public function flushSubs($an_id)
    {
        $key = 'subs-'.$an_id;
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
    }

    /**
     * 修改权限时获取所有导航列表，标记选中项
     * @param $ag_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function navsUnderGroup($ag_id)
    {
        $navs = $this->navs();
        foreach ($navs as &$nav) {
            $nav['is_checked'] = $this->checkNavPermission($nav['an_id'],$ag_id);
            $subs = $this->subs($nav['an_id']);
            foreach ($subs as &$sub) {
                $sub['is_checked'] = $this->checkSubPermission($sub['ans_id'],$ag_id);
            }
            $nav['subs'] = $subs;
        }
        return $navs;
    }



























}