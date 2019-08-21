<?php
class UnionSearchQueryXCGoodsRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.union.search.queryXCGoods";
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
                                    	                                            		                                                             	                        	                                                                                                                                                                                                                                                                                                               private $skuIds;
                              public function setSkuIds($skuIds ){
                 $this->skuIds=$skuIds;
                 $this->apiParas["skuIds"] = $skuIds;
              }

              public function getSkuIds(){
              	return $this->skuIds;
              }
                                                                                                                                        	                   			private $cid1;
    	                        
	public function setCid1($cid1){
		$this->cid1 = $cid1;
         $this->apiParas["cid1"] = $cid1;
	}

	public function getCid1(){
	  return $this->cid1;
	}

                        	                   			private $cid2;
    	                        
	public function setCid2($cid2){
		$this->cid2 = $cid2;
         $this->apiParas["cid2"] = $cid2;
	}

	public function getCid2(){
	  return $this->cid2;
	}

                        	                   			private $cid3;
    	                        
	public function setCid3($cid3){
		$this->cid3 = $cid3;
         $this->apiParas["cid3"] = $cid3;
	}

	public function getCid3(){
	  return $this->cid3;
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

                        	                   			private $keyword;
    	                        
	public function setKeyword($keyword){
		$this->keyword = $keyword;
         $this->apiParas["keyword"] = $keyword;
	}

	public function getKeyword(){
	  return $this->keyword;
	}

                        	                   			private $pricefrom;
    	                        
	public function setPricefrom($pricefrom){
		$this->pricefrom = $pricefrom;
         $this->apiParas["pricefrom"] = $pricefrom;
	}

	public function getPricefrom(){
	  return $this->pricefrom;
	}

                        	                   			private $priceto;
    	                        
	public function setPriceto($priceto){
		$this->priceto = $priceto;
         $this->apiParas["priceto"] = $priceto;
	}

	public function getPriceto(){
	  return $this->priceto;
	}

                        	                   			private $commissionShareStart;
    	                        
	public function setCommissionShareStart($commissionShareStart){
		$this->commissionShareStart = $commissionShareStart;
         $this->apiParas["commissionShareStart"] = $commissionShareStart;
	}

	public function getCommissionShareStart(){
	  return $this->commissionShareStart;
	}

                        	                   			private $commissionShareEnd;
    	                        
	public function setCommissionShareEnd($commissionShareEnd){
		$this->commissionShareEnd = $commissionShareEnd;
         $this->apiParas["commissionShareEnd"] = $commissionShareEnd;
	}

	public function getCommissionShareEnd(){
	  return $this->commissionShareEnd;
	}

                        	                   			private $owner;
    	                        
	public function setOwner($owner){
		$this->owner = $owner;
         $this->apiParas["owner"] = $owner;
	}

	public function getOwner(){
	  return $this->owner;
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

                        	                   			private $isPG;
    	                        
	public function setIsPG($isPG){
		$this->isPG = $isPG;
         $this->apiParas["isPG"] = $isPG;
	}

	public function getIsPG(){
	  return $this->isPG;
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

                        	                   			private $shopId;
    	                        
	public function setShopId($shopId){
		$this->shopId = $shopId;
         $this->apiParas["shopId"] = $shopId;
	}

	public function getShopId(){
	  return $this->shopId;
	}

                            }





        
 

