<?php

namespace app\common\model;
use think\Db;
use think\Config;
/**
 *  签到数据模块
 */
class UserExt extends BaseModel
{
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }


    //是否领取了新人红包
    public function isRedPack($userid){
        $ext = new Userext();
        $sysinfo = $ext->where(array('userid' =>$userid ))->find();
        return $sysinfo['redpack'];

    }

    public function addRedPack($userid){
        $ext = new Userext();
        $ext['redpack'] = 1;
        $ext['userid'] = $userid;
        $ext->allowfield(true)->save();
    }

   

}
