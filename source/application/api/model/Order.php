<?php

namespace app\api\model;

use think\Db;
use app\common\model\Order as OrderModel;
use app\common\exception\BaseException;

/**
 * 订单模型
 * Class Order
 * @package app\api\model
 */
class Order extends OrderModel
{
    
    /**
     * 获取基础订单数据
     */
    public function getBaseOder($userid,$pagetype)
    {
        $data['today'] = $this->gettotaldata(1,$userid);
        $data['yesterday'] = $this->gettotaldata(2,$userid);
        if($pagetype == 1){
            $data['month'] = $this->gettotaldata(3,$userid); 
            $data['lastmonth'] = $this->gettotaldata(4,$userid);
        } 
 
        return $data;
    }
    /**
     * 获取基础订单数据-笔数
     */
    public function getBaseCountOder($userid,$pagetype)
    {
        $data['today'] = $this->gettotalcount(1,$userid);
        $data['yesterday'] = $this->gettotalcount(2,$userid);
        if($pagetype == 1){
            $data['month'] = $this->gettotalcount(3,$userid); 
            $data['lastmonth'] = $this->gettotalcount(4,$userid);
        } 
         
        return $data;
    }

    /**
     * 获取基础订单数据-笔数
     */
    public function getOderDetails($userid)
    {   
        //上个月成交金额
        $data['lastmonth_cj'] = $this->gettotaldata(4,$userid,true);
        $data['month_yg'] = $this->gettotaldata(3,$userid); 
        $data['lastmonth_yg'] = $this->gettotaldata(4,$userid);

        //今日
        $data['today_bs'] = $this->gettotalcount(1,$userid);
        $data['today_yg'] = $this->gettotaldata(1,$userid);
        $data['today_js'] = $this->gettotaldata(1,$userid,true);

        //昨日
        $data['yesterday_bs'] = $this->gettotalcount(2,$userid);
        $data['yesterday_yg'] = $this->gettotaldata(2,$userid);
        $data['yesterday_js'] = $this->gettotaldata(2,$userid,true);

        return $data;
    }

    //查询订单列表
    //$orderstate:3：订单结算，12：订单付款(确认收货)， 13：订单失效，14：订单成功
    //$orderstate=0; 全部订单
    //$userid=0; 全部用户
    public function getorder_list($userid,$orderstate,$pageindex){
        return  $this->getorderlist($userid,$orderstate,$pageindex);
    }


    

}
