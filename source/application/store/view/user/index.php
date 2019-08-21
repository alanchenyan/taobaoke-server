<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">用户列表</div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>微信头像</th>
                                <th>微信昵称</th>
                                <th>性别</th>
                                <th>等级</th>
                                <th>余额</th>
                                <th>城市</th>
                                <th>注册时间</th>
                                <th>上次登录</th>
                                <th>手机</th>
                                <th>邀请码</th>
                                <th>上级ID</th>
                                <th>积分</th>
                                <th>阿里RID</th>
                                <th>阿里SID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['userid'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['avatarurl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['avatarurl'] ?>" width="72" height="72" alt="">
                                        </a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['nickname'] ?></td>
                                    <td class="am-text-middle"><?= $item['gender'] ?></td>
                                    <td class="am-text-middle"><?= $item['ulevel'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['umoney'] ?></td>
                                    <td class="am-text-middle"><?= $item['city'] ?: '--' ?></td>
                                    <td class="am-text-middle"><?= $item['createtime'] ?></td>
                                    <td class="am-text-middle"><?= $item['logintime'] ?></td>
                                    <td class="am-text-middle"><?= $item['phone'] ?></td>
                                    <td class="am-text-middle"><?= $item['yqcode'] ?></td>
                                    <td class="am-text-middle"><?= $item['agentid'] ?></td>
                                    <td class="am-text-middle"><?= $item['uscore'] ?></td>
                                    <td class="am-text-middle"><?= $item['relationid'] ?></td>
                                    <td class="am-text-middle"><?= $item['specialid'] ?></td>
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

