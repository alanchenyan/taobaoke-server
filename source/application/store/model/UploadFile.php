<?php

namespace app\store\model;

use app\common\model\UploadFile as UploadFileModel;

/**
 * 文件库模型
 * Class UploadFile
 * @package app\store\model
 */
class UploadFile extends UploadFileModel
{
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        return $this->save($data);
    }

    /**
     * 批量软删除
     * @param $fileIds
     * @return $this
     */
    public function softDelete($fileIds)
    {
        return $this->where('file_id', 'in', $fileIds)->update(['is_delete' => 1]);
    }

    /**
     * 批量移动文件分组
     * @param $group_id
     * @param $fileIds
     * @return $this
     */
    public function moveGroup($group_id, $fileIds)
    {
        return $this->where('file_id', 'in', $fileIds)->update(compact('group_id'));
    }

    //获得图片完整的地址
    public function getImgUrl($fileId){
        $imginfo = $this->where(array('file_id' =>$fileId ))->find();
        if($imginfo){
            return $imginfo['file_url']."/".$imginfo['file_name'];
        }
        return null;
    }

}
