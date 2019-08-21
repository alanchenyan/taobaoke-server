<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
use app\api\model\Brand as MedelBrand;

 

//品牌数据接口
 class Brandc extends Controller{
 	public function index()
 	{
 		echo "非法访问";
 	}


 	//获得品牌列表
 	public function getdata($cateid = 0)
 	{
 		$rediskey = JMCKEY1."_".$cateid;
 		$datastr = Cache::store('redis')->get($rediskey);

 		if($datastr){
 			return $this->renderSuccess(json_decode($datastr));
 		}
 		$brandModel = new MedelBrand();
 		$datalist = $brandModel->getList($cateid );
 
 		if(count($datalist) == 0)
 		{
 			return $this->renderError("没有找到品牌数据:".$cateid);
 		}
 		else
 		{
 			Cache::store('redis')->set($rediskey, json_encode($datalist), 60 * 60); 
 			return $this->renderSuccess($datalist);
 		}
 	}

	public function caiji($cateid){
		$brandModel = new MedelBrand();
 		//$datalist = $brandModel->caiji($cateid );
	}


 }