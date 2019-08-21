<?php
/**
 * TOP API: taobao.open.account.list request
 * 
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class OpenAccountListRequest
{
	/** 
	 * ISV自己账号的id列表，isvAccountId和openAccountId二选一必填, 每次最多查询 20 个帐户
	 **/
	private $isvAccountIds;
	
	/** 
	 * Open Account的id列表, 每次最多查询 20 个帐户
	 **/
	private $openAccountIds;
	
	private $apiParas = array();
	
	public function setIsvAccountIds($isvAccountIds)
	{
		$this->isvAccountIds = $isvAccountIds;
		$this->apiParas["isv_account_ids"] = $isvAccountIds;
	}

	public function getIsvAccountIds()
	{
		return $this->isvAccountIds;
	}

	public function setOpenAccountIds($openAccountIds)
	{
		$this->openAccountIds = $openAccountIds;
		$this->apiParas["open_account_ids"] = $openAccountIds;
	}

	public function getOpenAccountIds()
	{
		return $this->openAccountIds;
	}

	public function getApiMethodName()
	{
		return "taobao.open.account.list";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkMaxListSize($this->isvAccountIds,20,"isvAccountIds");
		RequestCheckUtil::checkMaxListSize($this->openAccountIds,20,"openAccountIds");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
