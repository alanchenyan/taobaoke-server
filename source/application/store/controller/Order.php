<?php

namespace app\store\controller;

use app\store\model\Order as OrderModel;

/**
 * 订单管理
 * Class Order
 * @package app\store\controller
 */
class Order extends Controller
{
    /**
     * 待发货订单列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function alllist()
    {
        $model = new OrderModel;
        $list = $model->getList([]);
        return $this->fetch('index', compact('list'));
    }

    //淘宝
    public function list0()
    {
        $model = new OrderModel;
        $list = $model->getList(['type'=>'0']);
        return $this->fetch('index', compact('list'));
    }

    //京东
    public function list1()
    {
        $model = new OrderModel;
        $list = $model->getList(['type'=>'2']);
        return $this->fetch('index', compact('list'));
    }

    //拼多多
     public function list2()
    {
        $model = new OrderModel;
        $list = $model->getList(['type'=>'1']);
        return $this->fetch('index', compact('list'));
    }


    //获取返利数据
    public function fanlist(){
         $model = new OrderModel;
         $list = $model->getFanli([]);
         return $this->fetch('fanlilist', compact('list'));
    }

    //查询返利数据
    public function fanlists(){
        $userid = $this->request->param('userid');
        $arrsche = [];
        if($userid){
            $arrsche['userid'] = $userid;
        }
 
        //订单查询
        $orderid = $this->request->param('orderid');
        if($orderid){
            $arrsche['orderid'] = $orderid;
        }
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');
        if($start_time && $end_time)
            $arrsche['createtime'] = array(array('egt',$start_time),array('lt',$end_time));

        //工资
        $earningtype = $this->request->param('earningtype');
        if($earningtype == 1)
            $arrsche['earningtime'] = array('not null','');

        //fanlilevel
        $fanlilevel = $this->request->param('fanlilevel');
        if($fanlilevel !=-1){
            $arrsche['fllevel'] = $fanlilevel;
        }

        $model = new OrderModel;
        $list = $model->getFanli($arrsche);
      
        return $this->fetch('fanlilist', compact('list'));
    }


    //发工资
    public function complete(){
        $model = new OrderModel;
        $list = $model->getJiesuan();
       return $this->fetch('js', compact('list'));
    }
    //开始结算
    public function jsdata(){
        $datay = date("d");
        if($datay != 25){
            echo "当天不是25日发工资的时间";
        }
        else
        {
            $model = new OrderModel;
            $model->jiesuan();
            echo "结算成功";
        }

    }

}
