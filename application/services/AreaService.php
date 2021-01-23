<?php
/**
 * Created by PhpStorm.
 * User: KeenSting
 * Date: 2018/6/6
 * Time: 下午3:24
 * Name: 梁勋
 * Phone: 13126734215
 * QQ: 707719848
 * File Description: 地域级联 服务
 */

namespace app\services;


use think\Db;

class AreaService
{
    private static $instance;
    const CACHE_TAGS = 'area';
    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }
    /**
     * 获取省直辖市的列表
     * @param $r_code
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProvince()
    {
        $key = 'province';
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $query = Db::table('province')->field('code as p_code,name');
        $province = $query->select();
        app('cache')->tag(self::CACHE_TAGS)->set($key,$province,3600);
        return $province;
    }

    /**获取城市列表
     * @param $p_id int 省直辖市的编号
     * @return array
     */
    public function getCity($p_id)
    {
        $key = 'c-'.$p_id;
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $city = Db::table('city')
            ->field('code as c_code,name')
            ->where('provincecode',$p_id)
            ->select();
        app('cache')->tag(self::CACHE_TAGS)->set($key,$city,3600);
        return $city;
    }


    /**
     * 获取区域列表
     * @param $c_id int 城市的标号
     * @return array
     */
    public function getArea($c_id)
    {
        $key = 'a-'.$c_id;
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            return app('cache')->tag(self::CACHE_TAGS)->get($key);
        $area = DB::table('area')
            ->field('code as a_code,name')
            ->where('citycode',$c_id)
            ->select();
        app('cache')->tag(self::CACHE_TAGS)->set($key,$area,3600);
        return $area;
    }

    public function getProvinceByCode($p_id)
    {
        $province = Db::name('province')->field('name')->where('code',$p_id)->find();
        return $province;
    }

    public function getCityByCode($c_id)
    {
        $city = Db::name('city')->field('name')->where('code',$c_id)->find();
        return $city;
    }

    public function getAreaByCode($a_id)
    {
        $area = Db::name('area')->field('name')->where('code',$a_id)->find();
        return $area;
    }

    /**
     * 清空所有该部分的缓存
     */
    public function clearCache()
    {
        app('cache')->tag(self::CACHE_TAGS)->clear();
    }
}