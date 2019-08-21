<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">提现申请列表</div>
                </div>


                <div class="widget-body am-fr">
                
                    <div class="am-scrollable-horizontal am-u-sm-12">
                     <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>用户ID</th>
                                <th>金额</th>
                                <th>支付宝</th>
                                <th>姓名</th>
                                <th>时间</th>
                                <th>订单号</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['userid'] ?></td>
                                    <td class="am-text-middle"><?= $item['money'] ?></td>
                                   <td class="am-text-middle"><?= $item['pays'] ?></td>
                                    <td class="am-text-middle"><?= $item['username'] ?></td>  <td class="am-text-middle"><?= $item['createtime'] ?></td>
                                    <td class="am-text-middle"><?= $item['ordernum'] ?></td>
                                    <?php  if($item['isflag']==0): ?>
                                        <td style="color: blue" class="am-text-middle">未打款</td>
                                     <?php  elseif($item['isflag']==1): ?>  
                                         <td style="color: red" class="am-text-middle">已打款</td>
                                  
                                     <?php endif; ?>

                                
                                   
                                  
                                     <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <a href="<?= url('caiwu/dakuan',
                                                ['id' => $item['id']]) ?>">
                                                <i class="am-icon-pencil"></i> 打款
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

        

    });
</script>

