<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2022/2/23
 * Time: 9:34
 */

namespace api\models\web\traits;

use api\exceptions\ErrorMsgException;
use api\exceptions\HttpBadRequestException;
use jinxing\admin\helpers\Helper;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

trait CurdModelTrait
{
    /**
     * @Notes: 添加/修改
     * @Function add
     * @param ActiveRecord $model
     * @param array $data
     * @param string $scene
     * @return ActiveRecord
     * @throws HttpBadRequestException
     * @throws ErrorMsgException
     * @author: Admin
     * @Time: 2022/2/22 14:09
     */
    public static function store (\yii\db\ActiveRecord $model,$data = [],$scene = '')
    {
        if ($scene == '') $scene = \Yii::$app->controller->action->id;
        if (!$data) throw new ErrorMsgException('未接收到数据');
        // 验证是否存在主键且不为空
        $model = self::ar($model,$data);
        if (!$scene) throw new ErrorMsgException('模型：'.$model::className().'未设置场景');
        // 验证是否定义了创建对象的验证场景
        if (ArrayHelper::getValue($model->scenarios(), $scene)) $model->scenario = $scene;
        // 对model对象各个字段进行赋值
        if (!$model->load($data, '')) throw new HttpBadRequestException(Helper::arrayToString($model->getErrors()));
        // 保存对象
        if (!$model->save()) throw new HttpBadRequestException('save：'.Helper::arrayToString($model->getErrors()));
        return $model;
    }

    /**
     * @Notes: 删除
     * @Function del
     * @param ActiveRecord $model
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @author: Admin
     * @Time: 2022/2/22 14:18
     */
    public static function del (\yii\db\ActiveRecord $model,$data = [])
    {
        $model = self::ar($model,$data);
        $scene = \Yii::$app->controller->action->id;
        if (ArrayHelper::getValue($model->scenarios(), $scene)) $model->scenario = 'delete';
        return $model->delete();
    }

    /**
     * @Notes: 获取ar对象
     * @Function ar
     * @param ActiveRecord $model
     * @param $data
     * @return ActiveRecord|null
     * @author: Admin
     * @Time: 2022/2/22 14:22
     */
    private static function ar($model,$data)
    {
        $pk = $model::primaryKey();
        if (!$pk[0]) throw new ErrorMsgException('模型：'.$model::className().'主键未设置');
        if (empty($data[$pk[0]])) return $model;
        /* @var $ar \yii\db\ActiveRecord */
        if (!$ar = static::findOne([$pk[0] => $data[$pk[0]]])) {
            return $model;
        }
        return $ar;
    }
}
