<?php
namespace app\common\taobao;
vendor('taobao.TopSdk');
//加载配置
use think\Config;
	


 /**
*  获取淘宝商品描述信息
*  抓取淘宝商品里面的信息
*/
class TkDesc  {

	function getDesc($itemid)
	{
		$url = 'https://hws.m.taobao.com/cache/wdesc/5.0/?id='.$itemid.'&qq-pf-to=pcqq.group';
		$prodcut = $this->get_taobao($url);
		return $prodcut;
	}


	/* *
	 * $url 淘宝链接
	 * */
	function get_taobao($url)
	{
	    if(empty($url)){return [];};
	    $ff = $this->curl_get($url);
	    $ff = trim(mb_convert_encoding($ff, "UTF-8", "GBK"));
	    preg_match('#tfsContent : ([\s\S]*) anchors#',$ff,$match);
	    $htmldata = $match[1];
	    $htmldata = substr($htmldata,1);
	    $htmldata = substr($htmldata,0,strlen($htmldata)-3); 
	    return $htmldata;
	}
	
	function curl_get($url)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_REFERER, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}


}