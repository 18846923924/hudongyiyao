<?php
/**
 * Description:设置缓存项
 * Created by PhpStorm.
 *
 * Date: 2019/4/12
 * Time: 15:42
 */

namespace app\services;



class TempCacheService
{
    private  static  $instance;
    const CACHE_TAGS = 'temp';

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self;
        return self::$instance;
    }


    /**设置缓存项
     * @param $data mixed 所有数据格式
     * @param int $time 缓存时间 单位 秒
     * @param null $key 存储的key 没有则自动生成
     * @return null|string
     */
    public function set($data,$time=300,$key=null)
    {
        if(empty($key))
            $key = str_random(24);
        app('cache')->tag(self::CACHE_TAGS)->set($key,$data,$time);
        return $key;
    }

    /**获取缓存项
     * @param $key string 存储key
     * @param bool $clear 取出数据后是否释放
     * @return bool|mixed
     */
    public function get($key,$clear=false)
    {
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
        {
            if($clear)
                return app('cache')->tag(self::CACHE_TAGS)->pull($key);
            else
                return app('cache')->tag(self::CACHE_TAGS)->get($key);
        }
        return false;
    }

    /**释放缓存
     * @param $key
     */
    public function delete($key)
    {
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
    }
}