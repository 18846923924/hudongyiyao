<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 * Date: 2019/4/22
 * Time: 16:05
 */


namespace app\services;


class CurlService{

    private static $instance;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance()
    {
        if(!(self::$instance instanceof  self))
            self::$instance = new self();
        return self::$instance;
    }


    /**
     * post方法
     * @param $url
     * @param $data
     * @param null $header
     * @param bool $is_debug
     * @param null $cert_path
     * @param null $pkey_path
     * @param null $ca_path
     * @return mixed
     * @throws SystemErr
     */
    public function post($url,$data,$header=null,$is_debug=false,$cert_path=null,$pkey_path=null,$ca_path=null)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        if($header)
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        if($cert_path && $pkey_path)
        {
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT,$cert_path);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY,$pkey_path);
        }
        if($ca_path) {
            curl_setopt($ch, CURLOPT_CAINFO, 'PEM');
            curl_setopt($ch, CURLOPT_CAPATH, $ca_path);
        }

        $re = curl_exec($ch);
        if($is_debug)
            return $re;
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if($code != 200)
            return $code;
        return $re;
    }


    /**
     * get方法
     * @param $url
     * @param null $data
     * @param null $header
     * @param bool $is_debug
     * @return mixed
     * @throws SystemErr
     */
    public function get($url,$data=null,$header=null,$is_debug=false)
    {

        if(is_array($data))
        {
            $url .= '?';
            $size = count($data);

            $index = 1;
            foreach ($data as $k=>$v)
            {
                $url .= $k.'='.$v;
                if($index != $size)
                    $url .= '&';
                $index ++;
            }
        }

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        if($header)
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        $re = curl_exec($ch);
        if($is_debug)
            return $re;

        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        if($code != 200)
            return $code;
        return $re;
    }

    function curl_post_ssl($url, $xmldata,$second = 30, $aHeader = array())
    {
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);//设置执行最长秒数
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');//证书类型
        curl_setopt($ch, CURLOPT_SSLCERT, config('xn.WeChat')['CertPath']);//证书位置
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');//CURLOPT_SSLKEY中规定的私钥的加密类型
        curl_setopt($ch, CURLOPT_SSLKEY, config('xn.WeChat')['PKeyPath']);//证书位置
        //curl_setopt($ch, CURLOPT_CAINFO, 'PEM');
        //curl_setopt($ch, CURLOPT_CAINFO, $isdir . 'rootca.pem');
        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);//设置头部
        }
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata);//全部数据使用HTTP协议中的"POST"操作来发送

        $data = curl_exec($ch);//执行回话
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }

    public function downloadImage($url, $path='/public/uploads/avatar')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $path = dirname(dirname(dirname(__FILE__))).$path;
        $filename = $this->saveAsImage($file, $path);
        return $filename;
    }

    private function saveAsImage($file, $path)
    {
        if (!file_exists($path)) {
            mkdir($path,0777,true);
        }
        $filename = str_random(8).time().'.jpg';
        $resource = fopen($path.DS . $filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
        return $filename;
    }


}