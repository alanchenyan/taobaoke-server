<?php
namespace app\web\controller;

  
 
use think\Config;
use think\Session;
use think\Cookie;
use app\web\model\article as ArticleMdeol;

/**
 * VIEW页面
 * Class Index
 * @package app\store\controller
 */
class Viewinfo extends \think\Controller
{
  
    public function index($cid)
    {
    	$model = new ArticleMdeol();
    	$info = $model->get_article_id($cid);
    	if($info){
    		return $this->fetch('info',compact('info'));
    	}
         
    }


 

}
