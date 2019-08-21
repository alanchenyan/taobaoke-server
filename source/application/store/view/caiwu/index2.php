<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">货币日志</div>
                </div>


                <div class="widget-body am-fr">
                
                    <div class="am-scrollable-horizontal am-u-sm-12">
                     <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>标题</th>
                                <th>时间</th>
                                <th>用户ID</th>
                                <th>值</th>
                                <th>类型</th>
                                <th>货币类型</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['title'] ?></td>
                                    <td class="am-text-middle"><?= $item['dtime'] ?></td>
                                   <td class="am-text-middle"><?= $item['userid'] ?></td>
                                    <td class="am-text-middle"><?= $item['value'] ?></td>  <td class="am-text-middle"><?= $item['types'] ?></td>
                                    
                                    <?php  if($item['moneytype']==1): ?>
                                        <td style="color: blue" class="am-text-middle">积分</td>
                                     <?php  elseif($item['moneytype']==2): ?>  
                                         <td style="color: red" class="am-text-middle">金额</td>
                                  
                                     <?php endif; ?>
 
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

