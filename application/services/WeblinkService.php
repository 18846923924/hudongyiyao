<?php
namespace app\services;
use app\exception\CommonException;
use app\model\Weblink as  WeblinkModel;
use think\Db;

class WeblinkService
{
    private static $instance;

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

    public function webWeblinkListAll(){
        $db_wl = new WeblinkModel();
        return $db_wl->where(["checkinfo"=>1])->field("id,title")->order("sort desc,id desc")->select();
    }

    /*后台服务*/
    public function getWeblinkList($name){
        $db_wl = new WeblinkModel();
        $query = $db_wl->field("id,title,sort,checkinfo,picurl,linkurl");
        if($name){
            $query->where("title","like","%$name%");
        }
        $data = $query->paginate(10,false,['query'=>request()->param()]);
        $data->appends('name', $name);
        return $data;
    }

    public function addWeblink($id, $title, $sort, $checkInfo, $picurl, $linkurl){
        $data = [
            'title' => $title,
            'sort' => $sort,
            'checkinfo' => $checkInfo,
            'picurl' => $picurl,
            'linkurl' => $linkurl,
            'posttime' => time()
        ];
        $db_wl = new WeblinkModel();
        if($id){
            $db_wl->where(["id"=>$id])->update($data);
        }else{
            $db_wl->save($data);
        }
        unset($db_wl);
    }

    public function WeblinkStatus($id,$status){
        $db_wl = new WeblinkModel();
        if ($status) {
            $personnel = $db_wl->field('id')->find($id);
            if (empty($personnel))
                throw new CommonException('该产品分类记录不存在');
        }
        $db_wl->where('id', $id)->update(['checkinfo' => $status]);
        unset($db_wl);
    }

    public function WeblinkSort($id,$sort){
        $db_wl = new WeblinkModel();
        $db_wl->where('id', $id)->update(['sort' => $sort]);
        unset($db_wl);
    }

    public function WeblinkDel($id){
        $db_wl = new WeblinkModel();
        $db_wl->where('id', $id)->delete();
        unset($db_wl);
    }

    // 前后台公用分类获取
    public function Weblink($is_admin)
    {
        $db_wl = new WeblinkModel();
        $query = $db_wl->order('sort desc,id desc');
        if (!$is_admin)
            $query->field('id,title');
        else
            $query->field('id,title');
        $cats = $query->select();
        unset($db_wl);
        return $cats;
    }
}