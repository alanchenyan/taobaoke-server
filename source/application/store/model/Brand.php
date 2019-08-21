<?php

namespace app\store\model;
 

use think\Db;
use app\common\model\BaseModel ;
//use app\stroe\model\UploadFile as UploadFileModel;

use think\Request;


//品牌类型
class Brand extends BaseModel{
	//自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

     //获得文章列表
    public function get_brand_list(){
        $request = Request::instance();
    	$list = Db::name('brand')->paginate(20,false,['query' => $request->request()]);
    	return $list;
    }

    
     //获得商品品牌数据列表
    public function getList($cateid)
    {
       return self::get(['id' => $cateid]);

    }


}