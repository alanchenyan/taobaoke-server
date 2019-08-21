<?php

namespace app\api\model;
 

use think\Db;
use app\common\model\BaseModel ;


/**
 * 商品品牌模型
 * Class Category
 * @package app\common\model
 */
class Brand extends BaseModel
{
    
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }


     //获得商品品牌数据列表
    public function getList($cateid)
    {
        $brandlist = new Brand();
        $finddata = $brandlist->where(array('cate' =>$cateid ))->select(); 
        return $finddata;

    }

    //采集数据测试
    public function caiji($cid){

        $brandlist = new Brand();
        $urls = 'http://api.meibeijie.com/index.php?s=%2Fapi%2Fbrand_topic_get_request&uid=3760&token=9d2e250aaf8655ab22&loginTime=1554187051309&cid=9&page=1';
        echo  $urls;
        echo "\r\n=========================\r\n";
        $datastr = curl($urls);
    
        $dataobj = json_decode($datastr,true);
        for ($i=0; $i <count($dataobj['data']) ; $i++) { 
             $infos = $dataobj['data'][$i];
             echo  $infos['name'];  
             $info = new Brand; 
             $info['name'] = $infos['name'];  
             $info['rebate'] = 5; 
             $info['imgs'] =  $infos['img'];   
             $info['urls'] =  $infos['url'];   
             $info['cate'] =   11;    
             $info->allowfield(true)->save();   
        }
        
    }


}
