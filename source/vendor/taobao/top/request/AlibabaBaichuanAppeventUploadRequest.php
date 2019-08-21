<?php
/**
 * TOP API: alibaba.baichuan.appevent.upload request
 * 
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class AlibabaBaichuanAppeventUploadRequest
{
	/** 
	 * 标识app
	 **/
	private $appid;
	
	/** 
	 * 标识场景
	 **/
	private $bizid;
	
	/** 
	 * 标识具体行为
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
		return "alibaba.baichuan.appevent.upload";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->appid,"appid");
		RequestCheckUtil::checkNotNull($this->bizid,"bizid");
		RequestCheckUtil::checkNotNull($this->params,"params");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
