<?php
class ServiceCouponImportCouponRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.service.coupon.importCoupon";
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
                                    	                                            		                                    	                   			private $unionId;
    	                        
	public function setUnionId($unionId){
		$this->unionId = $unionId;
         $this->apiParas["unionId"] = $unionId;
	}

	public function getUnionId(){
	  return $this->unionId;
	}

                        	                   			private $skuId;
    	                        
	public function setSkuId($skuId){
		$this->skuId = $skuId;
         $this->apiParas["skuId"] = $skuId;
	}

	public function getSkuId(){
	  return $this->skuId;
	}

                        	                   			private $couponLink;
    	                        
	public function setCouponLink($couponLink){
		$this->couponLink = $couponLink;
         $this->apiParas["couponLink"] = $couponLink;
	}

	public function getCouponLink(){
	  return $this->couponLink;
	}

                            }





        
 

