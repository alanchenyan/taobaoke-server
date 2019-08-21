<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">结算工资</div>
                </div>


                <div class="widget-body am-fr">
                 <div class="am-u-sm-12 am-u-md-6 am-u-lg-6">
                        <div class="am-form-group">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <a class="am-btn am-btn-default am-btn-success am-radius" href="javascript:jsfun(22)">
                                        <span class="am-icon-plus">开始结算(25日)</span>  
                                    </a>
                                </div>
                            </div>
                                <small style="color: red">每月25点击结算，系统会结算上个自然月的所有预估收入订单到用户的余额。请注意点击结算的时间</small>
                        </div>
                        
                    </div>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                     <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>用户ID</th>
                                <th>月份</th>
                                <th>结算金额</th>
                                <th>创建时间</th>
                            
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['userid'] ?></td>
                                    <td class="am-text-middle"><?= $item['moths'] ?></td>
                                   <td class="am-text-middle"><?= $item['money'] ?></td>
                                   
                                    <td class="am-text-middle"><?= $item['createtime'] ?></td> 
                                   
                                  
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
    var isfalg = false;
    function jsfun(argument) {
        if(isfalg){
            return ;
        }
        isfalg = true;
         $.get("<?= url('order/jsdata') ?>",function(data,status){
            alert("Data: " + data + "\nStatus: " + status);
          });
      
    }
</script>

