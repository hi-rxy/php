<?php
namespace common\models;

use common\models\traits\BaseModelTrait;
use common\services\Service;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
class GoodsAttr extends Service
{
    const STATUS_OPEN = 1; // 正常
    const STATUS_CLOSE = 0; // 删除

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_attr}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['goods_id', 'attr_id', 'type', 'status'], 'integer'],
            [['value', 'pic'], 'string', 'max' => 255],
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
            'attr_id' => '属性id/规格id',
            'value' => '属性值/规格值',
            'type' => '0表示参数1表示规格',
            'pic' => '规格图片',
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
     * 关联属性表
     * @return ActiveQuery
     */
    public function getAttr()
    {
        return $this->hasOne(Attr::className(), ['id' => 'attr_id'])->alias('attr_');
    }

    /**
     * 关联商品自定义规格表
     * @return ActiveQuery
     */
    public function getGoodsSpec()
    {
        return $this->hasOne(GoodsSpec::className(), ['id' => 'attr_id'])->alias('goods_spec_');
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

    /**
     * 查询数据
     * @param $where
     * @return array
     */
    public static function getSearchData ($where)
    {
        return self::find()->alias('goods_attr_')->where($where)->with(['goodsSpec'])->asArray()->all();
    }
}
