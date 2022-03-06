<?php
namespace common\models;

use common\services\Service;
use yii\base\ErrorException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%goods_type}}".
 *
 * @property int $id
 * @property string $name 类型名称
 * @property int $status 开启状态0表示关闭1表示开启
 */
class GoodsType extends Service
{
    const STATUS_CLOSE = 0; // 隐藏
    const STATUS_OPEN = 1; // 开启

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%goods_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
            // trim
            [['name'], 'trim'],
            // scenario 场景
            [['status'], 'integer', 'on' => 'status'],
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
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * 删除后的操作
     * 删除属性
     */
    public function afterDelete()
    {
        parent::afterDelete();
        try{
            return Attr::deleteAll(['type_id' => $this->id]);
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }

    /**
     * 状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => '开启',
            self::STATUS_CLOSE => '禁用'
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     *
     * @param int $intStatus 状态值
     *
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_CLOSE => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 关联属性表
     * @return ActiveQuery
     */
    public function getAttr()
    {
        return $this->hasMany(Attr::className(), ['type_id' => 'id'])->alias('attr_');
    }

    /**
     * 关联分类表
     * @return ActiveQuery
     */
    public function getGoodsClass()
    {
        return $this->hasMany(GoodsClass::className(), ['type_id' => 'id'])->alias('goods_class_');
    }

    /**
     * 获取实例
     * @param $id
     * @return mixed
     */
    public static function getInstance ($id)
    {
        return self::findModel(self::className(),$id);
    }

    /**
     * 默认条件
     * @return ActiveQuery
     */
    public static function queryCondition ()
    {
        $query = self::find();
        $query->andFilterWhere(['status' => self::STATUS_OPEN]);
        return $query;
    }
}
