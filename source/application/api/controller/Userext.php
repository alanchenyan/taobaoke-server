<?php

namespace app\api\controller;

use app\api\model\User as UserModel;

use app\common\model\Order  as MedelOrder;
use app\common\model\Moneylog  as MedelMoneylog;
use app\common\model\UserExt  as MedelUserext;

/**
 * 用户管理
 * Class User
 * @package app\api
 */
class Userext extends Controller{
	public function index()
    {
         
    }


    //获取用户是否领取信任红包
    public function isredpack(){
    	$userinfo= $this->getUser();
    	$model = new MedelUserext();
    	$ret = $model->isRedPack($userinfo["userid"]);
    	if(!$ret || $ret == 0){
    		//没有领取红包
    		$rdmoney = rand(1, 20);
    		$rdmoney = $rdmoney/10;
    		$msg['ret']= 0;
			$msg['money']= $rdmoney;

			$mlog = new MedelMoneylog();
            $mlog->InsetLog($userinfo["userid"],2,9,$rdmoney);

            //修改已领取红包
            $model->addRedPack($userinfo["userid"]);
		 

			return $this->renderSuccess($msg);
    	}else
    	{
    		return $this->renderError(''); 
    	}
 
    }


}