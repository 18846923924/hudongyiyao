<?php


namespace app\services;


use app\exception\CommonException;
use app\model\AdminGroup;
use app\model\AdminUser;
use think\Db;
use think\facade\Session;
use think\helper\Hash;

class AdminUserService
{
    private static $instance;

    const CACHE_TAGS = 'admin_user';

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }

    public function login($name, $pwd)
    {
        $db_au = new AdminUser();
        $admin_user = $db_au->where('name',$name)->field('pwd,status,au_id,ag_id,name,nickname')->find();
        if(empty($admin_user))
            throw new CommonException('账号或密码错误');
        if(!$admin_user['status'])
            throw new CommonException('账号已冻结');
        if(!Hash::check($pwd,$admin_user['pwd']))
            throw new CommonException('账号或密码错误');
        $db_au->update([
            'au_id'=>$admin_user['au_id'],
            'last_login_at'=>time()
        ]);
        $admin_user['name'] = $name;
        $admin_user['pwd'] = '';
        session('admin_user',$admin_user);
    }

    public function getAdminLoginStatus()
    {
        if(Session::has('admin_user'))
            return Session::get('admin_user');
        return false;
    }

    public function clearUserSession()
    {
        session('admin_user',null);
    }

    public function adminUserList()
    {
        $staffs = Db::name('admin_user au')
            ->leftJoin('admin_group ag','au.ag_id=ag.ag_id')
            ->field('ag.name group_name,au.name,au.ag_id,au.province,au.status,au.au_id,au.nickname')
            ->order('ag.ag_id')
            ->paginate(10);
        return $staffs;
    }

    /**
     * 修改密码
     * @param $au_id
     * @param $new_pwd
     * @param $old_pwd
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editPwd($au_id,$new_pwd,$old_pwd)
    {
        $db_au = new AdminUser();
        $admin = $db_au->field('pwd')->find($au_id);
        if(empty($admin))
            throw new CommonException('用户不存在');
        if(!Hash::check($old_pwd,$admin['pwd']))
            throw new CommonException('原密码输入错误');
        $db_au->update([
            'au_id'=>$au_id,
            'pwd'=>Hash::make($new_pwd)
        ]);
    }

    public function editNickname($nickname,$admin_user)
    {
        $db_au = new AdminUser();
        $db_au->update([
            'au_id'=>$admin_user['au_id'],
            'nickname'=>$nickname
        ]);
        unset($db_au);
        $admin_user['nickname'] = $nickname;
        session('admin_user',$admin_user);
    }

    /**
     * 分组列表
     * @param array $neq
     * @return array|mixed|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function adminGroup($neq = array())
    {
        $db_ag = new AdminGroup();
        if(!empty($neq))
            $group = $db_ag->where('ag_id','not in',$neq)->select();
        else
            $group = $db_ag->select();
        return $group;
    }

    public function delGroup($ag_id)
    {
        $db_au = new AdminUser();
        $au = $db_au->where('ag_id',$ag_id)->field('au_id')->find();
        unset($db_au);
        if(!empty($au))
            throw new CommonException('分组下存在用户，不可删除');
        $db_ag = new AdminGroup();
        $db_ag->where('ag_id',$ag_id)->delete();
        unset($db_ag);
    }

    /**
     * 添加分组
     * @param $name
     * @param $ag_id
     */
    public function addGroup($name,$ag_id)
    {
        $db_ag = new AdminGroup();
        if($ag_id)
            $db_ag->update([
                'ag_id'=>$ag_id,
                'name'=>$name
            ]);
        else
            $db_ag->insert(['name'=>$name]);
    }

    /**
     * 通过分组获取用户
     * @param $ag_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function adminGroupUser($ag_id)
    {
        $db_au = new AdminUser();
        $condition = ['ag_id'=>$ag_id];
        $users = $db_au->where($condition)->field('au_id,name')->select();
        return $users;
    }

    /**
     * 修改用户状态
     * @param $au_id
     * @param $status
     */
    public function changeAdminStatus($au_id,$status)
    {
        $db_au = new AdminUser();
        $db_au->update([
            'au_id'=>$au_id,
            'status'=>$status
        ]);
    }

    /**
     * 分组信息
     * @param $ag_id
     * @return mixed
     */
    public function groupInfo($ag_id)
    {
        $db_ag = new AdminGroup();
        $group = $db_ag->find($ag_id);
        return $group;
    }

    /**
     * 添加管理员
     * @param $ag_id
     * @param $name
     * @param $pwd
     * @param $nickname
     * @param $s_id
     * @return int|string
     * @throws CommonException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function adminUserAdd($ag_id,$name,$pwd,$nickname,$prov)
    {
        $db_au = new AdminUser();
        $au = $db_au->where('name',$name)->find();
        if(!empty($au))
            throw new CommonException('后台账号已存在');
        $au_id = $db_au->insertGetId([
            'ag_id'=>$ag_id,
            'name'=>$name,
            'province'=>$prov,
            'pwd'=>Hash::make($pwd),
            'status'=>1,
            'nickname'=>$nickname,
        ]);
        return $au_id;
    }

    public function adminAreaPermission($data){
      return Db::name("admin_area_permission")->insert($data);
    }

    public function initPwd($au_id)
    {
        $db_au = new AdminUser();
        $db_au->update([
            'au_id'=>$au_id,
            'pwd'=>Hash::make('e10adc3949ba59abbe56e057f20f883e')
        ]);
        unset($db_au);
    }

    public function updateAdminUser($au_id,$update)
    {
        $db_au = new AdminUser();
        $db_au->where('au_id',$au_id)->update($update);
        unset($db_au);
    }

    public function checkAdminUserExist($name)
    {
        $db_au = new AdminUser();
        $au = $db_au->where('name',$name)->find();
        if(!empty($au))
            throw new CommonException('改手机号已注册，请更换其他手机号');
    }

    public function checkAdminUserExist1($name)
    {
        $db_au = new AdminUser();
        $au = $db_au->where('name',$name)->where('status',1)->find();
        if(!empty($au))
            throw new CommonException('你已有店铺入驻');
    }

    public function getAdminUserById($au_id)
    {
        $db_au = new AdminUser();
        $admin_user = $db_au->field('name,nickname,ag_id')->find($au_id);
        unset($db_au);
        return $admin_user;
    }

    public function getAdminUserID(){
        if (!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
        $db_au = new AdminUser();
        $username = Session::get('admin_user');
        $au_id = $db_au->where("name",$username['name'])->value('au_id');
        return $au_id;
    }

    public function getAdminCode(){
        $db_au = new AdminUser();
        if(Session::has('admin_user')){
            $username = Session::get('admin_user');
            $adminInfo = $db_au->where("name",$username['name'])->find();
            if($adminInfo['ag_id'] != 1){
                $areaarr = Db::name("admin_area_permission")
                    ->where("au_id",$adminInfo['au_id'])
                    ->field("areacode")
                    ->select();
            }else{
                $areaarr = Db::name("province")->select();
            }

            return $areaarr;
        }else{
            return false;
        }

    }


}