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
 class TaokeAPI3 extends Controller
 {

 
 	public function index()
 	{
 		echo "非法访问";
 	}
 	
 	//获取今日上线的产品
 	public function getxb()
 	{

 		$surl = "http://v2.api.haodanku.com/excellent_editor/apikey/".Config::get('HDK_APPKEY')."/back/50"; 
 		$rediskey ="KEY_JBWAS";
 		$datastr = Cache::store('redis')->get($rediskey);
 		if(true){
 			$datastr = curl($surl);
 			$clientmsg = json_decode($datastr);
 			if(count($clientmsg->data) > 0){
 		 		//缓存 10分钟
 				$newarray = array();
 				foreach ($clientmsg->data as $key => $value) {
 						$datainfo['textstr'] = (string)$value->itemshorttitle;
 						$datainfo['textstr'] = $datainfo['textstr']." </br>原价:".(string)$value->itemprice." </br>券后价:".(string)$value->itemendprice;

 						$datainfo['itemid'] = (string)$value->itemid;
 						$datainfo['imgurl'] = (string)$value->itempic."_500x500.jpg";
 						$datainfo['imgurl'] = urldecode($datainfo['imgurl']);
 						$datainfo['textstr1'] =(string)$value->copy_text;
 						$datainfo['headurl'] = "http://nn.52juanmi.com/20190707003507ee9d95744.jpg";

 						$newprice =  (float)$value->itemendprice;
 						if ($newprice < 15) {
		                    $datainfo['headurl'] = "http://nn.52juanmi.com/20190707004512f6e130844.jpg";
		                }
		                else if ($newprice > 15 && $newprice <= 35)
		                {
		                    $datainfo['headurl']  = "http://nn.52juanmi.com/201907070036315f9307835.jpeg";
		                }
		                else if ($newprice > 35 && $newprice <= 80)
		                {
		                    $datainfo['headurl'] = "http://nn.52juanmi.com/20190707004627183460794.jpg";
		                }

 					 	array_push($newarray, $datainfo);
 					}
 					Cache::store('redis')->set($rediskey, json_encode($newarray), 60 * 60);
 					shuffle($newarray);
 		 			return $this->renderSuccess($newarray[0]); 
 				 
 		 	}
 		 	else{
 		 		return $this->renderError($clientmsg->msg);
 		 	}
 		}else {
 		 	$redis_data = json_decode($datastr,true);
 		 	shuffle($redis_data);
 		}
 		 
 		return $this->renderSuccess($redis_data[0]);

 	}

 	 

 } 	
  
