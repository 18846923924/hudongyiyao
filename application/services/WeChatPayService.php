<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 *
 * Date: 2019/6/25 0025
 * Time: 16:38
 */

namespace app\services;

use app\exception\CommonException;
use app\model\UserTicketPay;
use think\Db;
use think\facade\Log;

class WeChatPayService
{
    private static $instance;
    private $conf;
    //统一下单API
    const PreOrder = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    //订单退款
    const Refund = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
    // 企业付款到零钱
    const Transfer = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    // 开通商户
    const OpenStore = 'https://api.mch.weixin.qq.com/v3/ecommerce/applyments';

    private function __construct()
    {
        $this->conf = config('djl.WeChat');
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

    public function miniPayData($user_id,$price, $pay_no)
    {
        $data = null;
        Db::transaction(function () use ($user_id,$price, $pay_no, &$data) {
            // 生成微信预支付订单
            $user = UserService::getInstance()->getUserInfoById($user_id);
            $openid = $user['openid'];
            if (empty($openid))
                throw new CommonException('openid is required');
            $time = time();
            $result = $this->preOrder($price, $pay_no, 'JSAPI', $openid);
            $xml_parser = xml_parser_create();
            xml_parse_into_struct($xml_parser, $result, $re);
            Log::info('统一下单返回：'.json_encode($re));
            $prepay_id = $re[17]['value'];
            // 整理签名数据
            $app_id = $this->conf['AppId'];
            $key = $this->conf['Key'];
            $nonceStr = str_random(32);
            $package = "prepay_id=$prepay_id";
            // 签名加密,
            $pay_sign = strtoupper(md5('appId=' . $app_id . '&nonceStr=' . $nonceStr . '&package=' . $package . '&signType=MD5&timeStamp=' . $time . '&key=' . $key));
            $data = [
                'pay_status' => 0,
                'order_no' => $pay_no,
                'nonce_str' => $nonceStr,
                'package' => $package,
                'time_stamp' => $time,
                'pay_sign' => $pay_sign,
            ];
        });
        return $data;

    }

    /**
     * 企业付款到零钱使用条件
     * 1、商户号（或同主体其他非服务商商户号）已入驻90日
     *
     * 2、商户号（或同主体其他非服务商商户号）有30天连续正常交易
     *
     * 3、 登录微信支付商户平台-产品中心，开通企业付款。
     * @param $amount
     * @param $user_id
     * @param string $desc
     * @param string $check_name
     * @throws CommonException
     */
    function sendMoney($amount, $user_id, $desc = '账户提现', $check_name = '')
    {
        $user = UserService::getInstance()->getUserInfoById($user_id);
        $openid = $user['openid'];
        if (empty($openid))
            throw new CommonException('openid is required');
        $data = array(
            'mch_appid' => $this->conf['AppId'],//商户账号appid
            'mchid' => $this->conf['MchId'],//商户号
            'nonce_str' => str_random(32),//随机字符串
            'partner_trade_no' => date('YmdHis') . rand(1000, 9999),//商户订单号
            'openid' => $openid,//用户openid
            'check_name' => 'NO_CHECK',//校验用户姓名选项,
            're_user_name' => $check_name,//收款用户姓名
            'amount' => $amount,//金额
            'desc' => $desc,//企业付款描述信息
            'spbill_create_ip' => $this->conf['ServerIp'],//Ip地址
        );
        //生成签名算法
        $secrect_key = $this->conf['Key'];///这个就是个API密码。MD5 32位。
        $data = array_filter($data);
        ksort($data);
        $str = '';
        foreach ($data as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str .= 'key=' . $secrect_key;
        $data['sign'] = md5($str);
        //生成签名算法
        $xml = $this->convertArrayToXml($data);
        $re = CurlService::getInstance()->post(self::Transfer, $xml, null, false,
            $this->conf['CertPath'], $this->conf['PKeyPath']);
        if ($re) {
            $arr = $this->xmlStructureToArray($re);
            if ($arr['return_code'] == 'SUCCESS') {
                if ($arr['result_code'] != 'SUCCESS')
                    throw new CommonException($arr['return_msg'].'-'.$arr['err_code_des']);
            } else
                throw new CommonException($arr['return_msg'].'-'.$arr['err_code_des']);
        }else
            throw new CommonException("no response" . json_encode($re));
    }


    /**
     * 参数签名
     * @param $data
     */
    private function generateSign(&$data)
    {
        ksort($data);//参数按照ASCII有小到大排序
        $stringA = '';
        //生成stringA
        $length = count($data);
        $i = 0;
        foreach ($data as $k => $v) {
            $i++;
            $stringA .= $k . '=' . $v;
            if ($i != $length) {
                $stringA .= '&';
            }
        }

        $stringSignTemp = $stringA . '&key=' . $this->conf['Key'];
        $data['sign'] = strtoupper(md5($stringSignTemp));
    }

    /**
     * 将array转变为xml
     * @param $data
     * @return string
     */
    private function convertArrayToXml($data)
    {
        if (empty($data)) return 'error';
        $xml = '<xml>' . "\n";
        foreach ($data as $k => $v) {
            $xml .= '<' . $k . '>' . $v . '</' . $k . '>' . "\n";
        }
        $xml .= '</xml>';
        return $xml;
    }


    /**
     * 生成微信的预支付订单
     * @param $price int 订单金额，单位分
     * @param $order_no  string 商家自定义的订单编号
     * @param string $trade_type string JSAPI 公众号支付 NATIVE 扫码支付 APP APP支付
     * @return mixed
     * @throws SystemInternalError
     */
    private function preOrder($price, $order_no, $trade_type = 'NATIVE',$openid='')
    {
        $time = time();
        $data = [
            'appid' => $this->conf['AppId'],
            'openid' => $openid,
            'mch_id' => $this->conf['MchId'],
            'nonce_str' => str_random(18),
            'detail' => '凌志服饰(' . ($price / 100) . '元)',
            'attach' => '凌志服饰',
            'device_info' => 'MINI_APP',
            'body' => '凌志服饰',
            'total_fee' => $price,
            'fee_type' => 'CNY',
            'time_start' => date('YmdHis', $time),
            'time_expire' => date('YmdHis', $time + 600),
            'trade_type' => $trade_type,
            'out_trade_no' => $order_no,
            'notify_url' => HOST.$this->conf['NotifyUrl']
        ];
        $this->generateSign($data);
        $data = $this->convertArrayToXml($data);
        $result = CurlService::getInstance()->post(self::PreOrder, $data);
        if (empty($result)) throw new CommonException('wechat preorder failed!');
        return $result;
    }

    /**
     * 针对微信返回的xml参数，结构化为操作便利的数组
     * @param $xml
     * @return array
     */
    public function xmlStructureToArray($xml)
    {
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $xml, $arr);
        $data = [];
        foreach ($arr as $item) {
            if ($item['tag'] != 'XML')
                $data[strtolower($item['tag'])] = $item['value'];
        }
        unset($arr, $xml, $parser);
        return $data;
    }

    /**
     * 微信订单退款
     * @param $order_no
     * @param $refund_fee
     * @param $total_fee
     * @return array
     * @throws SystemInternalError
     * https://pay.weixin.qq.com/wiki/doc/api/native.php?chapter=9_4
     */
    public function refund($order_no,$refund_fee,$total_fee)
    {
        Log::info('订单退款：'.$order_no.'-'.$refund_fee.'-'.$total_fee);
        $data = [
            'appid'=>$this->conf['AppId'],
            'mch_id'=>$this->conf['MchId'],
            'nonce_str'=>str_random(18),
            'sign_type'=>'MD5',
            'out_trade_no'=>$order_no,
            'out_refund_no'=>str_random(18),
            'total_fee'=>$total_fee,
            'refund_fee'=>$refund_fee,
            'refund_fee_type'=>'CNY',
            'refund_desc'=>'订单退款,标号:'.$order_no,
            'notify_url'=>HOST.$this->conf['RefundNotifyUrl']
        ];

        $this->generateSign($data);
        $data = $this->convertArrayToXml($data);

        $re = CurlService::getInstance()->post(self::Refund,$data,null,false,$this->conf['CertPath'],$this->conf['PKeyPath']);

        if(empty($re)) throw new CommonException('wechat refund failed!');

        $arr = $this->xmlStructureToArray($re);

        Log::info(json_encode($arr));
        if($arr['return_code']=='SUCCESS' && $arr['result_code'] == 'SUCCESS')
            return [true,'success'];
        else {
            return [false,$arr['return_msg']];
        }

    }

    /**
     * 解析微信退款内容  AES-256-ECB  PKCS7Padding
     * @param $info
     * @return array
     * @throws SystemInternalError
     */
    public function decodeRefundNotifyInfo($info)
    {
        $data = base64_decode($info);
        $key = strtolower(md5($this->conf['Key']));
        $re = openssl_decrypt($data, 'AES-256-ECB', $key, OPENSSL_NO_PADDING);
        if ($re == false)
            throw new SystemInternalError('info decryption failed!');
        $len = strlen($re);
        $pad = ord($re[$len - 1]);
        if ($pad < 1 || $pad > 32)
            $pad = 0;
        $xml = substr($re, 0, $len - $pad);
        return $this->xmlStructureToArray($xml);
    }

    public function openStore()
    {
        $data = [
            'out_request_no'=>'1asdas',// 随机字符串
            'organization_type'=>'2401',
            'id_doc_type'=>'IDENTIFICATION_TYPE_MAINLAND_IDCARD',
            'id_card_info'=>[
                'id_card_copy'=>'身份证人像面照片,通过图片上传接口预先上传图片生成好的MediaID',
                'id_card_national'=>'身份证国徽面照片,通过图片上传接口预先上传图片生成好的MediaID',
                'id_card_name'=>'经营者姓名，该字段需进行加密处理，加密方法详见敏感信息加密说明',
                'id_card_number'=>'15位数字或17位数字+1位数字|X ，该字段需进行加密处理，加密方法详见敏感信息加密说明',
                'id_card_valid_time'=>'示例值：2026-06-06，长期'
            ],
            'need_account_info'=>true,
            'account_info'=>[
                'bank_account_type'=>'75',//若主体为小微，可填写：75-对私账户。
                'account_bank'=>'详细参见开户银行对照表',
                'account_name'=>'开户名称,',//该字段需进行加密处理，加密方法详见敏感信息加密说明。(提醒：必须在HTTP头中上送Wechatpay-Serial)
                'bank_address_code'=>'',//至少精确到市，详细参见省市区编号对照表。
                'bank_branch_id'=>'',//详细参见开户银行全称（含支行）对照表
                'bank_name'=>'',//详细参见开户银行全称（含支行）对照表
                'account_number'=>'',//该字段需进行加密处理，加密方法详见敏感信息加密说明。(提醒：必须在HTTP头中上送Wechatpay-Serial)
            ],
            'contact_info'=>[// 管理员信息
                'contact_type'=>'65',
                'contact_name'=>'id_card_name',// 与法人姓名一致
                'contact_id_card_number'=>'id_card_number',//与法人身份证一致
                'mobile_phone'=>'',// 手机号码
                'contact_email'=>'该字段需进行加密处理，加密方法详见敏感信息加密说明。(提醒：必须在HTTP头中上送Wechatpay-Serial)'
            ],
            'sales_scene_info'=>[
                'merchant_shortname'=>'',//将在支付完成页向买家展示，需与商家的实际售卖商品相符
            ]
        ];
    }

}