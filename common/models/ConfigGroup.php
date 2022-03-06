<?php

namespace common\models;

use common\models\traits\BaseModelTrait;
use common\services\Service;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%config_group}}".
 *
 * @property int $id
 * @property string $name 组名(英文)
 * @property string $title 组名(中文)
 * @property int $sort 组排序
 * @property int $status 开启状态0表示1表示开启
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class ConfigGroup extends Service
{
    const STATUS_CLOSE = 0; // 隐藏
    const STATUS_OPEN = 1; // 开启

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%config_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 200],
            // trim
            [['name', 'title'], 'trim'],
            // unique
            [['name', 'title'], 'unique'],
            // scenarios 设置场景
            [['sort'], 'integer', 'on' => ['sort']],
            [['status'], 'integer', 'on' => ['status']],
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
            'name' => '组名(英文)',
            'title' => '组名(中文)',
            'sort' => '组排序',
            'status' => '开启状态0表示1表示开启',
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
     * @param int $intStatus 状态值
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
     * 关联配置表
     * @return ActiveQuery
     */
    public function getConfig()
    {
        return $this->hasMany(Config::className(), ['group_id' => 'id'])->alias('config_');
    }

    /**
     * 获取配置组集合
     * @return array|ActiveRecord[]
     */
    public static function getList ()
    {
        return self::queryCondition()->select(['id','name','title','status'])->orderBy(['id' => SORT_DESC,'sort' => SORT_DESC])->asArray()->all();
    }

    /**
     * 默认查询条件
     * @return ActiveQuery
     */
    public static function queryCondition ()
    {
        $query = self::find();
        $query->andFilterWhere(['status' => self::STATUS_OPEN]);
        return $query;
    }
}
