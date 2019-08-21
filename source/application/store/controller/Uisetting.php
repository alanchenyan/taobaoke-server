<?php
namespace app\store\controller;
use think\Cache;
/**
 * 后台首页
 * Class Index
 * @package app\store\controller
 */
class Uisetting extends Controller
{
    public function index()
    {
    		$datastr = Cache::store('redis')->get('active_banner');
    		 $postdata = $this->request->post();
            return $this->fetch('index',compact('datastr'));

       
    }

    public function sava1(){
    	 $postdata = $this->request->post();
        $datastr= $postdata["datahtml"];
        Cache::store('redis')->set('active_banner', $datastr); 
        return $this->fetch('index',compact('datastr'));
         
    }

    public function navindex(){
        $datastr = Cache::store('redis')->get('nav_html');
        $postdata = $this->request->post();
        return $this->fetch('indexnav',compact('datastr'));
    }
    public function sava2(){
         $postdata = $this->request->post();
        $datastr= $postdata["datahtml"];
        Cache::store('redis')->set('nav_html', $datastr); 
        return $this->fetch('index',compact('datastr'));
         
    }


    public function demolist()
    {
        return $this->fetch('demo-list');
    }


}
