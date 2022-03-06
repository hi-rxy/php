<?php
namespace api\models\web\traits;

use yii\behaviors\TimestampBehavior;
use api\behaviors\UpdateBehavior;

/**
 * Trait AdminModelTrait 定义处理时间戳
 *
 * @package api\models\web\traits
 */
trait AdminModelTrait
{
    /**
     * @Notes: 定义行为处理时间
     * @Function behaviors
     * @return array
     * @author: Admin
     * @Time: 2022/2/22 13:27
     */
    public function behaviors()
    {
        return [
            // 时间处理
            TimestampBehavior::className(),
            // created_id 和 updated_id 字段的处理
            //UpdateBehavior::className(),
        ];
    }
}
