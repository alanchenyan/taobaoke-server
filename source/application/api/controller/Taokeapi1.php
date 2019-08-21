<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
use app\common\taobao\TkManager;
use app\common\taobao\TkConvert;
use app\common\taobao\TkFunction;
use app\common\model\Catelist as CateModel;
 
/****************************************

折淘客的API接口
地址：http://www.zhetaoke.com/user/open/
特点: 有些接口数据量有点少
秘钥：e3e1cec00e704bda8f188d3990bc6321
*****************************************/


/**
 * 第三方淘客接口获取
 * Class OtherAPI
 * @package app\api\controller
 */
 class TaokeAPI1 extends Controller
 {

 
 	public function index()
 	{
 		echo "非法访问";
 	}
 	
 	//获取今日上线的产品
 	public function getnewitem($pageindex = 1,$sorttype,$sorttype_sub)
 	{
 		$sort_str = TkFunction::GetZTkSort($sorttype,$sorttype_sub);
 		$urls ='http://api.zhetaoke.com:10000/api/api_all.ashx?appkey='.Config::get('ZTK_APPKEY').'&page='.$pageindex.'&page_size=20&sort='.$sort_str.'&today=1';
 		$rediskey = CKEY6."_".$pageindex."_".$sort_str;

 		$datastr = Cache::store('redis')->get($rediskey);
 		if(!$datastr){
 			$datastr = curl($urls);
 			$clientmsg = json_decode($datastr);
 			$newarray = array();
 			if($clientmsg->status == 200){
 		 		//缓存10
 		 		$datlist = $clientmsg->content;
 				foreach ($datlist as $key => $value) {
 					array_push($newarray, TkConvert::ConvertZTk($value));
 				}	
 		 		Cache::store('redis')->set($rediskey, json_encode($newarray), 60 * 10);

 		 		return $this->renderSuccess($newarray); 
 		 	}
 		 	else{
 		 		return $this->renderError("接口消息出错");
 		 	}
 		}else {
 		 	$redis_data = json_decode($datastr);
 		}
 		return $this->renderSuccess($redis_data);

 	}

 	//获取热们搜索关键词 20个
 	public function gethotkey(){
		$urls ='https://api.zhetaoke.com:10001/api/api_guanjianci.ashx?appkey='.Config::get('ZTK_APPKEY').'&page=1&page_size=21&type=1';
		$datastr = curl($urls);	
		$clientmsg = json_decode($datastr);
		if($clientmsg->status == 200){
			$datlist = $clientmsg->content;
			return $this->renderSuccess($datlist); 
		}
		else{
 		 	return $this->renderError("获取数据接口失败");
 		}
  	}

  	//解析商品的编码
  	//支持淘口令文案、长链接、二合一链接、短链接、喵口令、新浪短链，可直接返回特殊优惠券
  	//注意 sid：397  改成自己的
  	public function getcodeitem($strcode){
  		 

  	}

 } 	
  
