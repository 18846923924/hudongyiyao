<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/6/24 0024
 * Time: 08:52
 */

namespace app\services;


use app\exception\CommonException;
use app\model\Article;
use app\model\Banner;
use app\model\Goods;
use app\services\UserService;
use think\Db;

class BannerService
{
    private static $instance;

    const CACHE_TAGS = 'banner';

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }


    public function indexBanner($type,$num){
        $db_b = New Banner();
        $data = $db_b->where(["type"=>$type,"is_show"=>1])
            ->field("title,pic,url,retitle")
            ->order('sort desc,b_id desc')
            ->page(1, $num)->select();
        return $data;
    }

    public function getOneBanner($type){
        $db_b = New Banner();
        $data = $db_b->where(["type"=>$type,"is_show"=>1])
            ->field("title,pic,url,retitle")
            ->order('sort desc,b_id desc')
            ->find();
        return $data;
    }

    /*-后台-*/
    public function bannerList($is_admin)
    {
        $key = 'bl-'.$is_admin;
        if(app('cache')->tag(self::CACHE_TAGS)->has($key))
            app('cache')->tag(self::CACHE_TAGS)->get($key);
        $db_b = new Banner();
        if($is_admin)
            $banners = $db_b->order('sort')->paginate(10);
        else
            $banners = $db_b->order('sort')->where('is_show',1)->field('b_id,pic,url,type,is_show,sort')
                ->paginate(10);;
        if(!empty($banners))
            app('cache')->tag(self::CACHE_TAGS)->set($key,$banners,3600);
        return $banners;
    }

    public function flush()
    {
        $key = 'bl-1';
        $key1 = 'bl-0';
        app('cache')->tag(self::CACHE_TAGS)->rm($key);
        app('cache')->tag(self::CACHE_TAGS)->rm($key1);
    }

    public function addBanner($b_id,$title,$pic,$sort,$url,$type,$retitle)
    {

        $data = [
            'title'=>$title,
            'pic'=>$pic,
            'sort'=>$sort,
            'url'=>$url,
            'type'=>$type,
            'retitle'=>$retitle
        ];
        $db_b = new Banner();
        if($b_id)
            $db_b->where('b_id',$b_id)->update($data);
        else
            $db_b->save($data);
        unset($db_b);
        $this->flush();
    }

    public function delBanner($b_id,$pic)
    {
        $db_b = new Banner();
        $db_b->where('b_id',$b_id)->delete();
        FileService::getInstance()->delPic($pic);
        unset($db_b);
        $this->flush();
    }

    public function bannerSort($b_id,$sort)
    {
        $db_b = new Banner();
        $db_b->update([
            'b_id'=>$b_id,
            'sort'=>$sort
        ]);
        unset($db_b);
        $this->flush();
    }

    public function bannerDetail($b_id)
    {
        $db_b = new Banner();
        $banner = $db_b->find($b_id);
        unset($db_b);
        return $banner;
    }

    public function bannerShow($b_id,$is_show)
    {
        $db_b = new Banner();
        $db_b->update([
            'b_id'=>$b_id,
            'is_show'=>$is_show
        ]);
        unset($db_b);
        $this->flush();
    }


}