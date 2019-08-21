<?php

namespace app\store\controller;
use think\Config;
use think\Cache;
use app\common\library\sms\Driver as SmsDriver;
use app\store\model\Setting as SettingModel;

/**
 * 系统设置
 * Class Setting
 * @package app\store\controller
 */
class Setting extends Controller
{
    /**
     * 商城设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function store()
    {
        return $this->updateEvent('store');
    }

    /**
     * 京东授权
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function trade()
    {
        $data['datac'] = Config::get('JD_APPKEYT');
        $data['urlc'] = Config::get('JD_CALL');
        $tokens = Cache::store('redis')->get("jd_token");
        if($tokens){
            $tokendata = json_decode($tokens,true);
            $data['token'] = $tokendata["access_token"];
        }
        else
            $data['token'] = "";
        return $this->fetch('trade', compact('data'));
    }
    //授权地址：http://app.52juanmi.com/index.php/store/setting/authorjd
    public function authorjd($code){
        if(!isset($code)){
            echo "非法的code";
            return;
        }
        $urls ="https://auth.360buy.com/oauth/token?grant_type=authorization_code&client_id=".Config::get('JD_APPKEYT')."&client_secret=".Config::get('JD_SECRE')."&scope=read&redirect_uri=".Config::get('JD_CALL')."&code=".$code."&state=1234";
        $datastr = curl($urls);
        Cache::store('redis')->set("jd_token", $datastr);
        echo "授权成功:".$datastr;
        

    }

    /**
     * 短信通知
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function sms()
    {
        return $this->updateEvent('sms');
    }

    /**
     * 发送短信通知测试
     * @param $AccessKeyId
     * @param $AccessKeySecret
     * @param $sign
     * @param $msg_type
     * @param $template_code
     * @param $accept_phone
     * @return array
     * @throws \think\Exception
     */
    public function smsTest($AccessKeyId, $AccessKeySecret, $sign, $msg_type, $template_code, $accept_phone)
    {
        $SmsDriver = new SmsDriver([
            'default' => 'aliyun',
            'engine' => [
                'aliyun' => [
                    'AccessKeyId' => $AccessKeyId,
                    'AccessKeySecret' => $AccessKeySecret,
                    'sign' => $sign,
                    $msg_type => compact('template_code', 'accept_phone'),
                ],
            ],
        ]);
        $templateParams = [];
        if ($msg_type === 'order_pay') {
            $templateParams = ['order_no' => '2018071200000000'];
        }
        if ($SmsDriver->sendSms($msg_type, $templateParams, true)) {
            return $this->renderSuccess('发送成功');
        }
        return $this->renderError('发送失败 ' . $SmsDriver->getError());
    }

    /**
     * 上传设置
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function storage()
    {
        return $this->updateEvent('storage');
    }

    /**
     * 更新商城设置事件
     * @param $key
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    private function updateEvent($key)
    {
        if (!$this->request->isAjax()) {
            $values = SettingModel::getItem($key);
            return $this->fetch($key, compact('values'));
        }
        $model = new SettingModel;
        if ($model->edit($key, $this->postData($key))) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError('更新失败');
    }

}
