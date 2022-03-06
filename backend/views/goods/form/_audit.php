<?php

use jinxing\admin\web\ValidateAsset;

$this->title = '审核商品';
$this->registerJs($this->render('../js/_audit.js'));
ValidateAsset::register($this);
?>
<style>
    .footer,.page-header{
        display: none;
    }
</style>
<form class="form-horizontal" id="save" action="" method="post">
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">备注内容</label>
        <div class="col-sm-8">
            <textarea class="col-xs-12 col-sm-10" name="content" placeholder="审核不通过备注" id="editor" style="height: 150px;"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">状态</label>
        <div class="col-sm-8">
            <label>
                <input name="status" type="radio" value="1" class="ace">
                <span class="lbl"> 通过</span>
            </label>
            <label>
                <input name="status" type="radio" value="2" class="ace">
                <span class="lbl"> 拒绝</span>
            </label>
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