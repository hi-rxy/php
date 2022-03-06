<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%goods_attr_custom}}".
 *
 * @property int $id 主键id
 * @property int $goods_id 商品id
 * @property string|null $name 属性名称
 * @property string $value 属性值
 */
class GoodsAttrCustom extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attr_custom}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['name'], 'string', 'max' => 200],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'goods_id' => '商品id',
            'name' => '属性名称',
            'value' => '属性值',
        ];
    }

    /**
     * 关联商品表
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id'])->alias('goods_');
    }
}
