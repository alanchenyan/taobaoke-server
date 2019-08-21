<?php

namespace app\store\controller;

use app\common\library\sms\Driver as SmsDriver;
use app\store\model\Faquan as FaquanModel;



/**
 * 发圈设置
 * Class Setting
 * @package app\store\controller
 */
class Faquanc extends Controller
{

	//商品发圈列表
	public function index()
	{
		$model = new FaquanModel();
		$list = $model ->getDataByPage(1); 

		return $this->fetch('index',compact('list'));
	}

    //商品素材
    public function index2()
    {
        $model = new FaquanModel();
        $list = $model ->getDataByPage(2); 

        return $this->fetch('index2',compact('list'));
    }

 
	/**
     * 商品编辑 更新数据
     * @param $goods_id
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function edit($id)
    {

        if (!$this->request->isAjax()) {
            $model = FaquanModel::getDataID($id);
            $model->imglist = json_decode($model->imglist);
            return $this->fetch('edit', compact('model'));
        }
        // 商品修改
        $model = new FaquanModel();
        $model->remarktj = input('remarktj');
 		$model->id = input('id');
        // 更新记录
        $ret = $model->save(['remarktj'  => $model->remarktj],['id' => $model->id]);
        if($ret){
        	return $this->renderSuccess('更新成功');
        }
        else
        {
        	$error = $model->getError() ?: '更新失败';
        	return $this->renderError($error);
        }
    }


     /**
     * 删除商品
     * @param $goods_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($id)
    {
        $model = FaquanModel::getDataID($id);
        if (!$model->delete()) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    //增加商品
    public function add($type =1)
    {
        if (!$this->request->isAjax()) {
             return $this->fetch('add',compact('type'));
        }
        //增加入库
        $model = new FaquanModel();
        $model->additemSc($this->request->post(),$type);
        return $this->renderSuccess('添加成功', url('faquan/index2'));
    }



}