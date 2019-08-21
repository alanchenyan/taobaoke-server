<?php

namespace app\common\model;
use app\common\library\sms\engine\Aliyun;
use think\Request;
use think\Cache;
/**
 * 用户模型类
 * Class User
 * @package app\common\model
 */
class User extends BaseModel
{
    protected $name = 'user';

    // 性别
    private $gender = ['未知', '男', '女'];

    /**
     * 关联收货地址表
     * @return \think\model\relation\HasMany
     */
    public function address()
    {
        return $this->hasMany('UserAddress');
    }

    /**
     * 关联收货地址表 (默认地址)
     * @return \think\model\relation\BelongsTo
     */
    public function addressDefault()
    {
        return $this->belongsTo('UserAddress', 'address_id');
    }

    /**
     * 显示性别
     * @param $value
     * @return mixed
     */
    public function getGenderAttr($value)
    {
        return $this->gender[$value];
    }

    /**
     * 获取用户列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList()
    {
        $request = Request::instance();
        return $this->order(['createtime' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
    }

    /**
     * 获取用户信息
     * @param $where
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function detail($where)
    {
        return self::get($where);
    }




    //获取用户邀请码
    public function getcode()
    {
       $tempcode = null;
       $tempuser = null;
       do {
            $tempcode = substr(md5(microtime(true)), 0, 6);
            $tempuser = self::get(['yqcode' => $tempcode]);
        } while ($tempuser);

        if(!$tempcode)
            return null;
        return $tempcode;
    }

    //获取上级推荐人的ID
    public function getAgentUserId()
    {
        
    }

    //判断电话号码是否存在
    public function isPhone($phonenumber)
    {
        $phone = self::get(['phone' => $phonenumber  ]); 
        if(!$phone)
            return false;

        return true;           
    }

    public function sendLoginSMS($phone)
    {
        $aliyun = new Aliyun();
        //获取四位验证码
        $rnumber = rand(1000,9999);
        //$rnumber = 1234;
        $d["code"] = $rnumber;
        $str = json_encode($d);
        if($aliyun ->sendSms($phone,'SMS_153332144','卷米',$str))
        //if(true)
        {
            //保存到redis 2分钟。
            $keys = "sms_user_".$phone;
            Cache::store('redis')->set($keys, $rnumber, 60 * 2);
            return true;
        }
        return false;
     
    }

    //发送支付宝绑定验证码
    public function sendBindAipaySMS($phone)
    {
        $aliyun = new Aliyun();
        //获取四位验证码
        $rnumber = rand(1000,9999);
        //$rnumber = 1234;
        $d["code"] = $rnumber;
        $str = json_encode($d);
        if($aliyun ->sendSms($phone,'SMS_153332144','卷米',$str))
        {
            //保存到redis 2分钟。
            $keys = "sms_alipay_".$phone;
            Cache::store('redis')->set($keys, $rnumber, 60 * 2);
            return true;
        }
        return false;
     
    }
   
   //判断短信验证码-支付宝绑定
    public function isAlipaySms($phone,$smsnum)
    {
        $keys = "sms_alipay_".$phone;
        $smsCode = Cache::store('redis')->get($keys);
        if($smsCode == $smsnum)
        {
            return true;
        }
        return false;
    }


    //判断短信验证码
    public function isSms($phone,$smsnum)
    {
        $keys = "sms_user_".$phone;
        $smsCode = Cache::store('redis')->get($keys);
        if($smsCode == $smsnum)
        {
            return true;
        }
        return false;
    }
}
