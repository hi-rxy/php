<?php

namespace common\models;

use common\models\traits\BaseModelTrait;
use Yii;

/**
 * This is the model class for table "{{%region}}".
 *
 * @property int $id 地区id
 * @property int $pid pid
 * @property string $name 地区名称
 * @property int $sort 地区排序
 */
class Region extends \yii\db\ActiveRecord
{
    use BaseModelTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%region}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 50],
            // trim
            [['name'], 'trim'],
            // unique
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '地区id',
            'pid' => 'pid',
            'name' => '地区名称',
            'sort' => '地区排序',
        ];
    }

    /**
     * 取出一条数据
     * @param $where
     * @return array
     */
    public static function tableGetOne ($where)
    {
        return self::getDataOne(self::tableName(), $where, ['id', 'name', 'pid']);
    }

    /**
     * 获取数据集合
     * @return array
     */
    public static function tableGetData ($where,$indexBy = '')
    {
        $query = self::getQueryObject(self::tableName(), $where, ['id', 'name', 'pid']);
        $indexBy && $query->indexBy($indexBy);
        return $query->all();
    }

    /**
     * 获取字段值
     * @param $where
     * @param string $filed
     * @return array
     */
    public static function tableGetDataColumn ($where,$filed = 'id')
    {
        return self::getDataValue(self::tableName(), $where, $filed);
    }
}
