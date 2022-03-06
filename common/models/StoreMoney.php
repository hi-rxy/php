<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%store_money}}".
 *
 * @property int $id
 * @property int|null $order_id 订单id
 * @property string|null $order_sn 订单编号
 * @property int $store_id 店铺id
 * @property float|null $store_money 金额
 * @property int|null $money_type 交易类型1收入2提现
 * @property string|null $money_remark 简短描述
 * @property int|null $money_status 处理状态1成功2待处理3拒绝
 * @property string|null $money_reason 拒绝原因
 * @property int|null $created_time 添加时间
 */
class StoreMoney extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%store_money}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'store_id', 'money_type', 'money_status', 'created_time'], 'integer'],
            [['store_id'], 'required'],
            [['store_money'], 'number'],
            [['order_sn'], 'string', 'max' => 50],
            [['money_remark', 'money_reason'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单id',
            'order_sn' => '订单编号',
            'store_id' => '店铺id',
            'store_money' => '金额',
            'money_type' => '交易类型1收入2提现',
            'money_remark' => '简短描述',
            'money_status' => '处理状态1成功2待处理3拒绝',
            'money_reason' => '拒绝原因',
            'created_time' => '添加时间',
        ];
    }
}
