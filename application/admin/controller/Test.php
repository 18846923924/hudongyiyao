<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/11/5 0005
 * Time: 11:33
 */

namespace app\admin\controller;


use app\model\Shop;
use app\model\ShopAccount;
use app\Response;
use app\services\AdminUserService;
use app\services\AliKdiService;
use app\services\AreaService;
use app\services\ConfigurationService;
use app\services\CouponService;
use app\services\CreditsService;
use app\services\CurlService;
use app\services\DataService;
use app\services\FileService;
use app\services\FreightService;
use app\services\GoodsService;
use app\services\OrderService;
use app\services\ActivityService;
use app\services\ShopService;
use app\services\SystemService;
use app\services\UserService;
use app\services\WeChatPayService;
use app\services\WeChatService;
use think\Controller;
use think\Db;
use think\facade\Env;
use think\helper\Hash;

class Test extends Controller
{
    public function test()
    {
//        $items = Db::name('goods_cat')
//            ->where('gc_id','>',1671)
//            ->field('gc_id,name')
//            ->select();
//        foreach ($items as $item) {
//            Db::name('goods_cat')->where('gc_id',$item['gc_id'])->update([
//                'pic'=>'goods_cat/'.$item['name'].'.png'
//            ]);
//        }
        set_time_limit(0);
//        CouponService::getInstance()->drawRegisterCoupon(1);
//        $data = WeChatPayService::getInstance()->miniPayData(5,1, 's_202002271asd17401236a');
//        $data = WeChatService::getInstance()->qrCode('pages/index/index', 1);
//        $data = OrderService::getInstance()->orderCount(1);
//        $data = GoodsService::getInstance()->getPropertiesPrice(1519,'5eb9bfc2949f790bb695996b2171663a');
//        $data = WeChatPayService::getInstance()->refund('s_2020031220533953833331',1,8200);
//        $data = WeChatService::getInstance()->sendWxMess('oG56ywbNDhDZPEQRuHM8kVPbf_Ew','12312312123','帷拓测试消息推送','carl');
        $data = [
            [
                'type'=>'做工',
                'star'=>1,
            ]
        ];
        return json_encode($data,256);
        return Response::wrapData($data);

//        return UserService::getInstance()->getUserIdFromToken();
//        Db::name('user')->field('user_id')->chunk(100,function ($items){
//            foreach ($items as $item) {
//                $count = Db::name('user')->where('parent_id',$item['user_id'])->count('user_id');
//                Db::name('user')->where('user_id',$item['user_id'])->update(['next_count'=>$count]);
//            }
//
//        });
//        $data = MailService::getInstance()->sendEmail('测试邮件','测试','305817958@qq.com');
//        $shops = Db::name('shop')->where('first_category','>',0)->field('first_category,second_category,s_id')->select();
//        $db_sc= new ShopCategory();
//        foreach ($shops as $shop) {
//            $sc_name_1 = $db_sc->field('name')->find($shop['first_category']);
//            $sc_name_2 = $db_sc->field('name')->find($shop['second_category']);
//            ShopService::getInstance()->addShopItem($shop['s_id'],$shop['first_category'],$shop['second_category'],$sc_name_1,$sc_name_2);
//        }
        //ge.create_at,ge.content,ge.star,ge.pics,u.avatar,u,nickname
//        $data = UserService::getInstance()->nextUser(114,1,1);
//        $data = HuoBiService::getInstance()->get_market_tickers();
        // 删除已经没有用户的订单
//        Db::name('order')->field('user_id,o_id')->chunk(100,function ($items){
//            foreach ($items as $item) {
//                $user = Db::name('user')->where('user_id',$item['user_id'])->field('user_id')->find();
//                if(empty($user)){
//                    Db::name('order')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_address')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_after')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_express')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_goods')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_log')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_pay')->where('o_id',$item['o_id'])->delete();
//                }
//            }
//        });

//        OrderService::getInstance()->orderPay(2, '1', 1);
        return Response::wrapData();
//        Db::name('shop_account')->field('s_id,sa_id')->chunk(100,function ($items){
//            foreach ($items as $item) {
//                $count = Db::name('goods')->where('s_id',$item['s_id'])->count('g_id');
//                Db::name('shop_account')->where('s_id',$item['s_id'])->update(['goods_num'=>$count]);
//            }
//        });
//        Db::name('goods_data')->where('1=1')->update(['month_sales'=>0]);
//        $start_at = time()-259200;
//        $items = Db::name('order_goods')
//            ->field('sum(num) num,g_id')
//            ->where('create_at', '>=', $start_at)
//            ->group('g_id')
//            ->select();
//        foreach ($items as $item) {
//            Db::name('goods_data')->where('g_id',$item['g_id'])->update(['month_sales'=>$item['num']]);
//        }
//        $data = Db::name('order_goods')->field('sum(num) num,g_id')->group('g_id')->select();
//        Db::name('order')->field('o_id,user_id,s_id')->chunk(100,function ($items){
//            foreach ($items as $item) {
//                $user = Db::name('shop')->field('s_id')->find($item['s_id']);
//                if(empty($user)){
//                    Db::name('order')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_goods')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_address')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_after')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_express')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_log')->where('o_id',$item['o_id'])->delete();
//                    Db::name('order_pay')->where('o_id',$item['o_id'])->delete();
//                }
//            }
//        });
//        AliKdiService::getInstance()->kdiInfo('');
        $data = [
            [
                'bean' => 500,
                'money' => 40,
                'status' => 1,
                'create_at' => 1500000000,
            ]
        ];
        return Response::wrapData($data);
        $data = LotteryService::getInstance()->prizeRecord(1690, 1, 2);
        return Response::wrapData($data);
        $data = LotteryService::getInstance()->drawLottery(1690, 2);
        return Response::wrapData($data);

//        $order = OrderService::getInstance()->orderDetail(52);
//        return Response::wrapData($order);
//        $data = [
//            [
//                'o_id' => 1,
//                'order_no' => '123123213123',
//                'real_money' => 150000,
//                'status' => 1,
//                'create_at' => 1500000000,
//                's_id' => 1,
//                'op_id' => 1,
//                'goods' => [
//                    [
//                        'g_id' => 1,
//                        'goods_info' => '黑色拉杆箱',
//                        'price' => 15000,
//                        'num' => 2,
//                        'total_price' => 30000,
//                        'pic' => '图片地址',
//                        'goods_name' => '拉杆箱'
//                    ]
//                ]
//            ]
//        ];
//        return Response::wrapData($data);
//        $data = ConfigurationService::getInstance()->getConfLike('search_');
//        return Response::wrapData($data);
    }

    // 修正商品价格表的sku_name,直接改了商品规格属性值时，需执行此脚本
    public function goodsParamCorrection()
    {
        Db::name('goods_price')->field('gp_id,sku_ids')->chunk(100,function ($items){
            foreach ($items as $item) {
                $types = explode('_',$item['sku_ids']);
                $sku_name = [];
                foreach ($types as $type) {
                    $name_id = explode('-',$type)[1];
                    $name = GoodsService::getInstance()->getGoodsParamPropertyName($name_id);
                    array_push($sku_name,$name);
                }
                $sku_name = implode(' ',$sku_name);
                Db::name('goods_price')->where('gp_id',$item['gp_id'])->update(['sku_name'=>$sku_name]);
            }
        });
    }

    private function goodsParamsNameRecursion($params, $type_idx,$name_idx,$cell,&$data)
    {
        $items = $params[$type_idx]['items'];
        array_push($cell,$items[$name_idx]);
        if(count($params) - $type_idx == 1) {
            // 到了最后一个规格类型
            if(count($items) - $name_idx == 1) {
                // 到了最后一个规格类型的最后一个规格
                $this->goodsParamsNameRecursion($params,$type_idx,$name_idx,$cell,$data);
            }else{
                $name_idx += 1;
                $this->goodsParamsNameRecursion($params,$type_idx,$name_idx,$cell,$data);
            }

        }else{
            $type_idx += 1;
            $this->goodsParamsNameRecursion($params,$type_idx,0,$cell, $data);
        }
        array_push($data,$cell);
    }
}