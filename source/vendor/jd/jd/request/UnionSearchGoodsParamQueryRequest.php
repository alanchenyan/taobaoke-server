<?php
class UnionSearchGoodsParamQueryRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.union.search.goods.param.query";
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
                                    	                                            		                                    	                   			private $cat1Id;
    	                        
	public function setCat1Id($cat1Id){
		$this->cat1Id = $cat1Id;
         $this->apiParas["cat1Id"] = $cat1Id;
	}

	public function getCat1Id(){
	  return $this->cat1Id;
	}

                        	                   			private $owner;
    	                        
	public function setOwner($owner){
		$this->owner = $owner;
         $this->apiParas["owner"] = $owner;
	}

	public function getOwner(){
	  return $this->owner;
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

                        	                   			private $sortName;
    	                        
	public function setSortName($sortName){
		$this->sortName = $sortName;
         $this->apiParas["sortName"] = $sortName;
	}

	public function getSortName(){
	  return $this->sortName;
	}

                        	                   			private $sort;
    	                        
	public function setSort($sort){
		$this->sort = $sort;
         $this->apiParas["sort"] = $sort;
	}

	public function getSort(){
	  return $this->sort;
	}

                                                 	                        	                                                                                                                                                                                                                                                                                                               private $skuIds;
                              public function setSkuIds($skuIds ){
                 $this->skuIds=$skuIds;
                 $this->apiParas["skuIds"] = $skuIds;
              }

              public function getSkuIds(){
              	return $this->skuIds;
              }
                                                                                                                                        	                   			private $pingouPriceStart;
    	                        
	public function setPingouPriceStart($pingouPriceStart){
		$this->pingouPriceStart = $pingouPriceStart;
         $this->apiParas["pingouPriceStart"] = $pingouPriceStart;
	}

	public function getPingouPriceStart(){
	  return $this->pingouPriceStart;
	}

                        	                   			private $pingouPriceEnd;
    	                        
	public function setPingouPriceEnd($pingouPriceEnd){
		$this->pingouPriceEnd = $pingouPriceEnd;
         $this->apiParas["pingouPriceEnd"] = $pingouPriceEnd;
	}

	public function getPingouPriceEnd(){
	  return $this->pingouPriceEnd;
	}

                        	                   			private $wlCommissionShareStart;
    	                        
	public function setWlCommissionShareStart($wlCommissionShareStart){
		$this->wlCommissionShareStart = $wlCommissionShareStart;
         $this->apiParas["wlCommissionShareStart"] = $wlCommissionShareStart;
	}

	public function getWlCommissionShareStart(){
	  return $this->wlCommissionShareStart;
	}

                        	                   			private $wlCommissionShareEnd;
    	                        
	public function setWlCommissionShareEnd($wlCommissionShareEnd){
		$this->wlCommissionShareEnd = $wlCommissionShareEnd;
         $this->apiParas["wlCommissionShareEnd"] = $wlCommissionShareEnd;
	}

	public function getWlCommissionShareEnd(){
	  return $this->wlCommissionShareEnd;
	}

                            }





        
 

