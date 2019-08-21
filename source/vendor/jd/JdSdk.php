<?php
/**
 * JD SDK 入口文件
 * 请不要修改这个文件，除非你知道怎样修改以及怎样恢复
 */

/**
 * 定义常量开始
 * 在include("JdSdk.php")之前定义这些常量，不要直接修改本文件，以利于升级覆盖
 */
/**
 * SDK工作目录
 * 存放日志，JD缓存数据
 */
if (!defined("JD_SDK_WORK_DIR"))
{
	define("JD_SDK_WORK_DIR", "/tmp/");
}
/**
 * 是否处于开发模式
 * 在你自己电脑上开发程序的时候千万不要设为false，以免缓存造成你的代码修改了不生效
 * 部署到生产环境正式运营后，如果性能压力大，可以把此常量设定为false，能提高运行速度（对应的代价就是你下次升级程序时要清一下缓存）
 */
if (!defined("JD_SDK_DEV_MODE"))
{
	define("JD_SDK_DEV_MODE", true);
}
/**
 * 定义常量结束
 */

/**
 * 找到lotusphp入口文件，并初始化lotusphp
 * lotusphp是一个第三方php框架，其主页在：lotusphp.googlecode.com
 */
$lotusHome = dirname(__FILE__) . DIRECTORY_SEPARATOR . "lotusphp_runtime" . DIRECTORY_SEPARATOR;
include($lotusHome . "Lotus.php");
$lotus = new Lotus;
$lotus->option["autoload_dir"] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'jd';
$lotus->devMode = JD_SDK_DEV_MODE;
$lotus->defaultStoreDir = JD_SDK_WORK_DIR;
$lotus->init();
/*
//测试demo
$c = new JdClient();
$c->appKey = "26EAC2509056EB38FB623D9A49296D2C";
$c->appSecret = "1abdc5a97ecb4594ab7b772296bcfbbd";
$c->accessToken = "1f1d3048-220a-484d-ad93-f3808d9aacc1";
$c->serverUrl = "http://gw.api.360buy.net/routerjson";
$req = new JingdongSetListMapRequest;
$req->putOtherTextParam("cid", "1");
$req->putOtherTextParam("value_id", "1");
$req->putOtherTextParam("source", "1");
$req->putOtherTextParam("ip", "1");
$resp = $c->execute($req, $c->accessToken);
print(json_encode($resp));
*/