<?php
class UnionSearchQueryCouponGoodsRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.union.search.queryCouponGoods";
	}
	
	public function getApiParas(){
		return json_encode($this->apiParas);
	}
	
	public function check(){
		
	}
	
	public function putOtherTextParam($key, $value){
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
                                                        		                                                             	                        	                                                                                                                                                                                                                                                                                                               private $skuIdList;
                              public function setSkuIdList($skuIdList ){
                 $this->skuIdList=$skuIdList;
                 $this->apiParas["skuIdList"] = $skuIdList;
              }

              public function getSkuIdList(){
              	return $this->skuIdList;
              }
                                                                                                                                        	                   			private $pageIndex;
    	                        
	public function setPageIndex($pageIndex){
		$this->pageIndex = $pageIndex;
         $this->apiParas["pageIndex"] = $pageIndex;
	}

	public function getPageIndex(){
	  return $this->pageIndex;
	}

                        	                   			private $pageSize;
    	                        
	public function setPageSize($pageSize){
		$this->pageSize = $pageSize;
         $this->apiParas["pageSize"] = $pageSize;
	}

	public function getPageSize(){
	  return $this->pageSize;
	}

                        	                   			private $cid3;
    	                        
	public function setCid3($cid3){
		$this->cid3 = $cid3;
         $this->apiParas["cid3"] = $cid3;
	}

	public function getCid3(){
	  return $this->cid3;
	}

                        	                   			private $goodsKeyword;
    	                        
	public function setGoodsKeyword($goodsKeyword){
		$this->goodsKeyword = $goodsKeyword;
         $this->apiParas["goodsKeyword"] = $goodsKeyword;
	}

	public function getGoodsKeyword(){
	  return $this->goodsKeyword;
	}

                        	                   			private $priceFrom;
    	                        
	public function setPriceFrom($priceFrom){
		$this->priceFrom = $priceFrom;
         $this->apiParas["priceFrom"] = $priceFrom;
	}

	public function getPriceFrom(){
	  return $this->priceFrom;
	}

                        	                   			private $priceTo;
    	                        
	public function setPriceTo($priceTo){
		$this->priceTo = $priceTo;
         $this->apiParas["priceTo"] = $priceTo;
	}

	public function getPriceTo(){
	  return $this->priceTo;
	}

                                                    	}





        
 

