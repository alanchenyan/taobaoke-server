<?php
namespace app\common\taobao;
vendor('taobao.TopSdk');
//加载配置
use think\Config;

/**
*  淘宝客接口；初始化对象
*/
class TkManager  
{
	
	//创建淘宝客APP官方接口对象
	public static function   CreateTKApiApp()
	{
		$c = new \TopClient;
		$c->appkey = Config::get('LM_APPKEY');
		$c->secretKey = Config::get('LM_APPSECRET');
		return $c;
	}

	//创建淘宝客网站接口对象
	public static function   CreateTKApiWeb()
	{
		$c = new \TopClient;
		$c->appkey = Config::get('WEB_APPKEY');
		$c->secretKey = Config::get('WEB_APPSECRET');
		return $c;
	}

	//创建的是阿里百川的秘钥
	public static function   CreateTKApiBC()
	{
		$c = new \TopClient;
		$c->appkey = Config::get('BC_APPKEY');
		$c->secretKey = Config::get('BC_APPSECRET');
		return $c;
	}
}