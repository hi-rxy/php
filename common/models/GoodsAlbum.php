<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%goods_album}}".
 *
 * @property int $id 相册id
 * @property int $goods_id 商品id
 * @property string|null $photo_1 图1
 * @property string|null $photo_2 图2
 * @property string|null $photo_3 图3
 * @property string|null $photo_4 图4
 * @property int|null $created_at 添加时间
 * @property int|null $updated_at 修改时间
 */
class GoodsAlbum extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_album}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'created_at', 'updated_at'], 'integer'],
            [['photo_1', 'photo_2', 'photo_3', 'photo_4'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '相册id',
            'goods_id' => '商品id',
            'photo_1' => '图1',
            'photo_2' => '图2',
            'photo_3' => '图3',
            'photo_4' => '图4',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 自动把时间戳填充指定的属性
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ]
            ]
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
