<?php

namespace app\common\model;
 
use think\Cache;
 

/**
*  商品分类模型
*  分类列表
*/
class Catelist extends BaseModel
{

  //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    //API数据格式返回
    //获取全部分类数据
	public function getCateParntlist($pid)
	{
		 
		$user = new Catelist;
		$cateList = $user->where(array('pid' =>$pid ))->field(["id","name","pid","app_icon","cid"])->select();
		return $cateList;
	}

	public function getlist()
	{
		 
		$cateobj = new Catelist;
		$cateList = $cateobj->field(["id","name","pid","hdk_cid","app_icon","cid","app_ad_img","app_ad_url"])->select();
		return $cateList;
	}

	//获取分类ID 详细数据
	public function getCateInfo($id){
		$cateobj = new Catelist;
		$cateinfo = $cateobj->where(array('id' =>$id ))->find();
		return $cateinfo;
	}



}