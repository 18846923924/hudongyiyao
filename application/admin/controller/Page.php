<?php


namespace app\admin\controller;



use app\services\AdminUserService;
use app\services\AlbumService;
use app\services\AreaService;
use app\services\BannerService;
use app\services\ConfigurationService;
use app\services\NavService;
use app\services\SystemService;
use app\services\UserService;
use think\App;
use think\Controller;
use think\Db;

class Page extends Controller
{
    public function loginPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if ($admin_user)
            return $this->redirect('/cms');
        return view('admin/login');
    }

    /**
     * 外页
     * @return \think\response\View|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function commonPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirect('/cms/login');
        $this->assign('name', $admin_user['nickname']);
        $navs = NavService::getInstance()->wrapNavSubData($admin_user['ag_id']);
        $this->assign('navs', $navs);
        return view('admin/common');
    }

    /**
     * iframe页面父级页面跳转登录
     * @return string
     */
    public function redirectLogin()
    {
        return "<script language='javascript'>window.top.location.href='/cms/login'</script>";
    }

    public function pwdEditPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        return view('admin/system/pwd_edit',[
        ]);
    }

    /**
     * 首页
     * @return \think\response\View
     */
    public function indexPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        return view('admin/index',[
            'internal_user_count'=>Db::name('internal_user')->count('user_id'),
            'patient_count'=>Db::name('patient')->count('p_id'),
            // 'user_count'=>Db::name('user')->count('user_id'),
            'sys_info'=>[
                'os'=>PHP_OS,
                'web_server'=>$_SERVER['SERVER_SOFTWARE'],
                'phpv'=>phpversion(),
                'ip'=>GetHostByName($_SERVER['SERVER_NAME']),
                'fileupload'=>@ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown',
                'max_ex_time'=>@ini_get("max_execution_time").'s',
                'domain'=>$_SERVER['HTTP_HOST'],
                'memory_limit'=>ini_get('memory_limit'),
                'think_v'=>App::VERSION,
            ]
        ]);
    }

    public function nonePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        return view('admin/none');
    }

    public function userPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $phone = $this->request->get('phone','');
        $users = UserService::getInstance()->userList($phone);

        $coupons = CouponService::getInstance()->couponSelect();
        return view('admin/user/user',[
            'phone'=>$phone,
            'coupons'=>$coupons,
            'users'=>$users
        ]);
    }

    public function payLogPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $phone = $this->request->get('phone','');
        $logs = UserService::getInstance()->userPayLog($phone);
        return view('admin/user/user_log',[
            'phone'=>$phone,
            'logs'=>$logs
        ]);
    }

    public function userDetailPage($user_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/user'))
            return redirect('/cms/none');
        $user = UserService::getInstance()->getUserInfoById($user_id);
        $account = UserService::getInstance()->userAccount($user_id);
//        if($user['has_face']) {
//            $face_desc = FaceService::getInstance()->faceInfo($user_id);
//            $face = FaceService::getInstance()->faceResult($user_id);
//            $skin = FaceService::getInstance()->skinResult($user_id);
//        }else{
//            $face_desc = [];
//            $face = [];
//            $skin = [];
//        }
        return view('admin/user/user_detail',[
            'user'=>$user,
            'account'=>$account,
//            'face_desc'=>$face_desc,
//            'face'=>$face,
//            'skin'=>$skin,
        ]);
    }

    public function userAddPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/user'))
            return redirect('/cms/none');
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/user/user_add',[
            'albums'=>$albums
        ]);
    }

    public function userEditPage($user_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/user'))
            return redirect('/cms/none');
        $albums = AlbumService::getInstance()->albumList();
        $user = UserService::getInstance()->getUserInfoById($user_id);
        return view('admin/user/user_edit',[
            'albums'=>$albums,
            'user'=>$user
        ]);
    }

    public function messageListPage($user_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/user'))
            return redirect('/cms/none');
        $mess = UserService::getInstance()->messageListAdmin($user_id);
        return view('admin/user/user_mess',[
            'mess'=>$mess,
            'user_id'=>$user_id
        ]);
    }

    public function archivesPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $phone = $this->request->get('phone','');
        $archives = UserService::getInstance()->userArchivesList($phone);
        return view('admin/user/user_archives',[
            'phone'=>$phone,
            'archives'=>$archives
        ]);
    }

    public function groupPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $groups = AdminUserService::getInstance()->adminGroup();
        return view('admin/system/group',[
            'groups'=>$groups
        ]);
    }
    
    public function permissionPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $groups = SystemService::getInstance()->groupPermission();
        $this->assign('groups', $groups);
        return view('admin/system/permission');
    }

    public function staffPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $name = $this->request->get('name', '');
        $province = AreaService::getInstance()->getProvince();
        $staffs = AdminUserService::getInstance()->adminUserList($name);
        $groups = AdminUserService::getInstance()->adminGroup();//[4,5,6] 医院相关角色
        return view('admin/system/staff', [
            'staffs' => $staffs,
            'groups' => $groups,
            'name' => $name,
            'province' => $province
        ]);
    }

    public function staffAddPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/staff'))
            return redirect('/cms/none');
        $groups = AdminUserService::getInstance()->adminGroup();
        $shops = ShopService::getInstance()->shopListSearch();
        return view('admin/system/staff_add', [
            'groups' => $groups,
            'shops' => $shops,
        ]);
    }

    public function confPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if (!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $vip_price = ConfigurationService::getInstance()->getConf('vip_price');
        $lottery_credits = ConfigurationService::getInstance()->getConf('lottery_credits');
        $vip_product_id = ConfigurationService::getInstance()->getConf('vip_product_id');
        $point_mall_open = ConfigurationService::getInstance()->getConf('point_mall_open');
        $lottery_rule = ConfigurationService::getInstance()->getConf('lottery_rule');
        $bgs = ConfigurationService::getInstance()->getConfLike('bg_');
        $awards = ConfigurationService::getInstance()->getConfLike('award_');
        $services = ConfigurationService::getInstance()->getConfLike('service_');
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/system/conf', [
            'vip_price'=>$vip_price,
            'lottery_credits'=>$lottery_credits,
            'vip_product_id'=>$vip_product_id,
            'bgs'=>$bgs,
            'awards'=>$awards,
            'albums'=>$albums,
            'services'=>$services,
            'point_mall_open'=>$point_mall_open,
            'lottery_rule'=>$lottery_rule,
        ]);
    }

    public function albumListPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/album/album',[
            'albums'=>$albums
        ]);
    }

    public function albumPicPage($a_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        $name = $this->request->get('name');
        $pics = AlbumService::getInstance()->albumPic($a_id);
        return view('admin/album/album_pic',[
            'pics'=>$pics,
            'name'=>$name,
            'a_id'=>$a_id
        ]);
    }

    public function goodsParamPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $params = GoodsService::getInstance()->wrapGoodsParamsData();
        return view('admin/goods/params',[
            'params'=>$params
        ]);
    }

    public function goodsAddPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $params = GoodsService::getInstance()->wrapGoodsParamsData();
        $cats = GoodsService::getInstance()->goodsCat(1);
        $albums = AlbumService::getInstance()->albumList();
        $parts = GoodsService::getInstance()->goodsPartData();
        return view('admin/goods/goods_add',[
            'params'=>$params,
            'cats'=>$cats,
            'parts'=>$parts,
            'albums'=>$albums
        ]);
    }

    public function goodsEditPage($g_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/goods'))
            return redirect('/cms/none');
        $params = GoodsService::getInstance()->getGoodsDefaultParams($g_id);
        $cats = GoodsService::getInstance()->goodsCat(1);
        $albums = AlbumService::getInstance()->albumList();
        list($goods, $pic_banner, $pic_detail) = GoodsService::getInstance()->goodsInfo($g_id);
        $goods_data = GoodsService::getInstance()->goodsData($g_id);
        $parts = GoodsService::getInstance()->goodsPartData();
        return view('admin/goods/goods_edit',[
            'params'=>$params,
            'cats'=>$cats,
            'albums'=>$albums,
            'goods'=>$goods,
            'parts'=>$parts,
            'pic_banner'=>$pic_banner,
            'pic_detail'=>$pic_detail,
            'goods_data'=>$goods_data,
        ]);
    }

    public function goodsDetailPage($g_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/goods'))
            return redirect('/cms/none');
        list($goods, $pic_banner, $pic_detail) = GoodsService::getInstance()->goodsInfo($g_id);
        $prices = GoodsService::getInstance()->goodsPriceList($g_id);
        $properties = GoodsService::getInstance()->wrapGoodsProperties($g_id);
        return view('admin/goods/goods_detail',[
            'goods'=>$goods,
            'pic_banner'=>$pic_banner,
            'pic_detail'=>$pic_detail,
            'prices'=>$prices,
            'properties'=>$properties,
        ]);
    }

    public function goodsCatPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $cats = GoodsService::getInstance()->goodsCat(1);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/goods/goods_cat',[
            'cats'=>$cats,
            'albums'=>$albums,
        ]);
    }

    public function goodsPriceEditPage($g_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        $has_price = GoodsService::getInstance()->getGoodsHasPrice($g_id);
        $goods = GoodsService::getInstance()->getGoodsInfo($g_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/goods/goods_price_edit',[
            'g_id'=>$g_id,
            'goods'=>$goods,
            'albums'=>$albums,
            'has_price'=>$has_price
        ]);
    }

    public function goodsPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $goods_name = $this->request->get('goods_name','');
        $gc_id = $this->request->get('gc_id','');
        $goods = GoodsService::getInstance()->goodsList($goods_name,$gc_id);
        $cats = GoodsService::getInstance()->goodsCat(1);
        return view('admin/goods/goods',[
            'cats'=>$cats,
            'goods'=>$goods,
            'goods_name'=>$goods_name,
            'gc_id'=>$gc_id,
        ]);
    }

    public function goodsPartPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $parts = GoodsService::getInstance()->goodsPartList();
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/goods/goods_part',[
            'parts'=>$parts,
            'albums'=>$albums,
        ]);
    }

    public function goodsPartProjectPage($gp_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/goods/part'))
            return redirect('/cms/none');
        $projects = GoodsService::getInstance()->goodsPartProject($gp_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/goods/goods_part_project',[
            'projects'=>$projects,
            'gp_id'=>$gp_id,
            'albums'=>$albums,
        ]);
    }

    public function shopPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $shop_name = $this->request->get('shop_name','');
        $shops = ShopService::getInstance()->shopList($shop_name);
        return view('admin/shop/shop',[
            'shops'=>$shops,
            'shop_name'=>$shop_name
        ]);
    }

    public function shopAddPage($s_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/shop'))
            return redirect('/cms/none');
        $shop = ShopService::getInstance()->shopDetail($s_id);
        $province = AreaService::getInstance()->getProvince();
        if($s_id) {
            $city = AreaService::getInstance()->getCity($shop['p_code']);
            $area = AreaService::getInstance()->getArea($shop['c_code']);
            $pic_banner = AlbumService::getInstance()->picSort($shop['pic_banner']);
        }else{
            $city = [];
            $area = [];
            $pic_banner = [];
        }
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/shop/shop_add',[
            'shop'=>$shop,
            'province'=>$province,
            'city'=>$city,
            'area'=>$area,
            'pic_banner'=>$pic_banner,
            'albums'=>$albums,
        ]);
    }

    public function doctorPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $name = $this->request->get('name','');
        $s_id = $this->request->get('s_id','');
        $doctors = ShopService::getInstance()->doctorList($name,$s_id);
        $shops = ShopService::getInstance()->shopListSearch();
        return view('admin/shop/doctor',[
            'doctors'=>$doctors,
            'shops'=>$shops,
            'name'=>$name,
            's_id'=>$s_id
        ]);
    }

    public function doctorAddPage($sd_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/doctor'))
            return redirect('/cms/none');
        $doctor = ShopService::getInstance()->doctorDetail($sd_id);
        $shops = ShopService::getInstance()->shopListSearch();
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/shop/doctor_add',[
            'doctor'=>$doctor,
            'shops'=>$shops,
            'albums'=>$albums,
        ]);
    }

    public function schedulePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $s_id = $this->request->get('s_id','');
        $schedule_at = $this->request->get('schedule_at','');
        $schedules = ShopService::getInstance()->doctorSchedule($s_id,$schedule_at);
        $shops = ShopService::getInstance()->shopListSearch();
        return view('admin/shop/schedule',[
            'schedules'=>$schedules,
            'shops'=>$shops,
            's_id'=>$s_id,
            'schedule_at'=>$schedule_at,
        ]);
    }

    public function scheduleAddPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/schedule'))
            return redirect('/cms/none');
        $shops = ShopService::getInstance()->shopListSearch();
        return view('admin/shop/schedule_add',[
            'shops'=>$shops,
        ]);
    }

    public function scheduleEditPage($ss_id,$s_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/schedule'))
            return redirect('/cms/none');
        $shop = ShopService::getInstance()->shopDetail($s_id);
        $schedule = ShopService::getInstance()->scheduleDetail($ss_id);
        $doctors = ShopService::getInstance()->doctorSearchInShop($s_id);
        $check = ShopService::getInstance()->scheduleDoctorChecked($ss_id);
        $sd_ids = array_keys($check);
        foreach ($doctors as &$doctor) {
            if(in_array($doctor['sd_id'],$sd_ids)){
                $doctor['is_checked'] = 1;
                $doctor['begin_hour'] = $check[$doctor['sd_id']]['begin_hour'];
                $doctor['end_hour'] = $check[$doctor['sd_id']]['end_hour'];
            }else{
                $doctor['is_checked'] = 0;
                $doctor['begin_hour'] = 0;
                $doctor['end_hour'] = 0;
            }
        }
        return view('admin/shop/schedule_edit',[
            'doctors'=>$doctors,
            'sd_ids'=>$sd_ids,
            'ss_id'=>$ss_id,
            'shop'=>$shop,
            'schedule'=>$schedule,
        ]);
    }

    public function activitySeckillPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $seckills = ActivityService::getInstance()->secKillList();
        return view('admin/activity/seckill',[
            'seckills'=>$seckills
        ]);
    }

    public function activitySeckillGoodsPage($as_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/activity/seckill'))
            return redirect('/cms/none');
        $seckill = ActivityService::getInstance()->secKillDetail($as_id);
        $goods = ActivityService::getInstance()->secKillGoods($as_id);
        return view('admin/activity/seckill_goods',[
            'seckill'=>$seckill,
            'goods'=>$goods
        ]);
    }

    public function activitySeckillAddPage($as_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/activity/seckill'))
            return redirect('/cms/none');
        $seckill = ActivityService::getInstance()->secKillDetail($as_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/activity/seckill_add',[
            'seckill'=>$seckill,
            'albums'=>$albums
        ]);
    }

    public function activityGroupPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $groups = ActivityService::getInstance()->groupList();
        return view('admin/activity/group',[
            'groups'=>$groups
        ]);
    }

    public function activityGroupGoodsPage($ag_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/activity/group'))
            return redirect('/cms/none');
        $group = ActivityService::getInstance()->groupDetail($ag_id);
        $goods = ActivityService::getInstance()->groupGoods($ag_id);
        return view('admin/activity/group_goods',[
            'group'=>$group,
            'goods'=>$goods
        ]);
    }

    public function activityGroupAddPage($ag_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/activity/group'))
            return redirect('/cms/none');
        $group = ActivityService::getInstance()->groupDetail($ag_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/activity/group_add',[
            'group'=>$group,
            'albums'=>$albums
        ]);
    }

    public function couponPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $coupons = CouponService::getInstance()->couponList();
        return view('admin/activity/coupon',[
            'coupons'=>$coupons,
        ]);
    }

    public function couponAddPage($c_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/coupon'))
            return redirect('/cms/none');
        $coupon = CouponService::getInstance()->couponDetail($c_id);
        return view('admin/activity/coupon_add',[
            'coupon'=>$coupon,
        ]);
    }

    public function couponBatchPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/user'))
            return redirect('/cms/none');
        $coupons = CouponService::getInstance()->couponBatchList();
        $coupons_select = CouponService::getInstance()->couponSelect();
        return view('admin/user/coupon_batch',[
            'coupons'=>$coupons,
            'coupons_select'=>$coupons_select
        ]);
    }

    public function giftPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $gifts = ActivityService::getInstance()->gifts();
        return view('admin/activity/gift',[
            'gifts'=>$gifts,
        ]);
    }

    public function lotteryPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $prizes = LotteryService::getInstance()->lotteryPrize();
        $odds = LotteryService::getInstance()->lotteryPrizeOdds();
        return view('admin/activity/lottery',[
            'prizes'=>$prizes,
            'odds'=>$odds
        ]);
    }

    public function lotteryAddPage($lp_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/activity/lottery'))
            return redirect('/cms/none');
        $prize = LotteryService::getInstance()->lotteryPrizeDetail($lp_id);
        $odds = LotteryService::getInstance()->lotteryPrizeOdds();
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/activity/lottery_add',[
            'prize'=>$prize,
            'odds'=>$odds,
            'albums'=>$albums
        ]);
    }

    public function signPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $signs = SignService::getInstance()->signList();
        return view('admin/activity/sign',[
            'signs'=>$signs
        ]);
    }

    public function signAddPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/sign'))
            return redirect('/cms/none');
        $sign_at = SignService::getInstance()->getNextMonth();
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/activity/sign_add',[
            'sign_at'=>$sign_at,
            'albums'=>$albums
        ]);
    }

    public function signEditPage($s_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/sign'))
            return redirect('/cms/none');
        $sign = SignService::getInstance()->signDetail($s_id);
        $items = SignService::getInstance()->signItem($s_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/activity/sign_edit',[
            'items'=>$items,
            'sign'=>$sign,
            'albums'=>$albums
        ]);
    }

    public function signDetailPage($s_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/sign'))
            return redirect('/cms/none');
        $sign = SignService::getInstance()->signDetail($s_id);
        $items = SignService::getInstance()->signItem($s_id);
        return view('admin/activity/sign_detail',[
            'items'=>$items,
            'sign'=>$sign
        ]);
    }

    public function coursePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $title = $this->request->get('title','');
        $courses = CourseService::getInstance()->courseList($title);
        return view('admin/course/course',[
            'courses'=>$courses,
            'title'=>$title
        ]);
    }

    public function courseAddPage($c_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/course'))
            return redirect('/cms/none');
        list($course,$pic_detail) = CourseService::getInstance()->courseDetail($c_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/course/course_add',[
            'course'=>$course,
            'pic_detail'=>$pic_detail,
            'albums'=>$albums
        ]);
    }

    public function courseItemPage($c_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/course'))
            return redirect('/cms/none');
        $items = CourseService::getInstance()->courseItem($c_id);
        $course = CourseService::getInstance()->courseInfo($c_id);
        return view('admin/course/course_item',[
            'items'=>$items,
            'course'=>$course,
            'c_id'=>$c_id
        ]);
    }

    public function courseItemAddPage($c_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/course'))
            return redirect('/cms/none');
        $albums = AlbumService::getInstance()->albumList();
        $course = CourseService::getInstance()->courseInfo($c_id);
        return view('admin/course/course_item_add',[
            'albums'=>$albums,
            'course'=>$course,
            'c_id'=>$c_id
        ]);
    }

    public function courseItemEditPage($c_id,$ci_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/course'))
            return redirect('/cms/none');
        $course = CourseService::getInstance()->courseInfo($c_id);
        $albums = AlbumService::getInstance()->albumList();
        $item = CourseService::getInstance()->courseItemDetail($ci_id);
        return view('admin/course/course_item_edit',[
            'albums'=>$albums,
            'course'=>$course,
            'item'=>$item
        ]);
    }

    public function courseOrderPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $phone = $this->request->post('phone','');
        $title = $this->request->post('title','');
        $orders = CourseService::getInstance()->courseOrder($phone,$title);
        return view('admin/course/course_order',[
            'orders'=>$orders
        ]);
    }

    public function articlePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $articles = ArticleService::getInstance()->articleList();
        return view('admin/article/article',[
            'articles'=>$articles
        ]);
    }

    public function articleAddPage($a_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/article'))
            return redirect('/cms/none');
        list($article, $pic_intro) = ArticleService::getInstance()->articleDetail($a_id);
        $albums = AlbumService::getInstance()->albumList();
        $cat_1 = ArticleService::getInstance()->articleCatAll(0);
        if($a_id)
            $cat_2 = ArticleService::getInstance()->articleCatAll($article['ac_id_1']);
        else
            $cat_2 = [];
        return view('admin/article/article_add',[
            'article'=>$article,
            'pic_intro'=>$pic_intro,
            'albums'=>$albums,
            'cat_1'=>$cat_1,
            'cat_2'=>$cat_2
        ]);
    }

    public function articleQuestionPage($a_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/article'))
            return redirect('/cms/none');
        $title = ArticleService::getInstance()->articleField($a_id,'title');
        $questions = ArticleService::getInstance()->articleQuestion($a_id);
        return view('admin/article/article_question',[
            'questions'=>$questions,
            'title'=>$title,
            'a_id'=>$a_id
        ]);
    }

    public function articleGoodsPage($a_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/article'))
            return redirect('/cms/none');
        $goods = ArticleService::getInstance()->articleGoods($a_id);
        $title = ArticleService::getInstance()->articleField($a_id,'title');
        return view('admin/article/article_goods',[
            'goods'=>$goods,
            'title'=>$title,
            'a_id'=>$a_id
        ]);
    }

    public function articleItemPage($a_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/article'))
            return redirect('/cms/none');
        $type = $this->request->get('type','');
        $title = ArticleService::getInstance()->articleField($a_id,'title');
        $items = ArticleService::getInstance()->articleItem($a_id,$type);
        return view('admin/article/article_item',[
            'items'=>$items,
            'title'=>$title,
            'a_id'=>$a_id
        ]);
    }

    public function articleDetailPage($a_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/article'))
            return redirect('/cms/none');
        $items = ArticleService::getInstance()->articleItem($a_id,'');
        $questions = ArticleService::getInstance()->articleQuestion($a_id);
        $goods = ArticleService::getInstance()->articleGoods($a_id);
        list($article, $pic_intro) = ArticleService::getInstance()->articleDetail($a_id);
        return view('admin/article/article_detail',[
            'items'=>$items,
            'questions'=>$questions,
            'a_id'=>$a_id,
            'goods'=>$goods,
            'pic_intro'=>$pic_intro,
            'article'=>$article
        ]);
    }

    //分类
    public function articleCatPage(){
        $parent_id = $this->request->get('parent_id','');
        $cats = ArticleService::getInstance()->articleCatList($parent_id);
        $parents = ArticleService::getInstance()->articleCatAll(0);
        return view('admin/article/article_cat', [
            'cats' => $cats,
            'parents' => $parents,
            'parent_id' => $parent_id,
        ]);
    }

    public function articleCatAddPage($ac_id)
    {
        $parents = ArticleService::getInstance()->articleCatAll(0);
        $cat = ArticleService::getInstance()->articleCatDetail($ac_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/article/article_cat_add',[
            'parents'=>$parents,
            'cat'=>$cat,
            'albums'=>$albums
        ]);
    }

    public function articleCommonPage()
    {
        $articles = ArticleService::getInstance()->articleCommonList();
        return view('admin/article/article_common',[
            'articles'=>$articles
        ]);
    }

    public function articleCommonAddPage($ac_id)
    {
        list($article,$pics) = ArticleService::getInstance()->articleCommonDetail($ac_id);
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/article/article_common_add',[
            'article'=>$article,
            'pics'=>$pics,
            'albums'=>$albums
        ]);
    }

    public function articleCommonDetailPage($ac_id)
    {
        list($article,$pics) = ArticleService::getInstance()->articleCommonDetail($ac_id);
        return view('admin/article/article_common_detail',[
            'article'=>$article,
            'pics'=>$pics,
        ]);
    }


    public function bannerPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $type = $this->request->get('type','');
        $area = $this->request->get('area','');
        $banners = BannerService::getInstance()->bannerList(1);
        return view('admin/system/banner',[
            'banners'=>$banners,
            'type'=>$type,
            'area'=>$area
        ]);
    }

    public function bannerAddPage($b_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/banner'))
            return redirect('/cms/none');
        $banner = BannerService::getInstance()->bannerDetail($b_id);
        $cats = Db::name("bannertype")->select();
        $albums = AlbumService::getInstance()->albumList();
        if($b_id && $banner['type'] == 7) {
            $banner['pics'] = AlbumService::getInstance()->picSort($banner['type_id']);
        }
        return view('admin/system/banner_add',[
            'cats'=>$cats,
            'banner'=>$banner,
            'albums'=>$albums
        ]);
    }

    public function homePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $homes = HomeService::getInstance()->homeList();
        return view('admin/system/home',[
            'homes'=>$homes,
        ]);
    }

    public function homeAddPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/home'))
            return redirect('/cms/none');
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/system/home_add',[
            'albums'=>$albums
        ]);
    }

    public function homeEditPage($h_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/home'))
            return redirect('/cms/none');
        $albums = AlbumService::getInstance()->albumList();
        $home = HomeService::getInstance()->homeDetail($h_id);
        $home['ids'] = explode(',',$home['ids']);
        $show_content = 1;
        $select_type = 'checkbox';
        switch ($home['type']) {
            case 3:$content = GoodsService::getInstance()->goodsSearch();break;
            case 4:$content = GoodsService::getInstance()->goodsCategory();$select_type = 'radio';break;
            case 5:$content = CourseService::getInstance()->courseSearch();break;
            case 6:$content = ArticleService::getInstance()->articleSearch();break;
            case 7:$content = ArticleService::getInstance()->articleCommonSearch();break;
            default:$content = [];$show_content = 0;break;
        }
        return view('admin/system/home_edit',[
            'albums'=>$albums,
            'home'=>$home,
            'show_content'=>$show_content,
            'content'=>$content,
            'select_type'=>$select_type
        ]);
    }

    public function orderPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $shop_name = $this->request->get('shop_name','');
        $doctor_name = $this->request->get('doctor_name','');
        $phone = $this->request->get('phone','');
        $order_no = $this->request->get('order_no','');
        $status = $this->request->get('status','');
        $orders = OrderService::getInstance()->orderList($admin_user['s_id'],$shop_name,$doctor_name,$phone,$order_no,$status);
        return view('admin/order/order',[
            'shop_name'=>$shop_name,
            'doctor_name'=>$doctor_name,
            'order_no'=>$order_no,
            'phone'=>$phone,
            'status'=>$status,
            'orders'=>$orders
        ]);
    }

    public function orderDetailPage($o_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        $order = OrderService::getInstance()->orderDetail($o_id);
        $logs = OrderService::getInstance()->orderLog($o_id);
        $pres = OrderService::getInstance()->userOrderPreDetail($o_id);
        $pays = OrderService::getInstance()->orderPayDetail($o_id);
        $evaluate = OrderService::getInstance()->orderEvaluateDetail($o_id);
        $has_pre = OrderService::getInstance()->checkOrderHasPre($o_id);
        $user = UserService::getInstance()->getUserInfoById($order['user_id']);
        $reports = OrderService::getInstance()->orderReportList($o_id);
        $after = OrderService::getInstance()->orderAfterDetail($o_id);
        return view('admin/order/order_detail',[
            'order'=>$order,
            'after'=>$after,
            'logs'=>$logs,
            'pres'=>$pres,
            'pays'=>$pays,
            'evaluate'=>$evaluate,
            'has_pre'=>$has_pre,
            'reports'=>$reports,
            'user'=>$user,
            'ag_id'=>$admin_user['ag_id']
        ]);
    }

    public function orderReportPage($o_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        $reports = OrderService::getInstance()->orderReportList($o_id);
        return view('admin/order/order_report',[
            'reports'=>$reports,
            'o_id'=>$o_id
        ]);
    }

    public function orderReportDetailPage($or_id)
    {
        $report = OrderService::getInstance()->orderReportDetail($or_id);
        return view('admin/order/order_report_detail',[
            'report'=>$report,
        ]);
    }

    public function orderReportAddPage($o_id,$or_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        $report = OrderService::getInstance()->orderReportDetail($or_id);
        $albums = AlbumService::getInstance()->albumList();
        $pics = [];
        if($or_id)
            $pics = AlbumService::getInstance()->picSort($report['pic_banner']);
        return view('admin/order/order_report_add',[
            'report'=>$report,
            'albums'=>$albums,
            'pics'=>$pics,
            'o_id'=>$o_id
        ]);
    }

    public function orderEvaluatePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $evaluates = OrderService::getInstance()->orderEvaluateList();
        return view('admin/order/order_evaluate',[
            'evaluates'=>$evaluates
        ]);
    }

    public function orderVerifyPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $phone = $this->request->get('phone','');
        $orders = OrderService::getInstance()->orderVerifyList($admin_user['s_id'],$phone);
        return view('admin/order/order_verify',[
            'phone'=>$phone,
            'orders'=>$orders,
            'ag_id'=>$admin_user['ag_id']
        ]);
    }

    public function circleAddPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/circle'))
            return redirect('/cms/none');
        $users = UserService::getInstance()->getUserAdmin();
        $albums = AlbumService::getInstance()->albumList();
        return view('admin/circle/circle_add',[
            'users'=>$users,
            'albums'=>$albums
        ]);
    }

    public function circlePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $title = $this->request->get('title', '');
        $circles = CircleService::getInstance()->circleList($title);
        return view('admin/circle/circle', [
            'circles' => $circles,
            'title' => $title
        ]);
    }

    public function evaluatePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/circle'))
            return redirect('/cms/none');
        $uc_id = $this->request->param('uc_id', 0);
        $data = CircleService::getInstance()->evaluateList($uc_id);
        return $this->fetch('admin/circle/evaluate_list', [
            'data' => $data,
        ]);
    }

    public function circleDetailPage($uc_id)
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id'],'/cms/circle'))
            return redirect('/cms/none');
        $circle = CircleService::getInstance()->circleDetail($uc_id);
        $users = UserService::getInstance()->getUserAdmin();
        return view('admin/circle/circle_detail',[
            'circle'=>$circle,
            'users'=>$users
        ]);
    }

    public function topupPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $topups = TopUpService::getInstance()->topUpList();
        return view('admin/system/topup',[
            'topups'=>$topups
        ]);
    }

    public function messagePage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $mess = SystemService::getInstance()->messageList();
        return view('admin/system/mess',[
            'mess'=>$mess
        ]);
    }

    public function dataUserPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $today_data = DataService::getInstance()->userData();
        $begin_at = date('Y-m-01');
        $end_at = date('Y-m-d', strtotime("$begin_at +1 month -1 day"));
        return view('admin/data/user_data',[
            'today_data'=>$today_data,
            'begin_at'=>$begin_at,
            'end_at'=>$end_at
        ]);
    }

    public function userRankPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $rank = DataService::getInstance()->userRank();
        return view('admin/data/user_rank',[
            'rank'=>$rank,
        ]);
    }

    public function goodsRankPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $default_begin_at = date('Y-m-01');
        $default_end_at = date('Y-m-d', strtotime("$default_begin_at +1 month -1 day"));
        $sort = $this->request->get('sort',1);
        $begin_at = $this->request->get('begin_at',$default_begin_at);
        $end_at = $this->request->get('end_at',$default_end_at);
        $rank = DataService::getInstance()->goodsRank($begin_at,$end_at,$sort);
        return view('admin/data/goods_rank',[
            'rank'=>$rank,
            'begin_at'=>$begin_at,
            'end_at'=>$end_at,
            'sort'=>$sort
        ]);
    }

    public function orderDataPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $begin_at = date('Y-m-01');
        $end_at = date('Y-m-d', strtotime("$begin_at +1 month -1 day"));
        return view('admin/data/order_data',[
            'begin_at'=>$begin_at,
            'end_at'=>$end_at
        ]);
    }

    public function opinionConfPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $words = ConfigurationService::getInstance()->getConfLike('opinion_');
        return view('admin/system/opinion',[
            'words'=>$words,
        ]);
    }

    public function feedbackPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $feedback = SystemService::getInstance()->feedback();
        return view('admin/system/feedback',[
            'feedback'=>$feedback,
        ]);
    }

    public function giftConvertPage()
    {
        $admin_user = AdminUserService::getInstance()->getAdminLoginStatus();
        if(!$admin_user)
            return $this->redirectLogin();
        if (!SystemService::getInstance()->checkOperation($admin_user['ag_id']))
            return redirect('/cms/none');
        $phone = $this->request->get('phone','');
        $status = $this->request->get('status','');
        $gifts = UserService::getInstance()->giftConvert($phone,$status);
        return view('admin/activity/gift_convert',[
            'gifts'=>$gifts,
            'phone'=>$phone,
            'status'=>$status,
        ]);
    }



}