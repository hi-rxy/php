<?php

namespace backend\models;

use common\models\GoodsAttrCustom as GoodsAttrCustomAlias;
use jinxing\admin\helpers\Helper;
use yii;
use yii\db\Exception;

/**
 * This is the model class for table "{{%goods_attr_custom}}".
 *
 * @property int $id 主键id
 * @property int $goods_id 商品id
 * @property string|null $name 属性名称
 * @property string $value 属性值
 */
class GoodsAttrCustom extends GoodsAttrCustomAlias
{
    /**
     * 保存商品图片
     * @param int $goodsId
     * @param array $data
     * @return array|bool
     * @throws Exception
     */
    public static function saveGoodsAttrCustom($goodsId, array $data)
    {
        self::deleteAll(['goods_id' => $goodsId]);

        if (!empty($data))
        {
            $b = isset($data['attr_name']) ? $data['attr_name'] : "";
            $c = isset($data['attr_value']) ? $data['attr_value'] : "";

            $model = new self();
            if (!empty($b) && !empty($c)) 
            {
                foreach ($b as $k => $v) 
                {
                    $clone = clone $model;
                    
                    $d = ['goods_id' => $goodsId, 'name' => $b[$k], 'value' => $c[$k]];
                    
                    if ($clone->load($d, '') && !$clone->save()) throw new Exception(Helper::arrayToString($model->getErrors()));
                }
            }
        }
        return true;
    }
}
