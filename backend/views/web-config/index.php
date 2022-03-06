<?php

use jinxing\admin\web\ValidateAsset;
use yii\helpers\Url;

$this->title = '网站配置';
$this->registerJs($this->render('js/_form.js'));
ValidateAsset::register($this);
?>
<form class="form-horizontal" id="save" action="<?= Url::toRoute(['update']) ?>" method="post">
    <div class="tabbable">
        <ul class="nav nav-tabs padding-12 tab-color-blue background-blue">
            <?php foreach ($data as $key => $item) : ?>
                <li <?php if ($key == 0) { ?>class="active" <?php } ?>>
                    <a data-toggle="tab"
                       href="#<?= strtolower(str_replace('_', '', $item['name'])) ?>"><?= $item['title'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content">
            <?php foreach ($data as $key => $value) : ?>
                <div id="<?= strtolower(str_replace('_', '', $value['name'])) ?>"
                     class="tab-pane <?php if ($key == 0) { ?>in active<?php } ?>">
                    <?php foreach ($value['config'] as $k => $v) : ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label no-padding-left"><?= $v['title'] ?>:</label>
                            <div class="col-sm-4"><?php echo htmlspecialchars_decode($v['html']); ?></div>
                        </div>
                        <div class="clearfix"></div>
                    <?php endforeach; ?>
                    <div class="clearfix"></div>
                </div>
            <?php endforeach; ?>

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