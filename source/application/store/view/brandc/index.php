<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">品牌列表</div>
                </div>


                <div class="widget-body am-fr">
                 <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                        <div class="am-form-group">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a class="am-btn am-btn-default am-btn-success am-radius" href="<?= url('brandc/add') ?>">
                                        <span class="am-icon-plus">新增品牌</span>  
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                     <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>品牌名</th>
                                <th>返佣比例</th>
                                <th>品牌图标</th>
                                <th>品牌地址</th>
                                <th>分类</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['name'] ?></td>
                                    <td class="am-text-middle"><?= $item['rebate'] ?></td>
                                   <td class="am-text-middle"><?= $item['imgs'] ?></td>
                                   <td class="am-text-middle">
                                       <a href="<?= $item['urls'] ?>" title="点击查看" target="_blank">
                                        店铺地址
                                        </a>
                                   </td>
                                    <td class="am-text-middle"><?= $item['cate'] ?></td> 
                                   
                                  
                                     <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <!--<a href="<?= url('Brandc/edit',
                                                ['id' => $item['id']]) ?>">
                                                <i class="am-icon-pencil"></i> 编辑
                                            </a>-->
                                            <a href="javascript:;" class="item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['id'] ?>">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>
                                            
                                        </div>
                                    </td>
                                </tr>
                                    
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="8" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="am-u-lg-12 am-cf">
                        <div class="am-fr"><?= $list->render() ?> </div>
                        <div class="am-fr pagination-total am-margin-right">
                            <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
                        </div>
                    </div>
                </div>
        

                
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        // 删除元素
        var url = "<?= url('Brandc/delete') ?>";
        $('.item-delete').delete('id', url);

    });
</script>

