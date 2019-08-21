<?php

namespace app\store\model;
 

use think\Db;
use app\common\model\BaseModel ;
//use app\stroe\model\UploadFile as UploadFileModel;

use think\Request;


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

        //关闭字段
        $this->updateTime = false;
    }

 


    //获得发圈的商品列表
    public function getDataByPage($typeid)
    {
        $request = Request::instance();
    	$list = Db::name('faquan')->where('types',$typeid)->order('id','desc')->paginate(15,false,['query' => $request->request()]);
    	return $list;
    }

    //获得宣传素材列表2
    public function getSucaiDataByPage($pageindex)
    {
    	$list = Db::name('faquan')->where('types',2)->order('id','desc')->paginate(20,true,[
    				'list_rows' => 20,
    				'page'		=> $pageindex,
    		]);
    	return $list;
    }




    //通过ID；查找具体内容
    public static function getDataID($id)
    {   
        return self::get($id);
    }

    //更新商品
    public static function saveModel($model)
    {
        return $this->save([
            'remarktj'  => $model->remarktj
            ],
            ['id' => $model->id]);
    }


   //获得图片完整的地址
    public function getImgUrl($fileId){
        $imginfo = Db::name('UploadFile')->where('file_id',$fileId)->find();
        
        if($imginfo){
            return $imginfo['file_url']."/".$imginfo['file_name'];
        }
        return null;
    }

    //增加数据--素材库的
    public function additemSc($info,$type){
         $imglist  = array();
         //$upfile = new UploadFileModel();
         if(isset($info['imgs'])){
            for ($i=0; $i < count($info['imgs']) ; $i++) { 
                  
                  $fid = (int)$info['imgs'][$i];
                  $urls = $this->getImgUrl($fid);
                  array_push($imglist, $urls);
             }  
         }
        
         
         $itempic = $this->getImgUrl($info['mainimg']);

         $newdata = new Faquan;
         $newdata->remarktj     =  $info['remarktj'];
         $newdata->itempic     = $itempic;
         $newdata->imglist     =json_encode($imglist);
         $newdata->itemname     =$info['itemname'];
         $newdata->types     =$type;
         $newdata->addtime = date("Y-m-d H:i:s");
         $newdata->allowField(true)->save();
 
    }

     
}