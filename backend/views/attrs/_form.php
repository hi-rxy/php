<?php

use yii\helpers\Url;
use yii\web\Request;
use jinxing\admin\web\ValidateAsset;

$this->title = '配置' . $data['name'] . '属性';
$this->registerJs($this->render('js/_form.js'));
ValidateAsset::register($this);
?>
<style>
    label.error {
        color: red;
    }
    .footer {
        display: none
    }
</style>

<form class="form-horizontal" id="save" action="<?= Url::toRoute(['attrs/update']) ?>" method="post">
    <input type="hidden" name="_csrf-backend" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="typeId" value="<?= Yii::$app->request->get('id', 0) ?>">
    <input type="hidden" name="isNewRecord" value="<?= empty($rows) ? 0 : 1 ?>">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <?=$this->render('form/_table',compact('rows'));?>
        </div>
    </div>

    <div class="clearfix form-actions">
        <div class="col-md-offset-0 col-md-12">
            <button class="btn btn-info" type="submit" id="submit-btn">
                <i class="ace-icon fa fa-check bigger-110"></i>
                保存
            </button>
        </div>
    </div>
</form>


