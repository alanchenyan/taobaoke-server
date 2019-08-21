<?php

namespace app\common\library\wechat;

/**
 * 微信小程序用户管理类
 * Class WxUser
 * @package app\common\library\wechat
 */
class WxUser
{
    private $appId;
    private $appSecret;

    private $error;

    /**
     * 构造方法
     * WxUser constructor.
     * @param $appId
     * @param $appSecret
     */
    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * 获取session_key
     * @param $code
     * @return array|mixed
     */
    public function sessionKey($code)
    {
        /**
         * https://api.weixin.qq.com/sns/oauth2/access_token?appid=AppID&secret=AppSecret&code=XXXXXXX_type=authorization_code
         */
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $result = json_decode(curl($url, [
            'appid' => $this->appId,
            'secret' => $this->appSecret,
            'grant_type' => 'authorization_code',
            'code' => $code
        ]), true);
        if (isset($result['errcode'])) {
            $this->error = $result['errmsg'];
            return false;
        }
        return $result;
    }

    /**
     * 获取用户的基本信息
     * @param $code
     * @return array|mixed
     * https://api.weixin.qq.com/sns/userinfo?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
     {      
            "openid":" OPENID",
            "nickname": NICKNAME,
            "sex":"1", 1时是男性，值为2时是女性
            "province":"PROVINCE"
            "city":"CITY",
            "country":"COUNTRY",
            "headimgurl":    "http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
            "privilege":[ "PRIVILEGE1" "PRIVILEGE2"     ],
            "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
            }
     */
    public function getWxUser($openid,$access_token)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $result = json_decode(curl($url, [
            'access_token' => $access_token,
            'openid' => $openid,
            'lang' => 'zh_CN'
        ]), true);
        if (isset($result['errcode'])) {
            $this->error = $result['errmsg'];
            return false;
        }
        return $result;
    }

    public function getError()
    {
        return $this->error;
    }

}