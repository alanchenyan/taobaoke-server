<?php

namespace app\api\controller;

use app\api\model\User as UserModel;

use app\common\model\Order  as MedelOrder;
use app\common\model\Tixian  as MedelTixian;
/**
 * 用户管理
 * Class User
 * @package app\api
 */
class User extends Controller
{
    public function index()
    {
         $userinfo= $this->getUser();
         return $this->renderSuccess($userinfo);
    }

    /**
     * 用户自动登录--微信
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function loginWx()
    {
        $model = new UserModel();
        $user = $model->loginWX($this->request->post());
        $token = $model->getToken();
        return $this->renderSuccess(compact('user', 'token'));
    }

 
    public function loginpw()
    {
        $post = $this->request->post();
        $phone = $post['phone'];
        $pwd = $post['pwd'];
        $deviceid = $post['deviceid'];

        $model = new UserModel();
        $user = $model->loginpwd($phone,$pwd,$deviceid);
        if($user){
            $token = $model->getToken();
            return $this->renderSuccess(compact('user', 'token'));
        }else{
            return $this->renderError("账号或密码错误");
        }
    }

    //微信登录情况后
    //设置电话号码  
    //get请求
    public function getphone($phone)
    {
        //检查手机号是否存在绑定
        $model = new UserModel();
        $user = $model->getuserByPhone($phone);
        if($user){
            return $this->renderSuccess("1");
        }
        else{
            return $this->renderSuccess("0");
        }

    }
    //邀请码查询 匹配
    public function isvalidcode($code){
        if(strlen($code) ==6 || strlen($code) ==11)
        {
            $model = new UserModel();
            $user = $model->getuserByCode($code);
            $clilent=array();
            $clilent["img"] = $user["avatarurl"];
            $clilent["userid"] = $user["userid"];
            $clilent["nickname"] = $user["nickname"];
            if($user){
                return $this->renderSuccess($clilent);
            }
            else{
                return $this->renderSuccess(null);
            }
        }
    }

 

    //获得短信接口,获取短信验证码
    public function getsmscode($phone,$pkey,$ptype)
    {
        $myKEY = md5($phone."HUBAOLIN.");
        if($myKEY == $pkey){
            //验证成功  发送短信
            $model = new UserModel(); 
            if($ptype == 1){
                if($model->sendSMS($phone))
                    return $this->renderSuccess("1");
            }
             
            if($ptype == 2){
                if($model->sendBindSMS($phone))
                    return $this->renderSuccess("1");
            }
        }
        return $this->renderSuccess("0");
    }

    //检查短信验证码
    //$pkey:做验证
    //$ptype: 1:用手机号查询用户信息。登录返回。
    //       2:微信绑定手机号
    //return: 用户信息
    public function checksmscode()
    {
        //获取参数
        $postdata = $this->request->post();
        $phone =$postdata['phone'];
        $pcode =$postdata['pcode'];
        $ptype =$postdata['ptype'];
        $agentid_userid =$postdata['agentid'];
        $model = new UserModel;
        if($model->isaSMS($phone,$pcode))
        {
                switch ($ptype) {
                    case 1:         //手机登录,返回用户信息，不存在，并注册
                        $diveid =$postdata['deviceid'];    
                        $userinfo = $model->loginPhone($diveid,$phone,$agentid_userid);
                         if($userinfo){
                             $token = $model->getToken();
                             return $this->renderSuccess(compact('userinfo', 'token'));
                        }
                        break;
                    case 2:         //微信用户绑定手机号码
                        $userid =   $postdata['userid']; 
                        $openid =   $postdata['openid'];
                        $diveid =   $postdata['deviceid'];  
                        $userinfo = $model->BindPhone($userid,$openid,$phone,$agentid_userid,$diveid);
                        if($userinfo)
                        {
                            $token = $model->getToken();
                            return $this->renderSuccess(compact('userinfo', 'token'));
                        }
                        break;
                    default:
                        return $this->renderError("登录注册失败");
                        break;
                }
                return $this->renderSuccess("1");
          }

          return $this->renderError("验证码失效");
    }


    //获得粉丝数量
    //$userid:用户ID ；
    // 0-全部 -1间接的所有粉丝 其他 查看某个用户的粉丝
    public function getfansinum($userid,$pageindex){
        $model = new UserModel();
        $user = $this->getUser();   // 用户信息
        if(!$user)
            return;
        if($userid ==0){
            $list = $model->getfansilist($user,$pageindex);
            return $this->renderSuccess($list); 
        }
        if($userid ==-1){  //全部间接粉丝
            $list = $model->getfansilist2($user,$pageindex);
            return $this->renderSuccess($list); 
        }
         
    }


    //绑定支付宝
    public function bindalipay($name='',$alipay='',$smscode){
        $userinfo = $this->getUser();
        $model = new UserModel();
        $phone = $userinfo['phone'];
        if($name == '' || $smscode=='' || $alipay ==''){
            return $this->renderError("填写的信息有误");
        }
        if($model->isBindSMS($phone,$smscode)){
            //校验短信
            $model->bindAlipay($userinfo['userid'],$name,$alipay);
            return $this->renderSuccess('绑定成功'); 
        } 
        else
        {
            return $this->renderError("验证码无效");
        }
    }

    //查询用户的支付信息
    public function getalipay(){
        $userinfo = $this->getUser();
        $model = new UserModel();
        $datainfo = $model->getAlipay($userinfo['userid']);
        return $this->renderSuccess($datainfo); 
    }

    //提现金额
    public function tixian(){
        $userinfo = $this->getUser();
        $postdata = $this->request->post();
        $tmoney =$postdata['tmoney'];
        if($tmoney < 5 ){
            return $this->renderError("最低提现5元");
        }
        
        //检查money是否达到
        if($userinfo['umoney'] < $tmoney ){
            return $this->renderError("余额不足");
        }
        //下过订单的并有确认收货；才能提现
        $order = new MedelOrder();
        $count = $order->getsumorder($userinfo['userid'],3);
        if($count <= 0){
            return $this->renderError("初次提现 要求用户有购物订单");
        }

        //一天只能提现一次
        $modeltixian = new MedelTixian();
        $counttx = $modeltixian->getTixianDay($userinfo['userid']);
        if($counttx >0){
            return $this->renderError("每日只能提现一次");
        }

        //获取收款信息
        $model = new UserModel();
        $payinfo = $model->getAlipay($userinfo['userid']);
        if(!$payinfo){
            return $this->renderError("用户收款信息不存在");
        }
        $modeltixian->inserTx($userinfo['userid'],$payinfo['username'],$payinfo['alipay'],$tmoney);
        $leftmoney = $userinfo['umoney'] - $tmoney;
        $msg['newmoney'] = $leftmoney;
        return $this->renderSuccess($msg); 

    }

    //意见反馈
    public function yijian(){
        $userinfo = $this->getUser();
        $postdata = $this->request->post();
        $msg =$postdata['msg'];
        if($msg != ''){
            $msg = htmlspecialchars($msg);
            $model = new UserModel();
            $model->saveFk($userinfo['userid'],$msg);
            return $this->renderSuccess(""); 
        }   
         
    }

    //粉丝统计
    public function fansicount(){
        $userinfo = $this->getUser();
        $model = new UserModel();
        $fancount1 = $model->countfansi($userinfo['userid']);
        $listinfo = $model->getfansilist2($userinfo,0);
        $fancount2 = count($listinfo);
        $msg['f1'] = $fancount1;
        $msg['f2'] = $fancount2;
        return $this->renderSuccess($msg); 
    }

    //升级会员接口
    public function uplevel(){
        $userinfo = $this->getUser();
        $model = new UserModel();
        $fancount1 = $model->countfansi($userinfo['userid']);
        $listinfo = $model->getfansilist2($userinfo,0);
        $fancount2 = count($listinfo);
        if($fancount1 >=50 && $fancount2 >= 100){
            $model->upuserlevel($userinfo['userid']);
            return $this->renderSuccess(0); 
        }
        return $this->renderError(-1);
    }

}
