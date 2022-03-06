<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property int $id 订单id
 * @property int $main_order_id 主订单id
 * @property string $order_sn 订单编号
 * @property int $user_id 用户id
 * @property int $store_id 店铺id
 * @property int|null $source 订单来源0表示pc1表示wap
 * @property int $pay_type 支付方式1表示支付宝2表示微信
 * @property string|null $trade_no 支付宝或者微信订单号
 * @property string|null $out_trade_no 记录自己生成微信订单号
 * @property int|null $old_order_status 退款时原来的订单状态
 * @property float|null $shipping_price 物流费
 * @property float $pay_amount 订单总价
 * @property float $real_amount 实付订单
 * @property int $created_time 订单创建时间
 * @property int|null $pay_time 订单付款时间
 * @property int|null $send_time 订单发货时间
 * @property int|null $take_time 订单确认收货时间
 * @property int|null $comment_time 订单评价时间
 * @property int|null $cancel_time 订单取消时间
 * @property string|null $cancel_reason 取消原因
 * @property int|null $close_time 订单关闭时间
 * @property int|null $delete_time 订单删除时间
 * @property int|null $refund_time 订单处理中的时间
 * @property int|null $over_pay_time 超时未付款时间
 * @property int $order_status 订单方式0表示未付款1表示已付款2表示发货3确认收货4已评价5已取消6订单关闭7已删除8订单处理中9订单付款超时
 * @property int|null $is_pay_status 付款状态0表示未付款1表示已付款
 * @property int $is_comment 0表示未评价1已评价
 * @property string|null $order_ip 订单ip
 * @property string|null $order_post_script 订单留言
 * @property string $consignee_name 收货人姓名
 * @property string $consignee_mobile 收货人电话
 * @property string $consignee_province 收货人省份
 * @property string $consignee_city 收货人城市
 * @property string $consignee_district 收货人地区
 * @property string $consignee_address 收货详细地址
 */
class Order extends ActiveRecord
{
    const SOURCE_PC = 0;//pc
    const SOURCE_WAP = 1;//wap

    const PAYMENT_ALI = 1;//支付宝
    const PAYMENT_WECHAT = 2;//微信

    const STATUS_NOT_PAY = 0;//未付款
    const STATUS_SUCCESS = 1;//已付款
    const STATUS_FAIL = 2;//付款失败

    const ORDER_STATUS_NOT_PAY = 0;//未付款
    const ORDER_STATUS_PAY = 1;//已付款
    const ORDER_STATUS_DELIVERY = 2;//发货
    const ORDER_STATUS_RECEIPT_DELIVERY = 3;//确认收货
    const ORDER_STATUS_COMMENT = 4;//已评价
    const ORDER_STATUS_CANCEL = 5;//已取消
    const ORDER_STATUS_CLOSE = 6;//订单关闭
    const ORDER_STATUS_DELETE = 7;//已删除
    const ORDER_STATUS_PROCESSING = 8;//订单处理中
    const ORDER_STATUS_PAY_TIMEOUT = 9;//订单付款超时

    const COMMENT_NOT = 0;//未评价
    const COMMENT_SUCCESS = 1;//已评价

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['main_order_id', 'user_id'], 'required'],
            [['main_order_id', 'user_id', 'store_id', 'source', 'pay_type', 'old_order_status', 'created_time', 'pay_time', 'send_time', 'take_time', 'comment_time', 'cancel_time', 'close_time', 'delete_time', 'refund_time', 'over_pay_time', 'order_status', 'is_pay_status', 'is_comment'], 'integer'],
            [['shipping_price', 'pay_amount', 'real_amount'], 'number'],
            [['cancel_reason'], 'string'],
            [['order_sn', 'trade_no', 'out_trade_no', 'order_post_script', 'consignee_address'], 'string', 'max' => 200],
            [['order_ip'], 'string', 'max' => 100],
            [['consignee_name', 'consignee_mobile'], 'string', 'max' => 20],
            [['consignee_province', 'consignee_city', 'consignee_district'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单id',
            'main_order_id' => '主订单id',
            'order_sn' => '订单编号',
            'user_id' => '用户id',
            'store_id' => '店铺id',
            'source' => '订单来源0表示pc1表示wap',
            'pay_type' => '支付方式1表示支付宝2表示微信',
            'trade_no' => '支付宝或者微信订单号',
            'out_trade_no' => '记录自己生成微信订单号',
            'old_order_status' => '退款时原来的订单状态',
            'shipping_price' => '物流费',
            'pay_amount' => '订单总价',
            'real_amount' => '实付订单',
            'created_time' => '订单创建时间',
            'pay_time' => '订单付款时间',
            'send_time' => '订单发货时间',
            'take_time' => '订单确认收货时间',
            'comment_time' => '订单评价时间',
            'cancel_time' => '订单取消时间',
            'cancel_reason' => '取消原因',
            'close_time' => '订单关闭时间',
            'delete_time' => '订单删除时间',
            'refund_time' => '订单处理中的时间',
            'over_pay_time' => '超时未付款时间',
            'order_status' => '订单方式0表示未付款1表示已付款2表示发货3确认收货4已评价5已取消6订单关闭7已删除8订单处理中9订单付款超时',
            'is_pay_status' => '付款状态0表示未付款1表示已付款',
            'is_comment' => '0表示未评价1已评价',
            'order_ip' => '订单ip',
            'order_post_script' => '订单留言',
            'consignee_name' => '收货人姓名',
            'consignee_mobile' => '收货人电话',
            'consignee_province' => '收货人省份',
            'consignee_city' => '收货人城市',
            'consignee_district' => '收货人地区',
            'consignee_address' => '收货详细地址',
        ];
    }

    # 关联用户
    public function getUser ()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->alias('user_');
    }

    # 关联订单主表
    public function getOrderMain ()
    {
        return $this->hasOne(OrderMain::className(), ['id' => 'main_order_id'])->alias('order_main_');
    }

    # 关联店铺
    public function getStore ()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id'])->alias('store_');
    }
}
