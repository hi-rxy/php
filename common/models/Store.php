<?php
namespace common\models;

use common\models\traits\BaseModelTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property string $name 店铺名称
 * @property string|null $title 店铺标题
 * @property string|null $keywords 店铺关键词
 * @property string|null $desc 店铺描述
 * @property string|null $id_card_front 身份证正面
 * @property string|null $id_card_side 身份证反面
 * @property string|null $logo 店铺logo
 * @property float $money 店铺收入
 * @property int $status 0表示未审核1表示已审核2表示审核不通过
 * @property string|null $reason 审核失败的原因
 * @property string|null $province 省份
 * @property string|null $city 市
 * @property string|null $district 县区
 * @property string|null $user_address 用户地址
 * @property string|null $contact 联系人
 * @property string|null $contact_mobile 联系电话
 * @property string $template 店铺模板
 * @property int $is_open_store 0表示未开通1表示已开通2审核中3审核不通过
 * @property string|null $domain 店铺二级域名
 * @property int $created_at 注册时间
 * @property int $updated_time 更新时间
 */
class Store extends ActiveRecord
{
    use BaseModelTrait;

    const STATUS_NOT_AUDIT = 0; // 未审核
    const STATUS_AUDIT = 1; // 已审核
    const STATUS_REFUSE = 2; // 审核拒绝

    const STATUS_NOT_OPEN = 0; // 未申请
    const STATUS_OPEN = 1; // 已申请
    const STATUS_AUDITING = 2; // 审核中
    const STATUS_AUDIT_REFUSE = 3; // 审核拒绝

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%store}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'is_open_store', 'created_at', 'updated_time'], 'integer'],
            [['desc'], 'string'],
            [['money'], 'number'],
            [['name', 'title', 'keywords', 'id_card_front', 'id_card_side', 'logo', 'reason', 'user_address', 'template', 'domain'], 'string', 'max' => 200],
            [['province', 'city', 'district'], 'string', 'max' => 10],
            [['contact'], 'string', 'max' => 50],
            [['contact_mobile'], 'string', 'max' => 20],
            // trim
            [['name', 'title', 'keywords', 'desc', 'contact', 'contact_mobile'], 'trim'],
            // default
            [['status'], 'default' , 'value' => self::STATUS_NOT_AUDIT],
            [['status'], 'in', 'range' => [self::STATUS_NOT_AUDIT, self::STATUS_AUDIT, self::STATUS_REFUSE]],
            [['is_open_store'], 'default', 'value' => self::STATUS_NOT_OPEN],
            [['is_open_store'], 'in', 'range' => [self::STATUS_NOT_OPEN, self::STATUS_OPEN, self::STATUS_AUDITING, self::STATUS_AUDIT_REFUSE]],
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
            'name' => '店铺名称',
            'title' => '店铺标题',
            'keywords' => '店铺关键词',
            'desc' => '店铺描述',
            'id_card_front' => '身份证正面',
            'id_card_side' => '身份证反面',
            'logo' => '店铺logo',
            'money' => '店铺收入',
            'status' => '0表示未审核1表示已审核2表示审核不通过',
            'reason' => '审核失败的原因',
            'province' => '省份',
            'city' => '市',
            'district' => '县区',
            'user_address' => '用户地址',
            'contact' => '联系人',
            'contact_mobile' => '联系电话',
            'template' => '店铺模板',
            'is_open_store' => '0表示未开通1表示已开通2审核中3审核不通过',
            'domain' => '店铺二级域名',
            'created_at' => '注册时间',
            'updated_time' => '更新时间',
        ];
    }

    /**
     * 店铺状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getStoreArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_NOT_AUDIT => '未审核',
            self::STATUS_AUDIT => '已审核',
            self::STATUS_REFUSE => '审核拒绝',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取店铺状态值对应的颜色信息
     * @param int $intStatus 状态值
     * @return array|string
     */
    public static function getStoreStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_NOT_AUDIT => 'btn-gery',
            self::STATUS_AUDIT => 'btn-success',
            self::STATUS_REFUSE => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 店铺二级域名状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getStoreDomainArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_NOT_OPEN => '未申请',
            self::STATUS_OPEN => '已申请',
            self::STATUS_AUDITING => '审核中',
            self::STATUS_AUDIT_REFUSE => '审核拒绝',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取店铺二级域名状态值对应的颜色信息
     * @param int $intStatus 状态值
     * @return array|string
     */
    public static function getStoreDomainStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_NOT_OPEN => 'btn-gery',
            self::STATUS_OPEN => 'btn-success',
            self::STATUS_AUDITING => 'btn-primary',
            self::STATUS_AUDIT_REFUSE => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 关联商品表
     * @return ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['id' => 'store_id'])->alias('goods_');
    }

    /**
     * 关联店铺分类表
     * @return ActiveQuery
     */
    public function getStoreClass()
    {
        return $this->hasMany(StoreClass::className(), ['store_id' => 'id'])->alias('store_class_');
    }

    /**
     * 默认条件
     * @param $condition
     * @param string $operator
     * @return string[]
     */
    private static function getWhere ($condition,$operator = 'and')
    {
        $where   = [$operator];
        $where[] = ['status' => self::STATUS_AUDIT];
        array_push($where,$condition);
        return $where;
    }

    /**
     * 取出一条数据
     * @param $where
     * @return array
     */
    public static function tableGetOne ($where)
    {
        return self::getDataOne(self::tableName(), self::getWhere($where));
    }
}
