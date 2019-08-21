<?php

namespace app\store\controller\setting;

use app\store\controller\Controller;
use think\Cache as Driver;

/**
 * 清理缓存
 * Class Index
 * @package app\store\controller
 */
class Cache extends Controller
{
    /**
     * 清理缓存
     * @param bool $isForce
     * @return mixed
     */
    public function clear($isForce = false)
    {
        if ($this->request->isAjax()) {
            $data = $this->postData('cache');
            $this->rmCache($data['keys'], isset($data['isForce']) ? !!$data['isForce'] : false);
            return $this->renderSuccess('操作成功');
        }
        return $this->fetch('clear', [
            'cacheList' => $this->getCacheKeys(),
            'isForce' => !!$isForce ?: config('app_debug'),
        ]);
    }

    /**
     * 删除缓存
     * @param $keys
     * @param bool $isForce
     */
    private function rmCache($keys, $isForce = false)
    {
        if ($isForce === true) {
            Driver::clear();
        } else {
            $cacheList = $this->getCacheKeys();
            foreach (array_intersect(array_keys($cacheList), $keys) as $key) {
                Driver::store('redis')->has($cacheList[$key]['key']) && Driver::store('redis')->rm($cacheList[$key]['key']);
            }
        }
    }

    /**
     * 获取缓存索引数据
     */
    private function getCacheKeys()
    {
        return [
            'setting' => [
                'key' => CKEY1,
                'name' => '9.9包邮'
            ],
            'category' => [
                'key' => CKEY2,
                'name' => '热销数据'
            ],
            'wxapp1' => [
                'key' => CKEY3,
                'name' => '物品分类'
            ],
            'wxapp2' => [
                'key' => CKEY4,
                'name' => '爆款排行'
            ],
        ];
    }

}
