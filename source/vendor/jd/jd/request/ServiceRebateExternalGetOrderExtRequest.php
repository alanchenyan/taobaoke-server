<?php
class ServiceRebateExternalGetOrderExtRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.service.rebate.external.getOrderExt";
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
                              public function setUnionId($unionId ){
                 $this->unionId=$unionId;
                 $this->apiParas["unionId"] = $unionId;
              }

              public function getUnionId(){
              	return $this->unionId;
              }
                                                                                                                                                                                                                                                                                                                       private $orderId;
                              public function setOrderId($orderId ){
                 $this->orderId=$orderId;
                 $this->apiParas["orderId"] = $orderId;
              }

              public function getOrderId(){
              	return $this->orderId;
              }
                                                                                                                                                                                                                                                                                                                       private $skuId;
                              public function setSkuId($skuId ){
                 $this->skuId=$skuId;
                 $this->apiParas["skuId"] = $skuId;
              }

              public function getSkuId(){
              	return $this->skuId;
              }
                                                                                                                }





        
 

