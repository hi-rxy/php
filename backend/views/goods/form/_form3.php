<div id="graphic" class="tab-pane">
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">商品相册图</label>
        <div class="col-sm-8">
            <div class="fl imgUpload">
                <ul class="fl imgBox ui-sortable">
                    <?php
                    use yii\helpers\Html;

                    if (isset($goods['gallery_one']) && $goods['gallery_one']) :
                    foreach ($goods['gallery_one'] as $item) :?>
                        <li class="imgList">
                            <img width="100%" src="<?='/uploads'.$item?>">
                            <input type="hidden" name="goods_gallery[]" value="<?=$item?>" />
                            <span class="ace-icon fa fa-trash-o bigger-160 imgDel"><i></i></span>
                        </li>
                    <?php endforeach;
                    endif;?>
                    <span class="mainImg ui-sortable-handle">商品主图</span>
                    <input type="hidden" name="goods_gallery_hidden" value="" datatype="*" nullmsg="商品主图不能为空"/>
                </ul>
                <label class="fl imgFile">+
                    <input name="goods_gallery_file" type="file" multiple="" maxlength="5" accept="image/gif, image/jpg, image/jpeg, image/png">
                </label>
                <p class="fl imgPrompt">
                    共<i>0</i>张，还能上传<i>5</i>张
                </p>
                <p class="explain">备注：上传图片的最佳尺寸：800像素*800像素，其他尺寸会影响页效果，格式png，jpeg，jpg，gif。大小不超过2M，商品图片一共可以上传5张，默认第一张为主图封面.</p>
                <div id="goodsUpload"></div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">商品详情</label>
        <div class="col-sm-8">
            <?= \cliff363825\kindeditor\KindEditorWidget::widget([
                'model' => $model->isNewRecord ? (new \common\models\GoodsContent()) : $model->goodsContent,
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
                    'uploadJson' => yii\helpers\Url::to(['goods/upload'])
                ],
            ]); ?>
        </div>
    </div>
</div>