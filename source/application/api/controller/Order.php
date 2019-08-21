<?php

namespace app\api\controller;

use app\api\model\Order as OrderModel;
use app\api\model\User as UserModel;

/**
 * 订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {

        $this->user = $this->getUser();   // 用户信息
    }

    /*
     * 获取用户的 订单数据报表
     * 今日预估 昨日预估 本月预估  
     */
    public function getbasedata($pagetype =0){
        $user = $this->getUser();   // 用户信息
        if($user){
            $model = new OrderModel();
            $msg = $model->getBaseOder($user->userid,$pagetype);
            $usermodel = new UserModel;
            $fansicount = $usermodel->countfansi($user['userid']);
            $msg['fansinum'] = $fansicount;
            return $this->renderSuccess($msg);
        }
    }

    /*
     * 获取用户的 订单数据报表
     * 今日预估 昨日预估 本月预估  
     */
    public function getbasecountdata($pagetype =0){
        $user = $this->getUser();   
        if($user){
            $model = new OrderModel();
            $msg = $model->getBaseCountOder($user->userid,$pagetype);

            return $this->renderSuccess($msg);
        }
    }


    //订单统计详情
    public function getorderdetail(){
        $user = $this->getUser();   
        if($user){
            $model = new OrderModel();
            $msg = $model->getOderDetails($user->userid);
            return $this->renderSuccess($msg);
        }
    }

    //订单列表详情 $orderstate:3：订单结算，12：订单付款(未确认收货) ， 13：订单失效，14：订单成功(确认收货)
    //$orderid：客户端页面的订单ID；0-全部 1-已付款 2-已结算 3-已失效
    public function getorderlist($orderid,$pageindex = 1){
        $user = $this->getUser();  
        if($user){
            $model = new OrderModel();
            $list = $model->getorder_list($user->userid,$orderid,$pageindex);
            return $this->renderSuccess($list);
        }
        return $this->renderError("暂无数据");
    }


}
