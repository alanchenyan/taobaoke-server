<?php
/**
 * TOP API: alibaba.baichuan.appevent.batchupload request
 * 
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class AlibabaBaichuanAppeventBatchuploadRequest
{
	/** 
	 * app标识
	 **/
	private $appid;
	
	/** 
	 * 场景标识
	 **/
	private $bizid;
	
	/** 
	 * 具体实例集合
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
		return "alibaba.baichuan.appevent.batchupload";
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
