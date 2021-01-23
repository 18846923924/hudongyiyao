<?php
namespace app\services;
use app\exception\CommonException;
use app\model\Config as  ConfigModel;
use think\Db;

class ConfigService
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

    public function webConfigListAll(){
        $db_cf = new ConfigModel();
        return $db_cf->where(["vargroup"=>0])->field("varname,varinfo,varvalue")->order("orderid asc")->select();
    }

   

}