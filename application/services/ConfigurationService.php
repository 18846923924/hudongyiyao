<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 * Date: 2019/4/15
 * Time: 13:57
 */

namespace app\services;


use app\exception\CommonException;
use app\model\Configuration;

class ConfigurationService
{
    private static $instance;

    const CACHE_TAGS = 'conf';

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * 释放配置项
     * @param null $key
     */
    public function clear($key=null)
    {
        if($key)
            app('cache')->tag(self::CACHE_TAGS)->rm($key);
        else
            app('cache')->tag(self::CACHE_TAGS)->flush();
    }

    /**
     * 获取配置项
     * @param $key
     * @param $replace_line_break
     * @return mixed
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getConf($key,$replace_line_break = false)
    {
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
        {
            $val = app('cache')->tag(self::CACHE_TAGS)->get($key);
            if($replace_line_break)
                $val = preg_replace('/\n/','<br>',$val);
        }
        else{
            $db_c = new Configuration();
            $item = $db_c->where('key',$key)->field('val')->find();
            unset($db_c);
            if(empty($item))
                throw new CommonException('key值不存在');
            $val = $item['val'];
            if($replace_line_break)
                $val = preg_replace('/\n/','<br>',$val);
            if(!empty($val))
                app('cache')->tag(self::CACHE_TAGS)->set($key,$val,3600);
        }
        return $val;
    }

    /**
     * 更新配置项
     * @param $key
     * @param $val
     * @throws SystemErr
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function updateConf($key,$val)
    {
        if(empty($key))
            throw new CommonException('key must not be null');
        $db_c = new Configuration();
        $db_c->where('key',$key)->update(['val'=>$val]);
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
        $this->flushConf($key);
    }

    public function confDel($key)
    {
        $db_c = new Configuration();
        $db_c->where('key',$key)->delete();
        unset($db_c);
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
        $this->flushConf($key);
    }

    public function addConf($key,$val,$remark)
    {
        $db_c = new Configuration();
        $db_c->save([
            'key'=>$key,
            'val'=>$val,
            'remark'=>$remark
        ]);
        $this->flushConf($key);
        unset($db_c);
    }

    public function getConfLike($key)
    {
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $db_c = new Configuration();
        $conf = $db_c->where('key','like',$key.'%')->select();
        unset($db_c);
        app('cache')->tag(self::CACHE_TAGS)->set($key,$conf,3600);
        return $conf;
    }

    public function flushConf($key)
    {
        $key = explode('_',$key)[0].'_';
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
    }

}