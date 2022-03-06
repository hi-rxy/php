<?php

namespace backend\models;

use jinxing\admin\helpers\Helper;
use yii;
use yii\base\Exception;

/**
 * This is the model class for table "{{%goods_attr}}".
 *
 * @property int $id 主键id
 * @property int $goods_id 商品id
 * @property int $attr_id 属性id/规格id
 * @property string $value 属性值/规格值
 * @property int $type 0表示参数1表示规格
 * @property string|null $pic 规格图片
 * @property int $status 1正常0删除
 */
class GoodsAttr extends \common\models\GoodsAttr
{
    /**
     * 保存商品属性
     * @param array $spec_id
     * @param array $data
     * @param int $goodsId
     * @return array|bool
     * @throws yii\db\Exception
     */
    public static function saveGoodsAttrStandard(array $spec_id, array $data, $goodsId = '')
    {
        $a = [];
        $b = [];
        $d = self::getGoodsSpecValue($spec_id);
        $d = array_unique($d);

        if ($goodsId) 
        {
            # 2、查找当前商品原有自定义规格
            $b = self::getGoodsAttrByGid($goodsId);
            # 3、更新状态
            $c = array_diff(array_keys($b), $d);
            if (!empty($c)) self::updateStatus($goodsId, $c);
        }

        $model = new self();
        foreach ($data as $k => $v) 
        {
            /** @var GoodsAttr $clone */
            $clone = !empty($b) && isset($b[$v['value']]) && ($v['attr_id'] == $b[$v['value']]['attr_id']) ? self::findOne([$b[$v['value']]['id']]) : clone $model;

            if (in_array($v['value'], $d)) $v['status'] = 1; else $v['status'] = 2;

            $clone->goods_id = $v['goods_id'];
            $clone->attr_id = $v['attr_id'];
            $clone->value = $v['value'];
            $clone->pic = $v['pic'];
            $clone->type = $v['type'];
            $clone->status = $v['status'];
            if ($clone->save()) {
                if ($clone->isNewRecord) $a[$v['value']] = $clone->attributes['id']; else $a[$v['value']] = $clone->id;
            } else throw new yii\db\Exception(Helper::arrayToString($model->getErrors()));
        }

        return $a;
    }

    /**
     * @param int $goodsId
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public static function saveGoodsAttrParams($goodsId, array $data)
    {
        self::deleteAll(['type' => 0, 'goods_id' => $goodsId]);

        if (!empty($data)) 
        {
            $model = new self();
            foreach ($data as $k => $v)
            {
                foreach ($v as $k1 => $v1)
                {
                    if (empty($v1)) continue;
                    $clone = clone $model;
                    $b = ['goods_id' => $goodsId, 'attr_id' => $k, 'value' => $v1, 'type' => 0];
                    if ($clone->load($b, '') && !$clone->save()) throw new Exception(Helper::arrayToString($model->getErrors()));
                }
            }
        }
        return true;
    }

    public static function getGoodsSpecValue($spec_id)
    {
        $d = [];
        foreach ($spec_id as $k => $v)
        {
            foreach ($v as $ko => $vo)
            {
                $d[] = json_decode(trim(htmlspecialchars_decode($vo)), true)['spec_value'];
            }
        }
        return $d;
    }

    public static function getGoodsAttrByGid($goodsId)
    {
        return self::find()->where(['goods_id' => $goodsId, 'type' => 1])->select(['value', 'id', 'attr_id'])->indexBy('value')->asArray()->all();
    }

    public static function updateStatus($goodsId, $value)
    {
        self::updateAll(['status' => \common\models\Goods::STATUS_PENDING_REVIEW], ['and', ['goods_id' => $goodsId], ['type' => 1], ['in', 'value', $value]]);
    }
}
