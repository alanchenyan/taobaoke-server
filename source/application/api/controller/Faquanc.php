<?php

namespace app\api\controller;
use think\Config;
use think\Cache;
use app\api\model\Faquan as MedelFaquan;
use think\Db;

//卷米的发圈功能 数据接口
Class Faquanc extends Controller{
 
    public function index()
    {
        echo "非法访问";
    }



    //获取朋友圈列表； 每20条数据展示
    public function getlist($pageindex = 0)
    {
        $model = new MedelFaquan();
        $dblist = $model ->getDataByPage($pageindex);
        $counddata = count($dblist);
        if($counddata <=0){
            return $this->renderError("暂无数据:".$pageindex);
        }
        else
        {
            return $this->renderSuccess($dblist);

        }
    }

//获取素材
    public function getlistsc($pageindex = 0)
    {
        $model = new MedelFaquan();
        $dblist = $model ->getSucaiDataByPage($pageindex);
        $counddata = count($dblist);
        if($counddata <=0){
            return $this->renderError("暂无数据:".$pageindex);
        }
        else
        {
            return $this->renderSuccess($dblist);

        }
    }


    //获得学院文章列表
    //$adtype 类型 0：全部最新- 1-新手 2-高手 3-经验分享
    //http://127.0.0.1:8081/web/index.php/web/viewinfo/index/cid/4
    public function getxueyuan($pageindex = 0,$adtype =0)
    {
        if($adtype > 3)
            return;
        $list = array();
        $url = "http://app.52juanmi.com/index.php/web/viewinfo/index/cid";
        if($adtype == 0){
            $list = Db::name('article')->field(['id','title','createtime','imgurl','atype','author','clicks'])->order('createtime','desc')->paginate(20,true,[
                    'list_rows' => 20,
                    'page'      => $pageindex,
                ]);

            return $this->renderSuccess($list,$url);
        
        }else
        {
            //1-新手入门  2-高级学院 3-经验分享 4-常见问题 5-关于我们
            $list = Db::name('article')->field(['id','title','createtime','imgurl','atype','author','clicks'])->where('atype',$adtype)->order('createtime','desc')->paginate(20,true,[
                    'list_rows' => 20,
                    'page'      => $pageindex,
                ]);
            return $this->renderSuccess($list,$url);
        }
    }


    //获得达人说内容
    public function getdarenshuo($pageindex = 0){
        $list = Db::name('drshuo')->field(['id','name','head_img','image','talent_name','gooditem'])->order('id','desc')->paginate(20,true,[
                    'list_rows' => 20,
                    'page'      => $pageindex,
                ]);

        return $this->renderSuccess($list);
    }


}