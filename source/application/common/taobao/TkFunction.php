<?php
namespace app\common\taobao;
vendor('taobao.TopSdk');
//加载配置
use think\Config;


/**
*  淘宝客方法汇合
*/
class TkFunction  {
	
	//获取排序规则  ---阿里妈妈接口
	//排序_des（降序），排序_asc（升序），销量（total_sales），淘客佣金比率（tk_rate），    累计推广量（tk_total_sales），总支出佣金（tk_total_commi），价格（price）
	//客户端: //排序类型 1价格 2佣金 3销量 
	//$sorttype_sub: 0 升序 1降序
	public static function GetTkSort($sorttype,$sorttype_sub){
		$sort_data = "";
		if($sorttype == 1 && $sorttype_sub == 0)
		{
			//价格升序
			$sort_data ="price_asc";
		}
		else if($sorttype == 1 && $sorttype_sub == 1)
		{
			//价格升序
			$sort_data ="price_des";
		}
		else if($sorttype == 2&& $sorttype_sub == 0)
		{
			//佣金排序
			$sort_data ="tk_rate_asc";
		}
		else if($sorttype == 2&& $sorttype_sub == 1)
		{
			//佣金排序
			$sort_data ="tk_rate_des";
		}
		else if($sorttype == 3&& $sorttype_sub == 0)
		{
			//销量排序
			$sort_data ="total_sales_asc";
		}
		else if($sorttype == 3&& $sorttype_sub == 1)
		{
			//销量排序
			$sort_data ="total_sales_des";
		}
		return $sort_data;

	}

	//获取排序规则-- 折淘客的接口排序
	//商品排序方式，值为空：按照综合排序，new：按照更新时间排序，sale_num：按照总销量从大到小排序，commission_rate_asc：按照佣金价格从小到大排序，commission_rate_desc：按照佣金从大到小排序，price_asc：按照价格从小到大排序，price_desc：按照价格从大到小排序	
	public static function GetZTkSort($sorttype,$sorttype_sub){
		$sort_data = "";
		if($sorttype == 1 && $sorttype_sub == 0)
		{
			//价格升序
			$sort_data ="price_asc";
		}
		else if($sorttype == 1 && $sorttype_sub == 1)
		{
			//价格升序
			$sort_data ="price_desc";
		}
		else if($sorttype == 2&& $sorttype_sub == 0)
		{
			//佣金排序
			$sort_data ="commission_rate_asc";
		}
		else if($sorttype == 2&& $sorttype_sub == 1)
		{
			//佣金排序
			$sort_data ="commission_rate_desc";
		}
		else if($sorttype == 3&& $sorttype_sub == 0)
		{
			//销量排序
			$sort_data ="sale_num_asc";
		}
		else if($sorttype == 3&& $sorttype_sub == 1)
		{
			//销量排序
			$sort_data ="sale_num_desc";
		}
		return $sort_data;
	}

	//京东商品接口排序
	public static function GetJDkSort($sorttype,$sorttype_sub){
		$sort_data = "";
		if($sorttype == 1 && $sorttype_sub == 0)
		{
			//价格升序
			$sort_data ="price_asc";
		}
		else if($sorttype == 1 && $sorttype_sub == 1)
		{
			//价格升序
			$sort_data ="price_desc";
		}
		else if($sorttype == 2&& $sorttype_sub == 0)
		{
			//佣金排序
			$sort_data ="commissionShare_asc";
		}
		else if($sorttype == 2&& $sorttype_sub == 1)
		{
			//佣金排序
			$sort_data ="commissionShare_desc";
		}
		else if($sorttype == 3&& $sorttype_sub == 0)
		{
			//销量排序
			$sort_data ="inOrderCount30Days_asc";
		}
		else if($sorttype == 3&& $sorttype_sub == 1)
		{
			//销量排序
			$sort_data ="inOrderCount30Days_desc";
		}
		return $sort_data;
	}


	//拼多多商品排序
	public static function GetPddkSort($sorttype,$sorttype_sub){
		$sort_data = "";
		if($sorttype == 1 && $sorttype_sub == 0)
		{
			//价格升序
			$sort_data =3;
		}
		else if($sorttype == 1 && $sorttype_sub == 1)
		{
			//价格升序
			$sort_data =4;
		}
		else if($sorttype == 2&& $sorttype_sub == 0)
		{
			//佣金排序
			$sort_data =1;
		}
		else if($sorttype == 2&& $sorttype_sub == 1)
		{
			//佣金排序
			$sort_data =2;
		}
		else if($sorttype == 3&& $sorttype_sub == 0)
		{
			//销量排序
			$sort_data =5;
		}
		else if($sorttype == 3&& $sorttype_sub == 1)
		{
			//销量排序
			$sort_data =6;
		}
		return $sort_data;
	}

}