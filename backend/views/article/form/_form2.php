<div id="content" class="tab-pane">
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">内容</label>
        <div class="col-sm-8">
            <?= /** @var \common\models\Article $model */
            \cliff363825\kindeditor\KindEditorWidget::widget([
                'model' => $model->getIsNewRecord() ? (new \common\models\ArticleContent()) : $model->articleContent,
                'attribute' => 'content',
                'options' => [
                   'class' => 'col-xs-12 col-sm-10',
                   'name' => 'content',
                ], // html attributes
                'clientOptions' => [
                    'width' => '100%',
                    'height' => '350px',
                    'themeType' => \cliff363825\kindeditor\KindEditorWidget::THEME_TYPE_DEFAULT, // optional: default, simple, qq
                    'langType' => \cliff363825\kindeditor\KindEditorWidget::LANG_TYPE_ZH_CN, // optional: ar, en, ko, zh_CN, zh_TW
                    'uploadJson' => yii\helpers\Url::to(['article/upload'])
                ],
            ]); ?>
        </div>
    </div>
</div>