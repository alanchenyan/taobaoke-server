<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
use app\common\model\Syscfg as SysModel;
 
/**
 * 淘宝授权管理
 * Class OtherAPI
 * @package app\api\controller
 */
 class Syscfg extends Controller
 {

 	//淘宝客渠道信息备案
 	//文档地址:https://open.taobao.com/api.htm?docId=37988&docType=2&scopeId=14474
 	public function getsrid()
 	{ 
 	}	

 	//获得系统配置参数
 	public function getsys($cid)
 	{	
 		$model = new SysModel();
 		$sysinfo = $model ->getSysinfo($cid);
 		if($sysinfo)
 		{
 			$sysinfo['home_flash'] = $this->load_home_falsh();
 			$sysinfo['active_html'] = $this->load_active_banner();
 			$sysinfo['nav_html'] = $this->load_nav_html();
 			return $this->renderSuccess($sysinfo);
 		}
 		return $this->renderError('非法的系统请求'); 
 	}


 	//首页幻灯数据
 	//imgurl 图片地址
 	//linktype: 1:物品ID 2-网络地址 3-内部窗体
 	//maincolor： 图片的主色号
 	//linkurl: 打开的目标地址 配合linktype
 	private function load_home_falsh()
 	{
 		$data = array();
 		$data[0] = array('imgurl' =>'http://nn.52juanmi.com/20190502213349baa321981.jpg','maincolor' =>'#94F2FA',
 						  'linktype'=>3,'linkurl'=>'./rank/frame_rank_title','title'=>'每日排行','dataid'=>0);

 		$data[1] = array('imgurl' =>'http://nn.52juanmi.com/2019050221463921a563685.png',
 							'maincolor' =>'#F53E06','linktype'=>3,'linkurl'=>'jm_title','title'=>'低价好货','dataid'=>3);

 		$data[2] = array('imgurl' =>'http://nn.52juanmi.com/201905022317084b2d40904.jpg',
 							'maincolor' =>'#FC7241','linktype'=>2,'linkurl'=>'https://jupage.taobao.com/wow/tqg/act/webhome','title'=>'限时抢','dataid'=>0);

 		$data[3] = array('imgurl' =>'http://nn.52juanmi.com/201905022319463f79a4237.jpg',
 							'maincolor' =>'#C7D396','linktype'=>3,'linkurl'=>'jmgoods/frame_mother_head','title'=>'母婴生活','dataid'=>5);

 		return $data;
 	}


 	//首页活动广告代码配置
 	private function load_active_banner(){
 		$datastr = Cache::store('redis')->get('active_banner');
 		return $datastr;
 	}
 		//首页活动广告代码配置
 	private function load_nav_html(){
 		$datastr = Cache::store('redis')->get('nav_html');
 		return $datastr;
 	}

 	//首页导航菜单
 	//imgurl 图片地址
 	//linktype: 1:物品ID 2-网络地址 3-内部窗体
 	//maincolor： 图片的主色号
 	//linkurl: 打开的目标地址 配合linktype
 	private function load_home_nav()
 	{
 		$data = array();
 		$data[0] = array('imgurl' =>'../image/frame0/head_bn1.jpg','maincolor' =>'#F9A42F',
 						  'linktype'=>2,'linkurl'=>'https://mos.m.taobao.com/union/supercoupon?pid=mm_10898238_266600236_78469400021','title'=>'我是标题栏','dataid'=>0);

 		$data[1] = array('imgurl' =>'../image/frame0/head_bn2.jpg',
 							'maincolor' =>'#BD212F','linktype'=>2,'linkurl'=>'https://jupage.taobao.com/wow/tqg/act/webhome','title'=>'淘抢购','dataid'=>0);

 		$data[2] = array('imgurl' =>'../image/frame0/head_bn3.jpg',
 							'maincolor' =>'#3C0103','linktype'=>2,'linkurl'=>'#','title'=>'我是标题栏','dataid'=>0);

 		$data[3] = array('imgurl' =>'../image/frame0/head_bn4.jpg',
 							'maincolor' =>'#3C0103','linktype'=>3,'linkurl'=>'jm_title_cate2_frame','title'=>'9.9包邮','dataid'=>0);

 		return $data;
 	}

 	//获取分享的URL-短网址
 	public function get_share_url($isflag = false){
 		$userinfo = $this->getUser();
 		$ssurls = Cache::store('redis')->get('ShortUrl_'.$userinfo['userid']);
 		if($ssurls && !$isflag)
 			return $this->renderSuccess($ssurls);
 		if($ssurls && $isflag)
 			return $ssurls;

 		$redirect=Config::get('SYSTEM_DOMAIN')."index.php/api/wxapi/downurl";
 		$pageurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.Config::get('WX_GZ_APPID').'&redirect_uri='.$redirect.'&response_type=code&scope=snsapi_userinfo&state='.$userinfo['yqcode'].'#wechat_redirect';
 		$host = 'https://dwz.cn';
 		$path = '/admin/v2/create';
 		$url = $host . $path;
 		$method = 'POST';
 		$content_type = 'application/json';
    	// TODO: 设置Token
    	$token = Config::get('BAIDU_TOKEN');
    	// TODO：设置待注册长网址
    	$bodys = array('url'=>$pageurl); 
    	// 配置headers 
    	$headers = array('Content-Type:'.$content_type, 'Token:'.$token);
    
    	// 创建连接
    	$curl = curl_init($url);
    	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($curl, CURLOPT_FAILONERROR, false);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HEADER, false);
    	curl_setopt($curl, CURLOPT_POST, true);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($bodys));
    
    	// 发送请求
    	$response = curl_exec($curl);
    	curl_close($curl);
    	$urldata = json_decode($response,true);
    	if($urldata['Code'] == 0){
    		Cache::store('redis')->set('ShortUrl_'.$userinfo['userid'],$urldata['ShortUrl'],3600);
    		if(!$isflag)
    			return $this->renderSuccess($urldata['ShortUrl']);
    		else
    			return $urldata['ShortUrl'];
    	}else
    	{
    		if(!$isflag)
    			return $this->renderSuccess($pageurl);
    		else
    			return $pageurl;
    	}
    
     
     
 	}

 	//获取分享图片的列表
 	//X,Y 坐标
 	//H,W 长 宽
 	//x1,y1, 邀请码的坐标
 	public function shareims(){
 		$userinfo = $this->getUser();

 		//$pageurl = "http://app.52juanmi.com/h5/shareapp/index.html?yqm=".$userinfo['yqcode']."&from=singlemessage";
 		//$redirect=Config::get('SYSTEM_DOMAIN')."index.php/api/wxapi/downurl";
 		//$pageurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.Config::get('WX_GZ_APPID').'&redirect_uri='.$redirect.'&response_type=code&scope=snsapi_userinfo&state='.$userinfo['yqcode'].'#wechat_redirect';
 		$pageurl = $this->get_share_url(1);
 		$dataimg = qrcode64($pageurl);
 		$data = array();
	$data[0] = array('url' =>'http://nn.52juanmi.com/2019042920481266c866680.jpg','x' =>355,'y'=>1400,'h'=>385,'w'=>380,'x1' =>375,'y1'=>1890,'h1'=>33,'w1'=>320);
	$data[1] = array('url' =>'http://nn.52juanmi.com/201904291326308b3955681.jpg','x' =>360,'y'=>1010,'h'=>375,'w'=>375,'x1' =>375,'y1'=>1890,'h1'=>33,'w1'=>320);
	$data[2] = array('url' =>'http://nn.52juanmi.com/2019042913262440b954930.jpg','x' =>360,'y'=>1350,'h'=>340,'w'=>340,'x1' =>375,'y1'=>1890,'h1'=>33,'w1'=>320);
	$data[3] = array('url' =>'http://nn.52juanmi.com/20190429132617576446653.jpg','x' =>367,'y'=>1430,'h'=>363,'w'=>363,'x1' =>375,'y1'=>1870,'h1'=>33,'w1'=>320);
	$msg['erweima']= $dataimg;
	$msg['imgs']= $data;
	return $this->renderSuccess($msg);
 	}
 

 }
