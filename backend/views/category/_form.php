<?php

use jinxing\admin\web\ValidateAsset;
use yii\helpers\Url;
use yii\web\Request;

$this->title = '添加子级分类';
$this->registerJs($this->render('js/_form.js'));
ValidateAsset::register($this);
?>
<style>
    label.error {color: red;}
    .footer {display: none}
</style>
<form method="post" class="form-horizontal" name="save" action="<?php echo Url::toRoute(['children']) ?>" autocomplete="off" id="save">
    <input type="hidden" name="_csrf-backend" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="id" value="<?= Yii::$app->request->get('id', 0) ?>">
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-type_id required"> 分类 </label>
        <div class="col-sm-8 div-right-type_id">
            <select type="select" name="parent_id" class="form-control">
                <option value="0">请选择</option>
                <?=$options?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-name required"> 分类名称 </label>
        <div class="col-sm-8 div-right-name">
            <input type="text" required="true" rangelength="[2, 100]" name="name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-name required"> 分类昵称 </label>
        <div class="col-sm-8 div-right-name">
            <input type="text" required="true" rangelength="[2, 100]" name="alias" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-sort required"> 分类排序 </label>
        <div class="col-sm-8 div-right-sort">
            <input type="text" number="true" name="sort" value="1" class="form-control">
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