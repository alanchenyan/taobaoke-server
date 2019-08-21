<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
use app\common\taobao\TkManager;
use app\common\taobao\TkFunction;
use app\common\taobao\TkConvert;
use app\common\taobao\TkDesc;
use app\common\model\Catelist as CateModel;
 
 

/**
 * 第三方淘客接口获取
 * Class OtherAPI
 * @package app\api\controller
 */
 class TaokeAPI extends Controller
 {

 
 	public function index()
 	{
 		echo "非法访问";
 	}
 	
 	//淘宝客商品查询
 	public function desc($itemid)
 	{ 
		$tkdesc = new TkDesc();
		$obj = $tkdesc->getDesc($itemid);
		return $this->renderSuccess($obj);
 	}




 	//淘宝客接口：taobao.tbk.ju.tqg.get
 	//淘抢购数据接口；（限时抢购）
 	public function getqgitem($pagenumber = 1, $starttimie,$endtime)
 	{
 		$currdate = date('Y-m-d ', time());
 		if($starttimie == 0 || !$starttimie){
 			$starttimie = $currdate . "00:00:00";
 		}else
 		{
 			if($starttimie < 10)
 				$starttimie = $currdate . "0".$starttimie.":00:00";
 			else
 				$starttimie = $currdate . $starttimie.":00:00";
 		}
 		if($endtime < 10)
 			$endtime = $currdate . "0".$endtime.":00:00";
 		else
 			$endtime = $currdate . $endtime.":00:00";
		$c = TkManager::CreateTKApiAPP();
		$req = new \TbkJuTqgGetRequest;
		$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
		$req->setFields("click_url,pic_url,reserve_price,zk_final_price,total_amount,sold_num,title,category_name,start_time,end_time");
		$req->setStartTime($starttimie);
		$req->setEndTime($endtime);
		$req->setPageNo($pagenumber);
		$req->setPageSize("20");
		$resp = $c->execute($req);
		return $this->renderSuccess($resp);
 	}

 
 	//获取选品库内容数据
 	//选品库的ID
 	//生活日用：19298210   淘宝漏洞：19252176  吃货必备：19316637  第二件半价：19369398
 	public function getfavorites($fid,$pagenumber = 1)
 	{
 		$c = TkManager::CreateTKApiAPP();
		$req = new \TbkUatmFavoritesItemGetRequest;
		$req->setPlatform("1");
		$req->setPageSize("20");
		$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
		$req->setFavoritesId($fid);
		$req->setPageNo($pagenumber);
		$req->setFields("num_iid,title,pict_url,zk_final_price,user_type,provcity,item_url,volume,nick,shop_title,zk_final_price_wap,event_start_time,event_end_time,tk_rate,status,type,coupon_info,coupon_amount,coupon_start_time,coupon_end_time");
		$resp = $c->execute($req);
		$reslut = $resp->results;
		$newarray = array();
		$lenght = count($reslut->uatm_tbk_item);
		if($lenght > 0){
			for ($i=0; $i < $lenght; $i++) { 
			 	$info = $reslut->uatm_tbk_item[$i];
			 	array_push($newarray, TkConvert::ConvertTkXP($info));

			}
			return $this->renderSuccess($newarray);
		}
		 
	 
		return $this->renderError('没有新数据');
 	}

 	public function  getmaterialgoods($devicevalue = "",$pagenumber = 1,$materid){
 		$parms = (object)array();
 		$parms->mid = $materid; //猜你喜欢
 		$parms->device = $devicevalue;
 		$parms->devicetype = "UTDID";
 		$parms->pagenum = $pagenumber;
 		$parms->pagesize = 20;
 		$dataobj = $this->materialFun($parms);
 		$newarray = array();
 		if($dataobj->result_list)
 		{
 			$datlist = $dataobj->result_list->map_data;
 			if(count($datlist) > 0){
 				foreach ($datlist as $key => $value) {
 					array_push($newarray, TkConvert::ConvertTk($value));
 				}

 				return $this->renderSuccess($newarray);
 			}
 		}
 		return $this->renderError('没有新数据');
 	}

  

 	//获取商品详细信息
 	public function getgood($itemid){
 		$good_info = $this->getItemDetail($itemid);
 		if(!$good_info){
 			return $this->renderError("商品ID:".$itemid.' 不存在',$itemid);
 		}
 		$good_info = TkConvert::ConvertTk($good_info,true);
 		return $this->renderSuccess($good_info);
 	}

 	//获取宝贝简要数据
 	public function getgood2($itemid)
 	{
 		$good_info = $this->getItemData($itemid);
 		if(!$good_info){
 			return $this->renderError("商品ID:".$itemid.' 不存在',$itemid);
 		}
 		//$good_info = TkConvert::ConvertTk($good_info);
 		return $this->renderSuccess($good_info);
 	}

 	//获取宝贝 的高佣连接（券二合一）
 	//自购专用的；
 	public function geturl($itemid){
 		$user = $this->getUser();   // 用户信息
 		if(!$user)
 			return null;
 		//检查是否与渠道ID
 		if($user['relationid'] == 0){
 			return $this->renderError('请先做渠道备案'); 
 		}

 		$c = TkManager::CreateTKApiAPP();
 		$req = new \TbkDgMaterialOptionalRequest;
	 	$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
	 	$url = "http://item.taobao.com/item.htm?id=".$itemid;
		$req->setQ($url);
		$resp = $c->execute($req);
		if($resp->total_results <=0 )
		{
			return $this->renderError("该商品暂未加入推广计划",$itemid);
		}
		$iteminfo = $resp->result_list->map_data[0];
		$client =   array();
		$client['linkurl'] = (string)$iteminfo->coupon_share_url;
		$client['linkurl2'] = (string)$iteminfo->url."&relationId=".$user['relationid'];
		if($client['linkurl'] == ""){
			$client['linkurl'] = $client['linkurl2'];
		}
		else{
			$client['linkurl'] = $client['linkurl']."&relationId=".$user['relationid'];
		}

		$client['mkt'] = (int)$iteminfo->include_mkt;
		$client['id'] = (string)$iteminfo->item_id;

		return $this->renderSuccess($client);
 	}
 	public function geturl2($itemid){
 		$user = $this->getUser();   // 用户信息
 		if(!$user)
 			return null;

 		$c = TkManager::CreateTKApiAPP();
 		$req = new \TbkDgMaterialOptionalRequest;
	 	$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
	 	$url = "http://item.taobao.com/item.htm?id=".$itemid;
		$req->setQ($url);
		$resp = $c->execute($req);
		if($resp->total_results <=0 ){
			return $this->renderError("该商品暂未加入推广计划",$itemid);
		}
		$iteminfo = $resp->result_list->map_data[0];
		$client =   array();
		$client['linkurl'] = (string)$iteminfo->coupon_share_url;
		$client['linkurl2'] = (string)$iteminfo->url;
		if($client['linkurl'] == ""){
			$client['linkurl'] = $client['linkurl2'];
		}
		else{
			$client['linkurl'] = $client['linkurl'];
		}

		$client['mkt'] = (int)$iteminfo->include_mkt;
		$client['id'] = (string)$iteminfo->item_id;

		return $this->renderSuccess($client);
 	}

 	//品牌物料   
 	public function getbandgoods($devicevalue = "",$pagenumber = 1,$cateid = 0)
 	{	
 		//对应物料ID
 		$catetype = array();
 		$catetype[0] = 3786;
 		$catetype[1] = 3788;
 		$catetype[2] = 3790;
 		$catetype[3] = 3796;
 		$catetype[4] = 3796;
 		$catetype[5] = 3789;
 		$catetype[6] = 3787;
 		$catetype[7] = 3794;
 		$catetype[8] = 3796;
 		$catetype[9] = 3792;
 		$catetype[10] = 3795;
 		$catetype[11] = 3793;
 		$catetype[12] = 3793;
 		$catetype[13] = 3791;
 		$catetype[14] = 3786;
 		$material_id  = $catetype[$cateid];

 		$parms = (object)array();
 		$parms->mid = $material_id; //品牌物料
 		$parms->device = $devicevalue;
 		$parms->devicetype = "UTDID";
 		$parms->pagenum = $pagenumber;
 		$parms->pagesize = 20;

 		//重复分类；按照第10页开始吧；暂时这样
 		if($cateid == 3 || $cateid == 4 || $cateid == 8 ){
 			 $parms->pagenum = ($pagenumber+10);
 		}
 		 
 		$dataobj = $this->materialFun($parms);
 		return $this->renderSuccess($dataobj);
 	}

 	//获取分类数据
 	//taobao.tbk.dg.material.optional( 通用物料搜索API（导购） )
 	public function getitembycate($devicevalue = "",
 								  $pagenumber = 1,
 								  $cateid = 0,	//分类ID列表
 								  $sorttype = 0, //排序类型 1价格 2佣金 3销量
 								  $sorttype_sub = 0  // 0 升序 1降序
 								  )
 	{

 		//判断分类ID 是否存在
 		$model = new CateModel();
 		$cateinfo = $model->getCateInfo($cateid);
 		if(!$cateinfo){
 			return $this->renderError("找不到分类",$cateid);
 		}
 		$c = TkManager::CreateTKApiAPP();
 		$req = new \TbkDgMaterialOptionalRequest;
		$req->setPageSize(20);
		$req->setEndPrice("10000");
		
		//设置查询词；没有配置分类时候；设置
		if(intval($cateinfo['cid']) == 0 && $cateinfo['pid'] != 0 ){
			$req->setQ($cateinfo['name']);
			//找到父类的 cid；作用: 在父类的cid范围内查询关键次
			$p_info = $model->getCateInfo($cateinfo['pid']);
			$req->setCat($p_info['cid']);
		}
		else{
			$req->setCat($cateinfo['cid']);
		}
		 

		/* 推广位ID */
		$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
		$req->setPageNo($pagenumber);
		$req->setDeviceValue($devicevalue);
		$req->setDeviceEncrypt("MD5");
		$req->setDeviceType("UTDID");
		//按累计推广排序 total_sales（销量） tk_rate(佣金比例) tk_total_commi(总支出) 价格(price)
		$sort_data = TkFunction::GetTkSort($sorttype,$sorttype_sub);
		$req->setSort($sort_data); 		
		//加入消保
		$req->setNeedPrepay("true");
		$req->setHasCoupon("true");
		//包邮
		$req->setNeedFreeShipment("true");
		//好评率大于行业估值
		$req->setIncludeGoodRate("true");
		//商品筛选(特定媒体支持)-成交转化是否高于行业均值。True表示大于等于，false或不设置表示不限
		$req->setIncludePayRate30("true");
		//商品筛选(特定媒体支持)-店铺dsr评分。筛选大于等于当前设置的店铺dsr评分的商品0-50000之间
		$req->setStartDsr("10");
		$dataobj = $c->execute($req);

		$newarray = array();
 		if($dataobj->result_list)
 		{
 			$datlist = $dataobj->result_list->map_data;
 			if(count($datlist) > 0){
 				foreach ($datlist as $key => $value) {
 					array_push($newarray, TkConvert::ConvertTk($value,true));
 				}

 				return $this->renderSuccess($newarray);
 			}
 		}
		return $this->renderError('没有新物品'); 
 
 	}

 	//关键词查询物品
 	public function getitembykey($devicevalue = "",
 								  $pagenumber = 1,
 								  $keystype){

 		//优先解析商品
 		///$urls ='https://api.zhetaoke.com:10001/api/open_shangpin_id.ashx?appkey='.Config::get('ZTK_APPKEY').'&sid=397&content='.$strcode.'&type=1';

 		$c = TkManager::CreateTKApiAPP();
 		$req = new \TbkDgMaterialOptionalRequest;
		$req->setPageSize(20);
		$req->setPageNo($pagenumber);
		$req->setEndPrice("10000");
		$req->setDeviceValue($devicevalue);
		$req->setHasCoupon("true");
		if($keystype == 1){
			//潮流
			$req->setQ('潮流 搭配');
		}
		else if($keystype == 2){
			//母婴 宝妈
			$req->setQ('宝妈 母婴 儿童 幼儿 妈妈');
		}
		else if($keystype == 3){
			//网红 抖音
			$req->setQ('抖音 网红 神器');
		}
		else if($keystype == 4){
			//学生
			$req->setQ('学生');
		}
		$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
		$req->setNeedFreeShipment("true");//包邮
		$req->setNeedPrepay("true");
		$req->setIncludeGoodRate("true");
		$req->setIncludeRfdRate("true");
		$req->setStartTkRate("1500"); //15$的佣金比例
		$req->setSort('total_sales_des'); //默认照销量排行
		$req->setNpxLevel("2"); //-牛皮癣程度。取值：1不限，2无，3轻微
		$dataobj = $c->execute($req);
		$newarray = array();
		if($dataobj->result_list)
 		{
 			$datlist = $dataobj->result_list->map_data;
 			if(count($datlist) > 0){
 				foreach ($datlist as $key => $value) {
 					array_push($newarray, TkConvert::ConvertTk($value,true));
 				}
 				return $this->renderSuccess($newarray);
 			}
 		}
		return $this->renderError('没有新物品'); 
 	}


 	//全网搜索物品
 	public function serchwords($pagenumber = 1,
 								  $sorttype = 0,
 								  $sorttype_sub = 0){
 		$postdata = $this->request->post();
 		$keywords = $postdata['keywods'];
 		//https://api.zhetaoke.com:10001/api/open_taokouling_jiexi.ashx?appkey=e3e1cec00e704bda8f188d3990bc6321&sid=#sid#&content=￥TdJCbN68klT￥&type=1
 		$zheurl ='https://api.zhetaoke.com:10001/api/open_shangpin_id.ashx?appkey='.Config::get('ZTK_APPKEY').'&sid=397&content='.$keywords.'&type=1';
  
 		$datastr = curl($zheurl);
 		$codeitem = json_decode($datastr,true);
 		if(isset($codeitem['item_id'])){
 			$good_info = $this->getItemDetail($codeitem['item_id']);
	 		if($good_info){
	 			$newarray = array();	
	 			$good_info = TkConvert::ConvertTk($good_info,true);
	 			array_push($newarray, $good_info);
	 			return $this->renderSuccess($newarray);
	 		}
 		}
 		 
 		//echo $datastr;
 		//exit();
 		$c = TkManager::CreateTKApiAPP();
 		$req = new \TbkDgMaterialOptionalRequest;
		$req->setPageSize(20);
		$req->setPageNo($pagenumber);
 
		$req->setQ(urldecode($keywords));	
		$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
	 	if($sorttype != 0){
	 		$sort_data = TkFunction::GetTkSort($sorttype,$sorttype_sub);
			$req->setSort($sort_data); //默认照销量排行
	 	}
		$dataobj = $c->execute($req);
		$newarray = array();
		if($dataobj->result_list)
 		{
 			$datlist = $dataobj->result_list->map_data;
 			if(count($datlist) > 0){
 				foreach ($datlist as $key => $value) {
 					array_push($newarray, TkConvert::ConvertTk($value,true));
 				}
 				return $this->renderSuccess($newarray);
 			}
 		}
		return $this->renderError('没有更多的商品'); 

 	}

 

 	/* 通用物料导购
     * parms: 参数合集
     * { mid ：物料ID
         device：设备号加密后的值
         devicetype: 设备号类型：IMEI，或者IDFA，或者UTDID
         itemid： 物品ID 用于查询相似产品
		 pagenum: 分页编号
		 pagesize: 页面大小 
        }
 	*/
 	private function materialFun($parms)
 	{
		$c = TkManager::CreateTKApiAPP();
		$req = new \TbkDgOptimusMaterialRequest;
		$pagesize = $parms->pagesize == null?20:$parms->pagesize;
		$req->setPageSize($pagesize);
		/* 推广位ID */
		$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
		$req->setPageNo($parms->pagenum);
		$req->setMaterialId($parms->mid);
		$req->setDeviceValue($parms->device);

		$req->setDeviceEncrypt("MD5");
		$req->setDeviceType($parms->devicetype);
		$resp = $c->execute($req);
		return $resp;
 	}


 	//查找物品详情信息
 	//通过物料搜索查询；查询详细版本数据
 	private function getItemDetail($itemid){
 		$c = TkManager::CreateTKApiAPP();
 		$req = new \TbkDgMaterialOptionalRequest;
	 	$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
	 	$url = "http://item.taobao.com/item.htm?id=".$itemid;
		$req->setQ($url);
		$resp = $c->execute($req);
		if($resp->total_results <=0 )
		{
			return null;
		}
		return $resp->result_list->map_data[0];
 	}
 	
 	//商品简要数据查询
 	private function getItemData($itemid){
 		$c = TkManager::CreateTKApiAPP();
		$req = new \TbkItemInfoGetRequest;
		$req->setNumIids($itemid);
		$req->setPlatform("2");
		$req->setIp(Getip());
		return $c->execute($req);
 	}


 	//淘口令生成
 	//$rid: 渠道的rid； 如果要分享；必须带上RID；才能跟踪订单
 	 private function createkouling($itemid){
 		$user = $this->getUser();   // 用户信息
 		if(!$user)
 			return null;
 		//检查是否与渠道ID
 		if($user['relationid'] == 0){
 			return $this->renderError('请先做渠道备案'); 
 		}

 		$good_info = $this->getItemDetail($itemid);
 		if(!$good_info){
 			return $this->renderError("商品ID:".$itemid.' 不存在',$itemid);
 		}
 		$title = (string)$good_info->title;
 		$mainimg = (string)$good_info->pict_url;
 		$url = '';
 		//劵二合一地址；
 		if(isset($good_info->coupon_share_url)){
 			$url = (string)$good_info->coupon_share_url;
 		}else{
 			$url = (string)$good_info->url;
 		}
 		$url  = TkConvert::isImghttp($url);
 		//加上渠道ID ‘?relationId=1125526’
 		$url = $url."&relationId=".$user['relationid'];

 		//开始创建淘客令
 		$c = TkManager::CreateTKApiAPP();
		$req = new \TbkTpwdCreateRequest;
		$req->setText($title);
		$req->setUrl($url);
		$req->setLogo($good_info->pict_url);
		$resp = $c->execute($req);
		if(isset($resp->code)){
			return $this->renderError((string)$resp->sub_msg); 
		}

		$msg['url'] = $url;
		$msg['kouling'] = (string)$resp->data->model;
		$msg['title'] = $title;
		$msg['mainimg'] =$mainimg;
		$msg['iteminfo'] =TkConvert::ConvertTk($good_info);  
 		return $msg;
 	}

 	//获取淘口令
 	public function getkouling($itemid){
 		$itemkl=$this->createkouling($itemid);
 		if($itemkl ||  isset($itemkl['code']))
 			return $this->renderSuccess($itemkl);
 		else
 			return $this->renderError($itemkl['msg']); 
 	}

 	//获取分享物品的生成的二维码；
 	public function gershareewm($itemid){
 		$itemkl=$this->createkouling($itemid);
 
 		if(!$itemkl || isset($itemkl['code']) )
 			return $this->renderError($itemkl['msg']);  
 		else
 		{
 			//中间页面地址:
	 		$pageurl = Config::get('SYSTEM_DOMAIN')."h5/sharegoods/index.html";
	 		$pageurl = $pageurl ."?word=".$itemkl['kouling'];
	 		$pageurl = $pageurl ."&image=".$itemkl['mainimg'];
	 		$pageurl = $pageurl ."&title=".$itemkl['title'];
	 		$dataimg = qrcode64($pageurl);
	 		$msg["erweima"] = $dataimg;
	 		$msg["iteminfo"] = $itemkl['iteminfo'];
	 		$msg["kouling"] = $itemkl['kouling'];
	 		return $this->renderSuccess($msg);
 		}
 		 
 	}

 } 	
  
