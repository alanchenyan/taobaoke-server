<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"D:\XMAPP\htdocs\jmapp\web/../source/application/store\view\order\index.php";i:1560758533;s:70:"D:\XMAPP\htdocs\jmapp\source\application\store\view\layouts\layout.php";i:1554087002;}*/ ?>
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
                    <div class="widget-title am-cf">订单数据</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="order-list am-scrollable-horizontal am-u-sm-12 am-margin-top-xs">
                        <table width="100%" style="table-layout: fixed;" class="am-table am-table-centered
                        am-text-nowrap am-margin-bottom-xs">
                            <thead>
                            <tr>
                                <th width="220px" class="goods-detail titles">标题</th>
                                <th width="40">数量</th>
                                <th width="140">编号</th>
                                <th  width="50px" >买家id</th>
                                <th  width="50px" >状态</th>
                                <th  width="70px" >付款金额</th>
                              
                                <th  width="70px" >结算金额</th>
                                <th  width="70px" >预估收入</th>
                                <th width="70px" >佣金比例</th>
                                <th  width="50px" >平台</th>
                                <th  width="140px" >创建时间</th>
                                <th width="70">SID</th>
                                <th width="70">RID</th>
                                <th width="120px" >结算时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td style="white-space:nowrap;overflow:hidden;text-overflow: ellipsis;"  ><?= $item['title'] ?></td>
                                    <td class="am-text-middle">
                                       <?= $item['goods_number'] ?>
                                    </td>
                                    <td class="am-text-middle"><?= $item['goods_order'] ?></td>
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
                                     </td><td class="am-text-middle"><?= $item['special_id'] ?>
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
