<?php

namespace app\api\model;
use think\Db;

use app\common\model\Category as CategoryModel;

/**
 * 商品品牌模
 * Class Category
 * @package app\common\model
 */
class Category extends CategoryModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
//        'create_time',
        'update_time'
    ];

     //获得商品分类数据
    public function getTypeDataList()
    {
        
    }


}
