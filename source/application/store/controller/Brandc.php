<?php

namespace app\store\controller;

use app\store\model\Brand as BrandModel;
use app\common\model\Catelist as CateModel;

/**
 * 品牌分类管理 
 * Class Setting
 * @package app\store\controller
 */
class Brandc extends Controller
{

	//品牌列表
	public function index()
	{
		$model = new BrandModel();
		$list = $model ->get_brand_list(); 

		return $this->fetch('index',compact('list'));
	}
 
 
	/**
     * 商品编辑 更新数据
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {
        $model = new CateModel();
        if (!$this->request->isAjax()) {
            $model = $model->getList($id);
            return $this->fetch('edit', compact('model'));
        }
        // 商品修改
       // $model->update_article_info($this->request->post(),$id);
       // return $this->renderSuccess('修改成功', url('article/index'));
    }


     /**
     * 删除商品
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = new BrandModel();
        $indo = $model->getList($id);
        if (!$indo->delete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    //增加商品
    public function add()
    {
        if (!$this->request->isAjax()) {
            //获取分类
            $m = new CateModel();
            $catelist = $m->getCateParntlist(0);
            return $this->fetch('add',compact('catelist'));
        }
         
    }



}