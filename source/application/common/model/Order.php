<?php

namespace app\common\model;

use think\Hook;
use think\Db;
/**
 * 订单模型
 * Class Order
 * @package app\common\model
  * 3：订单结算(收入的)，12：订单付款， 13：订单失效，14：订单成功
 */
class Order extends BaseModel
{
 
 
    /*  获取订单数据统计
     *  $datetype  1=今日预估 2=昨日预估 3-本月预估 4-上月预估  
     *  注意预估拿的状态 12：订单付款 14：订单成功
     *  $isbill: 是否查询 状态为3的订单；已结算的订单
     */
    public function gettotaldata($datetype,$userid,$isbill = false)
    {
        
        $map['userid'] = $userid;
        $map['earningtime'] = null;
        if($datetype == 1){
            //查询今日
            $today=strtotime(date('Y-m-d 00:00:00'));
            $today2=strtotime(date('Y-m-d 00:00:00').'+1 day');
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
        }
        else if($datetype == 2){
            //查询昨日
            $today=strtotime(date('Y-m-d 00:00:00').'-1 day');
            $today2=strtotime(date('Y-m-d 00:00:00'));
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
             
        }
        else if($datetype == 3){
            //查询本月
            $today=strtotime(date('Y-m-01 00:00:00'));
            $today2 = strtotime(date('Y-m-d H:i:s'));
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
        }
        else if($datetype == 4){
            //查询上个月
            $today=strtotime(date('Y-m-01 00:00:00').'-1 month');
            $today2 = strtotime(date('Y-m-01 00:00:00').'-1 day');
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
        }
        $map['createtime'] = array(array('egt',$start_time),array('lt',$end_time));
        $map['orderstate'] = 1;
        
        $value = Db::name('orderfanli')->where($map)->sum('commission');
        $value =round($value,2);
        return  $value;
    }

    //付款笔数查询
    // 1-今日付款笔数 2-昨日笔数 3-本月笔数 4-上个月笔数
    public function gettotalcount($datetype,$userid){
        if($userid > 0)
            $map['userid'] = $userid;
        if($datetype == 1){
            //查询今日
            $today=strtotime(date('Y-m-d 00:00:00'));
            $today2=strtotime(date('Y-m-d 00:00:00').'+1 day');
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
        }
        else if($datetype == 2){
            //查询昨日
            $today=strtotime(date('Y-m-d 00:00:00').'-1 day');
            $today2=strtotime(date('Y-m-d 00:00:00'));
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
             
        }
        else if($datetype == 3){
            //查询本月月
            $today=strtotime(date('Y-m-01 00:00:00'));
            $today2 = strtotime(date('Y-m-d H:i:s'));
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
        }
        else if($datetype == 4){
            //查询上个月
            $today=strtotime(date('Y-m-01 00:00:00').'-1 month');
            $today2 = strtotime(date('Y-m-01 00:00:00').'-1 day');
            $start_time=date("Y-m-d H:i:s",$today);       
            $end_time=date("Y-m-d H:i:s",$today2);        
        }
        $map['createtime'] = array(array('egt',$start_time),array('lt',$end_time));
        $count = Db::name('orderfanli')->where($map)->count();
        return $count;
    }


    //查询用户总订单量
    //根据订单状态；查询 统计单量
    public function getsumorder($userid,$odertype){
         $order = new Order();
         return Db::name('orderfanli')->where('userid',$userid)->where('orderstate',0)->count();
    }


    //查询订单列表
    //$orderstate:1-有效  2-失效
    //$orderstate=0; 全部订单
    //$userid=0; 全部用户
    public function getorderlist($userid,$orderstate,$pageindex){
        if($userid > 0)
            $map['userid'] = $userid;
        if($orderstate > 0 && $orderstate ==1)
            $map['orderstate'] = 1;
        if($orderstate > 0 && $orderstate ==2)
            $map['orderstate'] = 0;

        $listdata = Db::name('orderfanli')->where($map)->order('createtime', 'DESC')->paginate(20,true,[
                    'list_rows' => 20,
                    'page'      => $pageindex,
            ]); 
 
        return $listdata;
    }


}
