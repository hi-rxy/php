<?php

namespace common\models;

use common\services\Service;
use Yii;
use yii\db\ActiveQuery;

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
class GoodsSpec extends Service
{
    const STATUS_OPEN = 1; // 正常
    const STATUS_CLOSE = 0; // 删除

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_spec}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'search', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 255],
            // trim
            [['name','value'], 'trim']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'goods_id' => '商品id',
            'name' => '属性名称',
            'value' => '属性值',
            'search' => '是否支持搜索1表示支持0表示不支持',
            'status' => '1正常0删除',
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

    /**
     * 关联商品属性表
     * @return ActiveQuery
     */
    public function getGoodsAttr()
    {
        return $this->hasOne(GoodsAttr::className(), ['id' => 'attr_id'])->alias('goods_attr_');
    }

    /**
     * 查询条件
     * @return ActiveQuery
     */
    public static function queryCondition ()
    {
        $query = self::find();
        $query->andFilterWhere(['status' => self::STATUS_OPEN]);
        return $query;
    }
}
