<?php
namespace app\common\taobao;
vendor('taobao.TopSdk');
//加载配置
use think\Config;

/**
* 
*/
class ItemClient
{
	 public $itemid = 0;
	 public $itempic = 0;
	 public $itemtitle = 0;
	 public $couponprice = 0;
	 //返利比例
	 public $commissionrate = 0;  
	 public $itemprice = 0;
	 //出售物品数量
	 public $sellnum = 0;
	 //券后价
	 public $newprice = 0;
	 //0表示集市，1表示商城
	 public $usertype = 0;
	 //优惠券时间
	 public $couponstart = 0;
	 public $couponendtime = 0;
}


/**
*   淘宝客其他接口字段转换
*/
class TkConvert 
{
	
	public static function checkDateFormat($date)
	{
	    //匹配日期格式
	    if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
	    {
	        //检测是否为日期
	        if(checkdate($parts[2],$parts[3],$parts[1]))
	            return true;
	        else
	        return false;
	    }
	    else
	        return false;
	}

	//检查图片地址是否带 http
	public static function isImghttp($url){
		$head = substr($url,0,4);
		if($head != 'http'){
			return 'https:'.$url;
		}
		return (string)$url;
	}


 
	 
	////商品简要信息封装 (阿里妈妈)
	//
	public static function ConvertTk($obk,$flag = false){
		$client =  new ItemClient();

		$client->itemid = (string)$obk->item_id;
		$client->itempic = self::isImghttp($obk->pict_url);
		$client->itemtitle = (string)$obk->title;
		$client->couponprice =isset($obk->coupon_amount)?(int)$obk->coupon_amount:0;

		//佣金结算；
		if($flag)
			$client->commissionrate = (float)($obk->commission_rate/100);
		else
			$client->commissionrate = (string)$obk->commission_rate;

		$client->itemprice = (float)$obk->zk_final_price;
		$client->sellnum =(int) $obk->volume;
		//券后价格
		$client->newprice = (float)$obk->zk_final_price-(float)$obk->coupon_amount;
		//	//0表示集市，1表示商城
		$client->usertype = (int)$obk->user_type;
		//优惠券的时间
		$client->starttime =   '19586233563';
		//$is_date=strtotime($obk->coupon_start_time)?strtotime($obk->coupon_start_time):false;
		if($client->couponprice == 0){
			$client->couponstart = 0;
			$client->couponendtime = 0; 
		}
		else if(self::checkDateFormat($obk->coupon_start_time))
		{
			$client->couponstart = (string)$obk->coupon_start_time;
			$client->couponendtime = (string)$obk->coupon_end_time;
		}
		else
		{
			$client->couponstart = date('Y-m-d ',substr($obk->coupon_start_time,0,10));
			$client->couponendtime = date('Y-m-d',substr($obk->coupon_end_time,0,10)); 
		}
		
		return $client;
	}

		//淘客选品库
	public static function ConvertTkXP($obk){
		$client =  new ItemClient();

		$client->itemid = (string)$obk->num_iid;
		$client->itempic = self::isImghttp($obk->pict_url);
		$client->itemtitle = (string)$obk->title;

		//$obk->coupon_info  满39元减10元
		$client->couponprice = 0;
		if(isset($obk->coupon_info)){
			$coupon_s = array();
			preg_match_all("/减(\d+)元/", $obk->coupon_info, $coupon_s);
			$client->couponprice =$coupon_s[1][0];
		}
		 
		 
		 
		$client->commissionrate = (float)$obk->tk_rate;
		$client->itemprice = (float)$obk->zk_final_price;
		$client->sellnum =(int) $obk->volume;
		//券后价格
		$client->newprice = (float)$obk->zk_final_price-(float)$client->couponprice;
		//	//0表示集市，1表示商城
		$client->usertype = (int)$obk->user_type;
		//优惠券的时间
		$client->starttime =   '19586233563';
		//$is_date=strtotime($obk->coupon_start_time)?strtotime($obk->coupon_start_time):false;
		if($client->couponprice == 0){
			$client->couponstart = 0;
			$client->couponendtime = 0; 
		}
		else if(self::checkDateFormat($obk->coupon_start_time))
		{
			$client->couponstart = (string)$obk->coupon_start_time;
			$client->couponendtime = (string)$obk->coupon_end_time;
		}
		else
		{
			$client->couponstart = date('Y-m-d ',substr($obk->coupon_start_time,0,10));
			$client->couponendtime = date('Y-m-d',substr($obk->coupon_end_time,0,10)); 
		}
		
		return $client;
	}

	//好单库字段转换
	//接口地址:https://www.haodanku.com/api/detail.html
	public static function ConvertHDk($obk){
		$client =  new ItemClient();
		$client->itemid = $obk->itemid;
		$client->itempic = $obk->itempic.'_310x310.jpg';
		$client->itemtitle = (string)$obk->itemtitle;
		$client->couponprice =isset($obk->couponmoney)?(int)$obk->couponmoney:0;
		$client->commissionrate = (float)$obk->tkrates;
		$client->itemprice = (float)$obk->itemprice;
		$client->sellnum =(int) $obk->itemsale;
		//券后价格
		$client->newprice = round($obk->itemendprice,2);
		//	//0表示集市，1表示商城
		$client->usertype = $obk->shoptype =='C'?0:1;
		//优惠券的时间
		$client->couponstart = date('Y-m-d ',$obk->couponstarttime);
		$client->couponendtime = date('Y-m-d ',$obk->couponendtime); 
		
		return $client;
	}

	//折淘客字段转换-
	//接口地址：http://www.zhetaoke.com/user/extend/extend_lingquan_default.aspx
	public static function ConvertZTk($obk){
		$client =  new ItemClient();
		$client->itemid =  $obk->tao_id;
		$client->itempic = $obk->pict_url.'_310x310.jpg';
		$client->itemtitle =  $obk->title;
		$client->couponprice =isset($obk->coupon_info_money)?(int)$obk->coupon_info_money:0;
		$client->commissionrate =  $obk->tkrate3;
		$client->itemprice = $obk->size;
		$client->sellnum =  $obk->volume;
		//券后价格
		$client->newprice = round($obk->quanhou_jiage,2);
		//	//0表示集市，1表示商城
		$client->usertype = $obk->user_type =='0'?0:1;
		//优惠券的时间
		$client->couponstart = $obk->coupon_start_time;
		$client->couponendtime = $obk->coupon_end_time; 
		
		return $client;
	}


	//转换JD数据
	public static function ConverJdc($obk){
		$client =  new ItemClient();
		$client->itemid =  $obk->skuId;
		$client->itempic = $obk->imageInfo->imageList[0]->url;

		$client->itemtitle =  $obk->skuName;
		if(count($obk->couponInfo->couponList) > 0){
			$client->couponprice =$obk->couponInfo->couponList[0]->discount;
			$client->couponstart = date('Y-m-d ',substr($obk->couponInfo->couponList[0]->useStartTime,0,10));
			$client->couponendtime = date('Y-m-d',substr($obk->couponInfo->couponList[0]->useEndTime,0,10)); 
			//jd 券url
			$client->couponurl = $obk->couponInfo->couponList[0]->link;
		}
		else{
			$client->couponprice = 0;
			$client->couponstart = 0;
			$client->couponendtime = 0; 
		}

		$client->commissionrate =  $obk->commissionInfo->commissionShare;
		$client->itemprice = $obk->priceInfo->price;
		if(isset($obk->inOrderCount30DaysSku)){
			$client->sellnum =  $obk->inOrderCount30DaysSku;  
		}else
		{
			$client->sellnum =  $obk->inOrderCount30Days;  
		}
		 
		//券后价格
		$client->newprice = round($client->itemprice-$client->couponprice,2);
		//	//0表示集市，1表示商城 2-JD 3-拼多多
		$client->usertype = 2;

		//图标列表
		$client->smallimg = $obk->imageInfo->imageList;
		 
		return $client;
	}

	//转换拼多多数据
	public static function ConverPdd($obk){
			$client =  new ItemClient();
		$client->itemid =  $obk->goods_id;
		$client->itempic = $obk->goods_thumbnail_url;

		$client->itemtitle =  $obk->goods_name;
		$client->couponprice =isset($obk->coupon_discount)?(int)$obk->coupon_discount:0;
		if($client->couponprice>0)
			$client->couponprice = $client->couponprice/100;

		 

		$client->commissionrate =  $obk->promotion_rate/10;
		$client->itemprice = round($obk->min_normal_price/100,2);
		$client->sellnum =  $obk->sales_tip;
		//券后价格
		$client->newprice = round(($obk->min_normal_price-$client->couponprice)/100,2);
		//	//0表示集市，1表示商城 2-JD 3-拼多多
		$client->usertype = 3;
		//优惠券的时间
		$client->couponstart = date('Y-m-d ',substr($obk->coupon_start_time,0,10));
		$client->couponendtime = date('Y-m-d',substr($obk->coupon_end_time,0,10)); 
		return $client;
	}



}