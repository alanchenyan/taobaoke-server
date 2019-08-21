<?php

/**
 * 接口返回model
 * @author auto create
 */
class Result
{
	
	/** 
	 * 错误码
	 **/
	public $error_code;
	
	/** 
	 * 错误信息
	 **/
	public $error_msg;
	
	/** 
	 * 额外扩展信息
	 **/
	public $extra;
	
	/** 
	 * 返回信息
	 **/
	public $message;
	
	/** 
	 * 返回数据
	 **/
	public $result_value;
	
	/** 
	 * 成功或者失败
	 **/
	public $success;
	
	/** 
	 * 额外的扩展字段
	 **/
	public $tracker;	
}
?>