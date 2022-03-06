<?php

namespace common\services;

use Closure;
use jinxing\admin\helpers\Helper;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

abstract class Service extends ActiveRecord
{
    /**
     * 获取实例
     * @param $modelClass
     * @param $id
     * @return mixed
     */
    protected static function findModel($modelClass, $id)
    {
        if (($model = $modelClass::findOne($id)) !== null) return $model;
        return (new $modelClass);
    }

    /**
     * 添加
     * @param ActiveRecord $ar
     * @param array $options
     * @return string|ActiveRecord
     * @throws \Exception
     */
    public static function doCreate(ActiveRecord $ar, array $options = [])
    {
        if (ArrayHelper::getValue($ar->scenarios(), 'create')) $ar->scenario = 'create';
        $formName = isset($options[$ar->formName()]) ? $ar->formName() : '';
        if ($ar->load($options,$formName) && $ar->save()) return $ar;
        return Helper::arrayToString($ar->getErrors());
    }

    /**
     * 修改
     * @param ActiveRecord $ar
     * @param array $options
     * @return string|ActiveRecord
     * @throws \Exception
     */
    public static function doUpdate(ActiveRecord $ar, array $options = [])
    {
        if (ArrayHelper::getValue($ar->scenarios(), 'update')) $ar->scenario = 'update';
        $formName = isset($options[$ar->formName()]) ? $ar->formName() : '';
        if ($ar->load($options,$formName) && $ar->save()) return $ar;
        return Helper::arrayToString($ar->getErrors());
    }

    /**
     * 删除
     * @param ActiveRecord $ar
     * @return string|ActiveRecord
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function doDelete(ActiveRecord $ar)
    {
        if ($ar->delete()) return $ar;
        return Helper::arrayToString($ar->getErrors());
    }

    /**
     * 更新字段
     * @param ActiveRecord $ar
     * @param $func
     * @param array $options
     * @return bool|string|object
     * @throws Exception
     */
    public static function doUpdateField(ActiveRecord $ar, $func, array $options)
    {
        try {
            $scenario = isset($options['name']) ? $options['name'] : $func;
            if (ArrayHelper::getValue($ar->scenarios(), $scenario)) $ar->scenario = $scenario;
            if ($func instanceof Closure) {
                $field = $options['name'];
                $ar->$field = call_user_func_array($func, [
                    $options['name'],
                    $options['value']
                ]);
            } else {
                $ar->$func = $options[$func];
            }
            return $ar->save() ? $ar : false;
        } catch (\yii\db\Exception $exception) {
            return $exception->getMessage();
        } catch (\yii\base\Exception $exception) {
            return $exception->getMessage();
        }
    }
}