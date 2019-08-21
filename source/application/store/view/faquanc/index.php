<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">发圈列表</div>
                </div>


                <div class="widget-body am-fr">
                 <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                        <div class="am-form-group">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a class="am-btn am-btn-default am-btn-success am-radius"
                                       href="<?= url('faquanc/add',['type' => 1]) ?>">
                                        <span class="am-icon-plus"></span> 新增物品
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
                                <th>ID</th>
                                <th>三方ID</th>
                                <th>物品ID</th>
                                <!--<th>推荐话语</th>-->
                                <th>主图</th>
                                <th>佣金比例</th>
                                <th>券后价</th>
                                <th>标题</th>
                                <th>增加时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['uid'] ?></td>
                                    <td class="am-text-middle"><?= $item['itemid'] ?></td>
                                   <!-- <td class="am-text-middle"  ><?= $item['remarktj'] ?></td> -->
                                    <td class="am-text-middle">
                                        <a href="<?= $item['itempic'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['itempic'] ?>" width="72" height="72" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['commissionrate'] ?></td>
                                    <td class="am-text-middle"><?= $item['newprice'] ?></td>
                                    <td class="am-text-middle"><?= $item['itemname'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['addtime'] ?: '--' ?></td>

                                     <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <a href="<?= url('Faquanc/edit',
                                                ['id' => $item['id']]) ?>">
                                                <i class="am-icon-pencil"></i> 编辑
                                            </a>
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
        var url = "<?= url('faquanc/delete') ?>";
        $('.item-delete').delete('id', url);

    });
</script>

