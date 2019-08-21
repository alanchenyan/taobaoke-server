<?php
namespace app\common\taobao;
 
//加载配置
use think\Config;
use think\Cache;

/*JDAPI类*/
/**
 * Class ZeusApi 宙斯接口调用类
 */
class JdApi
{
    private $appKey = '';   //  你的Key
    private $appScret = ''; //  你的Secret
 
    public function JdApi() {
        $this-> $appKey = Config::get('JD_APPKEYT');
        $this-> $appScret = Config::get('JD_SECRE');
    }

    /**
     * 获取宙斯接口数据
     * @param string $apiUrl    要获取的api
     * @param string $param_json    该api需要的参数，使用json格式，默认为 {}
     * @param string $version   版本可选为 2.0
     * @param bool $get 是否使用get，默认为post方式
     * @return mixed    京东返回的json格式的数据
     */
    public function GetZeusApiData($apiUrl='',$param_json = array(),$version='1.0',$get=false){
        $API['access_token'] = $this->refreshAccessToken(); //  生成的access_token，30天一换
        $API['app_key'] =  Config::get('JD_APPKEYT');
        $API['method'] = $apiUrl;
        $API['360buy_param_json'] = json_encode($param_json);
        $API['timestamp'] = date('Y-m-d H:i:s',time());
        $API['v'] = $version;
        ksort($API);    //  排序
        $str = '';      //  拼接的字符串
        foreach ($API as $k=>$v) $str.=$k.$v;
        $sign = strtoupper(md5($this->appScret.$str.$this->appScret));    //  生成签名    MD5加密转大写
        if ($get){
            //  用get方式拼接URL
            $url = "https://api.jd.com/routerjson?";
            foreach ($API as $k=>$v)
                $url .= urlencode($k) . '=' . $v . '&';  //  把参数和值url编码
            $url .= 'sign='.$sign;
            $res = self::curl_get($url);
        }else{
            //  用post方式获取数据
            $url = "https://api.jd.com/routerjson?";
            $API['sign'] = $sign;
            $res = self::curl_post($url,$API);
        }
        return $res;
    }




    //  刷新accessToken
    public function refreshAccessToken(){
        $datastr = Cache::store('redis')->get("jd_token");
        $isflag = false;
        if(!$datastr){
            echo "京东没有授权"; exit();
            return false;
        }
        $res = json_decode($datastr,true);
        if ($res['expires_in']*1000 + $res['time']  <  self::getMillisecond() - 86400000){ 
             //  获取刷新token的url
                $refreshUrl = "https://auth.360buy.com/oauth/token?client_id=".Config::get('JD_APPKEYT')."&client_secret=".Config::get('JD_SECRE')."&grant_type=refresh_token&refresh_token=".$res['refresh_token'];
                $datastr = curl($refreshUrl);
                Cache::store('redis')->set("jd_token", $datastr);
                $newAccessTokenArr = json_decode($datastr,true);
                $accessToken = $newAccessTokenArr['access_token'];
                return $accessToken;
        }
        return $res['access_token'];

 
    }
    //  get请求
    private static function curl_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    //  post请求
    private static function curl_post($url,$curlPost){
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
    private static function  getMillisecond(){
        list($t1, $t2) = explode(' ', microtime());
        return sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
}
 