<div class="logistics">
    <h3>选择支付方式</h3>
    <ul class="pay-list">
        <?php if(!is_weixin()):?>
            <li class="pay taobao selected" value="1"><img src="/static/default/images/zhifubao.jpg" />支付宝<span></span></li>
        <?php endif;?>
        <li class="pay qq <?php if(is_weixin()):?>selected<?php endif;?>" value="2"><img src="/static/default/images/weizhifu.jpg" />微信<span></span></li>
        <?php if(is_weixin()):?>
            <?php $pay_type = 2;?>
        <?php else:?>
            <?php $pay_type = 1;?>
        <?php endif;?>
        <input type="hidden" name="pay_type" value="<?=$pay_type?>">
    </ul>
</div>