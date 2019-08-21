<link rel="stylesheet" href="assets/store/css/goods.css">
<link rel="stylesheet" href="assets/store/plugins/umeditor/themes/default/css/umeditor.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">基本信息</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="itemname"
                                           value="<?= $model['itemname'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                 <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品价格 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input disabled="disabled"  type="text" class="tpl-form-input"  
                                           value="<?= $model['newprice'] ?>" name="newprice" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                 <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">淘宝ID </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <input disabled="disabled"  type="text" class="tpl-form-input"  
                                               value="<?= $model['itemid'] ?>" name="itemid" required>
                                    </div>
                            </div>
                             <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">初始销量</label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input disabled="disabled"  type="text" class="tpl-form-input" name="sellnum"
                                           value="<?= $model['sellnum'] ?>">
                                </div>
                            </div>

                             <div class="am-form-group">
                                 <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">浏览商品 </label>
                                    <div class="am-u-sm-9 am-u-end">
                                        <button type="button" onclick=" window.open('http://item.taobao.com/item.htm?id=<?= $model['itemid'] ?>', 'newwindow', 'height=600, width=1200, top=200, left=400, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no') " 
                                                class="am-btn am-btn-secondary am-radius">
                                            <i class="am-icon-chain"></i> 去淘宝浏览
                                        </button>
                                    </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">商品图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                   <div class="uploader-list am-cf">
                                            <?php foreach ($model['imglist'] as $key => $item): ?>

                                                <div class="file-item">
                                                    <img src="<?= $item ?>">
                                                    <input type="hidden" name="imglist"
                                                           value="<?= $key ?>">
                                                    <i class="iconfont "></i>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <div class="help-block am-margin-top-sm">
                                        <small>图片是网络地址 JSON；尽量使用CDN图片地址</small>
                                    </div>
                                </div>
                            </div>
 
 
 

                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">商品推荐语</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">商品详情 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <!-- 加载编辑器的容器 -->
                                    <textarea id="container" name="remarktj"><?= $model['remarktj'] ?></textarea>
                                </div>
                            </div>
                           
                    
                                <input type="hidden" name="id" value="<?= $model['id'] ?>" />
                            
                          
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit"  class="j-submit am-btn am-btn-secondary">提交
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

 

<script src="assets/store/js/ddsort.js"></script>
<script src="assets/store/plugins/umeditor/umeditor.config.js"></script>
<script src="assets/store/plugins/umeditor/umeditor.min.js"></script>
<script src="assets/store/js/goods.spec.js"></script>
<script>
    $(function () {


        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
