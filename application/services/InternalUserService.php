<?php
/**
 * Description:
 * Created by PhpStorm.
 * Date: 2020/12/25 0010
 * Time: 13:37
 * by: xiaohe&18846923924
 */
namespace app\services;

use app\exception\CommonException;
use app\model\AdminUser;
use app\model\InternalUser as InternalUserModel;
use think\Db;
use think\facade\Session;
use think\helper\Hash;

class InternalUserService
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

    /*后台账号*/
    public function adminGetIuli($areacode,$name,$prov){
        $db_iu = New InternalUserModel();
        if(!empty($areacode)){
            $areacode = is_array($areacode)?array_column($areacode, 'areacode'):$areacode;
            $areaCode = is_array($areacode)?implode(",", $areacode):$areacode;
        }else{
            $username = Session::get('admin_user');
            $db_au = new AdminUser();
            $areaCode = $db_au ->where("name",$username)->value('province');
        }
        $query = $db_iu->field("user_id,username,name,avatar,mobile,checkinfo,idnum,pwdtext")
            ->where("checkinfo",">=",0);
        if(!empty($prov)){
            $query->where("province",$prov);
        }else{
            if(!empty($areaCode)){
                $query->where("province","in",$areaCode);
            }
        }
        if(!empty($name)){
            $query->where("name|nickname|idnum","like","%".$name."%");
        }

        return $query->order("user_id desc,posttime desc")->paginate(10);
    }

    public function getUerInfo($username){
        $db_iu = New InternalUserModel();
        return $db_iu->where(["username"=>$username])->find();
    }

    public function adminGetIuAdd($user_id,$username,$pwd,$avatar,$mobile,$nickname,$name,$idnum,$province,$checkinfo){
        $db_iu = New InternalUserModel();
        $data = [
            "username"=>$username,
            "pwd"=>Hash::make($pwd),
            "pwdtext"=>$pwd,
            "avatar"=>$avatar,
            "mobile"=>$mobile,
            "nickname"=>$nickname,
            "name"=>$name,
            "idnum"=>$idnum,
            "province"=>$province,
            "checkinfo"=>$checkinfo,
            "posttime"=> time()
        ];
        if($user_id){
            $db_iu->where(["user_id"=>$user_id])->update($data);
        }else{
            $db_iu->save($data);
        }
    }

    public function getUerInfoByID($user_id){
        $db_iu = New InternalUserModel();
        return $db_iu->where(["user_id"=>$user_id])->find();
    }

    public function statusSet($user_id, $status){
        $db_g = New InternalUserModel();
        if ($status) {
            $userinfo = $db_g->field('user_id')->find($user_id);
            if (empty($userinfo))
                throw new CommonException('业务员记录不存在');
        }
        $db_g->where('user_id', $user_id)->update(['checkinfo' => $status]);
        unset($db_g);
    }

    public function InternalDel($user_id){
        $db_iu = new InternalUserModel();
        $info = Db::name("patient")->where(["user_id"=>$user_id])->find();
        if (!empty($info)){
            throw new CommonException('该业务员账号下尚有患者未转移');
        }
        $hinfo = Db::name("hopsital")->where(["user_id"=>$user_id])->find();
        if (!empty($hinfo)){
            throw new CommonException('该业务员账号有对应医院未安排');
        }
        $db_iu->where('user_id', $user_id)->update(['checkinfo'=>-2]);
        unset($db_g);
    }

}