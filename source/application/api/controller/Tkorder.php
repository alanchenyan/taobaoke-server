<?php
// +------------------------------------------------------------------------------------------------
// | 【使用说明】请将本文件上传至网站服务器：/application/index/controller/  目录下。
// +------------------------------------------------------------------------------------------------
// | [18淘客助手api文件(程序侠淘宝客专用)] Copyright (c) 2019 18.LA
// +------------------------------------------------------------------------------------------------
// | 最后修改：2019年4月19日
// +------------------------------------------------------------------------------------------------
// | 官网：http://taoke.18.la/
// +------------------------------------------------------------------------------------------------
 
namespace app\api\controller;

use think\Db;
use think\Request;
use think\Config;
use think\Cache;
use think\Log;
use app\common\taobao\PddApi;
use app\api\model\XingeApp;

class Tkorder extends Controller
{
	//定义返回数据
	private $return  = array('state'=>'ok','code'=>1,'message'=>'','version'=>'5.7');
	public function index()
    {
    	 
		$this->return['system']=urlencode('卷米淘客系统');//系统名称
		$this->return['updatetime']=urlencode('2019年6月16日');//最后修改日期

		//验证key
		$this->verify();

		//读取api参数
		$api=Request::instance()->post('api');

		//订单同步
		if($api=='postorder'){
			$this->postorder();
		}
		//拼多多订单同步
		elseif($api=='postpddorder'){
			$this->postpddorder();
		}
		//京东订单同步
		elseif($api=='postjdorder'){
			$this->postjdorder();
		}
		$this->returnExit($this->return,0,"未传入有效API参数");//code值设置为0，表示失败
    }






// +----------------------------------------
// | 接口验证相关函数
// +----------------------------------------
	//接口验证
	private function verify()
	{
		//读取post的基础参数
		$api=Request::instance()->post('api');//api名称
		$getkey=Request::instance()->post('key');
		//如果未传入有效参数
		if (!isset($getkey) || !isset($api)) {
			$this->returnExit($this->return,0,"API接口正常");//code值设置为0，表示失败
		}

		//读取网站授权码
		$web_auth_code = Config::get('ZHUSHOU_18KEY');

		//处理字符串
		 
		$getkey=strtoupper($getkey);
		$web_auth_code=trim($web_auth_code);
		$web_auth_code=strtoupper($web_auth_code);

		//判断密钥是否正确
		if($getkey!=$web_auth_code){
			$this->returnExit($this->return,0,"密钥错误:".$getkey." 配置:".$web_auth_code);//code值设置为0，表示失败
		}
		
		//接口验证
		if($api=='verify'){
			$this->returnExit($this->return,1,"验证成功");//code值设置为1，表示成功
		}
	}


// +----------------------------------------
// | 订单同步相关函数
// +----------------------------------------
	//订单同步
	private function postorder()
	{
		//获取post过来的订单内容
		$content=Request::instance()->post('content');
		if(empty($content)){
			$this->returnExit($this->return,0,"content参数不能为空");//code值设置为0，表示失败
		}
		$content=htmlspecialchars_decode($content);//把一些预定义的 HTML 实体转换为字符
		 
		$contentArr=@json_decode($content, true);//json解码
		//如果数组不为空
		if(!empty($contentArr)){
				//将订单数据以订单号和商品ID做键值，保存到新数组
				$contentArr=$this->orderOnly($contentArr);
				$db = Db::name('order');//操作union_order表
				$resultStr="";//记录订单入库结果字符串
	 
				//遍历订单数组
				foreach($contentArr as $orderData)
				{
					//判断是否为空数组
					if(count($orderData)<1){
						continue;
					}

					//读取订单编号和商品id
					$orderid=$orderData[0]['订单编号'];
					$goodsid=$orderData[0]['商品ID'];
					
					//遍历数组
					$id=0;
					$resultValue=1;
					foreach($orderData as $data){
						//格式化订单数据

						$newdata=$this->orderFormat($data);

						$goods_order_id = $db->where('goods_order',$orderid)->value('id');
						Db::startTrans();// 启动事务
						//判断数据是否存在
						if(isset($goods_order_id)){
							 
							//如果数据已存在,更新
							try{

								$result=$db->where('id',$goods_order_id)->update($newdata);
								// 提交事务
								Db::commit();
								$resultValue = ($resultValue==1) ? 1 : 0;//如果上次状态为1,这次也标记为1，否则设置为0
							} catch (\Exception $e) {
								// 回滚事务
								Db::rollback();
								$resultValue=0;
							}
						}else{
						 
							//如果数据不存在,新增
							try{
								$result=$db->insert($newdata);
								// 提交事务 
								Db::commit();
								if (!empty($result)) {
									//添加成功
									$resultValue = ($resultValue==1) ? 1 : 0;//如果上次状态为1,这次也标记为1，否则设置为0
								}else{
									//添加失败
									$resultValue=0;
								}
								
							} catch (\Exception $e) {
								// 回滚事务
								Db::rollback();
								Log::error('订单入库异常:'.$e->getMessage());
								$resultValue=0;
							} 
						}

						//订单入库成功 并且付款一条后；走返利流程
						if($resultValue == 1 && $newdata['order_status'] == 12){
							$this->SendUserFanli($newdata);
						}
						else if($resultValue == 1 && $newdata['order_status'] == 13){
							//取消返利
							$this->SendUserCancelFanli($newdata);
						}
						$id++;
					}
					//记录入库结果
					$resultStr=$this->resultState($resultStr,$orderid,$resultValue);
				}
				$this->returnExit($this->return,1,"result:".$resultStr);//code值设置为1，表示成功
			
			}else{
				$this->returnExit($this->return,0,"传入订单数据不正确");//code值设置为0，表示失败
			}
	}


	//将订单数据以订单号和商品ID做键值，保存到新数组
	private function orderOnly($contentArr)	
	{
		//遍历订单数组
		foreach($contentArr as $orderID=>$orderData)
		{
				foreach($orderData as $data){
					$key=$data['订单编号'].'_'.$data['商品ID'];
					$newData[$key][]=$data;
				}
		}
		return $newData;
	}



	//格式化订单数据
	function orderFormat($data){

		//将数据存储到符合当前系统的新数组
		$newData=array(
			//'id'=>0,//ID
			//'uid'=>0,//认领用户ID
			'type'=>0,//订单类型 0淘宝 1拼多多 2京东
			'goods_order'=>$data['订单编号'],//订单编号
			'goods_number'=>$data['商品数'],//成交数量
			'order_status'=>$data['订单状态'],//3：订单结算12：订单付款13:订单失效	
			'title'=>$data['商品信息'],//商品标题
			'goods_id'=>$data['商品ID'],//商品ID
			'price'=>$data['商品单价'],//商品价格
			'shop_type'=>$data['订单类型'],//订单类型
			'pay_price'=>$data['付款金额'],//付款金额
			'settlement_price'=>$data['结算金额'],//结算金额
			'commission'=>$data['效果预估'],//效果预估
			'commission_rate'=>$data['佣金比率'],//佣金比率
			//'status'=>0,//状态
			//'is_receive'=>0,//提现状态 0：未提现 1：已提现
			//'second_receive'=>0,//二代提现状态
			//'third_receive'=>0,//三代提现状态
			'terminal_type'=>$data['成交平台'],//成交平台
			'create_time'=>strtotime($data['创建时间']),//创建时间
			'adzone_id'=>$data['广告位ID'],//广告位ID
			'adzone_name'=>$data['渠道关系ID'] ? $this->getAdzoneName($data['渠道关系ID']) : $data['广告位名称'],//广告位名称
			'relation_id'=>$data['渠道关系ID'],//渠道id
			'special_id'=>$data['会员运营ID'],//渠道id
			'earning_time'=>strtotime($data['结算时间']),//结算时间
		);
		//将订单状态转化为程序侠支持的格式
		if($newData['order_status']=='订单结算' || $newData['order_status']=='订单完成'|| $newData['order_status']=='订单成功'){
			$newData['order_status']='3';
		}elseif($newData['order_status']=='订单付款'){
			$newData['order_status']='12';
		}if($newData['order_status']=='订单失效'){
			$newData['order_status']='13';
		}
		//处理维权订单
		if(strpos($data['维权状态'],"维权创建") !== false || strpos($data['维权状态'],"等待处理") !== false) {
			$newData['order_status']='12';//强制将订单状态设置为：订单付款
		}
		elseif(strpos($data['维权状态'],"维权成功") !== false) {
			$newData['order_status']='13';//强制将订单状态设置为：订单失效
		}
		elseif(strpos($data['维权状态'],"维权失败") !== false) {
			//$newData['order_status']='3';//强制将订单状态设置为：订单结算(因为订单也有可能本身是其他状态，所以此处不做处理即可)
		}

		//开启渠道返利
		if (isset($newData['relation_id'])) {
			//认领订单
			$selfuser = Db::name('user')->where('relationid',$newData['relation_id'])->find();
	   		if($selfuser){
	   			$newData['uid'] =$selfuser['userid'];
	   		}
		}
		return	$newData;
	}



 

// +----------------------------------------
// | 拼多多订单同步相关函数
// +----------------------------------------
	//订单同步
	private function postpddorder()
	{
		//获取post过来的订单内容
		$content=Request::instance()->post('content');
		if(empty($content)){
			$this->returnExit($this->return,0,"content参数不能为空");//code值设置为0，表示失败
		}
		$content=htmlspecialchars_decode($content);//把一些预定义的 HTML 实体转换为字符
		$contentArr=@json_decode($content, true);//json解码
	 
		//如果数组不为空
		if(!empty($contentArr)){
				$db = Db::name('order');//操作union_order表
				$resultStr="";//记录订单入库结果字符串
				//遍历订单数组
				foreach ($contentArr as $orderid => $orderdata)
				{
					//格式化订单数据
					$newdata = $this->orderFormatPdd($orderdata);
					//查询是否存在
					$goods_order_id = $db->where('goods_order',$orderid)->value('id');
					Db::startTrans();// 启动事务
					$resultValue=1;//定义存储返回结果变量
					//判断数据是否存在
					if(isset($goods_order_id)){
						//如果数据已存在,更新
						try{
							$result=$db->where('id',$goods_order_id)->update($newdata);
							// 提交事务
							Db::commit();
							$resultValue = 1;//不管数据是否有变动，都返回1
						} catch (\Exception $e) {
							// 回滚事务
							Db::rollback();
							$resultValue=0;
						}
					}else{
						//如果数据不存在,新增
						try{
							$result=$db->insert($newdata);
							// 提交事务
							Db::commit();
							$resultValue = (!empty($result)) ? 1 : 0;							
						} catch (\Exception $e) {
							// 回滚事务
							Db::rollback();
							$resultValue=0;
						}
					}

					//订单入库成功 并且付款一条后；走返利流程
					if($resultValue == 1 && $newdata['order_status'] == 12){
						$this->SendUserFanli($newdata);
					}
					else if($resultValue == 1 && $newdata['order_status'] == 13){
						//取消返利
						$this->SendUserCancelFanli($newdata);
					}


					//记录入库结果
					$resultStr=$this->resultState($resultStr,$orderid,$resultValue);
				}
				$this->returnExit($this->return,1,"result:".$resultStr);//code值设置为1，表示成功
			}else{
				$this->returnExit($this->return,0,"传入订单数据不正确");//code值设置为0，表示失败
			}
	}

	//格式化订单数据
	function orderFormatPdd($data){
		//设置订单状态
		$odtxt=$data['订单状态描述'];
		if ($odtxt == '未支付，已取消') {
			$order_status = 13;
		}elseif ($odtxt == '未支付') {
			$order_status = 13;
		}elseif ($odtxt == '已取消') {
			$order_status = 13;
		}elseif ($odtxt == '已支付') {
			$order_status = 12;
		}elseif ($odtxt == '待成团') {
			$order_status = 12;
		}elseif ($odtxt == '已成团') {
			$order_status = 12;
		}elseif ($odtxt == '确认收货') {
			$order_status = 12;
		}elseif ($odtxt == '审核通过') {
			$order_status = 12;
		}elseif ($odtxt == '审核失败') {
			$order_status = 13;
		}elseif ($odtxt == '已结算') {
			$order_status = 3;
		}elseif ($odtxt == '非多多进宝商品') {
			$order_status = 13;
		}elseif ($odtxt == '已处罚') {
			$order_status = 13;
		}else{
			$order_status = 13;
		}
		//设置结算时间
		$earning_time= (empty($data['结算时间'])) ? 0 : strtotime($data['结算时间']);

		//将数据存储到符合当前系统的新数组
		$newData=array(
			//'id'=>0,//ID
			//'uid'=>0,//认领用户ID
			'type'=>1,//订单类型 0淘宝 1拼多多 2京东
			'goods_order'=>$data['订单编号'],//订单编号
			'goods_number'=>$data['商品数'],//成交数量
			'order_status'=>$order_status,//3：订单结算12：订单付款13:订单失效	
			'title'=>$data['商品信息'],//商品标题
			'goods_id'=>$data['商品ID'],//商品ID
			'price'=>$data['商品价格'],//商品价格
			'shop_type'=>'拼多多',//订单类型
			'pay_price'=>$data['订单金额'],//付款金额
			'settlement_price'=>$data['订单金额'],//结算金额
			'commission'=>$data['预估佣金收入'],//效果预估
			'commission_rate'=>$data['佣金比例'],//佣金比率
			//'status'=>0,//状态
			//'is_receive'=>0,//提现状态 0：未提现 1：已提现
			//'second_receive'=>0,//二代提现状态
			//'third_receive'=>0,//三代提现状态
			'terminal_type'=>'无线',//成交平台
			'create_time'=>strtotime($data['创建时间']),//创建时间
			'adzone_id'=>$data['pid'],//广告位ID
			'adzone_name'=>$data['推广位名称'],//广告位名称
			'earning_time'=>$earning_time,//结算时间
		);

		//获取扩展字段信息
		$ddapi = new PddApi();
		$parme['order_sn']=$data['订单编号'];
		$datastr = $ddapi->GetPDDApi('pdd.ddk.order.detail.get',$parme);
		$orderinfo = json_decode($datastr,true);
		$orderdetail = $orderinfo['order_detail_response'];
		if($orderdetail){
			//获取自定义参数，就是用户ID
			$userid = $orderdetail['custom_parameters'];
			//$newData['relation_id'] = $userid;
			$newData['uid'] = $userid;
		}

		return	$newData;
	}


// +----------------------------------------
// | 拼多多订单同步相关函数
// +----------------------------------------
	//订单同步
	private function postjdorder()
	{
		//获取post过来的订单内容
		$content=Request::instance()->post('content');
		if(empty($content)){
			$this->returnExit($this->return,0,"content参数不能为空");//code值设置为0，表示失败
		}
		$content=htmlspecialchars_decode($content);//把一些预定义的 HTML 实体转换为字符
		$contentArr=@json_decode($content, true);//json解码
		//var_dump($contentArr);
		//如果数组不为空
		if(!empty($contentArr)){
				$db = Db::name('order');//操作union_order表
				$resultStr="";//记录订单入库结果字符串
				//遍历订单数组
				foreach ($contentArr as $orderid => $orderdata)
				{
					$resultValue=1;//定义存储返回结果变量
					foreach($orderdata as $data){
						//格式化订单数据
						$newdata = $this->orderFormatJd($data);
						//读取订单编号和商品id
						$orderid=$data['orderId'];
						$goodsid=$data['skuId'];
						//查询是否存在
						//查询是否存在
						$goods_order_id = $db->where('goods_order',$orderid)->value('id');
						Db::startTrans();// 启动事务
						//判断数据是否存在
						if(isset($goods_order_id)){
							//如果数据已存在,更新
							try{
								$result=$db->where('id',$goods_order_id)->update($newdata);
								// 提交事务
								Db::commit();
								//$resultValue = 1;//不管数据是否有变动，都返回1
								$resultValue = ($resultValue==1) ? 1 : 0;//如果上次状态为1,这次也标记为1，否则设置为0
							} catch (\Exception $e) {
								// 回滚事务
								Db::rollback();
								$resultValue=0;
							}
						}else{
							//如果数据不存在,新增
							try{
								$result=$db->insert($newdata);
								// 提交事务
								Db::commit();
								if (!empty($result)) {
									//添加成功
									$resultValue = ($resultValue==1) ? 1 : 0;//如果上次状态为1,这次也标记为1，否则设置为0
								}else{
									//添加失败
									$resultValue=0;
								}						
							} catch (\Exception $e) {
								// 回滚事务
								Db::rollback();
								$resultValue=0;
							}
						}
						//订单入库成功 并且付款一条后；走返利流程
						if($resultValue == 1 && $newdata['order_status'] == 12){
							$this->SendUserFanli($newdata);
						}
						else if($resultValue == 1 && $newdata['order_status'] == 13){
							//取消返利
							$this->SendUserCancelFanli($newdata);
						}

					}
					//记录入库结果
					$resultStr=$this->resultState($resultStr,$orderid,$resultValue);
				}
				$this->returnExit($this->return,1,"result:".$resultStr);//code值设置为1，表示成功
			}else{
				$this->returnExit($this->return,0,"传入订单数据不正确");//code值设置为0，表示失败
			}
	}

	//格式化订单数据
	function orderFormatJd($data){
		//设置订单状态
		$validCode=$data['validCode'];
		if ($validCode == 16 ||$validCode == 17) {
			$order_status = 12;//订单付款
		}elseif ($validCode == 18) {
			$order_status = 3;//订单结算
		}else{
			$order_status = 13;//订单失效
		}
		//设置结算时间
		$finishTime=$data['finishTime'];//结算时间
		$finishTime=($finishTime == 0 || $finishTime == "0")?0:strtotime($finishTime);

		//将数据存储到符合当前系统的新数组
		$newData=array(
			//'id'=>0,//ID
			//'uid'=>0,//认领用户ID
			'type'=>2,//订单类型 0淘宝 1拼多多 2京东
			'goods_order'=>$data['orderId'],//订单编号
			'goods_number'=>$data['skuNum'],//成交数量
			'order_status'=>$order_status,//3：订单结算12：订单付款13:订单失效	
			'title'=>$data['skuName'],//商品标题
			'goods_id'=>$data['skuId'],//商品ID
			'price'=>$data['price'],//商品价格
			'shop_type'=>'京东',//订单类型
			'pay_price'=>$data['estimateCosPrice'],//付款金额
			'settlement_price'=>$data['actualCosPrice'],//结算金额
			'commission'=>$data['estimateFee'],//效果预估
			'commission_rate'=>$data['commissionRate'],//佣金比率
			//'status'=>0,//状态
			//'is_receive'=>0,//提现状态 0：未提现 1：已提现
			//'second_receive'=>0,//二代提现状态
			//'third_receive'=>0,//三代提现状态
			'terminal_type'=>'无线',//成交平台
			'create_time'=>strtotime($data['orderTime']),//创建时间
			'adzone_id'=>$data['positionId'],//广告位ID
			'adzone_name'=>$data['positionId'],//广告位名称
			//'relation_id'=>$data['positionId'],//这个时候传 广告位ID 就是用户ID
			'earning_time'=>$finishTime,//结算时间
		);

		$newData['uid'] = $data['positionId'];
		return	$newData;
	}



// +----------------------------------------
// | 其他函数
// +----------------------------------------
	//返回json信息并退出
	private function returnExit($return,$code,$message,$data=''){
		$return['code']=$code;
		$return['message']=urlencode($message);
		if(!empty($data))	$return['data']=$data;
		exit(urldecode(json_encode($return)));
	}


	//构造返回状态结果字符串
	private function resultState($result,$id,$v){
		if (!empty($result))	$result.='|';
		$result.=$id.':'.$v;
		return	$result;
	}

	//清空缓存
	private function clearcache() {
		//清空缓存
		array_map( 'unlink', glob(RUNTIME_PATH.'/cache/*/*.php' ) );
		array_map( 'unlink', glob(RUNTIME_PATH.'/temp/*.php' ) );
	}



	//构造返利数据
	//$main_userid：产生订单的用户ID
	//$flevel:当前返利的等级fomatFanli
	//$yuprice:预估收入fomatFanli
	public function fomatFanli($data,$main_userid,$flevel,$yuprice,$rate,$userid){
			//将数据存储到符合当前系统的新数组
		$newData=array(
			'orderid'=>$data['goods_order'],//订单编号
			'orderuserid'=>$main_userid,//产生订单userid ID
			'fllevel'=>$flevel,//返利的层级
			'price'=>$data['pay_price'],//付款价格
			'itemnum'=>$data['goods_number'],//成交的数量
			'commission'=>$yuprice,//预估收入
			'commissionrate'=>$rate,
			'createtime'=>date('Y-m-d h:i:s', time()),
			'userid'=>$userid,
			'ordertype'=>$data['type'], //订单类型 0淘宝 1拼多多 2京东
			'itemtitle'=>$data['title'],//商品标题
			'itemid'=>$data['goods_id'], //商品标题
			'orderstate'=>1,

		);
		return $newData;

	}

	public function ts(){
		 /* if(Config::get('XINGGE_ACCID')!=''){
		  	$userid = '10160';
        	$content = '恭喜您!你有一笔来自 200的订单收益';
        	echo Config::get('XINGGE_ACCESSKEY');
        	$ret = XingeApp::PushAccountAndroid('2100338115', Config::get('XINGGE_ACCESSKEY'), "新的订单收益提醒", $content, $userid);
        	var_dump($ret);
        }*/
	}

	/**
    *计算返利价格
    *@param  
    *@param  orderstate 0-失效 1-有效  
    */
    public function SendUserFanli($data){
    	if(!isset($data['uid']))
    		return;
    	$userid =  $data['uid'];  
        //判断返利数据 是否已经返利过了；如果返利过来 就不在进行数据插入；
        $wheremap['orderid'] = $data['goods_order'];
        $wheremap['orderuserid'] = $userid;
        $orderinfo = Db::name('orderfanli')->where($wheremap)->find();
        if($orderinfo)
        	return;

        //查询用户
        $selfuser = Db::name('user')->where('userid',$userid)->find();
        if(!$selfuser)
        	return;
        $fanliconfig = Config::get('fanli_config');
        //自购
        $userlevel = $selfuser['ulevel'];
        $fanli_info  = $fanliconfig[$userlevel - 1];
        if(!$fanli_info){ return; }
        if($data['type']==1){
        	//拼多多
        	$commission = $data['commission'] * $fanli_info['ratepdd'];  
        }
        else if($data['type']==2){
        	//京东
        	$commission = $data['commission'] * $fanli_info['ratejd'];  
        }else{
        	$commission = $data['commission'] * $fanli_info['rate'];
        }
         
        $money = round($commission,2);
        $fanlidata = $this->fomatFanli($data,$userid,0,$money,$fanli_info['rate'],$userid);
        $res = Db::name('orderfanli')->insert($fanlidata);

        if(Config::get('XINGGE_ACCID')!=''){
        	$content = '恭喜您!你有一笔来自['.$data['shop_type']."]的订单收益";
        	$ret = XingeApp::PushAccountAndroid(Config::get('XINGGE_ACCID'), Config::get('XINGGE_ACCESSKEY'), "新的订单收益提醒",$content,(string)$userid);
        }
        	 
       

        if($res > 0){
        	 //上级返利
	        if($selfuser['agentid'] == 0) return;
	        $userinfo1 = Db::name('User')->where('userid',$selfuser['agentid'])->where('ustate','1')->find();
	        if(!$userinfo1) return;
	        $userlevel = $userinfo1['ulevel'];
	        $fanli_info  = $fanliconfig[$userlevel - 1];
	        if(!$fanli_info){ return; }

	        $commission = $data['commission'] * $fanli_info['rate1'];
	        $money = round($commission,2);
	        $fanlidata = $this->fomatFanli($data,$userid,1,$money,$fanli_info['rate1'],$userinfo1['userid']);
	        $res1 = Db::name('orderfanli')->insert($fanlidata);

	        if(Config::get('XINGGE_ACCID')!=''){
	        	$content = '恭喜您!你有一笔来自['.$data['shop_type']."]的订单收益";
	        	$ret = XingeApp::PushAccountAndroid(Config::get('XINGGE_ACCID'), Config::get('XINGGE_ACCESSKEY'), "新的订单收益提醒", $content, (string)$userinfo1['userid']);
        	}

	        //上上级返利
	        if($res1 > 0){
	        	 if($userinfo1['agentid'] == 0) return;
	        	 $userinfo2 = Db::name('User')->where('userid',$userinfo1['agentid'])->where('ustate','1')->find();
	        	 if(!$userinfo2) return;
	        	 $userlevel = $userinfo2['ulevel'];
	        	 $fanli_info  = $fanliconfig[$userlevel - 1];
	        	 if(!$fanli_info){ return; }

	        	 $commission = $data['commission'] * $fanli_info['rate2'];
		         $money = round($commission,2);
		         $fanlidata = $this->fomatFanli($data,$userid,3,$money,$fanli_info['rate2'],$userinfo2['userid']);
		         $res2 = Db::name('orderfanli')->insert($fanlidata);

		         if(Config::get('XINGGE_ACCID')!=''){
			        	$content = '恭喜您!你有一笔来自['.$data['shop_type']."]的订单收益";
			        	$ret = XingeApp::PushAccountAndroid(Config::get('XINGGE_ACCID'), Config::get('XINGGE_ACCESSKEY'), "新的订单收益提醒", $content, (string)$userinfo2['userid']);
        		 }
	        }

        }

        return true;
    }


    //取消返利
    public function SendUserCancelFanli($data){
    	$newdata['orderstate']=0;
    	$result=Db::name('orderfanli')->where('orderid',$data['goods_order'])->update($newdata);
    }



    //失效订处理
    public function procordererror($data){
    	if($data['relation_id'] == 0)
    		return;
        $orderinfo = Db::name('orderfanli')->where('orderid',$data['goods_order'])->find();
        if(!$orderinfo)
        	return;
        $updata["orderstate"]=0;
        Db::name('orderfanli')->where('orderid',$data['goods_order'])->update($updata);


    }

    //推广位名称
    public function getAdzoneName($relation_id){
      $name ='';
      if ($relation_id) {
        $name = '渠道&会员';
      }
      return $name;
    }

 
 

}