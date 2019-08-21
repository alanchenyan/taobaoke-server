<?php

namespace app\api\controller;

use app\api\model\Category as CategoryModel;
use app\common\model\Catelist as CateModel;
use think\Cache;
/**
 * 商品分类控制器
 * Class Goods
 * @package app\api\controller
 */
class Category extends Controller
{
    /**
     * 全部分类
     * @return array
     */
    public function lists()
    {
        $list = array_values(CategoryModel::getCacheTree());
        return $this->renderSuccess(compact('list'));

    }   


    //获得父商品类型
    public function catelist($pid = 0)
    {

       /* $rediskey = CKEY3.$pid;
        $datastr = Cache::store('redis')->get($rediskey);

        if(!$datastr){
             $model = new CateModel;
             $dataobj = $model->getCateParntlist($pid);
             //缓存一小时
             Cache::store('redis')->set($rediskey, json_encode($dataobj), 60 * 60); 
             return $this->renderSuccess($dataobj);
        }
        return $this->renderSuccess(json_decode($datastr));*/
    }

    //获取全部分类数据
    public function getallcatelist()
    {
         $rediskey = CKEY3;
         $datastr = Cache::store('redis')->get($rediskey);
          if(!$datastr){
             $model = new CateModel;
             $dataobj = $model->getlist();
             //缓存一小时
             Cache::store('redis')->set($rediskey, json_encode($dataobj), 60 * 60); 
             return $this->renderSuccess($dataobj);
        }
        return $this->renderSuccess(json_decode($datastr));
    }

    
}
