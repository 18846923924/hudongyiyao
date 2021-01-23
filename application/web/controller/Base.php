<?php
namespace app\web\controller;
use think\Db;
use think\Request;
use think\Controller;
use think\Session;

//use PHPMailer\PHPMailer\PHPMailer;

class Base  extends Controller
{
	public function __construct() {
	    parent::__construct();
		include 'x.php';

		$request  = $this->request;
		$controller =  $request->controller();
		//导航
		$nav = DB::name('nav')->field('n_id,title,linkurl,biaoshi')->where('pid',0)->where(["checkinfo"=>1])->order('sort')->select();
    		foreach ($nav as &$v) {
    			$subnav = DB::name('nav')->field('n_id,title,linkurl,biaoshi')->where('pid',$v['n_id'])->where(["checkinfo"=>1])->order('sort')->select();
    			foreach($subnav as &$sv){
                    $sv['url'] = $this->getUrl($sv['linkurl']);
    			}
    			$v['url'] = $this->getUrl($v['linkurl']);
    			$v['sub'] = $subnav;
    	}
        $gnav = Db::name("goods_cat")->where(["checkinfo"=>1])->select();
        $webname = webconfig('cfg_webname');//网站名称
        $webkeyword = webconfig('cfg_keyword');//网站关键词
        $webdescription =  webconfig('cfg_description');//网站描述
        $cfg_hotline =  webconfig('cfg_hotline');//电话热线
        $cfg_copyright = webconfig('cfg_copyright');//版权信息
        $cfg_icp = webconfig('cfg_icp');//备案编号
        $blogo = webconfig('cfg_mlogo2');//备案编号
        $cfg_lat = webconfig('cfg_lat');//经度
        $cfg_lng = webconfig('cfg_lng');//纬度 
        $cfg_webadd = webconfig('cfg_webadd');//地址
        $email = webconfig('cfg_webpath');//邮箱

        $firendlink = Db::name("weblink")
            ->where(["checkinfo"=>1])
            ->limit(0,8)
            ->order("sort desc,posttime desc")
            ->select();


        $this->assign('nav',$nav);
        $this->assign('gnav',$gnav);
        $this->assign('controller',$controller);
        $this->assign('webname',$webname);
        $this->assign('webkeyword',$webkeyword);
        $this->assign('webdescription',$webdescription);
        $this->assign('cfg_hotline',$cfg_hotline);
        $this->assign('cfg_copyright',$cfg_copyright);
        $this->assign('cfg_icp',$cfg_icp);
        $this->assign('blogo',$blogo);
        $this->assign('cfg_lat',$cfg_lat);
        $this->assign('cfg_lng',$cfg_lng);
        $this->assign('cfg_webadd',$cfg_webadd);
        $this->assign('email',$email);
        $this->assign('firendlink',$firendlink);


	}


	public function testSitemap() {


	    $sitemap = new \app\extra\Mysitemap("http://".$_SERVER['HTTP_HOST']);
	    
	   
	 	$sitemap->addItem('/index/about.html', '0.8', 'weekly', 'Today');
	 
	    
	     
	    
	}
	/**
	* 获取用户真实 IP
	*/
	function getIP(){
		
		static $realip;
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$realip = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$realip = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$realip = getenv("HTTP_X_FORWARDED_FOR");
			} else if (getenv("HTTP_CLIENT_IP")) {
				$realip = getenv("HTTP_CLIENT_IP");
			} else {
				$realip = getenv("REMOTE_ADDR");
			}
		}
		return $realip;
	}
	public function re_url($add){
  		$reurl = explode('?',$_SERVER["REQUEST_URI"]);//去掉后面的参数
  		$reurl1 = $reurl[0];
    	$reurlarray = explode('/',$reurl1);
    	return $reurlarray[$add];
  	}
  	public function send_email($to,$subject='',$content=''){
		Vendor('PHPMailer.PHPMailerAutoload');
		$mail = new PHPMailer();
		$mail->IsSMTP(); // 启用SMTP
		$mail->Host="smtp.163.com"; //smtp服务器的名称（这里以QQ邮箱为例）
		$mail->SMTPSecure = "ssl"; //目前规定必须使用ssl，非ssl的协议已经不支持了
		$mail-> Port = 465; //端口号
		$mail->SMTPDebug = 0; //用于debug PHPMailer信息
		$mail->SMTPAuth = true; //启用smtp认证
		$mail->Username = "siguoqi818@163.com"; //你的邮箱名
		$mail->Password = "CDDEHUGOTSNVOXQV" ; //邮箱授权码，注意是授权码，不是登录密码
		$mail->From = "siguoqi818@163.com"; //发件人地址（也就是你的邮箱地址）
		$mail->FromName = "台江县佀国旗反赌戒赌中心官网"; //发件人姓名
		$mail->AddAddress($to); //收件人地址
		$mail->WordWrap = 50; //设置每行字符长度
		$mail->IsHTML(true); // 是否HTML格式邮件
		$mail->CharSet="utf-8"; //设置邮件编码
		$mail->Subject =$subject; //邮件主题
		$mail->Body = $content; //邮件内容
		if($mail->Send()){
			// echo "success";
		}else{
			echo $mail->ErrorInfo;//打印错误信息
		}
	}

  	public function upload($file,$uploads){
		$time = date('Ymd',time());
		$files = request()->file($file);

		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $files->validate(['size'=>10485760,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . $uploads);
		if($info){
			$names = $info->getFilename();
			// 成功上传后 获取上传信息
			$picurl = $uploads.'/'.$time.'/'.$names;
			// 输出 42a79759f284b767dfcb2a0197904287.jpg
		}else{
			// 上传失败获取错误信息
			$this->error( $files->getError());
		}
		return $picurl;
	}

	public function getUrl($url){
        if($url!='#'){
            if(strstr($url,"?")){
                $params = strstr($url, '?');//加了？的参数
                $rurl = str_replace($params,'',$url);//去掉参数的方法名
                $url = url($rurl).$params;
            }
            else{
                $url = url($url);
            }
        }
        return $url;

    }
    public function downlog($file,$name,$controller,$cid){
    	if(Session::has('username'))
	    {
	        $username = Session::get('username');
	        $data = [
	        	"filename"   => $name,
	        	"controller" => $controller,
	        	"posttime"   => time(),
	        	"username"   => $username,
	        	"cid"   	 => $cid,
	        ];
	        DB::name("downlog")->insert($data);
	    }else{
	        $this->error("请先登录","/index/login");exit;
	    }
    }
    public function downloadfile($file,$name=''){
        $fileName = $name ? $name : pathinfo($file,PATHINFO_FILENAME);
        $filePath = realpath($file);
        
        $fp = fopen($filePath,'rb');
        
        if(!$filePath || !$fp){
            header('HTTP/1.1 404 Not Found');
            echo "Error: 404 Not Found.(server file path error)<!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding --><!-- Padding -->";
            exit;
        }
        
        $fileName = $fileName .'.'. pathinfo($filePath,PATHINFO_EXTENSION);
        $encoded_filename = urlencode($fileName);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        
        header('HTTP/1.1 200 OK');
        header( "Pragma: public" );
        header( "Expires: 0" );
        header("Content-type: application/octet-stream");
        header("Content-Length: ".filesize($filePath));
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($filePath));
        
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
        }
        
        ob_end_clean();
        // 输出文件内容 <--有些情况可能需要调用此函数
        fpassthru($fp);
        exit;
    }

    function is_mobile($str) { 
        return preg_match("/^1([38][0-9]|4[579]|5[0-3,5-9]|6[6]|7[0135678]|9[89])\d{8}$/", $str);  
    }

    function setRegSmsCache($data_cache) {
        Cache::set('sms_' . $data_cache['mobile'], $data_cache, 300);
    }

    function generate_code($length = 4) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }

    function checkRegSms($mobile, $code = false) {
	    if (!$mobile) return false;
	    if ($code === false) {   //判断60秒以内是否重复发送
	        if (!Cache::has('sms_' . $mobile)) return true;
	        if (Cache::get('sms_' . $mobile)['times'] > time()) {
	            return false;
	        } else {
	            return true;
	        }
	    } else {  //判断验证码是否输入正确
	        if (!Cache::has('sms_' . $mobile)) return false;
	        if (Cache::get('sms_' . $mobile)['code'] == $code) {
	            return true;
	        } else {
	            return false;
	        }
	    }
	}

		
}
