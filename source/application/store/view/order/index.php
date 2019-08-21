
<style type="text/css">
    .titles{
        text-overflow: ellipsis; 
white-space: nowrap; 
overflow: hidden; 
    }

</style>
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">订单数据</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="order-list am-scrollable-horizontal am-u-sm-12 am-margin-top-xs">
                        <table width="100%" style="table-layout: fixed;" class="am-table am-table-centered
                        am-text-nowrap am-margin-bottom-xs">
                            <thead>
                            <tr>
                                <th width="220px" class="goods-detail titles">标题</th>
                                <th width="50">平台</th>
                                <th width="130">编号</th>
                                <th  width="50px" >买家id</th>
                                <th  width="50px" >状态</th>
                                <th  width="70px" >付款金额</th>
                              
                                <th  width="70px" >结算金额</th>
                                <th  width="70px" >预估收入</th>
                                <th width="70px" >佣金比例</th>
                                <th  width="50px" >平台</th>
                                <th  width="140px" >创建时间</th>
                               
                                <th width="75">渠道ID</th>
                                <th width="120px" >结算时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"  >
                                        <a href="http://item.taobao.com/item.htm?id=<?= $item['goods_id'] ?>" target="_blank" >   <?= $item['title'] ?></a>
                                        </td>
                                    <td class="am-text-middle">
                                       <?= $item['shop_type'] ?>
                                    </td>
                                    <td class="am-text-middle">
                                        <input type="text" value="<?= $item['goods_order'] ?>" name="" style="width:100px;">
                                        </td>
                                    <td class="am-text-middle"><?= $item['uid'] ?></td>
                                    <?php  if($item['order_status']==12): ?>
                                        <td style="color: blue" class="am-text-middle">付款</td>
                                     <?php  elseif($item['order_status']==3): ?>  
                                         <td style="color: red" class="am-text-middle">结算</td>
                                    <?php  elseif($item['order_status']==13): ?>  
                                           <td style="color: block" class="am-text-middle">失效</td>
                                     <?php endif; ?>
                                        
                                    <td class="am-text-middle"><?= $item['pay_price'] ?></td>
                                    <td class="am-text-middle"><?= $item['settlement_price'] ?></td>

                                     <td class="am-text-middle"><?= $item['commission'] ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['commission_rate'] ?>
                                    </td><td class="am-text-middle"><?= $item['terminal_type'] ?>
                                    </td>
                                     </td><td class="am-text-middle"><?= $item['create_time'] ?>
                                    </td>
                                     

                                     </td><td class="am-text-middle"><?= $item['relation_id'] ?>
                                    </td>
                                     <?php  if($item['earning_time']==0): ?>
                                        <td  class="am-text-middle">未结算</td>
                                    <?php  else:?>    
                                         <td class="am-text-middle"><?= date("Y-m-d H:i:s", $item['earning_time'])?></td>
                                     <?php endif; ?>    
                                </tr>
                               
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="14" class="am-text-center">暂无记录</td>
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

