<?php
/**
 * TOP API: taobao.open.account.index.find request
 * 
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class OpenAccountIndexFindRequest
{
	/** 
	 * int MOBILE         = 1;int EMAIL          = 2;int ISV_ACCOUNT_ID = 3;int LOGIN_ID       = 4;int OPEN_ID        = 5;
	 **/
	private $indexType;
	
	/** 
	 * 具体值，当索引类型是 OPEN_ID 是，格式为 oauthPlatform|openId，即使用竖线分隔的组合值
	 **/
	private $indexValue;
	
	private $apiParas = array();
	
	public function setIndexType($indexType)
	{
		$this->indexType = $indexType;
		$this->apiParas["index_type"] = $indexType;
	}

	public function getIndexType()
	{
		return $this->indexType;
	}

	public function setIndexValue($indexValue)
	{
		$this->indexValue = $indexValue;
		$this->apiParas["index_value"] = $indexValue;
	}

	public function getIndexValue()
	{
		return $this->indexValue;
	}

	public function getApiMethodName()
	{
		return "taobao.open.account.index.find";
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
