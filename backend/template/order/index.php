<?php

use yii\helpers\Json;
use yii\helpers\Url;

list(, $url) = Yii::$app->assetManager->publish((new \backend\template\assets\AppAsset())->sourcePath);
$depends = ['depends' => 'backend\template\assets\AppAsset'];
$this->registerCssFile($url . '/css/cart.css', $depends);
$this->registerCssFile($url . '/css/confirem_order.css', $depends);
$this->registerJsFile($url . '/js/address.js', $depends);
$this->title = '立即购买-'.$model->name;
$renderParams = compact('model','address', 'result','page_data','count_price','count','count_status','count_status_1')
?>
<script>
    var uid = 1;
    var is_mobile = <?=is_mobile() ? 1 : 0?>;
    var ajaxCity = '<?=Url::to(['order/ajax-city'])?>';
    var ajaxAddressCreate = '<?=Url::to(['user-address/async-create'])?>';
    var ajaxAddressUpdate = '<?=Url::to(['user-address/async-update'])?>';
    var ajaxGetAddressInfo = '<?=Url::to(['user-address/async-views'])?>';
    var ajaxGetAddressDelete = '<?=Url::to(['user-address/async-delete'])?>';
    var ajaxSetDefaultAddress = '<?=Url::to(['user-address/async-set-default'])?>';
    var province_data = <?=Json::encode($address['province_data'])?>;
</script>
<form action="<?=Url::to(['order/buy-do'])?>" method="post">
    <!--地址 -->
    <div class="paycont">
        <?=$this->render('views/_shipping_address',$renderParams)?>
        <!--支付方式-->
        <?=$this->render('views/_payment',$renderParams)?>
        <div class="clear"></div>
        <!--订单 -->
        <?=$this->render('views/_order',$renderParams)?>
    </div>
</form>
<?=$this->render('views/_address')?>