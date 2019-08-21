<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
use app\common\taobao\TkManager;
use app\common\taobao\TkFunction;
use app\common\taobao\TkConvert;
use app\common\model\Catelist as CateModel;
 
/****************************************

好单库的API接口
地址：https://www.haodanku.com/api
特点: 有些接口数据量有点少

*****************************************/


/**
 * 第三方淘客接口获取
 * Class OtherAPI
 * @package app\api\controller
 */
 class TaokeAPI2 extends Controller
 {

 
 	public function index()
 	{
 		echo "非法访问";
 	}
 	
 	//好单数据接口 9.9包邮数据  
 	/*接口：	
		如果获取全部商品数据建议请求参数nav直接是3来获取全部商品数据，拉取全量数据是时尽量避开凌晨时段，这个时段由于检查失效下架比较多。
     */
 	public function get99you($minid = 1, $cateid = 0,$type=2)
 	{

 		 $urls ='http://v2.api.haodanku.com/column/apikey/'.Config::get('HDK_APPKEY').'/type/'.$type.'/back/20/min_id/'.$minid.'/cid/'.$cateid;
 		 $rediskey = CKEY1."_".$cateid."_".$minid.'_'.$type;
 		 $datastr = Cache::store('redis')->get($rediskey);
 		 if(!$datastr){
 			$datastr = curl($urls);
 			$clientmsg = json_decode($datastr);
 			if($clientmsg->code == 1){
 		 		//缓存 10分钟
 				$newarray = array();
 				$datlist = $clientmsg->data;
 				if(count($datlist) > 0){
 					foreach ($datlist as $key => $value) {
 					 	array_push($newarray, TkConvert::ConvertHDk($value));
 					}
 					$datas  = array();
 					$datas['min_id'] = $clientmsg->min_id;
 					$datas['data'] = $newarray;
 					Cache::store('redis')->set($rediskey, json_encode($datas), 60 * 10); 

 		 			return $this->renderSuccess($datas);
 				}
 		 	}
 		 	else{
 		 		return $this->renderError($clientmsg["msg"]);
 		 	}
 		}else {
 		 	$redis_data = json_decode($datastr,true);
 		 }
 		 $cl  = array();
 		 $cl["min_id"]  = $redis_data["min_id"];
 		 $cl["data"]  = $redis_data["data"];
 		 return $this->renderSuccess($cl);
 	} 	


 	//好单库 数据接口 爆款排行榜
 	//绑定数据接口''
 	//ale_type=1是实时销量榜（近2小时销量），type=2是今日爆单榜，type=3是昨日爆单榜，type=4是出单指数版
 	public function rankbk($minid = 1,$type=2){

 		$urls ='http://v2.api.haodanku.com/sales_list/apikey/'.Config::get('HDK_APPKEY').'/sale_type/2/cid/'.$type;
 		$rediskey = CKEY4."_".$minid.'_'.$type;
 		$datastr = Cache::store('redis')->get($rediskey);
 		if(!$datastr){
 			$datastr = curl($urls);
 			$clientmsg = json_decode($datastr);
 			if($clientmsg->code == 1){
 		 		//缓存 10分钟
 				$newarray = array();
 				$datlist = $clientmsg->data;
 				if(count($datlist) > 0){
 					foreach ($datlist as $key => $value) {
 					 	array_push($newarray, TkConvert::ConvertHDk($value));
 					}
 					$datas  = array();
 					$datas['min_id'] = $clientmsg->min_id;
 					$datas['data'] = $newarray;
 					Cache::store('redis')->set($rediskey, json_encode($datas), 60 * 10); 

 		 			return $this->renderSuccess($datas);
 				}
 		 	}
 		 	else{
 		 		return $this->renderError($clientmsg["msg"]);
 		 	}
 		}else {
 		 	$redis_data = json_decode($datastr,true);
 		 }
 		 $cl  = array();
 		 $cl["min_id"]  = $redis_data["min_id"];
 		 $cl["data"]  = $redis_data["data"];
 		 return $this->renderSuccess($cl);
 	}

 	
 	//好单库数据接口 (超值大牌API)
 	//http://v2.api.haodanku.com/brand/apikey/你的apikey/back/20/min_id/1
 	//品牌分类：默认选择全部分类，1是母婴童品，2百变女装，3是食品酒水，4是居家日用，5是美妆洗护，6是品质男装，7是舒适内衣，8是箱包配饰，9是男女鞋靴，10 是宠物用品，11是数码家电，12是车品文体
 	public function branditem($minid = 1,$type=0){
 		//类型转换
 		$catetype = array();
 		$catetype[0] = 0;
 		$catetype[1] = 2;
 		$catetype[2] = 6;
 		$catetype[3] = 9;
 		$catetype[4] = 8;
 		$catetype[5] = 1;
 		$catetype[6] = 7;
 		$catetype[7] = 5;
 		$catetype[8] = 8;
 		$catetype[9] = 4;
 		$catetype[10] = 12;
 		$catetype[11] = 11;
 		$catetype[12] = 11;
 		$catetype[13] = 3;
 		$catetype[14] = 10;
 		$b_cid = $catetype[$type];

 		$urls ='http://v2.api.haodanku.com/brand/apikey/'.Config::get('HDK_APPKEY').'/min_id/'.$minid.'/brandcat/'.$b_cid;
 		$rediskey = CKEY5."_".$minid.'_'.$b_cid;
 		$datastr = Cache::store('redis')->get($rediskey);
 		if(!$datastr){
 			$datastr = curl($urls);
 			$clientmsg = json_decode($datastr,true);
 			if($clientmsg["code"] == 1){
 		 		//缓存一小时
 		 		Cache::store('redis')->set($rediskey, $datastr, 60 * 60); 
 		 	}
 		 	else{
 		 		return $this->renderError($clientmsg["msg"]);
 		 	}
 		}else {
 		 	$clientmsg = json_decode($datastr,true);
 		 }
 		 $cl  = array();
 		 $cl["min_id"]  = $clientmsg["min_id"];
 		 $cl["data"]  = $clientmsg["data"];
 		 return $this->renderSuccess($cl);
 	}

 	//拉取最新商品；
 	//APP 首页底部商品展示  1-实时跑单的商品
 	//接口说明地址：https://www.haodanku.com/api/detail/show/1.html
 	public function getitemlist2($minid = 1,$type=0){
 		$urls ='http://v2.api.haodanku.com/itemlist/apikey/'.Config::get('HDK_APPKEY').'/nav/1/cid/0/back/20/min_id/'.$minid.'/sale_min/10/tkrates_min/30';
 		$rediskey = "APPINDEX_".$minid;
 		$datastr = Cache::store('redis')->get($rediskey);
 		if(!$datastr){
 			$datastr = curl($urls);
 			$clientmsg = json_decode($datastr);
 			if($clientmsg->code == 1){
 		 		//缓存 10分钟
 				$newarray = array();
 				$datlist = $clientmsg->data;
 				if(count($datlist) > 0){
 					foreach ($datlist as $key => $value) {
 					 	array_push($newarray, TkConvert::ConvertHDk($value));
 					}
 					$datas  = array();
 					$datas['min_id'] = $clientmsg->min_id;
 					$datas['data'] = $newarray;
 					Cache::store('redis')->set($rediskey, json_encode($datas), 60 * 10); 

 		 			return $this->renderSuccess($datas);
 				}
 		 	}
 		 	else{
 		 		return $this->renderError($clientmsg["msg"]);
 		 	}
 		}else {
 		 	$redis_data = json_decode($datastr,true);
 		 }
 		 $cl  = array();
 		 $cl["min_id"]  = $redis_data["min_id"];
 		 $cl["data"]  = $redis_data["data"];
 		 return $this->renderSuccess($cl);
 	}	

 	//APP 首页底部商品展示  1-抖音视频地址
 	//接口说明地址：http://v2.api.haodanku.com/get_trill_data/apikey/你的apikey/min_id/1/back/10/cat_id/1
 	public function getdy($minid = 1){
 		$urls ='http://v2.api.haodanku.com/get_trill_data/apikey/'.Config::get('HDK_APPKEY').'/min_id/'.$minid.'/back/50/cat_id/0';

 		$datastr = curl($urls);
 		$clientmsg = json_decode($datastr);
 		if($clientmsg->code == 1)
 		{
 		 	//缓存 10分钟
 			$newarray = array();
 			$datlist = $clientmsg->data;
 			if(count($datlist) > 0){
 				foreach ($datlist as $key => $value) {
 					$info["itemid"] = $value->itemid;
 					$info["itemtitle"] = $value->itemtitle;
 					$info["itemdesc"] = $value->itemdesc;
 					$info["itemprice"] = $value->itemprice;
 					$info["itemsale"] = $value->itemsale;
 					$info["itempic"] = $value->itempic;
 					$info["itemendprice"] = $value->itemendprice;
 					$info["tkrates"] = $value->tkrates;
 					$info["couponmoney"] = $value->couponmoney;
 					$info["dy_video_url"] = $value->dy_video_url;
 					$info["dy_video_like_count"] = $value->dy_video_like_count;
 					$info["dy_video_title"] = $value->dy_video_title;
 					array_push($newarray, $info);
 				}
 				shuffle($newarray);
 				$datas  = array();
 				$datas['min_id'] = $clientmsg->min_id;
 				$datas['data'] = $newarray;
 		 		return $this->renderSuccess($datas);
 		 	}
	 		 
	 	}else
	 	{
	 		return $this->renderError("暂无数据");
	 	}
	 }



 } 	
  
