<?php

namespace app\web\model;
 

use think\Db;
use app\common\model\BaseModel ;
//use app\stroe\model\UploadFile as UploadFileModel;

use think\Request;


//文章模型
//1-新手入门  2-高级学院 3-经验分享 4-常见问题 5-关于我们
class Article extends BaseModel{

	//自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化

        //关闭字段
        $this->updateTime = false;
    }


    //获得文章列表
 
    //文章ID；获取文章详情
    public function get_article_id($id){
        return self::get(['id' => $id]);
    }

   
 

 


}