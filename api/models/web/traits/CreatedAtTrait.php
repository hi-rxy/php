<?php
/**
 *
 * TimeTrait.php
 *
 * Author: jinxing.liu
 * Create: 2019-06-21 14:46
 * Editor: created by PhpStorm
 */

namespace api\models\web\traits;

use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Trait CreatedAtTrait 定义处理时间的方法
 *
 * @package app\models\traits
 */
trait CreatedAtTrait
{
    /**
     * 定义行为处理时间
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'time' => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }
}