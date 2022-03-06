<?php /** @var \common\models\Article $model */?>
<div id="base" class="tab-pane in active">
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">标题</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::textInput('title',$model->title,['required' =>  'required','rangelength' => '[2, 100]','class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">概述</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::textarea('summary',$model->summary,['id' => 'summary','class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">缩略图</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::hiddenInput('thumb',$model->thumb)?>
            <?=\yii\helpers\Html::fileInput('UploadForm[thumb]',$model->thumb,['id' => 'thumb','input-name' => 'thumb','input-type' => 'ace_file','file-name' => 'thumb','class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">分类</label>
        <div class="col-sm-8">
            <select type="select" name="category_id" class="col-xs-12 col-sm-10">
                <option value="0">请选择</option>
                <?= $options ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">属性</label>
        <div class="col-sm-8">
            <label class="inline">
                <?=\yii\helpers\Html::checkbox('is_headline',$model->is_headline,['class' => 'ace'])?>
                <span class="lbl"> 头条</span>
            </label>&nbsp;&nbsp;
            <label class="inline">
                <?=\yii\helpers\Html::checkbox('is_recommend',$model->is_recommend,['class' => 'ace'])?>
                <span class="lbl"> 推荐</span>
            </label>&nbsp;&nbsp;
            <label class="inline">
                <?=\yii\helpers\Html::checkbox('is_slide_show',$model->is_slide_show,['class' => 'ace'])?>
                <span class="lbl"> 幻灯</span>
            </label>&nbsp;&nbsp;
            <label class="inline">
                <?=\yii\helpers\Html::checkbox('is_special_recommend',$model->is_special_recommend,['class' => 'ace'])?>
                <span class="lbl"> 特别推荐</span>
            </label>&nbsp;&nbsp;
            <label class="inline">
                <?=\yii\helpers\Html::checkbox('is_roll',$model->is_roll,['class' => 'ace'])?>
                <span class="lbl"> 滚动</span>
            </label>&nbsp;&nbsp;
            <label class="inline">
                <?=\yii\helpers\Html::checkbox('is_bold',$model->is_bold,['class' => 'ace'])?>
                <span class="lbl"> 加粗</span>
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">SEO标题</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::textInput('seo_title',$model->seo_title,['rangelength' => '[2, 100]','class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">SEO关键字</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::textInput('seo_keywords',$model->seo_keywords,['rangelength' => '[2, 100]','class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">SEO描述</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::textInput('seo_description',$model->seo_description,['rangelength' => '[2, 100]','class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">可见</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::dropDownList('visibility',(is_null($model->visibility) ? 1 : $model->visibility),['0' => '请选择','1' => '可见','2' => '回复'],['class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">评论</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::dropDownList('can_comment',(is_null($model->can_comment) ? 1 : $model->can_comment),['0' => '请选择','1' => '是','2' => '否'],['class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">发布</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::dropDownList('status',(is_null($model->status) ? 1 : $model->status),['0' => '请选择','1' => '发布','2' => '草稿'],['class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">排序</label>
        <div class="col-sm-8">
            <?=\yii\helpers\Html::textInput('sorts',($model->sorts ?: 1),['class' => 'col-xs-12 col-sm-10'])?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>