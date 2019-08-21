<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
 
 

/**
 * 第三方淘客接口获取
 * Class OtherAPI
 * @package app\api\controller
 */
 class OtherAPI extends Controller
 {

 	public function index()
 	{
 		echo "非法访问";
 	}

 	 

 	//通过淘宝获取商品分类
 	public function itemtype()
 	{

		$c = new \TopClient;
		$c->appkey = Config::get('LM_APPKEY');
		$c->secretKey = Config::get('LM_APPSECRET');
		$req = new \ItempropvaluesGetRequest;
		$req->setFields("cid,pid,prop_name,vid,name,name_alias,status,sort_order");
		$req->setCid("50010538");
		//$req->setPvs("20561:1234");
		//$req->setDatetime("2000-01-01 00:00:00");
		//$req->setType("1");
		//$req->setAttrKeys("item_must_image");
		$resp = $c->execute($req);

		dump($resp);
 	}


 	//通过淘宝H5接口；来抓取商品的简要信息
 	public function h5iteminfo($itemid){
 		$urls ="https://ms.m.taobao.com/cache/mtop.wdetail.getItemDescx/4.1/?data=%7B%22item_num_id%22%3A%22".$itemid."%22%7D";
 		 
 		 $datastr = curl($urls);
 		 $dataobj = json_decode($datastr,true);
 		 //var_dump($dataobj);
 		 if(isset($dataobj['data']['images'])){
 		 	return $this->renderSuccess($dataobj['data']["images"]);
 		 }else
 		 {
 		 	return $this->renderError("未获取物品描述2");
 		 }
 	 
 	}

 	//通过大淘客获取详情
 	public function iteminfodtk($itemid){
 		$host = 'https://openapi.dataoke.com/api/goods/get-goods-details';
		$appKey = Config::get('DTK_APPKEY2');
		$appSecret = Config::get('DTK_SECRET');

		//默认必传参数
		$data = [
    		'appKey' => $appKey,
    		'version' => 'v1.0.0',
    		'goodsId'=>$itemid,
		];
		$data['sign'] = makeSign($data,$appSecret);;
		$url = $host .'?'. http_build_query($data);
		$datastr = curl($url);
		$dataobj = json_decode($datastr,true);
		if($dataobj['code'] == 0 && $dataobj['data']['detailPics'] !='')
			return $this->renderSuccess($dataobj['data']['detailPics']);
		else
			return $this->renderError("未获取物品描述");
 	}

 


 }