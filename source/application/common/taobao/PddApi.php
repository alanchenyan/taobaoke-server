<?php
namespace app\common\taobao;
 
//加载配置
use think\Config;


/*拼多多API类*/
class PDDApi
{
    public function GetPDDApi($apiType, $param)
    {
        $param['access_token'] =Config::get('PDD_APPSECRET');
        $param['client_id'] =  Config::get('PDD_CLIENT');
        $param['data_type'] = 'JSON';
        $param['type'] = $apiType;
        $param['timestamp'] = self::getMillisecond();
        ksort($param);    //  排序
        $str = '';      //  拼接的字符串
        foreach ($param as $k => $v) $str .= $k . $v;
        $sign = strtoupper(md5(Config::get('PDD_APPSECRET'). $str . Config::get('PDD_APPSECRET')));    //  生成签名    MD5加密转大写
        $param['sign'] = $sign;
        $url = 'http://gw-api.pinduoduo.com/api/router';
        return self::curl_post($url, $param);
    }
 
    //  post请求
    private static function curl_post($url, $curlPost)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
 
    //  获取13位时间戳
    private static function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}
 