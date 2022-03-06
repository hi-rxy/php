<?php
namespace common\models;

use common\models\traits\BaseModelTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%attr}}".
 *
 * @property int $id 属性id
 * @property int $type_id 类型id
 * @property string $name 属性名称
 * @property string $value 属性值
 * @property string $unit 属性单位
 * @property int $search 是否支持搜索0表示支持1表示支持
 * @property int $type 控件类型1单选2多选3下拉4复选框
 * @property int $sort 控件排序
 * @property int $status 开启状态1表示关闭0表示开启
 * @property int $style 0表示参数1表示规格
 */
class Attr extends ActiveRecord
{
    const STATUS_CLOSE = 1; // 关闭
    const STATUS_OPEN = 0; // 开启

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%attr}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'search', 'type', 'sort', 'status', 'style'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 10],
            // trim
            [['name', 'value'], 'trim'],
            // unique
            [['type_id', 'name'], 'unique'],
            // default
            ['status', 'default', 'value' => self::STATUS_OPEN],
            ['status', 'in', 'range' => [self::STATUS_CLOSE, self::STATUS_OPEN]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '属性id',
            'type_id' => '类型id',
            'name' => '属性名称',
            'value' => '属性值',
            'unit' => '属性单位',
            'search' => '是否支持搜索0表示支持1表示支持',
            'type' => '控件类型1单选2多选3下拉4复选框',
            'sort' => '控件排序',
            'status' => '开启状态0表示关闭1表示开启',
            'style' => '0表示参数1表示规格',
        ];
    }

    /**
     * 关联商品类型表
     * @return ActiveQuery
     */
    public function getGoodsType()
    {
        return $this->hasOne(GoodsType::className(), ['id' => 'type_id'])->alias('goods_type_');
    }

    /**
     * 关联商品属性表
     * @return ActiveQuery
     */
    public function getGoodsAttr()
    {
        return $this->hasMany(GoodsAttr::className(), ['attr_id' => 'id'])->alias('goods_attr_');
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
     * 属性类型集合
     * @param $typeId
     * @return array|ActiveRecord[]
     */
    public static function getAttrTypeList ($typeId)
    {
        return self::queryCondition()->andFilterWhere(['type_id' => $typeId])->orderBy(['id' => SORT_DESC,'sort' => SORT_DESC])->all();
    }

    /**
     * 属性ID集合
     * @param $typeId
     * @return array
     */
    public static function getIdsByTypeId ($typeId)
    {
        return self::queryCondition()->andFilterWhere(['type_id' => $typeId])->select(['id'])->column();
    }
}
