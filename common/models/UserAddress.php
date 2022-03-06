<?php

namespace common\models;

use common\models\traits\BaseModelTrait;
use yii\web\Request;

/**
 * This is the model class for table "{{%user_address}}".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property string $consignee_username 收货人姓名
 * @property int $consignee_province 省份
 * @property int $consignee_city 城市
 * @property int $consignee_district 地区
 * @property string $consignee_address 收货详细地址
 * @property string $consignee_mobile 手机号码
 * @property int $is_default 是否设置为默认1表示是0表示否
 */
class UserAddress extends \yii\db\ActiveRecord
{
    use BaseModelTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_address}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'consignee_province', 'consignee_city', 'consignee_district', 'is_default'], 'integer'],
            [['consignee_username'], 'required'],
            [['consignee_username'], 'string', 'max' => 50],
            [['consignee_address'], 'string', 'max' => 200],
            [['consignee_mobile'], 'string', 'max' => 20],
            // trim
            [['consignee_username','consignee_address','consignee_mobile'], 'trim']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'consignee_username' => '收货人姓名',
            'consignee_province' => '省份',
            'consignee_city' => '城市',
            'consignee_district' => '地区',
            'consignee_address' => '收货详细地址',
            'consignee_mobile' => '手机号码',
            'is_default' => '是否设置为默认1表示是0表示否',
        ];
    }

    # 关联用户
    public function getUser ()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->alias('user_');
    }

    public function getProvince ()
    {
        return $this->hasOne(Region::className(), ['id' => 'consignee_province'])->alias('region_province_');
    }

    public function getCity ()
    {
        return $this->hasOne(Region::className(), ['id' => 'consignee_city'])->alias('region_city_');
    }

    public function getDistrict ()
    {
        return $this->hasOne(Region::className(), ['id' => 'consignee_district'])->alias('region_district_');
    }

    /**
     * 取出一条数据
     * @param $where
     * @return array
     */
    public static function tableGetOne ($where)
    {
        return self::getDataOne(self::tableName(), $where);
    }

    /**
     * 获取数据集合
     * @return array
     */
    public static function tableGetData ($where,$indexBy = '')
    {
        $query = self::getQueryObject(self::tableName(), $where);
        $indexBy && $query->indexBy($indexBy);
        return $query->all();
    }

    /**
     * 获取用户地址信息
     * @param $address_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getAddressDetails ($address_id)
    {
        return self::find()->alias('user_address_')->with(['province','city','district'])->filterWhere(['user_address_.id' => $address_id])->asArray()->one();
    }
}
