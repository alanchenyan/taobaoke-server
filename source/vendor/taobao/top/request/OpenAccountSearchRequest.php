<?php
/**
 * TOP API: taobao.open.account.search request
 * 
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class OpenAccountSearchRequest
{
	/** 
	 * 基于阿里云OpenSearch服务，openSearch查询语法:https://help.aliyun.com/document_detail/29157.html，搜索服务能够基于id，loginId，mobile，email，isvAccountId，display_name,create_app_key,做搜索查询，示例中mobile可以基于模糊搜素，搜索135的账号，该搜索是分页返回，start表示开始行，hit表示一页返回值，最大500
	 **/
	private $query;
	
	private $apiParas = array();
	
	public function setQuery($query)
	{
		$this->query = $query;
		$this->apiParas["query"] = $query;
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function getApiMethodName()
	{
		return "taobao.open.account.search";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->query,"query");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
