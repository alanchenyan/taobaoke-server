<?php

namespace app\api\controller;
 
use think\Config;
use think\Cache;
use think\Db;

use app\common\model\Singin  as MedelSingin;
use app\common\model\Moneylog  as MedelMoneylog; 
use app\api\model\User as UserModel;
//活动相关数据接口
 class Activec extends Controller{
 	public function index()
 	{
 		echo "非法访问";
 	}

 	public function test(){
 		echo date("Ymd",strtotime("-0 day"));
 	}

 	//获取这个用户签到数据
 	public function singinlist($token){
 		$userinfo = $this->getUser($token);
 		if(!$userinfo){
 			return $this->renderErrorLogin('登录失效'); 
 		}
 		$model = new MedelSingin();
 		$sigin_user = $model->getSingin($userinfo['userid']);
 		if($sigin_user)
 			return $this->renderSuccess($sigin_user);
 		else
 			return $this->renderError('');
 	}

 	//签到数据
 	//$token： 用户登录的凭证
 	public function singin($token){
 		//判断用户是否登录
 		$userinfo = $this->getUser($token);
 		if(!$userinfo){
 			return $this->renderErrorLogin('登录失效'); 
 		}

 		$model = new MedelSingin();
 		$sigin_user = $model->getSingin($userinfo['userid']);
 	 	//当前时间
 	 	$currtime = date("Ymd");
 		$newjson = ''; 
 		$arrayobj = array();
 		if($sigin_user != null){
 			$datastr = $sigin_user['signdata'];
 			$arrayobj = json_decode($datastr);

 			//检查当天是否签到
 			foreach ($arrayobj as $key => $value) {
 				if($value->times == $currtime){
 					return $this->renderError('当日已签到,请明天再来');
 				}
 			}

 			$data['signDay'] = date("d");
 			$data['isget'] = 0;
 			$data['times'] = $currtime;
 			array_unshift($arrayobj, $data);
 			$newjson = json_encode($arrayobj);
 		}
 		else
 		{
 			$data['signDay'] = date("d");
 			$data['isget'] = 0;
 			$data['times'] = $currtime;
 			array_push($arrayobj, $data);
 			$newjson = json_encode($arrayobj);
 		}


 		//更新保存
 		$model->saveSin($userinfo['userid'],$newjson);
 		$mlog = new MedelMoneylog();
 		$mlog->InsetLog($userinfo['userid'],1,1,20);

 		//清理30天之前的数据
 		return $this->renderSuccess($arrayobj);
 	}
 

 	//领取签到奖励
    public function getreward($token,$id){
    	//判断用户是否登录
 		$userinfo = $this->getUser($token);
 		if(!$userinfo){
 			return $this->renderErrorLogin('登录失效'); 
 		}
 		$model = new MedelSingin();
 		$sigin_user = $model->getSingin($userinfo['userid']);
 		if($sigin_user != null){
 			$datastr = $sigin_user['signdata'];
 			$arrayobj = json_decode($datastr);
 			$days = 0;
 			$dtype = 0;
 			$subtype = 2;
 			$value = 0;
 			if($id == 1){
 				$days = 3;
 				$dtype = 1;
 				$value = 50;
 			}
 			else if($id == 2){
 				$days = 7;
 				$dtype = 1;
 				$value = 200;
 			}
 			else if($id == 3){
 				$days = 15;
 				$dtype = 2;
 				$value = 3;
 				$subtype = 3;
 			}
 			else if($id == 4){
 				$days = 20;
 				$dtype = 2;
 				$value = 5;
 				$subtype = 3;
 			}
 			else if($id == 5){
 				$days = 30;
 				$dtype = 2;
 				$value = 8;
 				$subtype = 3;
 			}

 			$flag = false;
 			$count = 0;
 			for ($i=0; $i <$days ; $i++) {
 				if ($i==0) 
 					$s_value = "0 day";
 				else
 					$s_value = "-".$i." day";
 
 				 $ctime = date("Ymd",strtotime($s_value));
 				 //检查连续性
 				 foreach ($arrayobj as $key => $objs) {
	 				if($objs->times == $ctime &&  $objs->isget == 0){
	 					$count = $count +1;
	 					$objs->isget = 1;
	 				}
 				}
 			}
 			if($count == $days){
 				//连续签到达到
 				$flag = true;
 				 //更新保存
 				$newjson = json_encode($arrayobj);
 				$model->saveSin($userinfo['userid'],$newjson);
 				$mlog = new MedelMoneylog();
 				$mlog->InsetLog($userinfo['userid'],$dtype,$subtype,$value);
 				$cl['list'] = $arrayobj;
 				$cl['dtype'] = $dtype;
 				$cl['dvalue'] = $value;
 				return $this->renderSuccess($cl);
 			}
 			else{
 				return $this->renderError('累计签到日期不够');
 			}


 		}
 		else
 		{
 			return $this->renderError('累计签到日期不够');
 		}
    }

    //获取兑换列表数据
    public function getscorelist($dt=0){
    	$arrayName = array();
    	$datainfo['id'] =1;
    	$datainfo['score'] =2000;
    	$datainfo['money'] =20;
    	array_push($arrayName, $datainfo);
    	$datainfo1['id'] =2;
    	$datainfo1['score'] =5000;
    	$datainfo1['money'] =50;
    	$datainfo2['id'] =3;
    	$datainfo2['score'] =10000;
    	$datainfo2['money'] =100;
    	array_push($arrayName, $datainfo1);
    	array_push($arrayName, $datainfo2);
    	if($dt == 0)
    		return $this->renderSuccess($arrayName);
    	else
    		return $arrayName;

    }

    //兑换红包接口
    public function dxitem($token,$id){
    	//判断用户是否登录
 		$userinfo = $this->getUser($token);
 		if(!$userinfo){
 			return $this->renderErrorLogin('登录失效'); 
 		}
 		$datalist = $this->getscorelist(1);
 		$datainfo = null;
 		for ($i=0; $i < count($datalist); $i++) { 
 			 if($datalist[$i]['id'] == $id)
 			 {
 			 	$datainfo = $datalist[$i];
 			 }
 		}
 		//判断积分是否足够
 		if($datainfo['score'] > $userinfo['uscore']){
 			return $this->renderError('您的积分不足');
 		}
 		//扣除积分
 		$mlog = new MedelMoneylog();
 		$mlog->InsetLog($userinfo['userid'],1,4,-$datainfo['score']);
 		//增加余额
 		$mlog->InsetLog($userinfo['userid'],2,5,$datainfo['money']);

 		$datainfo['money'] = $datainfo['money'];
 		$datainfo['score'] = $datainfo['score'];
 		return $this->renderSuccess($datainfo);
    }

    //查询货币日志
    public function getdlog($pageindex=1,$typeid=1,$token){
    	$userinfo = $this->getUser($token);
 		if(!$userinfo){
 			return $this->renderErrorLogin('登录失效'); 
 		}
    	$mlog = new MedelMoneylog();
    	$list = $mlog->getloglist($userinfo['userid'],$typeid, $pageindex);
    	if(count($list)==0){
    		return $this->renderError('');
    	}
    	return $this->renderSuccess($list);
    }

 }