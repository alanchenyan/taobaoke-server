<?php

namespace app\common\model;
 
use think\Cache;



/**
*  
*   系统数据模块  
*/
class Syscfg extends BaseModel
{
 
  //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }


    //根据系统参数数据
    //$cid渠道ID
	public function getSysinfo($cid)
	{
		 
		$sys = new Syscfg();
		$sysinfo = $sys->where(array('cid' =>$cid ))->find();
		return $sysinfo;
	}

}