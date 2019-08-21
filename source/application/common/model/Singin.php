<?php

namespace app\common\model;

/**
 *  签到数据模块
 */
class Singin extends BaseModel
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }


    //根据用户查询用户签到数据
    public function getSingin($userid){
    	$singin = new Singin();	
    	$info = $singin->where(array('userid' =>$userid ))->find();	
    	return 	$info;
    }

    //保存更新
    public function saveSin($userid,$jsondata){
  
    	$singin = self::get(['userid' => $userid]);
    	if($singin){
    		$singin->allowfield(true)->save([
		    'signdata'  => $jsondata,
			],['userid' => $userid]);
    	}
    	else
    	{
    		$info = new Singin([
			    'signdata'  =>  $jsondata,
			    'userid' =>  $userid
			]);
			$info->allowfield(true)->save();
    	}
    }

}
