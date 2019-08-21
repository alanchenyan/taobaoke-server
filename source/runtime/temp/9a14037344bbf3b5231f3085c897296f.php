<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"D:\XMAPP\htdocs\jmapp\web/../source/application/store\view\order\fanlilist.php";i:1560758403;s:70:"D:\XMAPP\htdocs\jmapp\source\application\store\view\layouts\layout.php";i:1554087002;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title><?= $setting['store']['values']['name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="assets/store/i/favicon.ico"/>
    <meta name="apple-mobile-web-app-title" content="<?= $setting['store']['values']['name'] ?>"/>
    <link rel="stylesheet" href="assets/store/css/amazeui.min.css"/>
    <link rel="stylesheet" href="assets/store/css/app.css"/>
    <link rel="stylesheet" href="//at.alicdn.com/t/font_783249_t6knt0guzo.css">
    <script src="assets/store/js/jquery.min.js"></script>
    <script src="//at.alicdn.com/t/font_783249_e5yrsf08rap.js"></script>
    <script>
        BASE_URL = '<?= isset($base_url) ? $base_url : '' ?>';
        STORE_URL = '<?= isset($store_url) ? $store_url : '' ?>';
    </script>
</head>
 
<body data-type="">
<div class="am-g tpl-g">
    <!-- 头部 -->
    <header class="tpl-header">
        <!-- 右侧内容 -->
        <div class="tpl-header-fluid">
            <!-- 侧边切换 -->
            <div class="am-fl tpl-header-button switch-button">
                <i class="iconfont icon-menufold"></i>
            </div>
            <!-- 刷新页面 -->
            <div class="am-fl tpl-header-button refresh-button">
                <i class="iconfont icon-refresh"></i>
            </div>
            <!-- 其它功能-->
            <div class="am-fr tpl-header-navbar">
                <ul>
                    <!-- 欢迎语 -->
                    <li class="am-text-sm tpl-header-navbar-welcome">
                        <a href="<?= url('store.user/renew') ?>">欢迎你，<span><?= $store['user']['user_name'] ?></span>
                        </a>
                    </li>
                    <!-- 退出 -->
                    <li class="am-text-sm">
                        <a href="<?= url('passport/logout') ?>">
                            <i class="iconfont icon-tuichu"></i> 退出
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- 侧边导航栏 -->
    <div class="left-sidebar">

        <?php $menus = $menus ?: []; $group = $group ?: 0; ?>
        <!-- 一级菜单 -->
        <ul class="sidebar-nav">
            <li class="sidebar-nav-heading"><?= $setting['store']['values']['name'] ?></li>
            <?php foreach ($menus as $key => $item): ?>
                <li class="sidebar-nav-link">
                    <a href="<?= isset($item['index']) ? url($item['index']) : 'javascript:void(0);' ?>"
                       class="<?= $item['active'] ? 'active' : '' ?>">
                        <?php if (isset($item['is_svg']) && $item['is_svg'] === true): ?>
                            <svg class="icon sidebar-nav-link-logo" aria-hidden="true">
                                <use xlink:href="#<?= $item['icon'] ?>"></use>
                            </svg>
                        <?php else: ?>
                            <i class="iconfont sidebar-nav-link-logo <?= $item['icon'] ?>"
                               style="<?= isset($item['color']) ? "color:{$item['color']};" : '' ?>"></i>
                        <?php endif; ?>
                        <?= $item['name'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- 子级菜单-->
        <?php $second = isset($menus[$group]['submenu']) ? $menus[$group]['submenu'] : []; if (!empty($second)) : ?>
            <ul class="left-sidebar-second">
                <li class="sidebar-second-title"><?= $menus[$group]['name'] ?></li>
                <li class="sidebar-second-item">
                    <?php foreach ($second as $item) : if (!isset($item['submenu'])): ?>
                            <!-- 二级菜单-->
                            <a href="<?= url($item['index']) ?>" class="<?= $item['active'] ? 'active' : '' ?>">
                                <?= $item['name']; ?>
                            </a>
                        <?php else: ?>
                            <!-- 三级菜单-->
                            <div class="sidebar-third-item">
                                <a href="javascript:void(0);"
                                   class="sidebar-nav-sub-title <?= $item['active'] ? 'active' : '' ?>">
                                    <i class="iconfont icon-caret"></i>
                                    <?= $item['name']; ?>
                                </a>
                                <ul class="sidebar-third-nav-sub">
                                    <?php foreach ($item['submenu'] as $third) : ?>
                                        <li>
                                            <a class="<?= $third['active'] ? 'active' : '' ?>"
                                               href="<?= url($third['index']) ?>">
                                                <?= $third['name']; ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; endforeach; ?>
                </li>
            </ul>
        <?php endif; ?>
    </div>

    <!-- 内容区域 start -->
    <div class="tpl-content-wrapper <?= empty($second) ? 'no-sidebar-second' : '' ?>">
        
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
                                   
                                     <?php  if(!$item['earningtime']): ?>
                                        <td  class="am-text-middle">未发工资</td>
                                    <?php  else:?>    
                                         <td style="color: red" class="am-text-middle">已发工资</td>
                                     <?php endif; ?>    

                                     </td><td class="am-text-middle"><?= $item['ordertype'] ?>
                                    </td>
                                     
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
    </div>
    <!-- 内容区域 end -->

</div>
<script src="assets/layer/layer.js"></script>
<script src="assets/store/js/jquery.form.min.js"></script>
<script src="assets/store/js/amazeui.min.js"></script>
<script src="assets/store/js/webuploader.html5only.js"></script>
<script src="assets/store/js/art-template.js"></script>
<script src="assets/store/js/app.js"></script>
<script src="assets/store/js/file.library.js"></script>
</body>

</html>
