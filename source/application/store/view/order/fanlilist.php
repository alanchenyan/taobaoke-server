
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
                    <div class="widget-title am-cf">返利数据</div>
                </div>
                <div class="widget-body am-fr">

<!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <form id="form-search" class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/store/order/fanlists">
                          
                            <div class="am-u-sm-12 am-u-md-9">
                                <div class="am fr">
                                    <div class="am-form-group am-fl">
                                             <select name="fanlilevel"
                                                data-am-selected="{btnSize: 'sm', placeholder: '层级'}">
                                                <option value="-1">选择</option>
                                                <option value="0">自购</option>
                                                <option value="1">一级</option>
                                                <option value="2">二级</option>
                                            </select>
                                    </div>
                                    <div  style="margin-left: 10px" class="am-form-group am-fl">
                                           <select name="earningtype"
                                                data-am-selected="{btnSize: 'sm', placeholder: '是否发工资'}">
                                            <option value="0">未发工资</option>
                                            <option value="1" >已发工资</option>
                                        </select>
                                    </div>
                                    <div style="margin-left: 10px" class="am-form-group tpl-form-border-form am-fl">
                                        <input type="text" name="start_time"
                                               class="am-form-field"
                                               value="" placeholder="请选择创建起始日期"
                                               data-am-datepicker>
                                    </div>
                                    <div style="margin-left: 10px" class="am-form-group tpl-form-border-form am-fl">
                                        <input type="text" name="end_time"
                                               class="am-form-field"
                                               value="" placeholder="请选择创建截止日期"
                                               data-am-datepicker>
                                    </div>
                                    <div class="am-form-group am-fl">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                            <input type="text" style="width: 150px;margin-left: 10px" class="am-form-field" name="orderid"
                                                   placeholder="输入订单编号" value=""></input>
                                                    <input type="text" style="width: 150px; margin-left: 10px" class="am-form-field" name="userid"
                                                   placeholder="用户ID" value=""></input>
                                              <button style="margin-left: 10px" class="am-btn am-btn-default am-icon-search"
                                                        type="submit"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>



                    <div class="order-list am-scrollable-horizontal am-u-sm-12 am-margin-top-xs">
                        <table width="100%" style="table-layout: fixed;" class="am-table am-table-centered
                        am-text-nowrap am-margin-bottom-xs">
                            <thead>
                            <tr>
                                <th width="50px" class="goods-detail titles">编号</th>
                                <th width="140">订单号</th>
                                <th width="50px" >用户ID</th>
                                <th  width="90px" >来源用户</th>
                                <th  width="90px" >返利层级</th>
                                <th  width="70px" >价格</th>
                                <th  width="70px" >数量</th>
                                <th  width="90px" >预估收入</th>
                                <th width="90px" >佣金比例</th>
                                <th  width="140px" >创建时间</th>
                                 <th width="120px" >状态</th>
                                <th width="120px" >工资时间</th>
                                <th width="50px" >返利类型</th>

                            </tr>
                            </thead>
                            <tbody>
                           <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"  ><?= $item['id'] ?></td>
                                    <td class="am-text-middle">
                                       <?= $item['orderid'] ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['userid'] ?></td>
                                    <td class="am-text-middle"><?= $item['orderuserid'] ?></td>
                                    <?php  if($item['fllevel']==0): ?>
                                        <td style="color: blue" class="am-text-middle">自购</td>
                                     <?php  elseif($item['fllevel']==1): ?>  
                                         <td style="color: red" class="am-text-middle">一级</td>
                                    <?php  elseif($item['fllevel']==2): ?>  
                                           <td style="color: block" class="am-text-middle">二级</td>
                                     <?php endif; ?>
                                        
                                    <td class="am-text-middle"><?= $item['price'] ?></td>
                                    <td class="am-text-middle"><?= $item['itemnum'] ?></td>

                                     <td class="am-text-middle"><?= $item['commission'] ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['commissionrate'] ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['createtime'] ?>
                                    </td>

                                     <?php  if($item['orderstate'] == 0): ?>
                                        <td  class="am-text-middle">失效返利</td>
                                    <?php  else:?>    
                                         <td   class="am-text-middle">有效返利</td>
                                     <?php endif; ?>   


                                   
                                     <?php  if(!$item['earningtime']): ?>
                                        <td  class="am-text-middle">未发工资</td>
                                    <?php  else:?>    
                                         <td style="color: red" class="am-text-middle">已发工资</td>
                                     <?php endif; ?>    


                                    <?php  if($item['ordertype'] == 0): ?>
                                        <td  class="am-text-middle">淘宝</td>
                                    <?php  elseif($item['ordertype'] == 1):?>    
                                         <td   class="am-text-middle">拼多多</td>
                                    <?php  elseif($item['ordertype'] == 2):?> 
                                        <td   class="am-text-middle">京东</td>
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


<script>
 
</script>