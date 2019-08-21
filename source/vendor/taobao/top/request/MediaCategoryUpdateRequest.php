<?php
/**
 * TOP API: taobao.media.category.update request
 * 
 * @author auto create
 * @since 1.0, 2019.01.21
 */
class MediaCategoryUpdateRequest
{
	/** 
	 * 文件分类ID,不能为空
	 **/
	private $mediaCategoryId;
	
	/** 
	 * 文件分类名，最大长度20字符，中英文都算一字符,不能为空
	 **/
	private $mediaCategoryName;
	
	private $apiParas = array();
	
	public function setMediaCategoryId($mediaCategoryId)
	{
		$this->mediaCategoryId = $mediaCategoryId;
		$this->apiParas["media_category_id"] = $mediaCategoryId;
	}

	public function getMediaCategoryId()
	{
		return $this->mediaCategoryId;
	}

	public function setMediaCategoryName($mediaCategoryName)
	{
		$this->mediaCategoryName = $mediaCategoryName;
		$this->apiParas["media_category_name"] = $mediaCategoryName;
	}

	public function getMediaCategoryName()
	{
		return $this->mediaCategoryName;
	}

	public function getApiMethodName()
	{
		return "taobao.media.category.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->mediaCategoryId,"mediaCategoryId");
		RequestCheckUtil::checkNotNull($this->mediaCategoryName,"mediaCategoryName");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
