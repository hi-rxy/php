<?php

use jinxing\admin\web\ValidateAsset;
use yii\helpers\Url;
use yii\web\Request;
use common\models\GoodsClass;

$this->title = '添加分类';
$this->registerJs($this->render('js/_form.js'));
ValidateAsset::register($this);

$classId = Yii::$app->request->get('id', 0);
$model = GoodsClass::findOne($classId);
?>
<style>
    label.error {
        color: red;
    }

    .footer {
        display: none
    }
</style>
<form method="post" class="form-horizontal" name="save" action="<?php echo Url::toRoute(['children']) ?>" autocomplete="off" id="save">
    <input type="hidden" name="_csrf-backend" value="<?= Yii::$app->request->csrfToken; ?>">
    <input type="hidden" name="id" value="<?= Yii::$app->request->get('id', 0) ?>">
    <input type="hidden" name="level" value="1">
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-name required"> 分类名称 </label>
        <div class="col-sm-8 div-right-name">
            <input type="text" required="true" rangelength="[2, 100]" name="name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-keywords "> 分类关键词 </label>
        <div class="col-sm-8 div-right-keywords">
            <input type="text" name="keywords" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-desc "> 分类描述 </label>
        <div class="col-sm-8 div-right-desc">
            <input type="text" name="desc" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-url "> 跳转地址 </label>
        <div class="col-sm-8 div-right-url">
            <input type="text" name="url" class="form-control ">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-type_id required"> 商品类型 </label>
        <div class="col-sm-8 div-right-type_id">
            <select type="select" name="type_id" class="form-control">
                <?php foreach ($goodsType as $key => $item) : ?>
                    <option <?php if ($model->type_id == $key){?>selected<?php }?> value="<?= $key ?>"><?= $item ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-status required"> 分类状态 </label>
        <div class="col-sm-8 div-right-status">
            <input type="radio" class="ace" checked name="status" value="1"> <span class="lbl"> 开启 </span>
            <input type="radio" class="ace" name="status" value="0"> <span class="lbl"> 关闭 </span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label div-left-is_nav required"> 导航显示 </label>
        <div class="col-sm-8 div-right-is_nav">
            <input type="radio" class="ace" checked name="is_nav" value="1"> <span class="lbl"> 显示 </span>
            <input type="radio" class="ace" name="is_nav" value="0"> <span class="lbl"> 隐藏 </span>
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