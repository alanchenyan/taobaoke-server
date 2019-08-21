<?php

namespace app\store\controller;

 
use app\store\model\article as ArticleModel;



/**
 * 文章系统管理
 * Class Setting
 * @package app\store\controller
 */
class Article extends Controller
{
    
    //商品发圈列表
    public function index()
    {
        $model = new ArticleModel();
        $list = $model->get_all_article_list();
        return $this->fetch('index',compact('list'));
    }
     //商品发圈列表
    public function index2()
    {
        $model = new ArticleModel();
        $list = $model->get_fankui_list();
        return $this->fetch('index2',compact('list'));
    }

     //增加商品
    public function add($type =1)
    {
        if (!$this->request->isAjax()) {
             return $this->fetch('add',compact('type'));
        }
        //增加入库
        $model = new ArticleModel();
        $model->add_article_info($this->request->post());
        return $this->renderSuccess('添加成功', url('article/index'));
        
    }

     /**
     * 删除商品
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = new ArticleModel();
        $indo = $model->get_article_id($id);
        if (!$indo->delete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }




    //编辑文章
    /**
     * 商品编辑 更新数据
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {

        $model = new ArticleModel();
        if (!$this->request->isAjax()) {
            $model = $model->get_article_id($id);
            return $this->fetch('edit', compact('model'));
        }
        // 商品修改
        $model->update_article_info($this->request->post(),$id);
        return $this->renderSuccess('修改成功', url('article/index'));
    }



}