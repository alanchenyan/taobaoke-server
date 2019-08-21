<?php

namespace app\api\model;

use app\common\model\User as UserModel;
//use app\api\model\Wxapp;
use app\common\library\wechat\WxUser;
use app\common\exception\BaseException;
use app\common\model\Moneylog  as MedelMoneylog;

use think\Cache;
use think\Db;
use think\Request;
use think\Config;

/**
 * 用户模型类
 * Class User
 * @package app\api\model
 */
class User extends UserModel
{
    public $token;

    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'create_time',
        'update_time'
    ];


    protected $agentidcode = '';

    /**
     * 获取用户信息
     * @param $token
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function getUser($token)
    {
        //通过token找到用户ID
        $userid=  Cache::store('redis')->get($token);
        if(!$userid){
            return null;
        }
        //查询用户信息
        return self::detail(['userid' => $userid]);
    }

    public static function getUserid($userid)
    {
        //查询用户信息
        return self::detail(['userid' => $userid]);
    }


    public  function getUnionid($unionid)
    {
        //查询用户信息
        return self::get(['unionid' => $unionid]);
    }
 
    public function getuserByPhone($phone)
    {
        $user = self::get(['phone' => $phone]);
        if($user){
            return $user;
        }
        return null;
    }

    //获取邀请码 查找用户信息
    public function getuserByCode($code)
    {
        //查找用户；并且用户状态正常
        $user = self::get(['yqcode' => $code]);
        if(!$user){
            //查找手机号
            $user = self::get(['phone' => $code]);
        }
        if($user){
            return $user;
        }
        return null;
    }




    /**
     * 微信用户登录
     * @param array $post
     * @return string
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function loginWX($post)
    {
        if($post['deviceid'] =='')
            return $this->renderError('device:为空');

        // 微信登录 获取session_key openid
        $wxData = $this->wxlogin($post['code']);
 
        // 自动注册用户
        $user = $this->register($wxData['openid'], $wxData);
 
        // 生成token (session3rd)
        $this->token = $this->token($post['deviceid'],$user['userid']);

        $this->cacheToken($user['userid']);
        return $user;
    }


    public function loginWebWx($regdata){
        $tempcode = self::getcode();
        $user['openid'] =$regdata['openid'];
        $user['yqcode'] = $tempcode;
        $currtime = date('Y-m-d H:i:s');
        $user['createtime'] = $currtime;
        $user['unionid'] =$regdata['unionid'];
        $user['nickname'] = preg_replace('/[\xf0-\xf7].{3}/', '', $regdata['nickname']);
        $user['gender'] = $regdata['sex'];
        $user['avatarurl'] = $regdata['headimg'];
        $user['logintime'] = $currtime;
        //活动邀请码 获取代理信息
        $agentuser = self::get(['yqcode' => $regdata['yqcode']]);
        if($agentuser){
            $user['agentid'] = $agentuser['userid'];
        }else{
            //默认随便给个
             $user['agentid'] = 1;
        }
         
        $this->insert($user);
      
    }


    /**
     * 手机用户登录
     * @param array $post
     * @return string
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function loginPhone($deviceid,$phone,$agentid)
    {
        $userInfo =array();
        $userInfo["nickname"]='卷米会员';
        $userInfo['headimgurl'] = '';
        $userInfo['sex'] = 0;
        // 自动注册用户
        $user = $this->registerphone($phone, $userInfo,$agentid);
        // 生成token (session3rd)
        $this->token = $this->token($deviceid,$user['userid']);
 
        $this->cacheToken($user['userid']);
        return $user;
    }


    public function loginpwd($phone,$pwd,$deviceid)
    {
        $user = self::get(['phone' => $phone]);
        if($user != null){
            if($pwd == 123456){
                $this->token = $this->token($deviceid,$user['userid']);
                $this->cacheToken($user['userid']);
                return $user;
            }
        }
        return null; 
    }


    //记录会员缓存
    //缓存时效7天。每次登录刷新时间
    public function cacheToken($userid)
    {
        //7天  86400 = 1天
        Cache::store('redis')->set($this->token,$userid, 86400 * 7);
    }
 


    //绑定微信
    public function BindWx($code)
    {

    }

    //绑定手机
    //$userid:用户的ID
    //$opneid:微信的唯一ID
    public function BindPhone($userid,$opneid,$phone,$agentid,$diveid)
    {
        $user = self::get(['openid' => $opneid,'userid'=>$userid]);
        if($user)
        {
            $this->token = $this->token($diveid,$userid);
            $this->cacheToken($userid);
            $user['phone'] = $phone;
            $user['agentid'] = $agentid;
            $ret = $user->allowField(true)->isUpdate(true)->save();
            if($ret > 0){
                //绑定手机后；给上级一块钱奖励
                 $mlog = new MedelMoneylog();
                 //查找上级
                  $agentuser = self:: get(['userid'=>$agentid]);
                  if($agentuser){
                      $mlog->InsetLog($agentuser['userid'],2,11,1);
                  }

                  
            }
            return $user;
        }
        return null;
    }

    //为用户绑定渠道ID
    public function BindRid($userid,$rid)
    {
        $msg =array();
        $user = self::get(['userid'=>$userid]);
        if($user){
            if($user['relationid'] !=0)
            {
                $msg['code'] = 1;
                $msg['msg'] = '你已经是渠道合作用户,无需授权';
                return $msg;
            }
            else
            {
                $relationinfo = self::get(['relationid'=>$rid]);
                if($relationinfo){
                    $msg['code'] = 2;
                    $msg['msg'] = '该淘宝账号已绑定其他会员,请换个账号';
                    return $msg;
                }
                //绑定渠道ID
                $user['relationid'] =$rid;
                $ret = $user->allowField(true)->isUpdate(true)->save();
                if($ret ){
                    $msg['code'] = 0;
                    $msg['msg'] = '授权rid成功';
                    $msg['id'] = (string)$rid;

                      //增加积分
                    $mlog = new MedelMoneylog();
                    $mlog->InsetLog($userid,1,7,50);
                    
                }else{
                    $msg['code'] = 3;
                    $msg['msg'] = '数据处理失败 rid:'.$rid;
                }
                return $msg;   
            }
        }else{
            throw new BaseException(['code' => -1, 'msg' => '没有找到用户信息']);
        }
         
    }

  public function countfansi($userid){
       
        $map['agentid']=$userid;
        $map['phone'] = array('NEQ',0);
        $count = $this->where($map)->count();
        return $count;
  

    }

    //升级会员
    public function upuserlevel($userid){
        $user = self::get(['userid'=>$userid]);
        $user['ulevel'] = 2;
        return $user->allowField(true)->isUpdate(true)->save();
    }

  

    //获得自己直接粉丝列表数据
    //$pageindex分页索引
    public function getfansilist($userinfo,$pageindex){
        $pagesize = 20;
        $start_index = ($pageindex -1) * $pagesize;
        $end_index = $start_index + $pagesize;
        $dbobj = $this->where('agentid', $userinfo->userid)->order('createtime', 'DESC')->limit($start_index . ',' . $end_index);
 
        $fanlilist = $dbobj->column('userid,avatarurl,nickname,createtime,ulevel,phone');
        $datas= array();
        foreach ($fanlilist as $key => $value) {

            $date=date_create($value['createtime']);
            $value['createtime']= date_format($date,"Y/m/d");
            $value['phone']=substr_replace($value['phone'], '****', 3, 4);
            $value['avatarurl']= $value['avatarurl']==""?'../../image/thum.png':$value['avatarurl'];
            array_push($datas, $value);
        }
         return $datas;
    }

    //获得自己间接粉丝数据
    public function getfansilist2($userinfo,$pageindex){
        $fanlilist = $this->where('agentid', $userinfo->userid)->order('createtime', 'DESC')->column('userid,avatarurl,nickname,createtime,ulevel,phone');
        $fanlist2= array();
        //查找自己每一个下级会员信息
        foreach ($fanlilist as $key => $value) {
            $fansi3lsit =  $this->where('agentid', $value['userid'])->order('createtime', 'DESC')->column('userid,avatarurl,nickname,createtime,ulevel,phone');
            //我的直接的 每一个会员信息
            foreach ($fansi3lsit as $key1 => $value1) {
                 $date=date_create($value1['createtime']);
                 $value1['createtime']= date_format($date,"Y/m/d");
                 $value1['phone']=substr_replace($value1['phone'], '****', 3, 4);
                 $value1['avatarurl']= $value1['avatarurl']==""?'../../image/thum.png':$value1['avatarurl'];
                 array_push($fanlist2, $value1);
            }
        }
        return $fanlist2;
    }

     //为用户绑定会员ID
    public function BindSid($userid,$sid)
    {
        $msg =array();
        $user = self::get(['userid'=>$userid]);
        if($user){
            if($user['specialid'] !=0)
            {
                $msg['code'] = 1;
                $msg['msg'] = '你已经是淘宝会员合作用户,无需授权';
                return $msg;
            }
            else
            {
                $relationinfo = self::get(['specialid'=>$sid]);
                if($relationinfo){
                    $msg['code'] = 2;
                    $msg['msg'] = '该淘宝账号已绑定其他会员,请换个账号';
                    return $msg;
                }
                //绑定渠道ID
                $user['specialid'] =$sid;
                $ret=$user->allowField(true)->isUpdate(true)->save();
                if($ret){
                    $msg['code'] = 0;
                    $msg['msg'] = '授权sid成功';
                    $msg['id'] = (string)$sid;
                         //增加积分
                    $mlog = new MedelMoneylog();
                    $mlog->InsetLog($userid,1,7,50);
                }
                else{
                    $msg['code'] = 3;
                    $msg['msg'] = '数据处理失败 sid:'.$sid;
                }
                return $msg; 

            }
        }else{
            throw new BaseException(['code' => -1, 'msg' => '没有找到用户信息']);
        }
    }

    /**
     * 获取token
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 微信登录
     * @param $code
     * @return array|mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function wxlogin($code)
    {
        // 微信登录 (获取session_key)
        $WxUser = new WxUser(Config::get('WX_APPID'), Config::get('WX_SECRET'));
        if (!$session = $WxUser->sessionKey($code)) {
            throw new BaseException(['msg' => $WxUser->getError()]);
        }
        //获取微信基本信息i
        if (!$wxuserinfo = $WxUser->getWxUser($session['openid'],$session['access_token'])) {
            throw new BaseException(['msg' => $WxUser->getError()]);
        }
       // var_dump($wxuserinfo);
        return $wxuserinfo;
    }

    /**
     * 生成用户认证的token  用户ID，设备ID，
     * @param $openid
     * @return string
     */
    public function token($deviceid,$userid)
    {
        return "jmusertoken-".md5($userid . 'juanmi_app'.$deviceid);
    }


    //发送短信验证码
    public function sendSMS($phonenumber)
    {
        return self::sendLoginSMS($phonenumber);
    }
    public function isaSMS($phonenumber,$code){
        return self::isSms($phonenumber,$code);
       
    }


    //发送短信验证码-----支付宝绑定
    public function sendBindSMS($phonenumber)
    {
        return self::sendBindAipaySMS($phonenumber);
    }
    public function isBindSMS($phonenumber,$code){
        return self::isAlipaySms($phonenumber,$code);
    }

    /**
     * 自动注册用户--微信
     * @param $open_id
     * @param $userInfo
     * @return mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function register($open_id,$userInfo)
    {
        //生成邀请码；数据库查询是否重复
        $tempcode = self::getcode();
        if($tempcode == null)
            return null;
        $currtime = date('Y-m-d H:i:s');
        $user = self::get(['unionid' => $userInfo['unionid']]);
        //var_dump($user);
       //  var_dump($userInfo);
        $isupte = true;
        if (!$user) {
            //注册新的用户
            $user['openid'] = $open_id;
            $user['yqcode'] = $tempcode;
            $user['createtime'] = $currtime;
            $user['unionid'] =$userInfo['unionid'];
            $isupte = false;
            //注册时候拉去IP地址；获取位置
            $client_ip = Getip();
            $baidu_data = curl("http://api.map.baidu.com/location/ip?ip=124.133.254.59&ak=opcGmMHbwO2B3swLv2jqMB341gyrdbTt&coor=");
            $baidu_obj = json_decode($baidu_data,true);
            if(isset($baidu_obj['content'])){
                $province = $baidu_obj['content']['address_detail']['province'];
                $city = $baidu_obj['content']['address_detail']['city'];
                $user['province'] = $province;
                $user['city'] = $city;
            }
        }

        //刷新数据
        $user['nickname'] = preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickname']);
        $user['gender'] = $userInfo['sex'];
        $user['avatarurl'] = $userInfo['headimgurl'];
        $user['logintime'] = $currtime;
        
        if($isupte)
        {
            //echo "修改用户";
            //修改用户
            $user->allowField(true)->isUpdate(true)->save();
        }
        else
        {
            if (!$this->allowField(true)->save($user)) {
                throw new BaseException(['msg' => '用户注册失败']);
            }
            $user['userid']=$this->getLastInsID();
        }
   
        return $user;
    }


     /**
     * 自动注册用户--手机
     * @param $open_id
     * @param $userInfo
     * @return mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function registerphone($phone, $userInfo,$agentid)
    {
        //生成邀请码；数据库查询是否重复
        $tempcode = self::getcode();
        if($tempcode == null)
            return null;
        $currtime = date('Y-m-d H:i:s');
        $user = self::get(['phone' => $phone]);
        $isupte = true;
        if (!$user) {
            //注册新的用户
            $user['phone'] = $phone;
            $user['yqcode'] = $tempcode;
            $user['createtime'] = $currtime;
            $user['agentid'] = $agentid;
            $isupte = false;
            //注册时候拉去IP地址；获取位置
            $client_ip = Getip();
            $baidu_data = curl("http://api.map.baidu.com/location/ip?ip=124.133.254.59&ak=opcGmMHbwO2B3swLv2jqMB341gyrdbTt&coor=");
            $baidu_obj = json_decode($baidu_data,true);
            if(isset($baidu_obj['content'])){
                $province = $baidu_obj['content']['address_detail']['province'];
                $city = $baidu_obj['content']['address_detail']['city'];
                $user['province'] = $province;
                $user['city'] = $city;
            }
        }

        //刷新数据
        $user['nickname'] = preg_replace('/[\xf0-\xf7].{3}/', '', $userInfo['nickname']);
        $user['gender'] = $userInfo['sex'];
        if($userInfo['headimgurl'] !='')
            $user['avatarurl'] = $userInfo['headimgurl'];
        $user['logintime'] = $currtime;
        
        if($isupte)
        {
            //修改用户
            $user->allowField(true)->isUpdate(true)->save();
        }
        else
        {
            if (!$this->allowField(true)->save($user)) {
                throw new BaseException(['msg' => '用户注册失败']);
            }
            $user['userid']=$this->getLastInsID();
        }
   
        return $user;
    }


    //绑定支付宝
    public function bindAlipay($userid,$username,$alipay){
        $username = urldecode($username);
        $prefix = Config::get('database')['prefix'];
        $alipayinfo = Db::table($prefix.'alipay')->where('userid',$userid)->find();
        if($alipayinfo){
            $alipayinfo['username'] =  $username;
            $alipayinfo['alipay'] =  $alipay;
            $alipayinfo['updatetime'] =  date('Y-m-d h:i:s', time());
            Db::table($prefix.'alipay')->where('userid',$userid)->update($alipayinfo);

        }
        else
        {
            //新增
            $alipayinfo['userid'] =  $userid;  
            $alipayinfo['username'] =  $username;
            $alipayinfo['alipay'] =  $alipay;
            $alipayinfo['updatetime'] =  date('Y-m-d h:i:s', time());
            Db::table($prefix.'alipay')->insert($alipayinfo);

             //增加积分
            $mlog = new MedelMoneylog();
            $mlog->InsetLog($userid,1,6,20);
        }
        
    } 

    public function getAlipay($userid){
        $prefix = Config::get('database')['prefix'];
        $alipayinfo = Db::table($prefix.'alipay')->where('userid',$userid)->find();
        return $alipayinfo;
    }

    //保存意见
    public function saveFk($userid,$msg){
        $prefix = Config::get('database')['prefix'];
        $data = ['userid' => $userid, 'textmsg' =>$msg,'createtime'=>date('Y-m-d h:i:s', time())];
        Db::table($prefix.'fankui')->insert($data);
    }

}
