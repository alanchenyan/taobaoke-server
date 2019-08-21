<?php

namespace app\api\model;
 

use think\Db;
use app\common\model\BaseModel ;



/**
 * 卷米社区数据管理
 * Class Category
 * @package app\common\model
 */
class Faquan extends BaseModel
{
	//自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }


    public function getDataByPage($pageindex)
    {
    	$list = Db::name('faquan')->where('types',1)->order('id','desc')->paginate(10,true,[
    				'list_rows' => 10,
    				'page'		=> $pageindex,
    		]);
    	return $list;
    }

    //获得宣传素材列表2
    public function getSucaiDataByPage($pageindex)
    {
    	$list = Db::name('faquan')->where('types',2)->order('id','desc')->paginate(10,true,[
    				'list_rows' => 10,
    				'page'		=> $pageindex,
    		]);
    	return $list;
    }
}