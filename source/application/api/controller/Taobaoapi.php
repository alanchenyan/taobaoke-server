<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
use app\common\taobao\TkManager;
use app\common\taobao\TkFunction;
use app\api\model\User as UserModel;

/**
 * 淘宝授权管理
 * Class OtherAPI
 * @package app\api\controller
 */
 class TaobaoAPI extends Controller
 {

 	//备案渠道ID信息
 	//文档地址:https://open.taobao.com/api.htm?docId=37988&docType=2&scopeId=14474
 	protected function get_rid($session)
 	{
 		//{"data":{"account_name":"熊**3","desc":"绑定成功","relation_id":"543608511"},"request_id":"6v5uiuf59c7s"}
 		$c = TkManager::CreateTKApiAPP();
		$req = new \TbkScPublisherInfoSaveRequest;
		$req->setRelationFrom("1");
		$req->setOfflineScene("1");
		$req->setOnlineScene("1");
		$req->setInviterCode(Config::get('RID_CODE'));
		$req->setInfoType("1");
		$req->setNote("卷米渠道");
		$resp = $c->execute($req, $session);
		return $resp;	
 	}

 	//会员备案信息
 	//只有营销主推商品库的商品才能够支持会员运营 id(即special_id) 的回传，故而在推广前请务必确认推广商品在营销主推商品库中。②营销主推商品库商品每日会变动，每日上午 8:00 以 后做更新
 	//material_id:固定入参为 6268

 	public function get_sid($session)
 	{
 		//{"data":{"account_name":"熊**3","desc":"绑定成功","relation_id":"543608511"},"request_id":"6v5uiuf59c7s"}
 		$c = TkManager::CreateTKApiAPP();
		$req = new \TbkScPublisherInfoSaveRequest;
		$req->setRelationFrom("1");
		$req->setOfflineScene("1");
		$req->setOnlineScene("1");
		$req->setInviterCode(Config::get('SID_CODE'));
		$req->setInfoType("1");
		$req->setNote("卷米会员");
		$resp = $c->execute($req, $session);
		return $resp;	
 	}



 	//获得授权回调
 	public function authorcode($code,$state)
 	{
 		$msg = array();
 		$msg['code'] = $code;
 		$msg['state'] = $state;
 		return $this->renderSuccess($msg);
 	}

 	//换取  换取access_token
 	//https://oauth.taobao.com/token
 	//token:用户的凭证
 	public function getaccesstoken($code,$token,$isrid)
 	{
 		//判断用户是否登录
 		$userinfo = $this->getUser($token);
 		if(!$userinfo){
 			//用户登录失败
 			return $this->renderErrorLogin('登录失效'); 
 		}
 		$urls ='https://oauth.taobao.com/token';
		$postfields= array('grant_type'=>'authorization_code',
		 'grant_type'=>'authorization_code',
		 'client_id'=>Config::get('LM_APPKEY'),
		 'client_secret'=>Config::get('LM_APPSECRET'),
		 'code'=>$code,
		 'view'=>'wap',
		 'redirect_uri'=>'http://app.52juanmi.com/web/');
		$post_data = '';
		foreach($postfields as $key=>$value){
 			$post_data .="$key=".urlencode($value)."&";
 		}

 		$ch = curl_init();
 		curl_setopt($ch, CURLOPT_URL, $urls);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, substr($post_data,0,-1));
		$output = curl_exec($ch);
		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$dataobj = json_decode($output,true);
		if(isset($dataobj['access_token'])){
			$resp = null;
			if($isrid == 1){
				//获得渠道ID
				$resp = $this->get_rid($dataobj['access_token']);
			}else{
				//获得会员ID
				$resp = $this->get_sid($dataobj['access_token']);
			}

			if(isset($resp->code)){
				//授权失败记录日志
				write_log(json_encode($resp), __DIR__);
				return $this->renderError($resp);
			}
			else
			{
				$apidata = $resp->data;
				$model = new UserModel();
				$client =array();
				if($isrid == 1){
					$rid = $apidata->relation_id;
					//绑定渠道
					$client = $model->BindRid($userinfo['userid'],$rid);
				}
				else{
					$sid = $apidata->special_id;
					//绑定会员
					$client = $model->BindSid($userinfo['userid'],$sid);
				}
				if($client['code'] == 0)
				{
					return $this->renderSuccess($client);
				}
				else
				{
				 	
					return $this->renderError($client); 
				}
			}
			 
		}
		else
		{
			return $this->renderError($dataobj); 
		}

 		 
 	}



 }
