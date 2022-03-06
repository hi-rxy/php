<?php

use jinxing\admin\helpers\Helper;
use jinxing\admin\web\ValidateAsset;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = $model->isNewRecord ? '发布商品' : '修改商品';
$url = Helper::getAssetUrl();
$depends = ['depends' => 'jinxing\admin\web\AdminAsset'];
$this->registerCssFile($url . '/js/kindeditor_4.1.10/themes/default/default.css', $depends);
$this->registerJsFile($url . '/js/kindeditor_4.1.10/kindeditor-all.min.js', $depends);
$this->registerJsFile($url . '/js/kindeditor_4.1.10/lang/zh_CN.js', $depends);
$this->registerJsFile($url . '/js/jquery-ui.min.js', $depends);
ValidateAsset::register($this);
$this->registerJs($this->render('js/_form.js'));
$renderParams = compact('model','goods');
?>
<link rel="stylesheet" href="/css/View/goods/goods.css?v=<?= time() ?>">
<form class="form-horizontal" id="save" action="<?= Url::current() ?>" method="post">
    <div class="tabbable">
        <ul class="nav nav-tabs padding-12 tab-color-blue background-blue">
            <li class="active"><a data-toggle="tab" href="#base">基础信息</a></li>
            <li class=""><a data-toggle="tab" href="#sales">销售信息</a></li>
            <li class=""><a data-toggle="tab" href="#graphic">图文信息</a></li>
        </ul>
        <div class="tab-content">
            <?=$this->render('form/_form1', $renderParams)?>
            <?=$this->render('form/_form2', $renderParams)?>
            <?=$this->render('form/_form3', $renderParams)?>
            <div class="clearfix form-actions">
                <div class="col-md-offset-0 col-md-12">
                    <button class="btn btn-info" type="submit" id="submit-btn">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        保存
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var config = {
            isNewRecord : <?=$model->isNewRecord ? 1 : 0?>,
            data : {
                'class_id' : <?=$model->isNewRecord ? 0 : $model->class_id?>,
                'store_class_id' : <?=$model->isNewRecord ? 0 : $model->store_class_id?>,
                'classIds' : <?=Json::encode($goods['classIds'])?>,
                'storeClassIds' : <?=Json::encode($goods['storeClassIds'])?>,
            },
            url: {
                getChildGoodsClass: "<?=Url::toRoute(['goods-class/get-child-class-by-goods'])?>",
                getChildStoreClass: "<?=Url::toRoute(['store-class/get-child-class-by-store'])?>",
                uploadGoodsImg: "<?=Url::toRoute(['upload'])?>",
            }
        }
    </script>
    <script src="/js/Views/goods/_form.js?v=<?= time() ?>"></script>
    <script src="/js/Views/goods/_sku.js?v=<?= time() ?>"></script>
<?php $this->endBlock(); ?>