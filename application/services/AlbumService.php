<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2019/12/5 0005
 * Time: 16:00
 */

namespace app\services;


use app\exception\CommonException;
use app\model\Album;
use app\model\AlbumPic;
use think\Db;
use think\db\Expression;

class AlbumService
{
    private static $instance;

    const CACHE_TAGS = 'album';

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

    public function addAlbum($name)
    {
        $db_a = new Album();
        $data = [
            'name' => $name
        ];
        $a_id = $db_a->insertGetId($data);
        unset($db_a);
        return $a_id;
    }

    public function albumName($a_id,$name)
    {
        if(empty($name))
            return ;
        $db_a = new Album();
        $db_a->where('a_id', $a_id)->update(['name'=>$name]);
        unset($db_a);
    }

    public function delAlbum($a_id)
    {
        $db_a = new Album();
        $album = $db_a->field('pic_num')->find($a_id);
        if($album['pic_num'])
            throw new CommonException('相册存在图片，不可删除');
        $db_a->where('a_id',$a_id)->delete();
        unset($db_a);
    }

    public function albumList()
    {
        $db_a = new Album();
        $albums = $db_a->select();
        unset($db_a);
        return $albums;
    }

    public function addAlbumPic($a_id,$pic,$pic_name,$width,$height,$size)
    {
        $db_ap = new AlbumPic();
        $ap_id = $db_ap->insertGetId([
            'a_id'=>$a_id,
            'pic'=>$pic,
            'pic_name'=>$pic_name,
            'width'=>$width,
            'height'=>$height,
            'pic_time'=>date('YmdHi'),
            'size'=>$size
        ]);
        $db_a = new Album();
        $db_a->where('a_id',$a_id)->setInc('pic_num');
        unset($db_ap);
        return $ap_id;
    }

    public function delPic($a_id,$ap_id,$pic)
    {
        $db_ap = new AlbumPic();
        $db_ap->where('ap_id',$ap_id)->delete();
        $db_a = new Album();
        $db_a->where('a_id',$a_id)->setDec('pic_num');
        unset($db_ap,$db_a);
        FileService::getInstance()->delPic($pic);
    }

    public function delPicBatch($ap_id,$a_id)
    {
        Db::transaction(function ()use ($ap_id,$a_id){
            if(empty($ap_id))
                throw new CommonException('请选择要删除的图片');
            $db_a = new Album();
            $db_a->where('a_id',$a_id)->update(['pic_num'=>Db::raw('pic_num-'.count($ap_id))]);
            $db_ap = new AlbumPic();
            foreach ($ap_id as $item) {
                $pic=  $db_ap->field('pic')->find($item);
                FileService::getInstance()->delPic($pic['pic']);
            }
            $db_ap->where('ap_id','in',$ap_id)->delete();
            unset($db_ap,$db_a);
        });
    }

    public function albumPic($a_id)
    {
        $db_ap = new AlbumPic();
        $pics = $db_ap->where('a_id',$a_id)->field('ap_id,pic,pic_name,width,height,size')->order('pic_time desc,pic_name')->paginate(10);
        unset($db_ap);
        return $pics;
    }

    public function albumPicPage($a_id,$page)
    {
        $db_ap = new AlbumPic();
        $pics = $db_ap->where('a_id',$a_id)
            ->field('ap_id,pic,pic_name,width,height')
            ->order('pic_time desc,pic_name')
            ->page($page)->limit(10)->select();
        unset($db_ap);
        return $pics;
    }

    public function albumPicCount($a_id)
    {
        $db_ap = new AlbumPic();
        $count = $db_ap->where('a_id',$a_id)->count('ap_id');
        unset($db_ap);
        return $count;
    }

    public function picSort($pic_ids)
    {
        if(empty($pic_ids))
            return [];
        $db_ap = new AlbumPic();
        $exp = new Expression('field(ap_id,' . $pic_ids . ')');
        $pic_arr = $db_ap->where('ap_id', 'in', $pic_ids)->order($exp)->select();
        unset($db_ap);
        return $pic_arr;
    }



}