<?php

use jinxing\admin\helpers\Helper;
use jinxing\admin\web\ValidateAsset;
use yii\helpers\Url;

/** @var \common\models\Article $model */
$this->title = $model->isNewRecord ? '发布文章' : '修改文章';
$this->registerJs($this->render('js/_form.js'));
$url = Helper::getAssetUrl();
$depends = ['depends' => 'jinxing\admin\web\AdminAsset'];
$this->registerCssFile($url . '/js/kindeditor_4.1.10/themes/default/default.css', $depends);
$this->registerJsFile($url . '/js/kindeditor_4.1.10/kindeditor-all.min.js', $depends);
$this->registerJsFile($url . '/js/kindeditor_4.1.10/lang/zh_CN.js', $depends);
ValidateAsset::register($this);
$renderParams = compact('model','options');
?>
<style>
    label.error {
        color: red;
    }
    .footer {
        display: none
    }
</style>
<script type="text/javascript">
    var config = {
        thumbPath : '<?=$model->thumb?>',
        isNewRecord : <?=$model->isNewRecord ? 1 : 0?>,
        url : {
            uploadUrl : '<?=Url::toRoute(['upload'])?>',
        }
    }
</script>
<form class="form-horizontal" id="save" action="<?= Url::current() ?>" method="post">
    <div class="tabbable">
        <ul class="nav nav-tabs padding-12 tab-color-blue background-blue">
            <li class="active"><a data-toggle="tab" href="#base">基本信息</a></li>
            <li class=""><a data-toggle="tab" href="#content">文章内容</a></li>
        </ul>

        <div class="tab-content">
            <?=$this->render('form/_form1',$renderParams);?>
            <?=$this->render('form/_form2',$renderParams);?>
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