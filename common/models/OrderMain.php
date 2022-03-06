<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_main}}".
 *
 * @property int $id
 * @property string $order_sn 订单编号
 * @property int $user_id 用户id
 * @property int $pay_type 支付方式1表示支付宝2表示微信
 * @property int $order_status 付款方式0表示未付款1表示已付款
 * @property float $order_price 订单总价
 * @property string|null $trade_no 支付宝或者微信订单号
 * @property string|null $out_trade_no 记录自己生成微信订单号
 */
class OrderMain extends \yii\db\ActiveRecord
{
    const STATUS_NOT_PAY = 0;//未付款
    const STATUS_PAY_SUCCESS = 1;//已付款
    const STATUS_PAY_FAIL = 2;//付款失败

    const PAYMENT_ALI = 1;//支付宝
    const PAYMENT_WECHAT = 2;//微信

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_main}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'pay_type', 'order_status'], 'integer'],
            [['order_price'], 'number'],
            [['order_sn', 'trade_no', 'out_trade_no'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_sn' => '订单编号',
            'user_id' => '用户id',
            'pay_type' => '支付方式1表示支付宝2表示微信',
            'order_status' => '付款方式0表示未付款1表示已付款',
            'order_price' => '订单总价',
            'trade_no' => '支付宝或者微信订单号',
            'out_trade_no' => '记录自己生成微信订单号',
        ];
    }

    # 关联用户
    public function getUser ()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->alias('user_');
    }
}
