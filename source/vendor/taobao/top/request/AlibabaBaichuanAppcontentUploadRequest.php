<?php
/**
 * TOP API: alibaba.baichuan.appcontent.upload request
 * 
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class AlibabaBaichuanAppcontentUploadRequest
{
	/** 
	 * app标识
	 **/
	private $appid;
	
	/** 
	 * 业务场景标识
	 **/
	private $bizid;
	
	/** 
	 * 具体操作
	 **/
	private $operate;
	
	/** 
	 * 入参
	 **/
	private $params;
	
	private $apiParas = array();
	
	public function setAppid($appid)
	{
		$this->appid = $appid;
		$this->apiParas["appid"] = $appid;
	}

	public function getAppid()
	{
		return $this->appid;
	}

	public function setBizid($bizid)
	{
		$this->bizid = $bizid;
		$this->apiParas["bizid"] = $bizid;
	}

	public function getBizid()
	{
		return $this->bizid;
	}

	public function setOperate($operate)
	{
		$this->operate = $operate;
		$this->apiParas["operate"] = $operate;
	}

	public function getOperate()
	{
		return $this->operate;
	}

	public function setParams($params)
	{
		$this->params = $params;
		$this->apiParas["params"] = $params;
	}

	public function getParams()
	{
		return $this->params;
	}

	public function getApiMethodName()
	{
		return "alibaba.baichuan.appcontent.upload";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
