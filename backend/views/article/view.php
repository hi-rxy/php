<?php

use yii\helpers\Html;

$this->title = "{$model->title}<small> <i class='icon-double-angle-right'></i> {$model->category->name}</small>";
?>
<?php
echo Html::decode($model->articleContent->content);
?>
