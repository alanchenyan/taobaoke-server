<?php
class ServicePromotionCouponGetCodeByUnionIdRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.service.promotion.coupon.getCodeByUnionId";
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
                                    	                                            		                                                             	                        	                                                                                                                                                                                                                                                                                                               private $couponUrl;
                              public function setCouponUrl($couponUrl ){
                 $this->couponUrl=$couponUrl;
                 $this->apiParas["couponUrl"] = $couponUrl;
              }

              public function getCouponUrl(){
              	return $this->couponUrl;
              }
                                                                                                                                                                 	                        	                                                                                                                                                                                                                                                                                                               private $materialIds;
                              public function setMaterialIds($materialIds ){
                 $this->materialIds=$materialIds;
                 $this->apiParas["materialIds"] = $materialIds;
              }

              public function getMaterialIds(){
              	return $this->materialIds;
              }
                                                                                                                                        	                   			private $unionId;
    	                        
	public function setUnionId($unionId){
		$this->unionId = $unionId;
         $this->apiParas["unionId"] = $unionId;
	}

	public function getUnionId(){
	  return $this->unionId;
	}

                        	                   			private $positionId;
    	                        
	public function setPositionId($positionId){
		$this->positionId = $positionId;
         $this->apiParas["positionId"] = $positionId;
	}

	public function getPositionId(){
	  return $this->positionId;
	}

                        	                   			private $pid;
    	                        
	public function setPid($pid){
		$this->pid = $pid;
         $this->apiParas["pid"] = $pid;
	}

	public function getPid(){
	  return $this->pid;
	}

                            }





        
 

