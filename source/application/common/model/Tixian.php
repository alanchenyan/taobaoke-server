<?php

namespace app\common\model;
use app\common\model\Moneylog  as MedelMoneylog;
/**
 * 会员提现功能
 * Class Tixian
 * @package app\common\model
 */
class Tixian extends BaseModel
{
    
    //查询当日是否有提现功能；
    public function getTixianDay($userid){
        //查询今日
        $today=strtotime(date('Y-m-d 00:00:00'));
        $today2=strtotime(date('Y-m-d 00:00:00').'+1 day');
        $start_time=date("Y-m-d H:i:s",$today);       
        $end_time=date("Y-m-d H:i:s",$today2);       
        $map['createtime'] = array(array('egt',$start_time),array('lt',$end_time));
        $map['userid'] = $userid;
        $co =  $this->where($map)->count();
        return $co;

    }

    //插入提现信息
    public function inserTx($userid,$name,$payadr,$money){
        $info = new Tixian();
        $info['userid'] = $userid;
        $info['username'] = $name;
        $info['pays'] = $payadr;
        $info['money'] = $money;
        $info['createtime'] =  date('Y-m-d H:i:s',time());
        //扣除余额
        $mlog = new MedelMoneylog();
        $mlog->InsetLog($userid,2,8,-$money);
        return $info->allowfield(true)->save();

    }

}
