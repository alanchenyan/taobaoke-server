<?php

namespace app\api\controller;
 
use think\Config;
use think\Cache;
use app\common\taobao\TkConvert;
use app\common\taobao\PddApi;
use app\common\taobao\JdApi;
use app\common\taobao\TkFunction;
vendor('jd.JdSdk');

//品牌数据接口
 class Jdc extends Controller{
 	public function index()
 	{
 		echo "非法访问";
 	}


 	//获得分类
 	public function getcate()
 	{
 		
 		$rediskey = CKEY7;
 		$datastr = Cache::store('redis')->get($rediskey);
 		if(!$datastr){
 			$data['type'] = 'cname';
 			$data['apikey'] = Config::get('MAYI_KEY');
 			$datastr = request_post('http://api-gw.haojingke.com/index.php/api/platform/openapi',$data);
 			Cache::store('redis')->set($rediskey,$datastr,60*24);
 			return $this->renderSuccess($datastr);
 		}
 	 	return $this->renderSuccess($datastr);
 	}


 	//获取京东链接
 	//物品ID：$itemid
 	//$userid:用户的ID
 	//优惠券地址
 	public function getlinkurl(){

 		$postdata = $this->request->post();
 		$itemid = $postdata['itemid'];
 		$userid = $postdata['userid'];
	 
 		$itemurl = urlencode('https://item.jd.com/'.$itemid.'.html');
 		 $urls = 'https://api.open.21ds.cn/jd_api_v1/getitemcpsurl?apkey='.Config::get('MAYI_KEY').'&materialId='.$itemurl.'&unionId='.Config::get('JD_ID').'&positionId='.$userid;
 		 //带优惠券地址
 		if(isset($postdata['coupurl'])){
 			//echo "优惠券地址:".$postdata['coupurl'];
 			$culrr = str_replace("amp;","",$postdata['coupurl']);
 			$urls =$urls."&couponUrl=".urlencode($culrr);

 			//echo "优惠券地址:".$culrr;
 		 }
 		 
 		 $datastr = curl($urls);
 		 $data = json_decode($datastr,true);
 		 
 		 $dataimg = qrcode64($data['data']['shortURL']);
 		 $data['erweima'] =$dataimg; 
 		 return $this->renderSuccess($data);
 	}



 	//$cateid：频道id，：1-好券商品,2-京粉APP-jingdong.大咖推荐,3-小程序-jingdong.好券商品,4-京粉APP-jingdong.主题街1-jingdong.服装运动,5-京粉APP-jingdong.主题街2-jingdong.精选家电,6-京粉APP-jingdong.主题街3-jingdong.超市,7-京粉APP-jingdong.主题街4-jingdong.居家生活,10-9.9专区,11-品牌好货-jingdong.潮流范儿,12-品牌好货-jingdong.精致生活,13-品牌好货-jingdong.数码先锋,14-品牌好货-jingdong.品质家电,15-京仓配送,16-公众号-jingdong.好券商品,17-公众号-jingdong.9.9,18-公众号-jingdong.京仓京配
 	//$typeid:0全部 1京东 2拼多多3蘑菇街
 	//$keyword:商品搜索
	public function getlist($cateid=0,$typeid = 0,$keyword='',$pageindex = 1){
		$urls = 'https://api.open.21ds.cn/jd_api_v1/jfjingxuan?apkey='.Config::get('MAYI_KEY').'&eliteId='.$cateid.'&pageIndex='.$pageindex.'&pageSize=20&sortName=inOrderCount30DaysSku&sort=desc';
		 
		$datastr = curl($urls);
 
 		$dataobj = json_decode($datastr);
 		$arraylsit = array();
 		foreach ($dataobj->data as $key => $value) {
 			$info = TkConvert::ConverJdc($value);
 			array_push($arraylsit, $info);
 		}
 		return $this->renderSuccess($arraylsit);
	}

	//京东的查询商品
	//$keys 商品的关键词
	public function serchjd($pagenumber = 1,$sorttype = 0,$sorttype_sub = 0){
		$postdata = $this->request->post();
 		$keywords = $postdata['keywods'];
		$keys = urldecode($keywords);
		$sorestr = TkFunction::GetJDkSort($sorttype,$sorttype_sub);
		$urls='https://api.open.21ds.cn/jd_api_v1/getjdunionitems?apkey='.Config::get('MAYI_KEY').'&pageIndex='.$pagenumber.'&keyword='.$keys;
		if($sorestr != ""){
			$arr = explode('_',$sorestr); 
			$urls= $urls."&sortName=".$arr[0];
			$urls= $urls."&sort=".$arr[1];
		}
		$datastr = curl($urls);
 
 		$dataobj = json_decode($datastr);

 		$arraylsit = array();
 		//var_dump($dataobj);
 		foreach ($dataobj->data->lists as $key => $value) {
 			$info = TkConvert::ConverJdc($value);
 			array_push($arraylsit, $info);
 		}
 		return $this->renderSuccess($arraylsit);
	}


	//获取详情数据 此接口收费 ；
	public function getJdDetails($itemid=0,$type=1){
		$urls = 'https://api.open.21ds.cn/jd_api_v1/getitemdesc?apkey='.Config::get('MAYI_KEY').'&skuid='.$itemid;
		$datastr = curl($urls);
 		$data = json_decode($datastr,true);
 		//echo $datastr;
 		return $this->renderSuccess($data['data']);
	}

	 

	//获得拼多多热销商品
	public function getddlist($pageindex = 0){
		$ddapi = new PddApi();
		$parme['offset']=$pageindex*20;
		$parme['sort_type'] = 2; //1-实时热销榜；2-实时收益榜
		$parme['limit']=20;
 
		$datastr = $ddapi->GetPDDApi('pdd.ddk.top.goods.list.query',$parme);
		$dataobj = json_decode($datastr);

 		$arraylsit = array();
 		$list = $dataobj->top_goods_list_get_response->list;
 		foreach ($list as $key => $value) {
 			$info = TkConvert::ConverPdd($value);
 			array_push($arraylsit, $info);
 		}
		return $this->renderSuccess($arraylsit);

	}

	//获取详情数据
	public function getPddDetails($itemid=0,$type=1){
		$ddapi = new PddApi();
 		$arrayName = array();
 		$arrayName[0]=$itemid;
		$parme['goods_id_list'] =json_encode($arrayName);
	 	$datastr = $ddapi->GetPDDApi('pdd.ddk.goods.detail',$parme);
	 	$dataobj = json_decode($datastr);
	 	return $this->renderSuccess($dataobj);
	}
	//获取拼多多 地址
	public function getpddlink($itemid,$userid){
		$ddapi = new PddApi();
 		 
 		$parme['p_id'] =Config::get('PDD_ZONEID');
 		$arrayName = array();
 		$arrayName[0]=$itemid;
 		$parme['goods_id_list'] =json_encode($arrayName);
 		$parme['generate_short_url'] ='true';
 		$parme['custom_parameters'] =$userid;
 		$parme['generate_weapp_webview'] ='true';
 		$datastr = $ddapi->GetPDDApi('pdd.ddk.goods.promotion.url.generate',$parme);
	 	$dataobj = json_decode($datastr,true);

	 	$list = $dataobj["goods_promotion_url_generate_response"]["goods_promotion_url_list"];
	 	$modelurl = $list[0]["mobile_short_url"];
	 	$dataimg = qrcode64($modelurl);
 		$dataobj['erweima'] =$dataimg; 
	 
	 	return $this->renderSuccess($dataobj); 
	}

	//拼多多搜索
	public function serchpddwords($pagenumber = 1,$sorttype = 0,$sorttype_sub = 0){
		$ddapi = new PddApi();
		$postdata = $this->request->post();
 		$keywords = $postdata['keywods'];
 		$parme['keyword'] = urldecode($keywords);
 		$parme['page'] = $pagenumber;
 		$parme['page_size'] = 20;
 		if($sorttype==0 && $sorttype_sub == 0)
 			$parme['sort_type'] = null;
 		else	
 			$parme['sort_type'] = TkFunction::GetPddkSort($sorttype,$sorttype_sub);

 		$parme['pid'] =Config::get('PDD_ZONEID');
 		 
 		
 		$datastr = $ddapi->GetPDDApi('pdd.ddk.goods.search',$parme);
 
		$dataobj = json_decode($datastr);
 		$arraylsit = array();
 		$list = $dataobj->goods_search_response->goods_list;
 
 		foreach ($list as $key => $value) {
 			$info = TkConvert::ConverPdd($value);
 			array_push($arraylsit, $info);
 		}
		return $this->renderSuccess($arraylsit);

	}

 }