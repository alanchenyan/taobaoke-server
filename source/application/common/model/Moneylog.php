<?php

namespace app\common\model;
use think\Db;
use think\Config;
/**
 *  签到数据模块
 */
class Moneylog extends BaseModel
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }


    //插入日志
    //typeid :1 米币 2：余额
    public function InsetLog($userid,$typeid,$subtype,$value){
        $strname = '';
        if($subtype == 1){
            $strname = '签到获得米币:'.$value;
        }
        else if($subtype == 2){
            $strname = '连续签到获得米币:'.$value;
        }
        else if($subtype == 3){
            $strname = '连续签到获得红包:'.$value;
        }
        else if($subtype == 4){
            $strname = '米币兑换扣除:'.$value;
        }
        else if($subtype == 5){
            $strname = '兑换红包获得:'.$value;
        }
        else if($subtype == 6){
            $strname = '绑定支付获得米币:'.$value;
        }
        else if($subtype == 7){
            $strname = '授权淘宝获得米币:'.$value;
        }
        else if($subtype == 8){
            $strname = '会员提现:'.$value;
        }
         else if($subtype == 9){
            $strname = '新人领取红包:'.$value;
        }
        else if($subtype == 10){
            $strname = '工资结算:'.$value;
        }
        else if($subtype == 11){
            $strname = '新人注册奖励:'.$value;
        }

        if($strname == '')
            return;
        $prefix = Config::get('database')['prefix'];
        if($typeid == 1){ 
            if($value >0) 
                Db::table($prefix.'user')->where('userid',$userid)->setInc('uscore',$value);
            else
                Db::table($prefix.'user')->where('userid',$userid)->setDec('uscore',-$value);
        }
        else if($typeid == 2){
             if($value >0) 
                Db::table($prefix.'user')->where('userid',$userid)->setInc('umoney',$value);
            else
                Db::table($prefix.'user')->where('userid',$userid)->setDec('umoney', -$value);

        }
        $moneylog = new Moneylog();
        $moneylog['title'] =  $strname;
        $moneylog['dtime'] =  date('Y-m-d H:i:s',time());
        $moneylog['userid'] =  $userid;
        $moneylog['value'] =  $value;
        $moneylog['types'] =  $subtype;
        $moneylog['moneytype'] =  $typeid;
        $moneylog->allowfield(true)->save();

    }

    //根据用户查询用户签到数据
    public function getloglist($userid,$typeid, $pageindex){
      
    	$list = Db::name('moneylog')->where('moneytype',$typeid)->where('userid',$userid)->order('dtime','desc')->paginate(20,true,[
                    'list_rows' => 20,
                    'page'      => $pageindex,
            ]);
    	return 	$list;
    }

   

}
