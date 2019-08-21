<?php

namespace app\api\controller;
vendor('taobao.TopSdk');
use think\Config;
use think\Cache;
use app\common\taobao\TkManager;
use app\common\taobao\TkFunction;
use app\common\taobao\TkConvert;
use app\common\taobao\TkDesc;
use app\common\model\wxmodel as WxApiModel;
use app\api\model\User as UserModel;


/**
 * 微信机器人接口；
 * Class Wxapi
 * @package app\api\controller
 */
 class WxApi extends Controller{
 	public function index()
 	{
 		echo "非法访问";
 	}

 	//获取发单的详细信息
 	//http://api.dataoke.com/index.php?r=Port/index&type=paoliang&appkey=b7d04eafc6&v=2
 	//大淘客跑量接口-该接口2小时更新一次
 	public function getitem($phone){
 		$model = new UserModel();
 		$user = $model->getuserByPhone($phone);
 		$relationid = $user["relationid"];
 		if($relationid == 0){
 			//渠道ID不存在；
 			return $this->renderError("没有此用户的渠道ID");
 		}

		$urls ='http://api.dataoke.com/index.php?r=Port/index&type=paoliang&appkey=b7d04eafc6&v=2';
		$datastr = curl($urls);	
		$clientmsg = json_decode($datastr);
		$data = $clientmsg->data;

		if($data->total_num >0){
			//随即获取当前需要发单的索引
			$curritemindex = rand(0,$data->total_num-1);
			//有数据
			$dkIteminfo = $clientmsg->result[$curritemindex];
			$items['img'] = $dkIteminfo->Pic;
			$items['selnumm'] = $dkIteminfo->Sales_num;
			$items['itemtitle'] = $dkIteminfo->Title;
			$items['newprice'] = $dkIteminfo->Price;
			$items['couprice'] = $dkIteminfo->Quan_price;
			$items['oldprice'] = $dkIteminfo->Org_Price;
			$items['itemid'] = $dkIteminfo->GoodsID;
			$items['content'] = $dkIteminfo->Introduce;
			$items['istmall'] = $dkIteminfo->IsTmall; //1-天猫


			$good_info = $this->getItemDetail($items['itemid']);
			if($good_info == null){
				return $this->renderError("物品不存在或者已下架");
			}

			$title = (string)$good_info->title;
 			$mainimg = (string)$good_info->pict_url;
 			$items['img'] =$mainimg;
 			//小图JSON字符串；
 			$small_images = $good_info->small_images;
 			$arrimg=array();
 			foreach ($small_images[0] as $key => $value) {
 				 array_push($arrimg, (string)$value);
 			}
 			$items['smallimg']= $arrimg;

 			$url = '';
	 		//劵二合一地址；
	 		if(isset($good_info->coupon_share_url)){
	 			$url = (string)$good_info->coupon_share_url;
	 		}else{
	 			$url = (string)$good_info->url;
	 		}
	 		$url  = TkConvert::isImghttp($url);
 			$url = $url."&relationId=".$relationid;


 			//*****************开始创建淘客令*************************/
	 		$c = TkManager::CreateTKApiAPP();
			$req = new \TbkTpwdCreateRequest;
			$req->setText($title);
			$req->setUrl($url);
			$req->setLogo($good_info->pict_url);
			$resp = $c->execute($req);
			if(isset($resp->code)){
				return $this->renderError((string)$resp->sub_msg); 
			}
			//商品口令
			$items['kouling'] = (string)$resp->data->model;
			 

			//*****************生产此商品的二维码*************************/ 
			//中间页面地址:
	 		$pageurl = "http://app.52juanmi.com/h5/sharegoods/index.html";
	 		$pageurl = $pageurl ."?word=".$items['kouling'];
	 		$pageurl = $pageurl ."&image=".$items['img'];
	 		$pageurl = $pageurl ."&title=".$items['itemtitle'];
	 		$dataimg = qrcode64($pageurl);
	 		$items["erweima"] = $dataimg;
	 		return $this->renderSuccess($items);


		}
		else{
 		 	return $this->renderError("没有发单数据");
 		}
 	}


 	 	//查找物品详情信息
 	//通过物料搜索查询；查询详细版本数据
 	private function getItemDetail($itemid){
 		$c = TkManager::CreateTKApiAPP();
 		$req = new \TbkDgMaterialOptionalRequest;
	 	$req->setAdzoneId(Config::get('TAOKE_ZONEID'));
	 	$url = "http://item.taobao.com/item.htm?id=".$itemid;
		$req->setQ($url);
		$resp = $c->execute($req);
		if($resp->total_results <=0 )
		{
			return null;
		}
		return $resp->result_list->map_data[0];
 	}



 	//扫码下载链接-
 	//微信授权信息保存
 	public function downurl(){
        $code = $this->request->param('code');
        if( ! $code ) {
            $this->renderError('缺少code参数!');
        }
        $yqcode = $this->request->param('state');
        if( ! $yqcode ) {
            $this->renderError('缺少邀请码参数!');
        }
         // 获取access_token
        //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?&grant_type=authorization_code';
        $url .= "&appid=".Config::get('WX_GZ_APPID');
        $url .= "&secret=".Config::get('WX_GZ_SECRET');
        $url .= "&code=".$code;

       
        $accessToken = \json_decode(file_get_contents($url));
        if( ! isset($accessToken->access_token, $accessToken->openid) ) {
            $this->renderError('获取数据失败!');
        }
        //获取用户信息
        $url2="https://api.weixin.qq.com/sns/userinfo?access_token=".$accessToken->access_token."&openid=".$accessToken->openid."&lang=zh_CN";
        $userInfo = \json_decode(file_get_contents($url2));
        if( ! isset($userInfo->openid) ) {
             $this->renderError('微信用户信息获取失败!');
        }

        $model = new UserModel();
        $usermodel = $model->getUnionid($userInfo->unionid);

        if(!isset($usermodel->userid))
        {
        	$regdata['openid']= $userInfo->openid;
        	$regdata['unionid']= $userInfo->unionid;
        	$regdata['nickname']= $userInfo->nickname;
        	$regdata['headimg']= $userInfo->headimgurl;
        	$regdata['sex']=  $userInfo->sex;
        	$regdata['yqcode']= $yqcode;
        
        	$model->loginWebWx($regdata);
        }

        //echo "uid:".$userInfo->unionid;
        $this->redirect(Config::get('DOWN_APP_URL'));

 	}

 }