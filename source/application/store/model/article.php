<?php

namespace app\store\model;
 

use think\Db;
use app\common\model\BaseModel ;
//use app\stroe\model\UploadFile as UploadFileModel;

use think\Request;
use think\Config;

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
    public function get_article_list($type){
        $request = Request::instance();
    	$list = Db::name('article')->where('atype',$type)->paginate(20,false,['query' => $request->request()]);
    	return $list;
    }


      //获得所有文章列表
    public function get_all_article_list(){
        $request = Request::instance();
    	$list = Db::name('article')->order('createtime','desc')->paginate(20,false,['query' => $request->request()]);
    	return $list;
    }

    //文章ID；获取文章详情
    public function get_article_id($id){
        return self::get(['id' => $id]);
    }

    //反馈列表
    public function get_fankui_list(){
         $request = Request::instance();
        $list = Db::name('fankui')->order('createtime','desc')->paginate(20,false,['query' => $request->request()]);
        return $list;
    }


    //获得图片完整的地址
    public function getImgUrl($fileId){
        $imginfo = Db::name('UploadFile')->where('file_id',$fileId)->find();
        
        if($imginfo){
            return $imginfo['file_url']."/".$imginfo['file_name'];
        }
        return null;
    }



    //增加文章
    public function add_article_info($info){

         $newdata = new Article;
         $newdata->title     =  $info['title'];
         $newdata->createtime     = date("Y-m-d H:i:s");

         $newdata->container     =$info['container'];
         $itempic = $this->getImgUrl($info['mainimg']);
         $newdata->imgurl     =$itempic;
         $newdata->atype     =intval($info['types']);
         $newdata->author     = $info['author'];

         if(intval($info['clicks'])== 0 ){
            $newdata->clicks     =  rand(2000, 45000);
         }else{
            $newdata->clicks     =  intval($info['clicks']);
         }
         $newdata->allowField(true)->save();
    }

    //更新文章
    public function update_article_info($info,$id){
         $newdata = self::get($id);
         $newdata->container     =$info['container'];
         if(isset($info['mainimg'])){
            if($info['mainimg'] != "" && $info['mainimg'] !=0){
                $newdata->imgurl = $this->getImgUrl($info['mainimg']);
  
            }  
         }
         
  
         $newdata->atype     =intval($info['types']);
         $newdata->author     =$info['author'];
         $newdata->title     =$info['title'];
         $newdata->allowField(true)->save();
 
    }
 


}