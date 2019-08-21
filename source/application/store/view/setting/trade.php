<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">授权回话</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require">Access token </label>
                                <div class="am-u-sm-9 am-input-group">
                                    <div class="am-u-sm-7">
                                        <input type="text" class="am-form-field" name="" value="<?= $data['token'] ?>" >
                                    </div>
                                    <label class="am-u-sm-5 am-form-label am-text-left">授权文档 http://help.jd.com/jos/question-594.html</label>
                                     
                                </div>
                            </div>
                      
                           
                              <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="button" onclick="opensq()" class="j-submit am-btn am-btn-secondary">授权
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
<script>
    function opensq(){

        var url = "https://auth.360buy.com/oauth/authorize?response_type=code&client_id={{$data['datac']}}&redirect_uri={{$data['urlc']}}&";
        window.open (url,'newwindow', 'height=500, width=450,top=0, left=0, toolbar=no, menubar=no,scrollbars=no, resizable=no,location=n o, status=no')

    }
</script>
