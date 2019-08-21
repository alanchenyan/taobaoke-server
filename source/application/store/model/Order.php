<?php

namespace app\store\model;

use app\common\model\Order as OrderModel;
use think\Request;
use think\Db;
use app\common\model\Moneylog  as MedelMoneylog;
/**
 * 订单管理
 * Class Order
 * @package app\store\model
 */
class Order extends OrderModel
{
    /**
     * 订单列表
     * @param $filter
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($filter)
    {
        return $this->where($filter)->order(['create_time' => 'desc'])->paginate(20, false, [
                'query' => Request::instance()->request()
            ]);
    }


    //返利订单数据源
    public function getFanli($filter){
         $list = Db::name('orderfanli')->where($filter)->order(['createtime' => 'desc'])->paginate(20, false, [
                'query' => Request::instance()->request()
            ]);
          //  echo $this->getLastSql();
 
            return $list;


    }
   
    //获得结算工资数据日志
    public function getJiesuan(){
         $list = Db::name('jiesuan')->order(['createtime' => 'desc'])->paginate(20, false, [
                'query' => Request::instance()->request()
            ]);
            return $list;
    }

    //结算
    public function jiesuan(){
        //判断上个月是否结算

        //查询上个月
        $today=strtotime(date('Y-m-01 00:00:00').'-1 month');
        $today2 = strtotime(date('Y-m-01 00:00:00').'-1 day');
        $start_time=date("Y-m-d H:i:s",$today);       
        $end_time=date("Y-m-d H:i:s",$today2);
        $map['createtime'] = array(array('egt',$start_time),array('lt',$end_time));
        $map['orderstate'] = 1;

        $js_list = Db::name('juanmi_jiesuan')->where("moths",date("Y-m-d",$today))->select();
        if(count($js_list)>0){
            return "上个月已经结算过了";
        }


        $orderlist = Db::name('orderfanli')->where($map)->select();

        $usertoal=array();

        foreach ($orderlist as $key => $value) {
             //下根绝订单编号，查询原始订单是否有效-做一次检验
            $datainfo = Db::name('order')->where("goods_order",$value['orderid'])->find();
            if($datainfo && $datainfo["order_status"] == 3){
                if(!$usertoal[$value['userid']])
                     $usertoal[$value['userid']] = 0;
                //存在 && 有效订单
                //增加余额
                $mlog = new MedelMoneylog();
                $mlog->InsetLog($value['userid'],2,10,$value['commission']);

                //修改返利状态
                $newdata["earningtime"] = date();
                Db::name('orderfanli')->where('id',$value['id'])->update($newdata);

                $usertoal[$value['userid']] = $usertoal[$value['userid']] + $value['commission'];
                 
                
            }
        }
        //插入工资表
        foreach ($usertoal as $key => $value) {
            $jsdata["userid"] = $key;
            $jsdata["moths"] =date("Y-m-d",$today);
            $jsdata["money"] =$value;
            $jsdata["createtime"] =date();
            Db::name('orderfanli')->insert($jsdata);
        }
    }
   
}
