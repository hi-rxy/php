<?php

namespace backend\models;

use jinxing\admin\helpers\Helper;
use yii;

/**
 * This is the model class for table "{{%goods_spec}}".
 *
 * @property int $id 属性id
 * @property int $goods_id 商品id
 * @property string $name 属性名称
 * @property string $value 属性值
 * @property int $search 是否支持搜索1表示支持0表示不支持
 * @property int $status 1正常0删除
 */
class GoodsSpec extends \common\models\GoodsSpec
{
    /**
     * 保存自定义属性
     * @param int $goodsId
     * @param array $data
     * @return array|bool
     * @throws yii\db\Exception
     */
    public static function saveGoodsSpec($goodsId, array $spec_details, array $goods_attr_pic)
    {
        # 1、商品自定义规格
        $a = $spec_details;
        $c = $goods_attr_pic;
        # 2、查找当前商品原有自定义规格
        $b = self::getGoodsSpecByGid($goodsId);
        # 3、更新状态
        $name = self::getGoodsSpecDiffByName($b, $a);

        if (!empty($name)) self::updateStatus($goodsId, $name);

        $key = array_keys($b);

        # 4、插入商品自定义规格表
        $model = new self();
        $d = [];
        foreach ($a as $k => $v) 
        {
            $e = ['name' => $k, 'value' => implode(",", $v), 'goods_id' => $goodsId];

            $clone = in_array($k, $key) ? self::findOne($b[$k]) : clone $model;

            if ($clone->load($e, '') && $clone->save()) 
            {
                $id = $clone->isNewRecord ? $clone->attributes['id'] : $clone->id;

                foreach ($a[$k] as $k1 => $v1)
                {
                    $d[] = ['goods_id' => $goodsId, 'attr_id' => $id, 'value' => $v1, 'type' => 1, 'pic' => isset($c[$k][$v1]) ? $c[$k][$v1] : ""];
                }
                return $d;
            } else throw new yii\db\Exception(Helper::arrayToString($model->getErrors()));
        }

        return true;
    }

    public static function getGoodsSpecByGid($goodsId)
    {
        return self::find()->select(['id', 'name'])->where(['goods_id' => $goodsId])->indexBy('name')->asArray()->all();
    }

    public static function getGoodsSpecDiffByName($a, $b)
    {
        return array_diff(array_keys($a), array_keys($b));
    }

    public static function updateStatus($goodsId, $name)
    {
        self::updateAll(['status' => 0], ['and', ['goods_id' => $goodsId], ['in', 'name', $name]]);
    }
}
