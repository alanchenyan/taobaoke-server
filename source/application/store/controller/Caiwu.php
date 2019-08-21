<?php

namespace app\store\controller;

use app\common\library\sms\Driver as SmsDriver;
use think\Db;
use think\Request;
use think\Config;
use think\Cache;
use think\Log;



/**
 * 发圈设置
 * Class Setting
 * @package app\store\controller
 */
class Caiwu extends Controller
{

	//提现列表
	public function index()
	{
        $request = Request::instance();
		$list = Db::name('tixian')->order('createtime', 'DESC')->paginate(20,false,['query' => $request->request()]);
      
        return $this->fetch('index',compact('list'));
	}

    //货币列表
    public function index2()
    {
        $request = Request::instance();
      $list = Db::name('moneylog')->order('dtime', 'DESC')->paginate(20,false,['query' => $request->request()]);
        return $this->fetch('index2',compact('list'));
    }

 
	 

}